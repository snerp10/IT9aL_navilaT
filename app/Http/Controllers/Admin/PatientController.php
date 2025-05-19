<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of all patients
     */
    public function index(Request $request)
    {
        // Query building
        $query = Patient::query();
        
        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('contact_number', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Ordering
        $query->orderBy($request->get('sort', 'created_at'), $request->get('direction', 'desc'));
        
        // Get paginated results
        $patients = $query->paginate(10)->withQueryString();
        
        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store a newly created patient
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:patients',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Set default status if not provided
        if (!isset($validated['status'])) {
            $validated['status'] = 'active';
        }

        // Generate a unique patient ID
        $validated['patient_id'] = 'PAT-' . Str::random(8);

        // Create the patient
        $patient = Patient::create($validated);
        
        // Optionally, create a user account if checkbox is checked
        if ($request->has('create_user_account')) {
            $userPassword = Str::random(8); // Generate a random password
            
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt($userPassword),
                'role' => 'Patient',
                'user_id' => $patient->patient_id, // Use same ID as patient
            ]);
            
            // Update the patient record to link to the user
            $patient->update(['user_id' => $user->id]);
            
            // Set a session variable with the generated password to show once
            session(['generated_password' => $userPassword]);
        }
        
        return redirect()->route('patients.index')->with('success', 'Patient created successfully.');
    }

    /**
     * Display the specified patient's details and medical history
     */
    public function show(Patient $patient)
    {
        // Get the patient's appointments with treatments
        $appointments = Appointment::where('patient_id', $patient->patient_id)
            ->with('treatments')
            ->orderBy('appointment_date', 'desc')
            ->get();
            
        // Get medical history
        $medicalHistory = Treatment::whereHas('appointment', function($q) use ($patient) {
                $q->where('appointments.patient_id', $patient->patient_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });
            
        // Get billing history - Fix the ambiguous column error by fully qualifying patient_id
        $billing = Billing::whereHas('appointment', function($q) use ($patient) {
                $q->where('appointments.patient_id', $patient->patient_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Upcoming appointments
        $upcomingAppointments = Appointment::where('patient_id', $patient->patient_id)
            ->where('appointment_date', '>=', Carbon::today())
            ->orderBy('appointment_date', 'asc')
            ->get();
            
        // Calculate patient's age
        $age = Carbon::parse($patient->birth_date)->age;
            
        return view('admin.patients.show', compact(
            'patient', 
            'appointments', 
            'medicalHistory', 
            'billing', 
            'upcomingAppointments',
            'age'
        ));
    }

    /**
     * Show the form for editing the patient's information
     */
    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update the patient's information
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:patients,email,' . $patient->id,
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $patient->update($validated);
        
        // If patient has a user account, update that too
        if ($patient->user_id && $user = User::where('user_id', $patient->patient_id)->first()) {
            $user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
            ]);
        }
        
        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the patient from the system
     */
    public function destroy(Patient $patient)
    {
        // Check if patient has treatments/appointments
        $hasAppointments = Appointment::where('patient_id', $patient->patient_id)->exists();
        
        if ($hasAppointments) {
            return redirect()->route('patients.show', $patient)->with('error', 
                'Cannot delete this patient because they have appointments. You can set their status to inactive instead.');
        }
        
        // Delete associated user account if exists
        if ($patient->user_id) {
            User::where('user_id', $patient->patient_id)->delete();
        }
        
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
    
    /**
     * Display the patient's dental chart
     */
    public function dentalChart(Patient $patient)
    {
        // Get dental chart data
        $teethData = $this->getPatientTeethData($patient->patient_id);
        
        return view('admin.patients.dental-chart', compact('patient', 'teethData'));
    }
    
    /**
     * Display the patient's appointment history
     */
    public function appointments(Patient $patient)
    {
        $appointments = Appointment::where('patient_id', $patient->patient_id)
            ->with(['dentist', 'treatments'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
        return view('admin.patients.appointments', compact('patient', 'appointments'));
    }
    
    /**
     * Display the patient's treatment history
     */
    public function treatments(Patient $patient)
    {
        $treatments = Treatment::whereHas('appointment', function($q) use ($patient) {
                $q->where('patient_id', $patient->patient_id);
            })
            ->with('appointment.dentist')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.patients.treatments', compact('patient', 'treatments'));
    }
    
    /**
     * Display the patient's billing history
     */
    public function billings(Patient $patient)
    {
        $billings = Billing::whereHas('appointment', function($q) use ($patient) {
                $q->where('appointments.patient_id', $patient->patient_id);
            })
            ->with('appointment')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $totalBilled = $billings->sum('total_amount');
        $totalPaid = $billings->sum('amount_paid');
        $outstandingBalance = $totalBilled - $totalPaid;
            
        return view('admin.patients.billings', compact('patient', 'billings', 'totalBilled', 'totalPaid', 'outstandingBalance'));
    }
    
    /**
     * Search patients by name, email, or contact number
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('q');
        
        $patients = Patient::where('first_name', 'like', "%{$searchTerm}%")
            ->orWhere('last_name', 'like', "%{$searchTerm}%")
            ->orWhere('email', 'like', "%{$searchTerm}%")
            ->orWhere('contact_number', 'like', "%{$searchTerm}%")
            ->get(['patient_id', 'first_name', 'last_name', 'email', 'contact_number']);
            
        return response()->json($patients);
    }
    
    /**
     * Add a medical note to the patient's record
     */
    public function addNote(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'note_type' => 'required|in:general,allergy,medication,medical_history',
            'note_content' => 'required|string',
        ]);
        
        $field = match($validated['note_type']) {
            'allergy' => 'allergies',
            'medication' => 'current_medications',
            'medical_history' => 'medical_history',
            default => 'notes'
        };
        
        // Add the note with timestamp
        $note = "[" . now()->format('Y-m-d H:i') . "] " . $validated['note_content'] . "\n\n";
        
        // Append to existing notes or create new
        $patient->$field = $patient->$field 
            ? $note . $patient->$field
            : $note;
            
        $patient->save();
        
        return redirect()->back()->with('success', 'Note added successfully');
    }
    
    /**
     * Helper method to get patient's teeth data
     */
    private function getPatientTeethData($patientId)
    {
        // In a real system, you would fetch this from your database
        // For now, returning a placeholder structure
        $teethData = [];
        
        // Adult teeth: 32 teeth (1-32)
        for ($i = 1; $i <= 32; $i++) {
            $teethData[$i] = [
                'number' => $i,
                'status' => 'healthy',
                'treatments' => [],
                'notes' => ''
            ];
        }
        
        // You'd fetch real treatments and populate this data
        // This is just demo data
        $treatments = Treatment::whereHas('appointment', function($q) use ($patientId) {
                $q->where('patient_id', $patientId);
            })
            ->where('tooth_number', '>', 0)
            ->get();
            
        foreach ($treatments as $treatment) {
            if (isset($teethData[$treatment->tooth_number])) {
                $teethData[$treatment->tooth_number]['status'] = $treatment->treatment_type;
                $teethData[$treatment->tooth_number]['treatments'][] = [
                    'date' => $treatment->created_at->format('Y-m-d'),
                    'type' => $treatment->treatment_type,
                    'dentist' => $treatment->appointment->dentist->first_name . ' ' . $treatment->appointment->dentist->last_name,
                    'notes' => $treatment->notes
                ];
            }
        }
        
        return $teethData;
    }
}
