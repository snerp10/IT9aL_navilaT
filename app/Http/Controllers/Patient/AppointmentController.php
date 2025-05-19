<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient record not found. Please contact the clinic.');
        }
        $all = Appointment::where('patient_id', $patient->patient_id)
            ->orderBy('appointment_date', 'desc')
            ->get();
        $upcomingAppointments = $all->filter(function($a) {
            return $a->status === 'Scheduled' && \Carbon\Carbon::parse($a->appointment_date)->isToday() || ($a->status === 'Scheduled' && \Carbon\Carbon::parse($a->appointment_date)->isFuture());
        });
        $pastAppointments = $all->filter(function($a) {
            return in_array($a->status, ['Completed', 'No Show']) || ($a->status === 'Scheduled' && \Carbon\Carbon::parse($a->appointment_date)->isPast());
        });
        $cancelledAppointments = $all->filter(function($a) {
            return $a->status === 'Canceled';
        });
        return view('patient.appointments', compact('upcomingAppointments', 'pastAppointments', 'cancelledAppointments', 'patient'));
    }

    public function showBookForm()
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Your patient profile is not complete. Please contact the clinic to complete your registration.');
        }
        return view('patient.book-appointment');
    }

    public function book(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Your patient profile is not complete. Please contact the clinic to complete your registration.');
        }
        $validated = $request->validate([
            'service' => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string|in:morning,afternoon',
            'notes' => 'nullable|string|max:500',
        ]);
        $dentist = Employee::where('role', 'Dentist')->first();
        if (!$dentist) {
            return redirect()->route('patient.appointments')->with('error', 'No dentist available. Please contact the clinic.');
        }
        $appointment = new Appointment();
        $appointment->patient_id = $patient->patient_id;
        $appointment->dentist_id = $dentist->employee_id;
        $appointment->appointment_date = $validated['appointment_date'];
        $appointment->status = 'Scheduled';
        $appointment->reason_for_visit = 'Service: ' . $validated['service'] . ' | Time slot: ' . $validated['time_slot'];
        $appointment->notes = $validated['notes'] ?? null;
        $appointment->save();
        return redirect()->route('patient.appointments')
            ->with('success', 'Appointment request submitted successfully. We will contact you to confirm your appointment.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $patient = $user->patient;
        $appointment = Appointment::where('appointment_id', $id)
            ->where('patient_id', $patient->patient_id)
            ->firstOrFail();
        return view('patient.appointment-show', compact('appointment', 'patient'));
    }

    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        $patient = $user->patient;
        $appointment = Appointment::where('appointment_id', $id)
            ->where('patient_id', $patient->patient_id)
            ->firstOrFail();
        $appointment->status = 'Canceled';
        if ($request->filled('cancellation_reason')) {
            $appointment->cancellation_reason = $request->input('cancellation_reason');
        }
        $appointment->save();
        return redirect()->route('patient.appointments')->with('success', 'Appointment cancelled successfully.');
    }
}
