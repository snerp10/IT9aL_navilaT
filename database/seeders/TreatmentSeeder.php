<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\DentalService;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all completed appointments
        $completedAppointments = Appointment::where('status', 'Completed')->get();
        
        // Get all dental services
        $dentalServices = DentalService::where('is_active', true)->get();
        
        if ($completedAppointments->isEmpty() || $dentalServices->isEmpty()) {
            echo "Warning: Cannot create treatments. Make sure completed appointments and dental services exist in the database.\n";
            return;
        }

        // Create treatments for completed appointments
        foreach ($completedAppointments as $appointment) {
            // Number of services performed in this appointment (1-3)
            $numServices = rand(1, 3);
            
            // Choose random services for this appointment
            $selectedServices = $dentalServices->random(min($numServices, $dentalServices->count()));
            
            foreach ($selectedServices as $service) {
                // Create treatment
                Treatment::create([
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->appointment_id,
                    'dentist_id' => $appointment->dentist_id,
                    'service_id' => $service->service_id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'treatment_date' => $appointment->appointment_date->toDateString(),
                    'notes' => $this->getRandomTreatmentNotes($service->category),
                    'tooth_number' => $this->getRandomToothNumber($service->category),
                    'status' => 'completed',
                    'cost' => $service->standard_cost * (rand(90, 110) / 100), // Randomize the cost a bit (±10%)
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->appointment_date
                ]);
            }
        }
    }
    
    /**
     * Generate random treatment notes based on service category.
     */
    private function getRandomTreatmentNotes($category)
    {
        switch ($category) {
            case 'Diagnostic':
                $notes = [
                    'Complete examination performed. No significant issues detected.',
                    'X-rays show potential cavity on tooth #18.',
                    'Regular check-up performed. Patient has good oral hygiene.',
                    'Recommended more frequent brushing and flossing.',
                    'Reviewed patient\'s oral health history and current practices.'
                ];
                break;
                
            case 'Preventive':
                $notes = [
                    'Preventive cleaning completed. Removed plaque and tartar.',
                    'Applied fluoride varnish to strengthen enamel.',
                    'Dental sealants applied to molars to prevent decay.',
                    'Patient shows improved oral hygiene since last visit.',
                    'Demonstrated proper brushing and flossing techniques.'
                ];
                break;
                
            case 'Restorative':
                $notes = [
                    'Composite filling placed on tooth. Good margin adaptation.',
                    'Crown preparation completed. Temporary crown placed.',
                    'Root canal completed on tooth. No complications.',
                    'Amalgam filling replaced with composite for better aesthetics.',
                    'Bridge work started. Impressions taken for lab fabrication.'
                ];
                break;
                
            case 'Cosmetic':
                $notes = [
                    'Teeth whitening procedure completed. Patient satisfied with results.',
                    'Veneer preparation completed on front teeth.',
                    'Dental bonding performed to repair chipped tooth.',
                    'Discussed options for smile makeover.',
                    'Took impressions for cosmetic procedures.'
                ];
                break;
                
            case 'Orthodontic':
                $notes = [
                    'Initial orthodontic consultation. Discussed treatment options.',
                    'Braces adjusted. Patient experiencing minor discomfort.',
                    'Clear aligner trays provided with wearing instructions.',
                    'Progress evaluation shows good teeth movement.',
                    'Retainer fitted and care instructions provided.'
                ];
                break;
                
            case 'Periodontal':
                $notes = [
                    'Deep cleaning performed to treat gum disease.',
                    'Scaling and root planing completed on upper quadrant.',
                    'Gum tissue showing improvement since last treatment.',
                    'Localized antibiotic treatment applied to affected areas.',
                    'Recommended specialized mouth rinse for gum health.'
                ];
                break;
                
            case 'Oral Surgery':
                $notes = [
                    'Tooth extraction performed without complications.',
                    'Wisdom tooth removed. Post-operative instructions given.',
                    'Surgical extraction of impacted tooth completed.',
                    'Sutures placed and post-surgical care explained.',
                    'Follow-up appointment scheduled to check healing.'
                ];
                break;
                
            case 'Prosthodontic':
                $notes = [
                    'Denture impressions taken and sent to lab.',
                    'Denture delivered and adjusted for comfort.',
                    'Implant placement surgery performed successfully.',
                    'Abutment placed on implant. Ready for final crown.',
                    'Final crown placed on implant. Occlusion adjusted.'
                ];
                break;
                
            case 'Endodontic':
                $notes = [
                    'Root canal therapy initiated. Temporary filling placed.',
                    'Root canal completed with gutta-percha filling.',
                    'Post-endodontic restoration discussed with patient.',
                    'Evaluated for persistent pain following root canal.',
                    'Apicoectomy performed to treat infection at root tip.'
                ];
                break;
                
            case 'Pediatric':
                $notes = [
                    'Child\'s first dental visit. Examination and cleaning completed.',
                    'Applied fluoride treatment to prevent cavities.',
                    'Sealants placed on permanent molars.',
                    'Treated minor cavity with minimal invasive technique.',
                    'Discussed proper oral hygiene habits with parent and child.'
                ];
                break;
                
            default:
                $notes = ['Treatment completed successfully.'];
        }
        
        return $notes[array_rand($notes)];
    }
    
    /**
     * Generate random tooth numbers based on service category.
     * Returns an integer value or null, as the tooth_number field is an integer in the database.
     */
    private function getRandomToothNumber($category)
    {
        // Categories that typically don't involve specific teeth
        $noToothCategories = ['Diagnostic', 'Preventive', 'Cosmetic', 'Orthodontic', 'Periodontal'];
        
        if (in_array($category, $noToothCategories)) {
            return null;
        }
        
        // Adult teeth are numbered 1-32 in standard notation
        switch ($category) {
            case 'Oral Surgery':
                // Wisdom teeth are 1, 16, 17, 32
                $wisdomTeeth = [1, 16, 17, 32];
                return $wisdomTeeth[array_rand($wisdomTeeth)];
                
            default:
                // Random tooth number
                return rand(1, 32);
        }
    }
}