<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Treatment;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    /**
     * Display the receptionist dashboard
     */
    public function dashboard()
    {
        // Get today's appointments
        $todayAppointments = Appointment::with(['patient', 'dentist'])
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_date')
            ->get();
        
        // Get recently registered patients (last 5)
        $recentPatients = Patient::latest()->take(5)->get();
        
        // Count appointments by status
        $scheduledToday = $todayAppointments->where('status', 'Scheduled')->count();
        $completedToday = $todayAppointments->where('status', 'Completed')->count();
        $canceledToday = $todayAppointments->where('status', 'Canceled')->count();
        
        // Get all active dentists
        $dentists = Employee::where('role', 'Dentist')
            ->where('employment_status', 'Active')
            ->get();
            
        // Count of active dentists    
        $activeDentists = $dentists->count();
        
        // Get upcoming appointments for the next 7 days
        $upcomingAppointments = Appointment::with(['patient', 'dentist'])
            ->whereDate('appointment_date', '>=', Carbon::today())
            ->whereDate('appointment_date', '<=', Carbon::today()->addDays(7))
            ->orderBy('appointment_date')
            ->take(10)
            ->get();
        
        // Recent payments
        $recentPayments = Billing::with('patient')
            ->where('payment_status', '!=', 'Pending')
            ->latest()
            ->take(5)
            ->get();
        
        return view('receptionist.dashboard', compact(
            'todayAppointments',
            'recentPatients',
            'scheduledToday',
            'completedToday',
            'canceledToday',
            'activeDentists',
            'dentists', // Added dentists collection
            'upcomingAppointments',
            'recentPayments'
        ));
    }
    
    /**
     * Display the patient check-in page
     */
    public function patientCheckIn()
    {
        // Get today's appointments
        $todayAppointments = Appointment::with(['patient', 'dentist'])
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_date')
            ->get();
        
        return view('receptionist.patient-check-in', compact('todayAppointments'));
    }
    
    /**
     * Process a patient check-in
     */
    public function processCheckIn(Appointment $appointment)
    {
        try {
            // Update the appointment status
            $appointment->status = 'In Progress';
            $appointment->check_in_time = Carbon::now();
            $appointment->save();
            
            return redirect()->route('receptionist.patient-check-in')
                ->with('success', 'Patient successfully checked in for their appointment.');
        } catch (\Exception $e) {
            return redirect()->route('receptionist.patient-check-in')
                ->with('error', 'There was an error checking in the patient: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the appointment management page
     */
    public function appointments()
    {
        $appointments = Appointment::with(['patient', 'dentist'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
        return view('receptionist.appointments.index', compact('appointments'));
    }
    
    /**
     * Show form to create a new appointment
     */
    public function createAppointment()
    {
        $patients = Patient::all();
        $dentists = Employee::where('role', 'Dentist')->get();
        
        return view('receptionist.appointments.create', compact('patients', 'dentists'));
    }
    
    /**
     * Store a new appointment
     */
    public function storeAppointment(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'dentist_id' => 'required|exists:employees,employee_id',
            'appointment_date' => 'required|date|after:now',
            'status' => 'required|in:Scheduled,Completed,Canceled,No Show',
            'notes' => 'nullable|string',
            'reason_for_visit' => 'required|string',
        ]);
        
        Appointment::create($validated);
        
        return redirect()->route('receptionist.appointments')
            ->with('success', 'Appointment created successfully');
    }
    
    /**
     * Show patient search page
     */
    public function patientSearch()
    {
        return view('receptionist.patients.search');
    }
    
    /**
     * Process patient search
     */
    public function searchPatient(Request $request)
    {
        $query = $request->input('query');
        
        $patients = Patient::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('contact_number', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate(10);
            
        return view('receptionist.patients.search-results', compact('patients', 'query'));
    }
    
    /**
     * View patient details
     */
    public function patientDetails(Patient $patient)
    {
        $appointments = Appointment::where('patient_id', $patient->patient_id)
            ->with('dentist')
            ->orderBy('appointment_date', 'desc')
            ->get();
            
        $treatments = Treatment::whereHas('appointment', function($q) use ($patient) {
            $q->where('patient_id', $patient->patient_id);
        })->with(['dentalService', 'appointment.dentist'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        $billings = Billing::where('patient_id', $patient->patient_id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('receptionist.patients.details', compact(
            'patient',
            'appointments',
            'treatments',
            'billings'
        ));
    }
    
    /**
     * Display payment processing page
     */
    public function payments()
    {
        $pendingPayments = Billing::where('payment_status', 'Pending')
            ->with(['patient', 'treatment'])
            ->paginate(10);
            
        return view('receptionist.payments.index', compact('pendingPayments'));
    }
    
    /**
     * Process a payment
     */
    public function processPayment(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,GCash,Maya,PayPal',
        ]);
        
        // Update payment information
        $newAmountPaid = $billing->amount_paid + $validated['amount_paid'];
        $newStatus = $newAmountPaid >= $billing->amount_due ? 'Paid' : 'Partial';
        
        $billing->update([
            'amount_paid' => $newAmountPaid,
            'payment_status' => $newStatus,
            'payment_method' => $validated['payment_method'],
        ]);
        
        return redirect()->route('receptionist.payments')
            ->with('success', 'Payment processed successfully');
    }
}