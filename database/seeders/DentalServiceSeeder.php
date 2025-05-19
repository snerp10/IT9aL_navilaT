<?php

namespace Database\Seeders;

use App\Models\DentalService;
use Illuminate\Database\Seeder;

class DentalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Diagnostic & Preventive Services
            [
                'name' => 'Comprehensive Oral Examination',
                'description' => 'Thorough evaluation of teeth, gums, and oral cavity to detect dental problems.',
                'standard_cost' => 800.00,
                'standard_duration' => 30,
                'category' => 'Diagnostic',
                'is_active' => true
            ],
            [
                'name' => 'Routine Dental Check-up',
                'description' => 'Regular examination to maintain oral health and prevent dental issues.',
                'standard_cost' => 500.00,
                'standard_duration' => 20,
                'category' => 'Diagnostic',
                'is_active' => true
            ],
            [
                'name' => 'Dental X-rays',
                'description' => 'Digital imaging of teeth and jawbones to detect hidden dental problems.',
                'standard_cost' => 700.00,
                'standard_duration' => 15,
                'category' => 'Diagnostic',
                'is_active' => true
            ],
            [
                'name' => 'Dental Cleaning',
                'description' => 'Professional removal of plaque and tartar from teeth surfaces.',
                'standard_cost' => 1200.00,
                'standard_duration' => 45,
                'category' => 'Preventive',
                'is_active' => true
            ],
            [
                'name' => 'Dental Sealants',
                'description' => 'Protective coating applied to back teeth to prevent decay.',
                'standard_cost' => 400.00,
                'standard_duration' => 20,
                'category' => 'Preventive',
                'is_active' => true
            ],
            [
                'name' => 'Fluoride Treatment',
                'description' => 'Application of fluoride to teeth to prevent decay and strengthen enamel.',
                'standard_cost' => 300.00,
                'standard_duration' => 15,
                'category' => 'Preventive',
                'is_active' => true
            ],
            
            // Restorative Dentistry
            [
                'name' => 'Dental Filling (Composite)',
                'description' => 'Tooth-colored filling to repair decay or minor fractures.',
                'standard_cost' => 1500.00,
                'standard_duration' => 60,
                'category' => 'Restorative',
                'is_active' => true
            ],
            [
                'name' => 'Dental Filling (Amalgam)',
                'description' => 'Silver-colored filling to repair decay or minor fractures.',
                'standard_cost' => 1200.00,
                'standard_duration' => 45,
                'category' => 'Restorative',
                'is_active' => true
            ],
            [
                'name' => 'Dental Crown',
                'description' => 'Custom-made cap that covers a damaged or weak tooth.',
                'standard_cost' => 8000.00,
                'standard_duration' => 90,
                'category' => 'Restorative',
                'is_active' => true
            ],
            [
                'name' => 'Dental Bridge',
                'description' => 'Fixed replacement for missing teeth that bridges the gap.',
                'standard_cost' => 12000.00,
                'standard_duration' => 120,
                'category' => 'Restorative',
                'is_active' => true
            ],
            [
                'name' => 'Root Canal Therapy',
                'description' => 'Treatment of infected dental pulp to save a severely damaged tooth.',
                'standard_cost' => 7000.00,
                'standard_duration' => 90,
                'category' => 'Endodontic',
                'is_active' => true
            ],
            
            // Cosmetic Dentistry
            [
                'name' => 'Teeth Whitening',
                'description' => 'Professional bleaching to remove stains and discoloration from teeth.',
                'standard_cost' => 5000.00,
                'standard_duration' => 60,
                'category' => 'Cosmetic',
                'is_active' => true
            ],
            [
                'name' => 'Dental Veneers',
                'description' => 'Custom-made shells that cover the front surface of teeth to improve appearance.',
                'standard_cost' => 9000.00,
                'standard_duration' => 90,
                'category' => 'Cosmetic',
                'is_active' => true
            ],
            [
                'name' => 'Dental Bonding',
                'description' => 'Application of tooth-colored resin to repair or improve appearance of teeth.',
                'standard_cost' => 2500.00,
                'standard_duration' => 60,
                'category' => 'Cosmetic',
                'is_active' => true
            ],
            
            // Orthodontic Treatment
            [
                'name' => 'Orthodontic Consultation',
                'description' => 'Initial evaluation for orthodontic treatment options.',
                'standard_cost' => 1000.00,
                'standard_duration' => 45,
                'category' => 'Orthodontic',
                'is_active' => true
            ],
            [
                'name' => 'Traditional Braces',
                'description' => 'Metal brackets and wires to straighten teeth and correct bite issues.',
                'standard_cost' => 50000.00,
                'standard_duration' => 60,
                'category' => 'Orthodontic',
                'is_active' => true
            ],
            [
                'name' => 'Clear Aligners',
                'description' => 'Transparent, removable aligners for teeth straightening.',
                'standard_cost' => 70000.00,
                'standard_duration' => 60,
                'category' => 'Orthodontic',
                'is_active' => true
            ],
            
            // Periodontal Treatment
            [
                'name' => 'Scaling and Root Planing',
                'description' => 'Deep cleaning of teeth roots to treat gum disease.',
                'standard_cost' => 3000.00,
                'standard_duration' => 60,
                'category' => 'Periodontal',
                'is_active' => true
            ],
            [
                'name' => 'Periodontal Maintenance',
                'description' => 'Regular cleaning for patients with history of gum disease.',
                'standard_cost' => 1800.00,
                'standard_duration' => 45,
                'category' => 'Periodontal',
                'is_active' => true
            ],
            
            // Oral Surgery
            [
                'name' => 'Simple Tooth Extraction',
                'description' => 'Removal of a visible tooth from the mouth.',
                'standard_cost' => 1500.00,
                'standard_duration' => 45,
                'category' => 'Oral Surgery',
                'is_active' => true
            ],
            [
                'name' => 'Surgical Tooth Extraction',
                'description' => 'Removal of a tooth that cannot be easily accessed, such as impacted wisdom teeth.',
                'standard_cost' => 3500.00,
                'standard_duration' => 60,
                'category' => 'Oral Surgery',
                'is_active' => true
            ],
            [
                'name' => 'Wisdom Tooth Extraction',
                'description' => 'Removal of third molars, typically due to impaction or crowding.',
                'standard_cost' => 4000.00,
                'standard_duration' => 75,
                'category' => 'Oral Surgery',
                'is_active' => true
            ],
            
            // Prosthodontics
            [
                'name' => 'Complete Dentures',
                'description' => 'Removable replacement for missing teeth and surrounding tissues.',
                'standard_cost' => 15000.00,
                'standard_duration' => 90,
                'category' => 'Prosthodontic',
                'is_active' => true
            ],
            [
                'name' => 'Partial Dentures',
                'description' => 'Removable replacement for a section of missing teeth.',
                'standard_cost' => 10000.00,
                'standard_duration' => 75,
                'category' => 'Prosthodontic',
                'is_active' => true
            ],
            [
                'name' => 'Dental Implant Consultation',
                'description' => 'Initial evaluation for dental implant placement.',
                'standard_cost' => 1200.00,
                'standard_duration' => 45,
                'category' => 'Prosthodontic',
                'is_active' => true
            ],
            [
                'name' => 'Dental Implant Placement',
                'description' => 'Surgical placement of titanium post to replace tooth root.',
                'standard_cost' => 25000.00,
                'standard_duration' => 120,
                'category' => 'Prosthodontic',
                'is_active' => true
            ],
            [
                'name' => 'Implant Crown',
                'description' => 'Custom-made crown attached to dental implant.',
                'standard_cost' => 12000.00,
                'standard_duration' => 60,
                'category' => 'Prosthodontic',
                'is_active' => true
            ],
            
            // Pediatric Dentistry
            [
                'name' => 'Pediatric Dental Examination',
                'description' => 'Comprehensive dental check-up for children.',
                'standard_cost' => 600.00,
                'standard_duration' => 30,
                'category' => 'Pediatric',
                'is_active' => true
            ],
            [
                'name' => 'Pediatric Dental Cleaning',
                'description' => 'Professional teeth cleaning tailored for children.',
                'standard_cost' => 800.00,
                'standard_duration' => 30,
                'category' => 'Pediatric',
                'is_active' => true
            ],
            [
                'name' => 'Pediatric Dental Sealants',
                'description' => 'Preventive treatment to protect children\'s teeth from decay.',
                'standard_cost' => 350.00,
                'standard_duration' => 20,
                'category' => 'Pediatric',
                'is_active' => true
            ],
        ];

        foreach ($services as $service) {
            DentalService::create($service);
        }
    }
}