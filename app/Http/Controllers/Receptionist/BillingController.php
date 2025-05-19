<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    /**
     * Display a listing of the billings.
     */
    public function index(Request $request)
    {
        $query = Billing::with(['patient', 'treatment']);
        
        // Apply filters if provided
        if ($request->has('status') && !empty($request->status)) {
            $query->where('payment_status', $request->status);
        }
        
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }
        
        // Order by date descending
        $query->orderBy('invoice_date', 'desc');
        
        $billings = $query->paginate(10);
        
        return view('receptionist.billing.index', compact('billings'));
    }

    /**
     * Show the form for creating a new billing.
     */
    public function create(Request $request)
    {
        $selectedPatientId = $request->input('patient_id');
        $selectedTreatmentId = $request->input('treatment_id');
        
        $patient = null;
        $treatment = null;
        
        if ($selectedPatientId) {
            $patient = Patient::findOrFail($selectedPatientId);
        }
        
        if ($selectedTreatmentId) {
            $treatment = Treatment::with('appointment.dentist')->findOrFail($selectedTreatmentId);
        }
        
        $patients = Patient::orderBy('last_name')->get();
        $treatments = Treatment::whereDoesntHave('billings', function($query) {
                          $query->where('payment_status', 'Paid');
                      })
                      ->where('status', 'Completed')
                      ->with('patient', 'appointment.dentist')
                      ->get();
        
        return view('receptionist.billing.create', compact(
            'patients', 
            'treatments', 
            'patient', 
            'treatment',
            'selectedPatientId',
            'selectedTreatmentId'
        ));
    }

    /**
     * Store a newly created billing in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'treatment_id' => 'nullable|exists:treatments,treatment_id',
            'description' => 'required|string|max:255',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'payment_status' => 'required|in:Pending,Partial,Paid',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'additional_charges' => 'nullable|numeric|min:0',
            'additional_charges_description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Create an invoice number (format: INV-YEAR-MONTH-RANDOM)
        $invoiceNumber = 'INV-' . date('Ym') . '-' . Str::upper(Str::random(5));
        
        // Calculate payment status based on amount paid
        $amountDue = $request->amount_due + ($request->additional_charges ?? 0) - ($request->discount ?? 0);
        $amountPaid = $request->amount_paid ?? 0;
        
        if ($amountPaid <= 0) {
            $paymentStatus = 'Pending';
        } elseif ($amountPaid < $amountDue) {
            $paymentStatus = 'Partial';
        } else {
            $paymentStatus = 'Paid';
        }

        // Create the billing record
        $billing = Billing::create([
            'invoice_number' => $invoiceNumber,
            'patient_id' => $request->patient_id,
            'treatment_id' => $request->treatment_id,
            'description' => $request->description,
            'amount_due' => $request->amount_due,
            'amount_paid' => $amountPaid,
            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'additional_charges' => $request->additional_charges,
            'additional_charges_description' => $request->additional_charges_description,
            'discount' => $request->discount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('receptionist.billing.show', $billing)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified billing.
     */
    public function show(Billing $billing)
    {
        $billing->load(['patient', 'treatment.appointment.dentist']);
        return view('receptionist.billing.show', compact('billing'));
    }

    /**
     * Show the form for editing the specified billing.
     */
    public function edit(Billing $billing)
    {
        $billing->load(['patient', 'treatment']);
        $patients = Patient::orderBy('last_name')->get();
        $treatments = Treatment::where(function($query) use ($billing) {
                          $query->whereDoesntHave('billings', function($q) {
                              $q->where('payment_status', 'Paid');
                          })->orWhere('treatment_id', $billing->treatment_id);
                      })
                      ->where('status', 'Completed')
                      ->with('patient', 'appointment.dentist')
                      ->get();
        
        return view('receptionist.billing.edit', compact('billing', 'patients', 'treatments'));
    }

    /**
     * Update the specified billing in storage.
     */
    public function update(Request $request, Billing $billing)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'treatment_id' => 'nullable|exists:treatments,treatment_id',
            'description' => 'required|string|max:255',
            'amount_due' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Pending,Partial,Paid',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'additional_charges' => 'nullable|numeric|min:0',
            'additional_charges_description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Update the billing record
        $billing->update([
            'patient_id' => $request->patient_id,
            'treatment_id' => $request->treatment_id,
            'description' => $request->description,
            'amount_due' => $request->amount_due,
            'payment_status' => $request->payment_status,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'additional_charges' => $request->additional_charges,
            'additional_charges_description' => $request->additional_charges_description,
            'discount' => $request->discount,
            'notes' => $request->notes,
        ]);

        return redirect()->route('receptionist.billing.show', $billing)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Show the form for processing payment.
     */
    public function processPaymentForm(Billing $billing)
    {
        $billing->load(['patient', 'treatment']);
        return view('receptionist.billing.process-payment', compact('billing'));
    }

    /**
     * Process a payment for a billing.
     */
    public function processPayment(Request $request, Billing $billing)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'payment_date' => 'required|date',
            'payment_notes' => 'nullable|string',
        ]);

        // Calculate the new total amount paid
        $newAmountPaid = $billing->amount_paid + $request->amount_paid;
        $amountDue = $billing->amount_due + ($billing->additional_charges ?? 0) - ($billing->discount ?? 0);
        
        // Calculate new payment status
        if ($newAmountPaid <= 0) {
            $paymentStatus = 'Pending';
        } elseif ($newAmountPaid < $amountDue) {
            $paymentStatus = 'Partial';
        } else {
            $paymentStatus = 'Paid';
        }

        // Update the billing record - removed both notes and payment_date fields
        $billing->update([
            'amount_paid' => $newAmountPaid,
            'payment_method' => $request->payment_method,
            'payment_status' => $paymentStatus,
        ]);

        return redirect()->route('receptionist.billing.show', $billing)
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Display patient billing history.
     */
    public function patientBilling(Patient $patient)
    {
        $billings = Billing::where('patient_id', $patient->patient_id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    
        return view('receptionist.billing.patient', compact('patient', 'billings'));
    }

    /**
     * Display a printable invoice.
     */
    public function printInvoice(Billing $billing)
    {
        $billing->load(['patient', 'treatment.appointment.dentist']);
        return view('receptionist.billing.print-invoice', compact('billing'));
    }
}