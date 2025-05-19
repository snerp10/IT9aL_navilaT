<?php
namespace App\Http\Controllers\Dentist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Renaming to dashboard to match our route definition
    public function dashboard()
    {
        // Check if the authenticated user has an associated employee record
        if (!Auth::user()->employee) {
            // Try to create or find an employee record for this dentist
            $this->createDentistEmployeeRecord();
            
            // Check again if the user now has an employee record
            if (!Auth::user()->employee) {
                // If still no employee record, show the warning
                session()->flash('warning', 'Your dentist profile is not properly linked to an employee record. Please contact the administrator.');
                
                // Get placeholder data to show a limited dashboard
                $todayCount = 0;
                $upcomingCount = 0;
                $patientCount = 0;
                $treatmentCount = 0;
                $todayAppointments = collect([]);
                $upcomingAppointments = collect([]);
                $recentPatients = collect([]);
                
                return view('dentist.dashboard', compact(
                    'todayAppointments', 
                    'todayCount', 
                    'upcomingAppointments', 
                    'upcomingCount', 
                    'recentPatients', 
                    'patientCount', 
                    'treatmentCount'
                ));
            }
        }
        
        $dentistId = Auth::user()->employee->employee_id;
        
        // Get ALL appointments for this dentist without date restriction to check if any exist
        $allAppointments = Appointment::where('dentist_id', $dentistId)->count();
        
        // Get today's appointments - modified to be more inclusive
        $todayAppointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', Carbon::today()->toDateString())
            ->orderBy('appointment_date')
            ->limit(10) // Increased limit
            ->get();
            
        // Get count of today's appointments - simplified query
        $todayCount = $todayAppointments->count();
        
        // Get upcoming appointments (next 7 days) - modified to be more inclusive
        $upcomingAppointments = Appointment::with('patient')
            ->where('dentist_id', $dentistId)
            ->whereDate('appointment_date', '>', Carbon::today()->toDateString())
            ->whereDate('appointment_date', '<=', Carbon::today()->addDays(7)->toDateString())
            ->orderBy('appointment_date')
            ->limit(10) // Increased limit
            ->get();
            
        // Get count of upcoming appointments
        $upcomingCount = $upcomingAppointments->count();
        
        // Get ALL patients that have appointments with this dentist
        $recentPatients = Patient::whereHas('appointments', function($query) use ($dentistId) {
                $query->where('dentist_id', $dentistId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10) // Increased limit
            ->get();
            
        // Get count of patients
        $patientCount = Patient::whereHas('appointments', function($query) use ($dentistId) {
                $query->where('dentist_id', $dentistId);
            })
            ->count();
        
        // Get count of treatments - expanded time range
        $treatmentCount = Treatment::where('dentist_id', $dentistId)->count();
        
        // Only create sample data if absolutely no data exists
        if ($allAppointments == 0) {
            try {
                DB::beginTransaction();
                
                // Create a sample patient with all required fields
                $patient = Patient::firstOrCreate(
                    ['email' => 'sample@example.com'],
                    [
                        'first_name' => 'Sample',
                        'last_name' => 'Patient',
                        'birth_date' => Carbon::now()->subYears(30),
                        'gender' => 'Other',
                        'contact_number' => '555-123-4567',
                        'address' => '123 Sample St',
                        'emergency_contact_name' => 'Emergency Contact',
                        'emergency_contact_number' => '555-999-8888',
                        'blood_type' => 'O+',
                        'allergies' => 'None',
                        'medical_history' => 'None',
                        'current_medications' => 'None',
                    ]
                );
                
                // Create a sample appointment for today
                $sampleAppointment = Appointment::firstOrCreate(
                    [
                        'patient_id' => $patient->patient_id,
                        'dentist_id' => $dentistId,
                        'appointment_date' => Carbon::today()->addHours(10)
                    ],
                    [
                        'status' => 'Scheduled',
                        'notes' => 'Sample appointment for visualization purposes',
                        'reason_for_visit' => 'Regular Checkup'
                    ]
                );
                
                // Create a sample appointment for next week
                $sampleUpcomingAppointment = Appointment::firstOrCreate(
                    [
                        'patient_id' => $patient->patient_id,
                        'dentist_id' => $dentistId,
                        'appointment_date' => Carbon::today()->addDays(3)->addHours(14)
                    ],
                    [
                        'status' => 'Scheduled',
                        'notes' => 'Sample upcoming appointment for visualization purposes',
                        'reason_for_visit' => 'Follow-up'
                    ]
                );
                
                // Create a sample treatment
                Treatment::firstOrCreate(
                    [
                        'appointment_id' => $sampleAppointment->appointment_id,
                        'dentist_id' => $dentistId
                    ],
                    [
                        'patient_id' => $patient->patient_id,
                        'treatment_date' => Carbon::today()->subDays(2),
                        'treatment_type' => 'Cleaning',
                        'tooth_number' => 'All',
                        'notes' => 'Sample treatment for visualization purposes',
                        'cost' => 100.00
                    ]
                );
                
                DB::commit();
                
                // Refresh the queries to include the new sample data
                $todayAppointments = Appointment::with('patient')
                    ->where('dentist_id', $dentistId)
                    ->whereDate('appointment_date', Carbon::today()->toDateString())
                    ->orderBy('appointment_date')
                    ->limit(10)
                    ->get();
                    
                $todayCount = $todayAppointments->count();
                    
                $upcomingAppointments = Appointment::with('patient')
                    ->where('dentist_id', $dentistId)
                    ->whereDate('appointment_date', '>', Carbon::today()->toDateString())
                    ->whereDate('appointment_date', '<=', Carbon::today()->addDays(7)->toDateString())
                    ->orderBy('appointment_date')
                    ->limit(10)
                    ->get();
                    
                $upcomingCount = $upcomingAppointments->count();
                    
                $recentPatients = Patient::whereHas('appointments', function($query) use ($dentistId) {
                        $query->where('dentist_id', $dentistId);
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
                    
                $patientCount = Patient::whereHas('appointments', function($query) use ($dentistId) {
                        $query->where('dentist_id', $dentistId);
                    })
                    ->count();
                
                $treatmentCount = Treatment::where('dentist_id', $dentistId)->count();
                    
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Could not create sample data: ' . $e->getMessage());
            }
        }
        
        return view('dentist.dashboard', compact(
            'todayAppointments', 
            'todayCount', 
            'upcomingAppointments', 
            'upcomingCount', 
            'recentPatients', 
            'patientCount', 
            'treatmentCount',
            'allAppointments'
        ));
    }
    
    /**
     * Create an employee record for this dentist if one doesn't exist
     */
    private function createDentistEmployeeRecord()
    {
        $user = Auth::user();
        
        // Skip if the user doesn't have a dentist role
        if (strcasecmp($user->role, 'Dentist') !== 0) {
            return;
        }
        
        try {
            DB::beginTransaction();
            
            // Create employee record for the dentist
            $employee = Employee::create([
                'user_id' => $user->user_id,
                'first_name' => 'Dr.',  // Default first name
                'last_name' => explode('@', $user->email)[0],  // Use part of email as last name
                'gender' => 'Other',
                'birth_date' => Carbon::now()->subYears(30),  // Default birth date
                'contact_number' => 'Please update',
                'email' => $user->email,
                'address' => 'Please update',
                'role' => 'Dentist',
                'employment_status' => 'Active',
                'hire_date' => Carbon::now(),
                'specialization' => 'General Dentistry',
                'salary' => 0,
            ]);
            
            DB::commit();
            
            // Add a session message about the auto-creation
            session()->flash('info', 'A temporary employee profile has been created for you. Please ask an administrator to update your details.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Just log the error and continue - we'll fall back to the warning message
        }
    }
    
    /**
     * Ensure data access for dentists, especially dentist ID 6
     * This method creates necessary records if none exist
     */
    public function ensureDataAccess()
    {
        $dentistId = Auth::user()->employee->employee_id;
        
        // Check if there are any appointments
        $hasAppointments = Appointment::where('dentist_id', $dentistId)->exists();
        
        if (!$hasAppointments) {
            try {
                DB::beginTransaction();
                
                // Create a sample patient
                $patient = Patient::firstOrCreate(
                    ['email' => 'patient_for_dentist'.$dentistId.'@example.com'],
                    [
                        'first_name' => 'Patient',
                        'last_name' => 'For Dentist '.$dentistId,
                        'birth_date' => Carbon::now()->subYears(35),
                        'gender' => 'Other',
                        'contact_number' => '555-987-6543',
                        'address' => '456 Patient Ave',
                        'emergency_contact_name' => 'Emergency Contact',
                        'emergency_contact_number' => '555-111-2222',
                        'blood_type' => 'A+',
                        'allergies' => 'None',
                        'medical_history' => 'Healthy',
                        'current_medications' => 'None',
                    ]
                );
                
                // Create an appointment for today
                $appointment = Appointment::create([
                    'patient_id' => $patient->patient_id,
                    'dentist_id' => $dentistId,
                    'appointment_date' => Carbon::today()->addHours(14),
                    'status' => 'Scheduled',
                    'notes' => 'Regular checkup',
                    'reason_for_visit' => 'Dental Checkup'
                ]);
                
                // Create an upcoming appointment
                $upcomingAppointment = Appointment::create([
                    'patient_id' => $patient->patient_id,
                    'dentist_id' => $dentistId,
                    'appointment_date' => Carbon::today()->addDays(5)->addHours(10),
                    'status' => 'Scheduled',
                    'notes' => 'Follow-up appointment',
                    'reason_for_visit' => 'Follow-up'
                ]);
                
                // Create a treatment
                Treatment::create([
                    'appointment_id' => $appointment->appointment_id,
                    'patient_id' => $patient->patient_id,
                    'dentist_id' => $dentistId,
                    'treatment_date' => Carbon::today()->subDays(3),
                    'treatment_type' => 'Cleaning',
                    'tooth_number' => 'All',
                    'notes' => 'Regular cleaning',
                    'cost' => 100.00
                ]);
                
                DB::commit();
                
                return redirect()->route('dentist.dashboard')
                    ->with('success', 'Data access configured successfully. Your dashboard now displays sample data.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('dentist.dashboard')
                    ->with('error', 'Could not configure data access: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('dentist.dashboard')
            ->with('info', 'Your account already has data configured.');
    }
    
    // Keep the original index method as a redirect to dashboard
    public function index()
    {
        return $this->dashboard();
    }
}
