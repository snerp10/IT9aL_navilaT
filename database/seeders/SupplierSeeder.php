<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Dental Solutions Inc.',
                'contact_person' => 'John Garcia',
                'phone' => '09123456789',
                'email' => 'info@dentalsolutions.com',
                'address' => '123 Dental Avenue, Makati City',
            ],
            [
                'name' => 'OrthoTech Supplies',
                'contact_person' => 'Maria Santos',
                'phone' => '09234567890',
                'email' => 'orders@orthotech.com',
                'address' => '456 Orthodontic Boulevard, Quezon City',
            ],
            [
                'name' => 'SurgicalDent Pro',
                'contact_person' => 'Dr. Roberto Cruz',
                'phone' => '09345678901',
                'email' => 'sales@surgicaldentpro.com',
                'address' => '789 Surgical Street, Pasig City',
            ],
            [
                'name' => 'Endo Specialists Co.',
                'contact_person' => 'Ana Reyes',
                'phone' => '09456789012',
                'email' => 'info@endospecialists.com',
                'address' => '321 Root Canal Road, Mandaluyong City',
            ],
            [
                'name' => 'ImpressionTech Systems',
                'contact_person' => 'Miguel Tan',
                'phone' => '09567890123',
                'email' => 'sales@impressiontech.com',
                'address' => '654 Impression Avenue, Taguig City',
            ],
            [
                'name' => 'MediClean Supplies',
                'contact_person' => 'Sofia Mendoza',
                'phone' => '09678901234',
                'email' => 'orders@mediclean.com',
                'address' => '987 Sanitization Street, Pasay City',
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }
        
        $this->command->info('Dental suppliers seeded successfully!');
    }
}