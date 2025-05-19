<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\Patient;
use App\Models\DentalService;
use App\Models\Employee;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    /**
     * Display a listing of all treatments.
     */
    public function index(Request $request)
    {
        $query = Treatment::with(['patient', 'appointment', 'dentalService']);
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by dentist if provided
        if ($request->has('dentist_id')) {
            $query->whereHas('appointment', function($q) use ($request) {
                $q->where('dentist_id', $request->dentist_id);
            });
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        $treatments = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.treatments.index', compact('treatments'));
    }

    /**
     * Show the form for creating a new treatment.
     */
    public function create(Request $request, Appointment $appointment)
    {
        // Verify appointment status
        if ($appointment->status != 'In Progress' && $appointment->status != 'Completed') {
            return back()->with('error', 'Treatments can only be added to in-progress or completed appointments.');
        }
        
        $patient_id = $request->query('patient_id');
        $patient = null;
        
        if ($patient_id) {
            $patient = Patient::findOrFail($patient_id);
        }
        
        $patients = Patient::orderBy('last_name')->get();
        $dentalServices = DentalService::orderBy('name')->get();
        $dentists = Employee::where('role', 'Dentist')->orderBy('last_name')->get();
        $appointments = Appointment::with(['patient', 'dentist'])->get();
        
        return view('admin.treatments.create', [
            'appointment' => $appointment,
            'dentalServices' => $dentalServices,
            'patients' => $patients,
            'dentists' => $dentists,
            'patient' => $patient,
            'appointments' => $appointments
        ]);
    }

    /**
     * Store a newly created treatment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'service_id' => 'required|exists:dental_services,service_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'tooth_number' => 'nullable|integer|min:1|max:32',
            'cost' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:Planned,In Progress,Completed',
            'notes' => 'nullable|string',
        ]);

        $treatment = Treatment::create([
            'patient_id' => $request->input('patient_id'),
            'appointment_id' => $request->input('appointment_id'),
            'service_id' => $request->input('service_id'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'tooth_number' => $request->input('tooth_number'),
            'cost' => $request->input('cost'),
            'duration' => $request->input('duration'),
            'status' => $request->input('status'),
            'notes' => $request->input('notes'),
        ]);

        // Update appointment status if treatment is completed
        if ($treatment->status === 'Completed' && $treatment->appointment) {
            $treatment->appointment->status = 'Completed';
            $treatment->appointment->save();
        }

        // Redirect based on source
        if ($request->has('from_appointment')) {
            return redirect()->route('treatments.appointment-treatments', $treatment->appointment_id)
                ->with('success', 'Treatment created successfully');
        } elseif ($request->has('from_patient')) {
            return redirect()->route('treatments.patient-treatments', $treatment->patient_id)
                ->with('success', 'Treatment created successfully');
        } else {
            return redirect()->route('treatments.show', $treatment->treatment_id)
                ->with('success', 'Treatment created successfully');
        }
    }

    /**
     * Display the specified treatment.
     */
    public function show($id)
    {
        $treatment = Treatment::with(['patient', 'dentalService', 'appointment.dentist', 'billings'])->findOrFail($id);
        return view('admin.treatments.show', compact('treatment'));
    }

    /**
     * Show the form for editing the specified treatment.
     */
    public function edit(Treatment $treatment)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentalServices = DentalService::orderBy('name')->get();
        $dentists = Employee::where('role', 'Dentist')->orderBy('last_name')->get();
        $appointments = Appointment::with(['patient', 'dentist'])->get();
        
        return view('treatments.edit', [
            'treatment' => $treatment,
            'dentalServices' => $dentalServices,
            'patients' => $patients,
            'dentists' => $dentists,
            'appointments' => $appointments
        ]);
    }

    /**
     * Update the specified treatment in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'service_id' => 'required|exists:dental_services,service_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'tooth_number' => 'nullable|integer|min:1|max:32',
            'cost' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:Planned,In Progress,Completed',
            'notes' => 'nullable|string',
        ]);

        $treatment = Treatment::findOrFail($id);
        $treatment->update($request->all());

        // Update appointment status if treatment is completed
        if ($treatment->status === 'Completed' && $treatment->appointment) {
            $treatment->appointment->status = 'Completed';
            $treatment->appointment->save();
        }

        return redirect()->route('treatments.show', $treatment->treatment_id)
            ->with('success', 'Treatment updated successfully');
    }

    /**
     * Remove the specified treatment from storage.
     */
    public function destroy($id)
    {
        $treatment = Treatment::findOrFail($id);
        
        // Check if there are any billings for this treatment
        if ($treatment->billings()->count() > 0) {
            return redirect()->route('treatments.index')
                ->with('error', 'Cannot delete treatment with associated billing records.');
        }
        
        $treatment->delete();

        return redirect()->route('treatments.index')
            ->with('success', 'Treatment deleted successfully');
    }

    /**
     * Display all treatments for a specific patient.
     */
    public function patientTreatments($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $treatments = Treatment::with(['appointment.dentist', 'dentalService'])
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get active dental services for the "Add Treatment" button
        $dentalServices = DentalService::where('is_active', true)->get();
        
        return view('admin.treatments.patient-treatments', compact('patient', 'treatments', 'dentalServices'));
    }
    
    /**
     * Display treatments for a specific appointment.
     */
    public function appointmentTreatments($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $treatments = Treatment::where('appointment_id', $appointmentId)
            ->with(['patient', 'dentalService'])
            ->get();
            
        // Get active dental services for the "Add Treatment" button
        $dentalServices = DentalService::where('is_active', true)->get();
            
        // Load the patient and dentist
        $appointment->load(['patient', 'dentist']);
        
        return view('admin.treatments.appointment-treatments', compact(
            'treatments', 
            'appointment', 
            'dentalServices'
        ));
    }
    
    /**
     * Get service details via AJAX for the treatment form.
     */
    public function getServiceDetails(Request $request)
    {
        $serviceId = $request->input('service_id');
        $service = DentalService::find($serviceId);
        
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }
        
        return response()->json([
            'name' => $service->name,
            'description' => $service->description,
            'cost' => $service->standard_cost,
            'duration' => $service->standard_duration,
        ]);
    }
}