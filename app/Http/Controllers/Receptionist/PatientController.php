<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $query = Patient::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Order by name
        $query->orderBy('last_name')->orderBy('first_name');
        
        // Get patients with pagination
        $patients = $query->paginate(10);
        
        return view('receptionist.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('receptionist.patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        // Validate patient data
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|unique:patients,email',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_number' => 'required|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:100',
            'insurance_policy_number' => 'nullable|string|max:50',
            'create_user_account' => 'nullable|boolean',
        ]);
        
        // Generate patient ID
        $patientIdPrefix = 'PT';
        $latestPatient = Patient::latest()->first();
        $newIdNumber = $latestPatient ? intval(substr($latestPatient->patient_id, 2)) + 1 : 1000;
        $patientId = $patientIdPrefix . $newIdNumber;
        
        // Add patient ID and default status to validated data
        $validated['patient_id'] = $patientId;
        $validated['status'] = 'active';
        
        // Create patient record
        $patient = Patient::create($validated);
        
        // Create user account if requested
        $generatedPassword = null;
        if ($request->has('create_user_account') && $request->create_user_account == 1) {
            $generatedPassword = Str::random(10);
            
            User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($generatedPassword),
                'role' => 'patient',
                'patient_id' => $patient->patient_id,
            ]);
        }
        
        // Redirect with success message
        if ($generatedPassword) {
            return redirect()->route('receptionist.patients.index')
                ->with('success', 'Patient created successfully!')
                ->with('generated_password', $generatedPassword);
        } else {
            return redirect()->route('receptionist.patients.index')
                ->with('success', 'Patient created successfully!');
        }
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        // Calculate age from birth date
        $age = Carbon::parse($patient->birth_date)->age;
        
        // Get patient's appointments
        $appointments = Appointment::with(['dentist'])
            ->where('patient_id', $patient->patient_id)
            ->orderBy('appointment_date', 'desc')
            ->get();
        
        return view('receptionist.patients.show', compact('patient', 'age', 'appointments'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        return view('receptionist.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        // Validate patient data
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|unique:patients,email,'.$patient->id.',id',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_number' => 'required|string|max:20',
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:100',
            'insurance_policy_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Update patient record
        $patient->update($validated);
        
        // Redirect with success message
        return redirect()->route('receptionist.patients.show', $patient)
            ->with('success', 'Patient updated successfully!');
    }

    /**
     * Search patients by name, email, or phone number.
     */
    public function search(Request $request)
    {
        if (!$request->has('q') || empty($request->q)) {
            return response()->json([]);
        }
        
        $search = $request->q;
        
        $patients = Patient::where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('contact_number', 'like', "%{$search}%")
            ->limit(10)
            ->get(['patient_id', 'first_name', 'last_name', 'contact_number', 'email']);
        
        return response()->json($patients);
    }
}