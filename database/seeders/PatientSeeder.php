<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @param int|null $index The index of the specific patient to seed (zero-based), or null to seed all
     */
    public function run(?int $index = null): void
    {
        $patients = [
            [
                'first_name' => 'Ana',
                'last_name' => 'Garcia',
                'email' => 'ana.garcia@example.com',
                'contact_number' => '09171234567',
                'address' => '123 Main St, Makati City',
                'birth_date' => '1985-06-15',
                'gender' => 'Female',
                'emergency_contact_name' => 'Roberto Garcia',
                'emergency_contact_number' => '09189876543',
                'medical_history' => 'No significant medical issues',
                'allergies' => 'Penicillin',
                'create_user' => true
            ],
            [
                'first_name' => 'Ricardo',
                'last_name' => 'Lim',
                'email' => 'ricardo.lim@example.com',
                'contact_number' => '09182345678',
                'address' => '456 Oak Avenue, Quezon City',
                'birth_date' => '1990-02-23',
                'gender' => 'Male',
                'emergency_contact_name' => 'Maria Lim',
                'emergency_contact_number' => '09187654321',
                'medical_history' => 'Hypertension',
                'allergies' => 'None',
                'create_user' => true
            ],
            [
                'first_name' => 'Elena',
                'last_name' => 'Santos',
                'email' => 'elena.santos@example.com',
                'contact_number' => '09193456789',
                'address' => '789 Pine Lane, Taguig City',
                'birth_date' => '1978-11-10',
                'gender' => 'Female',
                'emergency_contact_name' => 'Pedro Santos',
                'emergency_contact_number' => '09197654321',
                'medical_history' => 'Diabetes Type 2',
                'allergies' => 'Sulfa drugs',
                'create_user' => true
            ],
            [
                'first_name' => 'Miguel',
                'last_name' => 'Reyes',
                'email' => 'miguel.reyes@example.com',
                'contact_number' => '09204567890',
                'address' => '101 Cedar Drive, Pasig City',
                'birth_date' => '1995-08-05',
                'gender' => 'Male',
                'emergency_contact_name' => 'Clara Reyes',
                'emergency_contact_number' => '09208765432',
                'medical_history' => 'No significant medical issues',
                'allergies' => 'None',
                'create_user' => true
            ],
            [
                'first_name' => 'Isabella',
                'last_name' => 'Tan',
                'email' => 'isabella.tan@example.com',
                'contact_number' => '09215678901',
                'address' => '202 Maple Court, Mandaluyong City',
                'birth_date' => '1988-04-12',
                'gender' => 'Female',
                'emergency_contact_name' => 'David Tan',
                'emergency_contact_number' => '09218765432',
                'medical_history' => 'Asthma',
                'allergies' => 'Shellfish',
                'create_user' => true
            ],
            [
                'first_name' => 'Gabriel',
                'last_name' => 'Cruz',
                'email' => 'gabriel.cruz@example.com',
                'contact_number' => '09226789012',
                'address' => '303 Birch Street, San Juan City',
                'birth_date' => '1992-09-30',
                'gender' => 'Male',
                'emergency_contact_name' => 'Sophia Cruz',
                'emergency_contact_number' => '09227654321',
                'medical_history' => 'Underwent appendectomy in 2018',
                'allergies' => 'Ibuprofen',
                'create_user' => true
            ],
            [
                'first_name' => 'Sofia',
                'last_name' => 'Rivera',
                'email' => 'sofia.rivera@example.com',
                'contact_number' => '09237890123',
                'address' => '404 Palm Road, Parañaque City',
                'birth_date' => '1983-12-18',
                'gender' => 'Female',
                'emergency_contact_name' => 'Mateo Rivera',
                'emergency_contact_number' => '09238765432',
                'medical_history' => 'Had braces as a teenager',
                'allergies' => 'None',
                'create_user' => true
            ],
            [
                'first_name' => 'Diego',
                'last_name' => 'Gonzalez',
                'email' => 'diego.gonzalez@example.com',
                'contact_number' => '09248901234',
                'address' => '505 Spruce Avenue, Muntinlupa City',
                'birth_date' => '1975-07-25',
                'gender' => 'Male',
                'emergency_contact_name' => 'Camila Gonzalez',
                'emergency_contact_number' => '09247654321',
                'medical_history' => 'High cholesterol',
                'allergies' => 'Aspirin',
                'create_user' => true
            ],
            [
                'first_name' => 'Valentina',
                'last_name' => 'Lopez',
                'email' => 'valentina.lopez@example.com',
                'contact_number' => '09259012345',
                'address' => '606 Elm Boulevard, Las Piñas City',
                'birth_date' => '1998-02-14',
                'gender' => 'Female',
                'emergency_contact_name' => 'Javier Lopez',
                'emergency_contact_number' => '09258765432',
                'medical_history' => 'No significant medical issues',
                'allergies' => 'None',
                'create_user' => true
            ],
            [
                'first_name' => 'Lucas',
                'last_name' => 'Fernandez',
                'email' => 'lucas.fernandez@example.com',
                'contact_number' => '09260123456',
                'address' => '707 Oak Street, Marikina City',
                'birth_date' => '1980-05-08',
                'gender' => 'Male',
                'emergency_contact_name' => 'Isabella Fernandez',
                'emergency_contact_number' => '09268765432',
                'medical_history' => 'Previous root canal on lower right molar',
                'allergies' => 'Latex',
                'create_user' => true
            ]
        ];

        if ($index !== null) {
            // Seed a specific patient if index is provided
            if (isset($patients[$index])) {
                $this->seedPatient($patients[$index]);
                $this->command->info("Seeded patient: {$patients[$index]['first_name']} {$patients[$index]['last_name']}");
            } else {
                $this->command->error("Invalid patient index. Available indexes: 0-" . (count($patients) - 1));
            }
        } else {
            // Seed all patients
            foreach ($patients as $patientData) {
                $this->seedPatient($patientData);
            }
            $this->command->info("Seeded all patients");
        }
    }

    /**
     * Seed a single patient
     * 
     * @param array $patientData The patient data to seed
     */
    private function seedPatient(array $patientData): void
    {
        $createUser = $patientData['create_user'] ?? false;
        unset($patientData['create_user']);
        
        // Check if patient with this email already exists
        $existingPatient = Patient::where('email', $patientData['email'])->first();
        if ($existingPatient) {
            $this->command->warn("Patient with email {$patientData['email']} already exists. Skipping.");
            return;
        }
        
        // Create the patient record
        $patient = Patient::create($patientData);
        
        // If we need to create a user account for this patient
        if ($createUser) {
            // Check if user with this email already exists
            $existingUser = User::where('email', $patientData['email'])->first();
            
            if ($existingUser) {
                // Link existing user to the patient
                $patient->user_id = $existingUser->user_id;
                $patient->save();
                $this->command->info("Linked patient to existing user with email: {$patientData['email']}");
            } else {
                // Create new user
                $user = User::create([
                    'email' => $patientData['email'],
                    'password' => Hash::make('patient123'),
                    'role' => 'Patient',
                    'email_verified_at' => now(),
                ]);
                
                // Link user ID to patient
                $patient->user_id = $user->user_id;
                $patient->save();
                $this->command->info("Created new user for patient with email: {$patientData['email']}");
            }
        }
    }
}