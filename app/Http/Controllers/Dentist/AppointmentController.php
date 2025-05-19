<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\DentalService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the dentist's appointments.
     */
    public function index(Request $request)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Get the authenticated dentist's ID
        $dentistId = Auth::user()->employee->employee_id;
        
        // Initialize the query for the dentist's appointments
        $query = Appointment::with(['patient', 'treatments'])
            ->where('dentist_id', $dentistId);
        
        // Apply date filter if provided
        if ($request->has('date') && !empty($request->date)) {
            $query->whereDate('appointment_date', $request->date);
        }
        
        // Apply status filter if provided
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
        
        // Default: Order by date
        $query->orderBy('appointment_date');
        
        // Get appointments with pagination
        $appointments = $query->paginate(10)->withQueryString();
        
        return view('dentist.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create(Request $request)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Get patients to select for the appointment
        $patients = Patient::orderBy('last_name')->orderBy('first_name')->get();
        
        // Get dental services
        $dentalServices = DentalService::where('is_active', true)->orderBy('name')->get();
        
        // Pre-select patient if passed in query string
        $selectedPatient = null;
        if ($request->has('patient_id') && !empty($request->patient_id)) {
            $selectedPatient = Patient::find($request->patient_id);
        }
        
        // Default suggested times (9 AM to 5 PM, 30 min intervals)
        $suggestedTimes = [];
        $startTime = Carbon::today()->setHour(9)->setMinute(0);
        $endTime = Carbon::today()->setHour(17)->setMinute(0);
        
        while ($startTime <= $endTime) {
            $suggestedTimes[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }
        
        return view('dentist.appointments.create', compact(
            'patients', 
            'dentalServices', 
            'selectedPatient',
            'suggestedTimes'
        ));
    }
    
    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'reason_for_visit' => 'required|string|max:500',
            'notes' => 'nullable|string',
        ]);
        
        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'])->format('Y-m-d') . ' ' . $validated['appointment_time'] . ':00';
        
        // Create appointment
        $appointment = new Appointment();
        $appointment->patient_id = $validated['patient_id'];
        $appointment->dentist_id = $dentistId;
        $appointment->appointment_date = $appointmentDateTime;
        $appointment->reason_for_visit = $validated['reason_for_visit'];
        $appointment->status = 'Scheduled';
        
        // Add notes if provided
        if (!empty($validated['notes'])) {
            $noteWithTimestamp = "[" . now()->format('Y-m-d H:i') . " - Dr. " . Auth::user()->employee->first_name . " " . Auth::user()->employee->last_name . "] " . $validated['notes'];
            $appointment->notes = $noteWithTimestamp;
        }
        
        $appointment->save();
        
        return redirect()->route('dentist.appointments.show', $appointment)
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Ensure the dentist can only view their own appointments
        if ($appointment->dentist_id != Auth::user()->employee->employee_id) {
            return redirect()->route('dentist.appointments.index')
                ->with('error', 'You are not authorized to view this appointment.');
        }
        
        $appointment->load(['patient', 'treatments']);
        
        // Get available dental services for creating treatments
        $dentalServices = DentalService::where('is_active', true)->orderBy('name')->get();
        
        return view('dentist.appointments.show', compact('appointment', 'dentalServices'));
    }

    /**
     * Update the appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Ensure the dentist can only update their own appointments
        if ($appointment->dentist_id != Auth::user()->employee->employee_id) {
            return redirect()->route('dentist.appointments.index')
                ->with('error', 'You are not authorized to update this appointment.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:Scheduled,In Progress,Completed,Canceled,No Show',
            'notes' => 'nullable|string',
        ]);
        
        $appointment->status = $validated['status'];
        
        // Append notes if provided
        if (!empty($validated['notes'])) {
            $newNote = "[" . now()->format('Y-m-d H:i') . " - Dr. " . Auth::user()->employee->first_name . " " . Auth::user()->employee->last_name . "] " . $validated['notes'] . "\n\n";
            $appointment->notes = $appointment->notes ? $newNote . $appointment->notes : $newNote;
        }
        
        $appointment->save();
        
        return redirect()->route('dentist.appointments.show', $appointment)
            ->with('success', 'Appointment status updated successfully.');
    }

    /**
     * Display today's appointments.
     */
    public function todayAppointments()
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        $appointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_date')
            ->get();
            
        return view('dentist.appointments.today', compact('appointments'));
    }

    /**
     * Display upcoming appointments.
     */
    public function upcomingAppointments()
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        $appointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', '>', Carbon::today())
            ->whereIn('status', ['Scheduled', 'Confirmed'])
            ->orderBy('appointment_date')
            ->paginate(10);
            
        return view('dentist.appointments.upcoming', compact('appointments'));
    }

    /**
     * Display calendar view.
     */
    public function calendar()
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        $appointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', '>=', Carbon::now()->subDays(30))
            ->whereDate('appointment_date', '<=', Carbon::now()->addDays(60))
            ->get();
            
        return view('dentist.appointments.calendar', compact('appointments'));
    }

    /**
     * Get calendar events in JSON format for FullCalendar.
     */
    public function calendarEvents()
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return response()->json(['error' => 'No dentist profile found'], 403);
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        $appointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', '>=', Carbon::now()->subDays(30))
            ->whereDate('appointment_date', '<=', Carbon::now()->addDays(60))
            ->get();
            
        $events = [];
        
        foreach ($appointments as $appointment) {
            $patientName = $appointment->patient ? $appointment->patient->first_name . ' ' . $appointment->patient->last_name : 'No Patient';
            
            $events[] = [
                'id' => $appointment->appointment_id,
                'title' => $patientName . ' - ' . ($appointment->service ?? 'General Checkup'),
                'start' => $appointment->appointment_date,
                'end' => Carbon::parse($appointment->appointment_date)->addMinutes($appointment->duration ?? 30)->toDateTimeString(),
                'status' => $appointment->status,
                'color' => $this->getStatusColor($appointment->status),
            ];
        }
        
        return response()->json($events);
    }
    
    /**
     * Get appointment details in JSON format.
     */
    public function getAppointmentDetails(Appointment $appointment)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return response()->json(['success' => false, 'message' => 'No dentist profile found'], 403);
        }
        
        // Ensure the dentist can only view their own appointments
        if ($appointment->dentist_id != Auth::user()->employee->employee_id) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to view this appointment'], 403);
        }
        
        $appointment->load('patient');
        
        return response()->json([
            'success' => true,
            'appointment' => $appointment
        ]);
    }
    
    /**
     * Cancel an appointment.
     */
    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Ensure the dentist can only cancel their own appointments
        if ($appointment->dentist_id != Auth::user()->employee->employee_id) {
            return redirect()->route('dentist.appointments.index')
                ->with('error', 'You are not authorized to cancel this appointment.');
        }
        
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:5',
        ]);
        
        // Add cancellation reason as a note
        $cancellationNote = "[" . now()->format('Y-m-d H:i') . " - Dr. " . Auth::user()->employee->first_name . " " . Auth::user()->employee->last_name . "] CANCELED: " . $validated['cancellation_reason'] . "\n\n";
        $appointment->notes = $appointment->notes ? $cancellationNote . $appointment->notes : $cancellationNote;
        
        // Update appointment status
        $appointment->status = 'Canceled';
        $appointment->save();
        
        // Redirect based on where the request came from
        $referer = $request->headers->get('referer');
        
        if (strpos($referer, 'calendar') !== false) {
            return redirect()->route('dentist.appointments.calendar')
                ->with('success', 'Appointment canceled successfully.');
        }
        
        return redirect()->route('dentist.appointments.index')
            ->with('success', 'Appointment canceled successfully.');
    }
    
    /**
     * Helper method to get the status color for calendar events.
     */
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'Scheduled':
                return '#0dcaf0'; // info
            case 'In Progress':
                return '#0d6efd'; // primary
            case 'Completed':
                return '#198754'; // success
            case 'Canceled':
                return '#6c757d'; // secondary
            case 'No Show':
                return '#dc3545'; // danger
            default:
                return '#0dcaf0'; // info
        }
    }

    /**
     * Display appointments for a specific patient.
     */
    public function patientAppointments(Patient $patient)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Get all appointments for this patient that were created by this dentist
        $appointments = Appointment::with(['treatments'])
            ->where('patient_id', $patient->patient_id)
            ->where('dentist_id', $dentistId)
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
        
        return view('dentist.appointments.patient_appointments', compact('patient', 'appointments'));
    }
}