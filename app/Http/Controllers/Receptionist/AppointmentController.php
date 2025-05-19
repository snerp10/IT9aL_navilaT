<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\DentalService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'dentist']);
        
        // Apply view filters
        if ($request->has('view')) {
            switch ($request->view) {
                case 'today':
                    $query->whereDate('appointment_date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('appointment_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'dentist':
                    if ($request->has('dentist_id')) {
                        $query->where('dentist_id', $request->dentist_id);
                    }
                    break;
                // Calendar view is handled differently in the frontend
            }
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }
        
        // Order by date
        $query->orderBy('appointment_date');
        
        // Get appointments with pagination
        $appointments = $query->paginate(10);
        
        // Get dentists for filter
        $dentists = Employee::where('role', 'Dentist')->get();
        
        return view('receptionist.appointments.index', compact('appointments', 'dentists'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(Request $request)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Employee::where('role', 'Dentist')
            ->where('employment_status', 'Active')
            ->orderBy('last_name')
            ->get();
        $dentalServices = DentalService::orderBy('name')->get();
        
        // If patient_id is provided, pre-select that patient
        $selectedPatientId = $request->input('patient_id');
        
        return view('receptionist.appointments.create', compact('patients', 'dentists', 'dentalServices', 'selectedPatientId'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        // Validate appointment data
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'dentist_id' => 'required|exists:employees,employee_id',
            'appointment_date' => 'required|date|after:now',
            'reason_for_visit' => 'required|string|max:255',
            'status' => 'required|in:Scheduled,Completed,Canceled,No Show',
            'notes' => 'nullable|string',
        ]);
        
        // Create appointment
        $appointment = Appointment::create($validated);
        
        return redirect()->route('receptionist.appointments.index')
            ->with('success', 'Appointment scheduled successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'dentist']);
        
        return view('receptionist.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Employee::where('role', 'Dentist')
            ->where('employment_status', 'Active')
            ->orderBy('last_name')
            ->get();
        $dentalServices = DentalService::orderBy('name')->get();
        
        return view('receptionist.appointments.edit', compact('appointment', 'patients', 'dentists', 'dentalServices'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validate appointment data
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'dentist_id' => 'required|exists:employees,employee_id',
            'appointment_date' => 'required|date',
            'reason_for_visit' => 'required|string|max:255',
            'status' => 'required|in:Scheduled,Completed,Canceled,No Show',
            'notes' => 'nullable|string',
        ]);
        
        // Update appointment
        $appointment->update($validated);
        
        return redirect()->route('receptionist.appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Check in a patient for their appointment.
     */
    public function checkIn(Appointment $appointment)
    {
        // Update appointment status - using 'Scheduled' since 'In Progress' isn't in the enum
        $appointment->update([
            'status' => 'Scheduled',
            'check_in_time' => Carbon::now(),
        ]);
        
        return redirect()->route('receptionist.appointments.index')
            ->with('success', 'Patient checked in successfully!');
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Appointment $appointment)
    {
        // Update appointment status
        $appointment->update([
            'status' => 'Canceled',
        ]);
        
        return redirect()->route('receptionist.appointments.index')
            ->with('success', 'Appointment cancelled successfully!');
    }

    /**
     * Display patient appointment history.
     */
    public function patientHistory(Patient $patient)
    {
        $appointments = Appointment::with(['dentist'])
            ->where('patient_id', $patient->patient_id)
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        return view('receptionist.appointments.patient-history', compact('patient', 'appointments'));
    }

    /**
     * Display calendar view of appointments.
     */
    public function calendar()
    {
        $appointments = Appointment::with(['patient', 'dentist'])
            ->whereDate('appointment_date', '>=', Carbon::now()->subDays(30))
            ->whereDate('appointment_date', '<=', Carbon::now()->addDays(60))
            ->get();
        
        $dentists = Employee::where('role', 'Dentist')->get();
        
        return view('receptionist.appointments.calendar', compact('appointments', 'dentists'));
    }
}