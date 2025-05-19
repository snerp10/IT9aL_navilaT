<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the Seeders in the correct order
        $this->call([
            // First create users
            UserSeeder::class,
            
            // Then create patients and employees
            PatientSeeder::class, 
            EmployeeSeeder::class,
            
            // Create dental services
            DentalServiceSeeder::class,
            
            // Create appointments
            AppointmentSeeder::class,
            
            // Create treatments for completed appointments
            TreatmentSeeder::class,
            
            // Create billing records for treatments
            BillingSeeder::class,
            
            // Create financial reports
            FinancialReportSeeder::class,
            
            // Create suppliers and products last
            SupplierSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
