<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all patients
        $patients = Patient::all();
        
        // Get dentist employees
        $dentists = Employee::where('role', 'Dentist')->get();
        
        if ($patients->isEmpty() || $dentists->isEmpty()) {
            echo "Warning: Cannot create appointments. Make sure patients and dentists exist in the database.\n";
            return;
        }
        
        // Create past, current, and future appointments
        $statuses = ['Scheduled', 'Completed', 'Canceled', 'No Show'];
        $reasonsForVisit = [
            'Regular check-up',
            'Tooth pain',
            'Cleaning',
            'Root canal',
            'Filling',
            'Crown placement',
            'Wisdom tooth extraction',
            'Cosmetic consultation',
            'Orthodontic adjustment',
            'Emergency visit'
        ];
        
        // Start date for appointments (30 days ago)
        $startDate = Carbon::now()->subDays(30);
        
        // Generate 50 appointments spread across past and future dates
        for ($i = 0; $i < 50; $i++) {
            // Randomly select a patient and dentist
            $patient = $patients->random();
            $dentist = $dentists->random();
            
            // Create a date between 30 days ago and 30 days in future
            $appointmentDate = Carbon::now()->subDays(30)->addDays(rand(0, 60));
            
            // Create a time between 9am and 5pm
            $hour = rand(9, 16); // 9am to 4pm (to allow for hour-long appointments)
            $minute = [0, 15, 30, 45][rand(0, 3)]; // Quarter-hour intervals
            
            $appointmentDateTime = $appointmentDate->copy()->setHour($hour)->setMinute($minute)->setSecond(0);
            
            // Determine appointment status based on date
            $status = 'Scheduled';
            if ($appointmentDateTime->isPast()) {
                // Past appointments are either completed, cancelled, or no-show
                $status = $statuses[rand(1, 3)];
            }
            
            // Create the appointment
            Appointment::create([
                'patient_id' => $patient->patient_id,
                'dentist_id' => $dentist->employee_id,
                'appointment_date' => $appointmentDateTime,
                'status' => $status,
                'notes' => $this->getRandomNotes($status),
                'reason_for_visit' => $reasonsForVisit[array_rand($reasonsForVisit)],
                'created_at' => Carbon::now()->subDays(rand(1, 45)), // Random creation date
            ]);
        }
    }
    
    /**
     * Generate random appointment notes based on status.
     */
    private function getRandomNotes($status)
    {
        switch ($status) {
            case 'Scheduled':
                $notes = [
                    'Regular check-up appointment',
                    'Patient requested early morning appointment',
                    'Follow-up for previous treatment',
                    'New patient initial consultation',
                    'Tooth pain on upper right side',
                    'Needs cleaning and check for cavities',
                    'Cosmetic consultation requested'
                ];
                break;
                
            case 'Completed':
                $notes = [
                    'Treatment completed successfully',
                    'Patient arrived 10 minutes late but procedure went well',
                    'Recommended follow-up in 6 months',
                    'Advised better oral hygiene practices',
                    'Completed cleaning and found 2 cavities for treatment next visit',
                    'Patient is responding well to treatment plan'
                ];
                break;
                
            case 'Canceled':
                $notes = [
                    'Patient called to reschedule',
                    'Dentist had emergency, needs to be rescheduled',
                    'Patient feeling unwell, will call to reschedule',
                    'Family emergency, patient to call back',
                    'Bad weather conditions, office closed'
                ];
                break;
                
            case 'No Show':
                $notes = [
                    'Patient did not show up or call',
                    'Attempted to contact patient with no response',
                    'Second no-show, consider follow-up call',
                    'Patient forgot appointment, will reschedule',
                    'Patient arrived 30+ minutes late, could not be accommodated'
                ];
                break;
                
            default:
                $notes = ['No additional notes'];
        }
        
        return $notes[array_rand($notes)];
    }
}