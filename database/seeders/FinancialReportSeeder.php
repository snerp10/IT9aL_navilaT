<?php

namespace Database\Seeders;

use App\Models\FinancialReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinancialReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $adminUser = User::where('role', 'Admin')->first();
        
        if (!$adminUser) {
            echo "Warning: Cannot create financial reports. No admin user found.\n";
            return;
        }
        
        $userId = $adminUser->id;
        
        // Generate monthly reports for the past 12 months
        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::now()->subMonths($i)->startOfMonth();
            
            // Randomize the financial data
            $revenueFromServices = rand(80000, 150000);
            $productCost = rand(10000, 30000);
            $totalExpenses = rand(40000, 70000);
            
            FinancialReport::create([
                'total_product_cost' => $productCost,
                'revenue_from_services' => $revenueFromServices,
                'total_expenses' => $totalExpenses,
                'report_date' => $date->format('Y-m-d'),
                'report_type' => 'monthly',
                'user_id' => $userId,
                'created_at' => $date->copy()->addDays(5), // Generated a few days after month end
                'updated_at' => $date->copy()->addDays(5)
            ]);
        }
        
        // Generate quarterly reports for the past 4 quarters
        for ($i = 0; $i < 4; $i++) {
            $date = Carbon::now()->subMonths($i * 3)->startOfQuarter();
            
            // Randomize the financial data (higher for quarterly)
            $revenueFromServices = rand(240000, 450000);
            $productCost = rand(30000, 90000);
            $totalExpenses = rand(120000, 210000);
            
            FinancialReport::create([
                'total_product_cost' => $productCost,
                'revenue_from_services' => $revenueFromServices,
                'total_expenses' => $totalExpenses,
                'report_date' => $date->format('Y-m-d'),
                'report_type' => 'quarterly',
                'user_id' => $userId,
                'created_at' => $date->copy()->addDays(10), // Generated a few days after quarter end
                'updated_at' => $date->copy()->addDays(10)
            ]);
        }
        
        // Generate annual report for previous year
        $annualDate = Carbon::now()->subYear()->startOfYear();
        
        FinancialReport::create([
            'total_product_cost' => rand(120000, 360000),
            'revenue_from_services' => rand(960000, 1800000),
            'total_expenses' => rand(480000, 840000),
            'report_date' => $annualDate->format('Y-m-d'),
            'report_type' => 'annual',
            'user_id' => $userId,
            'created_at' => $annualDate->copy()->addDays(15), // Generated a couple weeks after year end
            'updated_at' => $annualDate->copy()->addDays(15)
        ]);
    }
}