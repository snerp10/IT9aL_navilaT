<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\DentalService;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the treatments.
     */
    public function index()
    {
        $treatments = Treatment::with(['patient', 'appointment', 'dentalService'])->get();
        return view('admin.treatments.index', compact('treatments'));
    }

    /**
     * Show the form for creating a new treatment.
     */
    public function create(Request $request)
    {
        $appointments = Appointment::with(['patient', 'dentist'])->get();
        $patients = Patient::all();
        $dentalServices = DentalService::orderBy('name')->get();
        
        // Pre-select appointment or patient if provided in the request
        $selectedAppointmentId = $request->query('appointment_id');
        $selectedPatientId = $request->query('patient_id');
        
        return view('admin.treatments.create', compact(
            'appointments', 
            'patients', 
            'dentalServices', 
            'selectedAppointmentId', 
            'selectedPatientId'
        ));
    }

    /**
     * Store a newly created treatment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'service_id' => 'nullable|exists:dental_services,service_id',
            'dentist_id' => 'required|exists:employees,employee_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'tooth_number' => 'nullable|integer|min:1|max:32',
            'notes' => 'nullable|string',
            'status' => 'required|in:Planned,In Progress,Completed',
        ]);

        // Set treatment_date from the appointment's date
        $appointment = Appointment::findOrFail($validated['appointment_id']);
        $validated['treatment_date'] = $appointment->appointment_date;

        // Create the treatment
        $treatment = Treatment::create($validated);

        // Redirect based on source
        if ($request->has('from_appointment')) {
            return redirect()->route('treatments.appointment-treatments', $treatment->appointment_id)
                ->with('success', 'Treatment record created successfully.');
        } elseif ($request->has('from_patient')) {
            return redirect()->route('treatments.patient-treatments', $treatment->patient_id)
                ->with('success', 'Treatment record created successfully.');
        } else {
            return redirect()->route('treatments.index')
                ->with('success', 'Treatment record created successfully.');
        }
    }

    /**
     * Display the specified treatment.
     */
    public function show(Treatment $treatment)
    {
        $treatment->load(['patient', 'appointment', 'dentalService', 'billings']);
        return view('admin.treatments.show', compact('treatment'));
    }

    /**
     * Show the form for editing the specified treatment.
     */
    public function edit(Treatment $treatment)
    {
        $appointments = Appointment::with(['patient', 'dentist'])->get();
        $patients = Patient::all();
        $dentalServices = DentalService::orderBy('name')->get();
        
        return view('admin.treatments.edit', compact(
            'treatment', 
            'appointments', 
            'patients', 
            'dentalServices'
        ));
    }

    /**
     * Update the specified treatment in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'service_id' => 'nullable|exists:dental_services,service_id',
            'dentist_id' => 'required|exists:employees,employee_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'tooth_number' => 'nullable|integer|min:1|max:32',
            'notes' => 'nullable|string',
            'status' => 'required|in:Planned,In Progress,Completed',
        ]);

        $treatment->update($validated);

        return redirect()->route('treatments.show', $treatment)
            ->with('success', 'Treatment record updated successfully.');
    }

    /**
     * Remove the specified treatment from storage.
     */
    public function destroy(Treatment $treatment)
    {
        // Check if there are any billings for this treatment
        if ($treatment->billings()->count() > 0) {
            return redirect()->route('treatments.index')
                ->with('error', 'Cannot delete treatment with associated billing records.');
        }

        $treatment->delete();

        return redirect()->route('treatments.index')
            ->with('success', 'Treatment record deleted successfully.');
    }

    /**
     * Display treatments for a specific patient.
     */
    public function patientTreatments(Patient $patient)
    {
        $treatments = Treatment::where('patient_id', $patient->patient_id)
            ->with(['appointment', 'dentalService'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get active dental services for the "Add Treatment" button
        $dentalServices = DentalService::where('is_active', true)->get();
            
        return view('admin.treatments.patient-treatments', compact(
            'treatments', 
            'patient', 
            'dentalServices'
        ));
    }

    /**
     * Display treatments for a specific appointment.
     */
    public function appointmentTreatments(Appointment $appointment)
    {
        $treatments = Treatment::where('appointment_id', $appointment->appointment_id)
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