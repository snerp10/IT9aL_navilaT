<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(?int $index = null): void
    {
        // Define employees data
        $employees = [
            // Dentist 1
            [
                'first_name' => 'Dr. Juan',
                'last_name' => 'Reyes',
                'middle_name' => null,
                'gender' => 'Male',
                'role' => 'Dentist',
                'specialization' => 'General Dentistry',
                'contact_number' => '09' . rand(100000000, 999999999),
                'email' => 'dentist1@example.com',
                'address' => $this->getRandomAddress(),
                'hire_date' => now()->subMonths(rand(1, 36))->format('Y-m-d'),
                'birth_date' => now()->subYears(rand(28, 60))->format('Y-m-d'),
                'salary' => rand(50000, 80000),
                'employment_status' => 'Active',
                'years_of_experience' => rand(5, 15),
                'education' => 'Doctor of Dental Medicine, University of the Philippines',
                'certifications' => 'Board Certified, Philippine Dental Association'
            ],
            // Dentist 2
            [
                'first_name' => 'Dr. Sofia',
                'last_name' => 'Cruz',
                'middle_name' => 'L',
                'gender' => 'Female',
                'role' => 'Dentist',
                'specialization' => 'Orthodontics',
                'contact_number' => '09' . rand(100000000, 999999999),
                'email' => 'dentist2@example.com',
                'address' => $this->getRandomAddress(),
                'hire_date' => now()->subMonths(rand(1, 36))->format('Y-m-d'),
                'birth_date' => now()->subYears(rand(28, 60))->format('Y-m-d'),
                'salary' => rand(50000, 80000),
                'employment_status' => 'Active',
                'years_of_experience' => rand(5, 15),
                'education' => 'Doctor of Dental Medicine, University of the East',
                'certifications' => 'Specialty in Orthodontics, American Board of Orthodontics'
            ],
            // Dentist 3
            [
                'first_name' => 'Dr. Carlos',
                'last_name' => 'Mendoza',
                'middle_name' => null,
                'gender' => 'Male',
                'role' => 'Dentist',
                'specialization' => 'Pediatric Dentistry',
                'contact_number' => '09' . rand(100000000, 999999999),
                'email' => 'dentist3@example.com',
                'address' => $this->getRandomAddress(),
                'hire_date' => now()->subMonths(rand(1, 36))->format('Y-m-d'),
                'birth_date' => now()->subYears(rand(28, 60))->format('Y-m-d'),
                'salary' => rand(50000, 80000),
                'employment_status' => 'Active',
                'years_of_experience' => rand(5, 15),
                'education' => 'Doctor of Dental Medicine, Centro Escolar University',
                'certifications' => 'Specialty in Pediatric Dentistry, Philippine Board of Pediatric Dentistry'
            ],
            // Receptionist
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'middle_name' => 'G',
                'gender' => 'Female',
                'role' => 'Receptionist',
                'specialization' => null,
                'contact_number' => '09' . rand(100000000, 999999999),
                'email' => 'receptionist@example.com',
                'address' => $this->getRandomAddress(),
                'hire_date' => now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'birth_date' => now()->subYears(rand(25, 45))->format('Y-m-d'),
                'salary' => rand(25000, 35000),
                'employment_status' => 'Active',
                'years_of_experience' => rand(1, 5),
                'education' => 'Bachelor of Science in Business Administration',
                'certifications' => null
            ],
            // Admin
            [
                'first_name' => 'Rafael',
                'last_name' => 'Torres',
                'middle_name' => null,
                'gender' => 'Male',
                'role' => 'Admin',
                'specialization' => null,
                'contact_number' => '09' . rand(100000000, 999999999),
                'email' => 'admin@example.com',
                'address' => $this->getRandomAddress(),
                'hire_date' => now()->subMonths(rand(1, 36))->format('Y-m-d'),
                'birth_date' => now()->subYears(rand(30, 50))->format('Y-m-d'),
                'salary' => rand(40000, 60000),
                'employment_status' => 'Active',
                'years_of_experience' => rand(5, 10),
                'education' => 'Master of Business Administration',
                'certifications' => 'Healthcare Management Certification'
            ]
        ];

        if ($index !== null) {
            // Seed a specific employee if index is provided
            if (isset($employees[$index])) {
                $this->seedEmployee($employees[$index]);
                $this->command->info("Seeded employee: {$employees[$index]['first_name']} {$employees[$index]['last_name']}");
            } else {
                $this->command->error("Invalid employee index. Available indexes: 0-" . (count($employees) - 1));
            }
        } else {
            // Seed all employees
            foreach ($employees as $employeeData) {
                $this->seedEmployee($employeeData);
            }
            $this->command->info("Seeded all employees");
        }
    }
    
    /**
     * Seed a single employee
     * 
     * @param array $employeeData The employee data to seed
     */
    private function seedEmployee(array $employeeData): void
    {
        // Check if employee with this email already exists
        $existingEmployee = Employee::where('email', $employeeData['email'])->first();
        if ($existingEmployee) {
            $this->command->warn("Employee with email {$employeeData['email']} already exists. Skipping.");
            return;
        }
        
        // Check if user exists or create one
        $user = User::where('email', $employeeData['email'])->first();
        
        if (!$user) {
            $user = User::create([
                'email' => $employeeData['email'],
                'password' => Hash::make('password123'),
                'role' => strtolower($employeeData['role']),
                'email_verified_at' => now(),
            ]);
            $this->command->info("Created new user for {$employeeData['role']} with email: {$employeeData['email']}");
        }
        
        // Create the employee record
        $employee = new Employee($employeeData);
        $employee->user_id = $user->user_id;
        $employee->save();
        
        $this->command->info("Created employee: {$employeeData['first_name']} {$employeeData['last_name']}");
    }
    
    /**
     * Get a random address.
     */
    private function getRandomAddress()
    {
        $streets = [
            'Rizal Avenue',
            'Mabini Street',
            'Quezon Boulevard',
            'Taft Avenue',
            'EDSA',
            'Ortigas Avenue',
            'Shaw Boulevard',
            'Katipunan Road',
            'Timog Avenue',
            'Aurora Boulevard'
        ];
        
        $cities = [
            'Makati City',
            'Quezon City',
            'Manila',
            'Taguig City',
            'Pasig City',
            'Mandaluyong City',
            'San Juan City',
            'Parañaque City',
            'Pasay City',
            'Marikina City'
        ];
        
        $streetNumber = rand(1, 999);
        $street = $streets[array_rand($streets)];
        $city = $cities[array_rand($cities)];
        
        return "{$streetNumber} {$street}, {$city}";
    }
}