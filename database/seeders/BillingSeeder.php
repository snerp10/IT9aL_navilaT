<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all completed treatments
        $treatments = Treatment::where('status', 'completed')->get();
        
        if ($treatments->isEmpty()) {
            echo "Warning: Cannot create billing records. Make sure completed treatments exist in the database.\n";
            return;
        }

        // Create billing records for treatments
        $invoiceNumber = 10001; // Starting invoice number
        
        foreach ($treatments as $treatment) {
            // 80% of treatments are paid, 20% are pending
            $isPaid = (rand(1, 100) <= 80);
            
            // Create billing record
            $billing = Billing::create([
                'patient_id' => $treatment->patient_id,
                'treatment_id' => $treatment->treatment_id,
                'invoice_number' => 'INV-' . $invoiceNumber++,
                'amount_due' => $treatment->cost,
                'amount_paid' => $isPaid ? $treatment->cost : 0,
                'payment_status' => $isPaid ? 'Paid' : 'Pending',
                'payment_method' => $this->getRandomPaymentMethod(), // Always provide a payment method, even for pending payments
                'due_date' => Carbon::now()->addDays(30),
                'created_at' => $treatment->created_at,
                'updated_at' => $isPaid ? $treatment->updated_at : $treatment->created_at
            ]);
        }
    }
    
    /**
     * Get a random payment method.
     */
    private function getRandomPaymentMethod()
    {
        // Only use payment methods allowed in the migration
        $methods = ['Cash', 'GCash', 'Maya', 'PayPal'];
        return $methods[array_rand($methods)];
    }
}