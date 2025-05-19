<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'email' => 'admin@navilat.com',
            'password' => Hash::make('password123'),
            'role' => 'Admin',
            'email_verified_at' => now(),
        ]);

        // Create Receptionist User
        User::create([
            'email' => 'receptionist@navilat.com',
            'password' => Hash::make('password123'),
            'role' => 'Receptionist',
            'email_verified_at' => now(),
        ]);

        // Create Dentist Users (3)
        $dentists = [
            [
                'email' => 'dentist1@navilat.com',
                'specialization' => 'General Dentistry'
            ],
            [
                'email' => 'dentist2@navilat.com',
                'specialization' => 'Orthodontics'
            ],
            [
                'email' => 'dentist3@navilat.com',
                'specialization' => 'Pediatric Dentistry'
            ]
        ];

        foreach ($dentists as $dentist) {
            $userData = [
                'email' => $dentist['email'],
                'password' => Hash::make('password123'),
                'role' => 'Dentist',
                'email_verified_at' => now(),
            ];
            
            User::create($userData);
        }
    }
}