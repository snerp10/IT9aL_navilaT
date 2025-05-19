<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Display a listing of the billing records.
     */
    public function index()
    {
        $billings = Billing::with(['patient', 'treatment'])->get();
        return view('admin.billing.index', compact('billings'));
    }

    /**
     * Show the form for creating a new billing.
     */
    public function create()
    {
        $patients = Patient::all();
        $treatments = Treatment::with('appointment')->get();
        return view('admin.billing.create', compact('patients', 'treatments'));
    }

    /**
     * Store a newly created billing in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'treatment_id' => 'required|exists:treatments,treatment_id',
            'invoice_number' => 'required|string|unique:billing,invoice_number',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Pending,Paid,Overdue',
            'payment_method' => 'required|in:Cash,GCash,Maya,PayPal',
            'due_date' => 'nullable|date',
        ]);

        Billing::create($validated);

        return redirect()->route('billing.index')
            ->with('success', 'Billing record created successfully.');
    }

    /**
     * Display the specified billing.
     */
    public function show(Billing $billing)
    {
        $billing->load(['patient', 'treatment']);
        return view('admin.billing.show', compact('billing'));
    }

    /**
     * Show the form for editing the specified billing.
     */
    public function edit(Billing $billing)
    {
        $patients = Patient::all();
        $treatments = Treatment::with('appointment')->get();
        return view('admin.billing.edit', compact('billing', 'patients', 'treatments'));
    }

    /**
     * Update the specified billing in storage.
     */
    public function update(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'treatment_id' => 'required|exists:treatments,treatment_id',
            'invoice_number' => 'required|string|unique:billing,invoice_number,' . $billing->billing_id . ',billing_id',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Pending,Paid,Overdue',
            'payment_method' => 'required|in:Cash,GCash,Maya,PayPal',
            'due_date' => 'nullable|date',
        ]);

        $billing->update($validated);

        return redirect()->route('billing.index')
            ->with('success', 'Billing record updated successfully.');
    }

    /**
     * Remove the specified billing from storage.
     */
    public function destroy(Billing $billing)
    {
        $billing->delete();

        return redirect()->route('billing.index')
            ->with('success', 'Billing record deleted successfully.');
    }

    /**
     * Display billing records for a specific patient.
     */
    public function patientBillings(Patient $patient)
    {
        $billings = Billing::where('patient_id', $patient->patient_id)
            ->with('treatment')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.billing.patient-billings', compact('billings', 'patient'));
    }

    /**
     * Process payment for a billing record.
     */
    public function processPayment(Request $request, Billing $billing)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Cash,GCash,Maya,PayPal',
        ]);

        $newAmountPaid = $billing->amount_paid + $validated['amount_paid'];
        
        // Check if payment is completed
        $status = $billing->payment_status;
        if ($newAmountPaid >= $billing->amount_due) {
            $status = 'Paid';
        }

        $billing->update([
            'amount_paid' => $newAmountPaid,
            'payment_status' => $status,
            'payment_method' => $validated['payment_method'],
        ]);

        return redirect()->route('billing.show', $billing)
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Generate invoice PDF.
     */
    public function generateInvoice(Billing $billing)
    {
        $billing->load(['patient', 'treatment']);
        
        // PDF generation logic would go here
        // For example, using a package like barryvdh/laravel-dompdf
        
        return view('admin.billing.invoice', compact('billing'));
    }
    
    /**
     * Display the payments management interface.
     * Shows payment methods, transaction history, and payment processing options.
     */
    public function paymentsManagement()
    {
        // Fetch recent payments for the view
        $recentPayments = Billing::where('payment_status', 'Paid')
            ->orWhere('payment_status', 'Partial')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
            
        // Calculate total paid today
        $todayPayments = Billing::whereDate('updated_at', today())
            ->where(function($query) {
                $query->where('payment_status', 'Paid')
                      ->orWhere('payment_status', 'Partial');
            })
            ->sum('amount_paid');
        
        // Get count of pending payments
        $pendingCount = Billing::where('payment_status', 'Pending')
            ->orWhere('payment_status', 'Overdue')
            ->count();
            
        // Get payment statistics
        $paymentStats = [
            'total_paid_today' => $todayPayments,
            'pending_count' => $pendingCount,
            'recent_transactions' => $recentPayments->count(),
            'total_revenue_month' => Billing::whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->sum('amount_paid')
        ];
            
        return view('admin.billing.payments', compact('recentPayments', 'paymentStats'));
    }
    
    /**
     * Display a listing of pending payments.
     */
    public function pendingPayments()
    {
        $billings = Billing::where('payment_status', 'Pending')
            ->orWhere('payment_status', 'Overdue')
            ->with(['patient', 'treatment'])
            ->orderBy('due_date', 'asc')
            ->get();
            
        return view('admin.billing.index', [
            'billings' => $billings,
            'title' => 'Pending Payments',
            'status' => 'pending'
        ]);
    }
    
    /**
     * Display a listing of completed payments.
     */
    public function completedPayments()
    {
        $billings = Billing::where('payment_status', 'Paid')
            ->with(['patient', 'treatment'])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('admin.billing.index', [
            'billings' => $billings,
            'title' => 'Completed Payments',
            'status' => 'paid'
        ]);
    }
}