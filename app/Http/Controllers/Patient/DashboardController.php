<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the patient dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();
        $patient = $user->patient;
        
        // Check if patient record exists
        if (!$patient) {
            // If patient record doesn't exist, show a welcome screen with limited functionality
            return view('patient.dashboard-welcome', [
                'user' => $user
            ]);
        }
        
        // Use patient_id instead of id
        $patientId = $patient->patient_id;
        
        // Get upcoming appointments
        $upcomingAppointments = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            ->orderBy('appointment_date')
            ->take(5)
            ->get();
            
        // Get recent treatments
        $recentTreatments = Treatment::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get pending bills
        $pendingBills = Billing::where('patient_id', $patientId)
            ->where('payment_status', 'pending')
            ->orderBy('due_date')
            ->get();
            
        return view('patient.dashboard', compact(
            'patient', 
            'upcomingAppointments', 
            'recentTreatments', 
            'pendingBills'
        ));
    }
    
    /**
     * Display the patient's appointment history.
     *
     * @return \Illuminate\Http\Response
     */
    public function appointments()
    {
        $user = Auth::user();
        $patient = $user->patient;
        
        // Check if patient record exists
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient record not found. Please contact the clinic.');
        }
        
        // Use patient_id instead of id
        $patientId = $patient->patient_id;
        
        $appointments = Appointment::where('patient_id', $patientId)
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
        return view('patient.appointments', compact('appointments', 'patient'));
    }
    
    /**
     * Display the patient's treatment history.
     *
     * @return \Illuminate\Http\Response
     */
    public function treatments()
    {
        $user = Auth::user();
        $patient = $user->patient;
        
        // Check if patient record exists
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient record not found. Please contact the clinic.');
        }
        
        // Use patient_id instead of id
        $patientId = $patient->patient_id;
        
        $treatments = Treatment::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('patient.treatments', compact('treatments', 'patient'));
    }
    
    /**
     * Display the patient's billing history.
     *
     * @return \Illuminate\Http\Response
     */
    public function billings()
    {
        $user = Auth::user();
        $patient = $user->patient;
        
        // Check if patient record exists
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient record not found. Please contact the clinic.');
        }
        
        // Use patient_id instead of id
        $patientId = $patient->patient_id;
        
        $billings = Billing::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('patient.billings', compact('billings', 'patient'));
    }
    
    /**
     * Book a new appointment for the patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookAppointment(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;
        
        // Check if patient record exists
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Your patient profile is not complete. Please contact the clinic to complete your registration.');
        }
        
        // Validate the incoming request
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string|in:morning,afternoon',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Create a new appointment
        $appointment = new Appointment();
        $appointment->patient_id = $patient->patient_id;
        $appointment->appointment_date = $validated['appointment_date'];
        $appointment->status = 'Requested'; // Initial status
        $appointment->reason_for_visit = 'Service: ' . $validated['service'] . ' | Time slot: ' . $validated['time_slot'];
        $appointment->notes = $validated['notes'] ?? null;
        $appointment->save();
        
        return redirect()->route('patient.appointments')
            ->with('success', 'Appointment request submitted successfully. We will contact you to confirm your appointment.');
    }
    
    /**
     * Show the appointment booking form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showBookAppointmentForm()
    {
        $user = Auth::user();
        $patient = $user->patient;
        
        // Check if patient record exists
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Your patient profile is not complete. Please contact the clinic to complete your registration.');
        }
        
        return view('patient.book-appointment');
    }
    
    /**
     * Show form to complete patient profile if missing.
     */
    public function completeProfileForm()
    {
        $user = Auth::user();
        if ($user->patient) {
            return redirect()->route('patient.dashboard')->with('info', 'Your profile is already complete.');
        }
        return view('patient.complete-profile', ['user' => $user]);
    }

    /**
     * Store completed patient profile.
     */
    public function completeProfileSave(Request $request)
    {
        $user = Auth::user();
        if ($user->patient) {
            return redirect()->route('patient.dashboard')->with('info', 'Your profile is already complete.');
        }
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_number' => 'required|string|max:20',
        ]);
        $validated['user_id'] = $user->user_id;
        $validated['email'] = $user->email;
        
        \App\Models\Patient::create($validated);
        return redirect()->route('patient.dashboard')->with('success', 'Profile completed successfully!');
    }
}
