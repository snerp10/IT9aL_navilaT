<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Initialize the query
        $query = Appointment::query()->with(['patient', 'dentist']);
        
        // Get view type
        $viewType = $request->input('view', 'default');
        
        // Handle special view types
        if ($viewType === 'dentist') {
            $selectedDate = $request->input('date', date('Y-m-d'));
            $dentists = Employee::where('role', 'Dentist')
                ->with(['appointments' => function($query) use ($selectedDate) {
                    $query->whereDate('appointment_date', $selectedDate)
                          ->with('patient');
                }])
                ->get();

            return view('admin.appointments.dentist-schedules', compact('dentists'));
        } 
        elseif ($request->input('status') === 'upcoming') {
            $dentists = Employee::where('role', 'Dentist')->get();
            
            // Get the period: today, tomorrow, week, month
            $period = $request->input('period', 'week');
            
            $startDate = Carbon::today();
            $endDate = null;
            
            // Set date range based on period
            switch ($period) {
                case 'today':
                    $endDate = Carbon::today()->endOfDay();
                    break;
                    
                case 'tomorrow':
                    $startDate = Carbon::tomorrow();
                    $endDate = Carbon::tomorrow()->endOfDay();
                    break;
                    
                case 'week':
                    $endDate = Carbon::today()->addDays(7);
                    break;
                    
                case 'month':
                    $endDate = Carbon::today()->addMonth();
                    break;
            }
            
            // Apply date range filter
            $query->whereBetween('appointment_date', [$startDate, $endDate]);
            
            // Apply dentist filter if provided
            if ($request->filled('dentist_id')) {
                $query->where('dentist_id', $request->dentist_id);
            }
            
            // Apply confirmation status filter if provided
            if ($request->input('confirmation') === 'confirmed') {
                $query->where('status', 'Confirmed');
            } elseif ($request->input('confirmation') === 'unconfirmed') {
                $query->whereIn('status', ['Scheduled', 'Pending']);
            }
            
            // Order by appointment date only (since appointment_time is part of appointment_date)
            $query->orderBy('appointment_date');
            
            // Paginate the results
            $appointments = $query->paginate(15)->withQueryString();
            
            // Group appointments by date for display
            $appointmentsByDate = $appointments->groupBy(function ($appointment) {
                return Carbon::parse($appointment->appointment_date)->format('Y-m-d');
            });
            
            // Calculate stats from the full query (not just current page)
            $statsQuery = clone $query;
            $allAppointments = $statsQuery->get();
            
            $todayCount = $allAppointments->filter(function ($appointment) {
                return Carbon::parse($appointment->appointment_date)->isToday();
            })->count();
            
            $confirmedCount = $allAppointments->where('status', 'Confirmed')->count();
            $unconfirmedCount = $allAppointments->whereIn('status', ['Scheduled', 'Pending'])->count();
            
            $weekCount = $allAppointments->filter(function ($appointment) {
                return Carbon::parse($appointment->appointment_date)->isBetween(
                    Carbon::today(),
                    Carbon::today()->addDays(7)
                );
            })->count();
            
            return view('admin.appointments.upcoming', compact(
                'appointments', 
                'appointmentsByDate', 
                'dentists', 
                'todayCount', 
                'confirmedCount', 
                'unconfirmedCount', 
                'weekCount'
            ));
        }
        
        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply role-based filters
        if ($user->role === 'Admin') {
            // No additional restrictions for admin
        } elseif ($user->role === 'Receptionist') {
            // No additional restrictions for receptionist
        } elseif ($user->role === 'Dentist') {
            $query->where('dentist_id', $user->employee->employee_id);
        } elseif ($user->role === 'Patient') {
            $query->where('patient_id', $user->patient->patient_id);
        } else {
            abort(403);
        }
        
        // Order by date
        $query->orderBy('appointment_date', 'desc');
        
        // Paginate the results
        $appointments = $query->paginate(10)->withQueryString();
        
        // Return the appropriate view based on user role
        if ($user->role === 'Admin') {
            return view('admin.appointments.index', compact('appointments'));
        } elseif ($user->role === 'Receptionist') {
            return view('receptionist.appointments.index', compact('appointments'));
        } elseif ($user->role === 'Dentist') {
            return view('dentist.appointments.index', compact('appointments'));
        } else {
            return view('patient.appointments.index', compact('appointments'));
        }
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            $patients = Patient::all();
            $dentists = Employee::where('role', 'Dentist')->get();
            return view('admin.appointments.create', compact('patients', 'dentists'));
        } elseif ($user->role === 'Receptionist') {
            $patients = Patient::all();
            $dentists = Employee::where('role', 'Dentist')->get();
            return view('receptionist.appointments.create', compact('patients', 'dentists'));
        }
        abort(403);
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['Admin', 'Receptionist'])) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'dentist_id' => 'required|exists:employees,employee_id',
            'appointment_date' => 'required|date|after:now',
            'status' => 'required|in:Scheduled,Completed,Canceled,No Show',
            'notes' => 'nullable|string',
            'reason_for_visit' => 'required|string',
        ]);

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment, Request $request)
    {
        $user = auth()->user();
        $appointment->load(['patient', 'dentist', 'treatments']);
        
        if ($user->role === 'Admin') {
            return view('admin.appointments.show', compact('appointment'));
        } elseif ($user->role === 'Receptionist') {
            return view('receptionist.appointments.show', compact('appointment'));
        } elseif ($user->role === 'Dentist' && $appointment->dentist_id == $user->employee->employee_id) {
            return view('dentist.appointments.show', compact('appointment'));
        } elseif ($user->role === 'Patient' && $appointment->patient_id == $user->patient->patient_id) {
            return view('patient.appointments.show', compact('appointment'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $user = auth()->user();
        if ($user->role === 'Admin') {
            $patients = Patient::all();
            $dentists = Employee::where('role', 'Dentist')->get();
            return view('admin.appointments.edit', compact('appointment', 'patients', 'dentists'));
        } elseif ($user->role === 'Receptionist') {
            $patients = Patient::all();
            $dentists = Employee::where('role', 'Dentist')->get();
            return view('receptionist.appointments.edit', compact('appointment', 'patients', 'dentists'));
        }
        abort(403);
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['Admin', 'Receptionist'])) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'dentist_id' => 'required|exists:employees,employee_id',
            'appointment_date' => 'required|date',
            'status' => 'required|in:Scheduled,Completed,Canceled,No Show', // Updated to match database enum values
            'notes' => 'nullable|string',
            'reason_for_visit' => 'required|string',
        ]);

        // Make sure status is a valid string
        if (isset($validated['status'])) {
            // Add quotes to ensure it's treated as a string
            $validated['status'] = (string) $validated['status'];
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['Admin', 'Receptionist'])) {
            abort(403);
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Display appointments for the specific dentist.
     */
    public function dentistSchedule(Employee $dentist)
    {
        $appointments = Appointment::where('dentist_id', $dentist->employee_id)
            ->with('patient')
            ->orderBy('appointment_date')
            ->get();
            
        return view('admin.appointments.dentist-schedules', compact('appointments', 'dentist'));
    }

    /**
     * Display appointments for the specific patient.
     */
    public function patientHistory(Patient $patient)
    {
        $appointments = Appointment::where('patient_id', $patient->patient_id)
            ->with('dentist')
            ->orderBy('appointment_date', 'desc')
            ->get();
            
        return view('appointments.patient-history', compact('appointments', 'patient'));
    }
}