<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\DentalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the dentist's treatments.
     */
    public function index(Request $request)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        $query = Treatment::with(['patient', 'appointment', 'dentalService'])
            ->where('dentist_id', $dentistId);
        
        // Filter by status if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by patient if provided
        if ($request->has('patient_id') && !empty($request->patient_id)) {
            $query->where('patient_id', $request->patient_id);
        }
        
        // Filter by treatment type/service if provided
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->where('service_id', $request->service_id);
        }
        
        // Default sorting by created date, most recent first
        $query->orderBy('created_at', 'desc');
        
        $treatments = $query->paginate(10)->withQueryString();
        
        // Get dental services for filter dropdown
        $dentalServices = DentalService::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('dentist.treatments.index', compact('treatments', 'dentalServices'));
    }

    /**
     * Display the specified treatment.
     */
    public function show(Treatment $treatment)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Ensure the dentist can only view their own treatments
        if ($treatment->dentist_id != Auth::user()->employee->employee_id) {
            return redirect()->route('dentist.treatments.index')
                ->with('error', 'You are not authorized to view this treatment.');
        }
        
        $treatment->load(['patient', 'appointment', 'dentalService']);
        
        return view('dentist.treatments.show', compact('treatment'));
    }

    /**
     * Show the form for creating a new treatment.
     */
    public function create(Request $request)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // If appointment_id is provided, validate the appointment belongs to this dentist
        $appointmentId = $request->query('appointment_id');
        
        if ($appointmentId) {
            $appointment = Appointment::find($appointmentId);
            
            if (!$appointment || $appointment->dentist_id != $dentistId) {
                return redirect()->route('dentist.appointments.index')
                    ->with('error', 'You are not authorized to add treatments to this appointment.');
            }
            
            // Load the appointment with its patient
            $appointment->load('patient');
        } else {
            $appointment = null;
        }
        
        $dentalServices = DentalService::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('dentist.treatments.create', compact('appointment', 'dentalServices'));
    }

    /**
     * Store a newly created treatment in storage.
     */
    public function store(Request $request)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Validate inputs
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id',
            'service_id' => 'nullable|exists:dental_services,service_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'tooth_number' => 'nullable|integer|min:1|max:32',
            'notes' => 'nullable|string',
            'status' => 'required|in:Planned,In Progress,Completed',
        ]);
        
        // Verify the appointment belongs to this dentist
        $appointment = Appointment::find($validated['appointment_id']);
        
        if (!$appointment || $appointment->dentist_id != $dentistId) {
            return redirect()->route('dentist.appointments.index')
                ->with('error', 'You are not authorized to add treatments to this appointment.');
        }
        
        // Get the patient_id from the appointment
        $validated['patient_id'] = $appointment->patient_id;
        $validated['dentist_id'] = $dentistId;
        
        // Add treatment_date (default to current date and time)
        $validated['treatment_date'] = now();
        
        // Create the treatment record
        $treatment = Treatment::create($validated);
        
        // If the treatment is completed, update the appointment status accordingly
        if ($validated['status'] == 'Completed' && $appointment->status != 'Completed') {
            $appointment->status = 'Completed';
            $appointment->save();
        } elseif ($validated['status'] == 'In Progress' && $appointment->status == 'Scheduled') {
            $appointment->status = 'In Progress';
            $appointment->save();
        }
        
        return redirect()->route('dentist.appointments.show', $appointment)
            ->with('success', 'Treatment record added successfully.');
    }

    /**
     * Show the form for editing the specified treatment.
     */
    public function edit(Treatment $treatment)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Ensure the dentist can only edit their own treatments
        if ($treatment->dentist_id != Auth::user()->employee->employee_id) {
            return redirect()->route('dentist.treatments.index')
                ->with('error', 'You are not authorized to edit this treatment.');
        }
        
        // Don't allow editing if there's a billing record attached
        if ($treatment->billings()->exists()) {
            return redirect()->route('dentist.treatments.show', $treatment)
                ->with('error', 'This treatment cannot be edited because it has associated billing records.');
        }
        
        $dentalServices = DentalService::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $treatment->load(['appointment', 'patient']);
        
        return view('dentist.treatments.edit', compact('treatment', 'dentalServices'));
    }

    /**
     * Update the specified treatment in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Ensure the dentist can only update their own treatments
        if ($treatment->dentist_id != Auth::user()->employee->employee_id) {
            return redirect()->route('dentist.treatments.index')
                ->with('error', 'You are not authorized to update this treatment.');
        }
        
        // Don't allow updating if there's a billing record attached
        if ($treatment->billings()->exists()) {
            return redirect()->route('dentist.treatments.show', $treatment)
                ->with('error', 'This treatment cannot be updated because it has associated billing records.');
        }
        
        $validated = $request->validate([
            'service_id' => 'nullable|exists:dental_services,service_id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'tooth_number' => 'nullable|integer|min:1|max:32',
            'notes' => 'nullable|string',
            'status' => 'required|in:Planned,In Progress,Completed',
        ]);
        
        // If treatment_date is not set, update it
        if (!$treatment->treatment_date) {
            $validated['treatment_date'] = now();
        }
        
        $treatment->update($validated);
        
        // Update the appointment status if necessary
        $appointment = $treatment->appointment;
        
        if ($validated['status'] == 'Completed' && $appointment->status != 'Completed') {
            $appointment->status = 'Completed';
            $appointment->save();
        } elseif ($validated['status'] == 'In Progress' && $appointment->status == 'Scheduled') {
            $appointment->status = 'In Progress';
            $appointment->save();
        }
        
        return redirect()->route('dentist.treatments.show', $treatment)
            ->with('success', 'Treatment record updated successfully.');
    }

    /**
     * Get dental service details via AJAX
     */
    public function getServiceDetails(Request $request)
    {
        $serviceId = $request->input('service_id');
        $service = DentalService::findOrFail($serviceId);
        
        return response()->json([
            'name' => $service->name,
            'description' => $service->description,
            'cost' => $service->standard_cost,
            'duration' => $service->standard_duration,
        ]);
    }

    /**
     * Display treatments for a specific patient.
     */
    public function patientTreatments(Patient $patient)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Get all treatments for this patient that were created by this dentist
        $treatments = Treatment::with(['appointment', 'dentalService'])
            ->where('patient_id', $patient->patient_id)
            ->where('dentist_id', $dentistId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('dentist.treatments.patient_treatments', compact('patient', 'treatments'));
    }
}