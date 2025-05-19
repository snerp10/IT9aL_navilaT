<?php

namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\ClinicalNote;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index(Request $request)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Base query - getting patients that have had appointments with this dentist
        $query = Patient::select('patients.*')
            ->distinct()
            ->join('appointments', 'patients.patient_id', '=', 'appointments.patient_id')
            ->where('appointments.dentist_id', $dentistId);
        
        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('patients.first_name', 'like', "%{$search}%")
                  ->orWhere('patients.last_name', 'like', "%{$search}%")
                  ->orWhere('patients.contact_number', 'like', "%{$search}%")
                  ->orWhere('patients.email', 'like', "%{$search}%");
            });
        }
        
        // Filter for recent patients if requested
        if ($request->has('recent') && $request->recent === 'true') {
            $query->whereExists(function ($query) use ($dentistId) {
                $query->select(\DB::raw(1))
                      ->from('appointments')
                      ->whereColumn('appointments.patient_id', 'patients.patient_id')
                      ->where('appointments.dentist_id', $dentistId)
                      ->whereDate('appointments.appointment_date', '>=', Carbon::now()->subDays(30));
            });
        }
        
        // Sort by last appointment date by default
        $patients = $query->orderByDesc(
            Appointment::select('appointment_date')
                ->whereColumn('appointments.patient_id', 'patients.patient_id')
                ->where('appointments.dentist_id', $dentistId)
                ->orderByDesc('appointment_date')
                ->limit(1)
        )->paginate(10)->withQueryString();
        
        return view('dentist.patients.index', compact('patients'));
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Get patient's appointments with this dentist
        $appointments = Appointment::where('patient_id', $patient->patient_id)
            ->where('dentist_id', $dentistId)
            ->orderByDesc('appointment_date')
            ->get();
        
        // Get treatments for this patient
        $treatments = Treatment::where('patient_id', $patient->patient_id)
            ->where('dentist_id', $dentistId)
            ->orderByDesc('created_at')
            ->get();
        
        // Set an empty collection for clinical notes since the table doesn't exist
        $clinicalNotes = collect();  // Using Laravel's collection instead of querying the non-existent table
        
        // Get billing records for this patient
        $billings = Billing::where('patient_id', $patient->patient_id)
            ->orderByDesc('billing_date')
            ->get();
        
        return view('dentist.patients.show', compact('patient', 'appointments', 'treatments', 'clinicalNotes', 'billings'));
    }

    /**
     * Display dental chart for the patient.
     */
    public function dentalChart(Patient $patient)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Get all treatments for this patient grouped by tooth number
        $treatments = Treatment::where('patient_id', $patient->patient_id)
            ->whereNotNull('tooth_number')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('tooth_number');
        
        return view('dentist.patients.dental-chart', compact('patient', 'treatments'));
    }

    /**
     * Add a clinical note for the patient.
     */
    public function addNote(Request $request, Patient $patient)
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a dentist profile. Please contact the administrator.');
        }
        
        // Since the clinical_notes table doesn't exist, we'll just provide feedback to the user
        return redirect()->route('dentist.patients.show', $patient->patient_id)
            ->with('info', 'Clinical notes feature is currently unavailable.');
    }
}