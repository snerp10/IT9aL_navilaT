@extends('layouts.receptionist')

@section('title', 'Create New Billing')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Create New Billing</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('receptionist.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('receptionist.billing.index') }}">Billing Management</a></li>
        <li class="breadcrumb-item active">Create New Billing</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-file-invoice me-1"></i>
            Billing Information
        </div>
        <div class="card-body">
            <form action="{{ route('receptionist.billing.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="patient_id" class="form-label">Patient *</label>
                            <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->patient_id }}" {{ old('patient_id', $selectedPatientId) == $patient->patient_id ? 'selected' : '' }}>
                                        {{ $patient->last_name }}, {{ $patient->first_name }} (ID: {{ $patient->patient_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="treatment_id" class="form-label">Related Treatment (Optional)</label>
                            <select name="treatment_id" id="treatment_id" class="form-control @error('treatment_id') is-invalid @enderror">
                                <option value="">-- Select Treatment --</option>
                                @foreach($treatments as $treatment)
                                    <option value="{{ $treatment->treatment_id }}" {{ old('treatment_id', $selectedTreatmentId) == $treatment->treatment_id ? 'selected' : '' }}
                                        data-patient="{{ $treatment->patient->patient_id ?? '' }}"
                                        data-amount="{{ $treatment->cost }}"
                                        data-description="{{ $treatment->procedure_name ?? 'Dental Treatment' }}">
                                        {{ $treatment->procedure_name ?? 'Treatment' }} - {{ $treatment->patient->first_name ?? 'Unknown' }} 
                                        ({{ $treatment->appointment->appointment_date ?? 'No date' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('treatment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Selecting a treatment will auto-fill some fields.</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                value="{{ old('description') }}" required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date *</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="due_date" class="form-label">Due Date *</label>
                            <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="payment_status" class="form-label">Payment Status *</label>
                            <select name="payment_status" id="payment_status" class="form-control @error('payment_status') is-invalid @enderror" required>
                                <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Partial" {{ old('payment_status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                <option value="Paid" {{ old('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <hr>
                        <h5>Billing Amount</h5>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="amount_due" class="form-label">Amount Due *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" name="amount_due" id="amount_due" class="form-control @error('amount_due') is-invalid @enderror" 
                                    value="{{ old('amount_due', '0.00') }}" min="0" step="0.01" required>
                            </div>
                            @error('amount_due')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="additional_charges" class="form-label">Additional Charges</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" name="additional_charges" id="additional_charges" class="form-control @error('additional_charges') is-invalid @enderror" 
                                    value="{{ old('additional_charges', '0.00') }}" min="0" step="0.01">
                            </div>
                            @error('additional_charges')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="discount" class="form-label">Discount</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" 
                                    value="{{ old('discount', '0.00') }}" min="0" step="0.01">
                            </div>
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="additional_charges_description" class="form-label">Additional Charges Description</label>
                            <input type="text" name="additional_charges_description" id="additional_charges_description" 
                                class="form-control @error('additional_charges_description') is-invalid @enderror" 
                                value="{{ old('additional_charges_description') }}">
                            @error('additional_charges_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <hr>
                        <h5>Payment Information (Optional)</h5>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" name="amount_paid" id="amount_paid" class="form-control @error('amount_paid') is-invalid @enderror" 
                                    value="{{ old('amount_paid', '0.00') }}" min="0" step="0.01">
                            </div>
                            @error('amount_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
                                <option value="">-- Select Payment Method --</option>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="Debit Card" {{ old('payment_method') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
                                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="GCash" {{ old('payment_method') == 'GCash' ? 'selected' : '' }}>GCash</option>
                                <option value="Maya" {{ old('payment_method') == 'Maya' ? 'selected' : '' }}>Maya</option>
                                <option value="Insurance" {{ old('payment_method') == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('receptionist.billing.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Billing</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // When treatment is selected, autofill related fields
        $('#treatment_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            
            // Set patient and amount if a treatment is selected
            if ($(this).val()) {
                var patientId = selectedOption.data('patient');
                var amount = selectedOption.data('amount');
                var description = selectedOption.data('description');
                
                // Set the patient dropdown
                $('#patient_id').val(patientId);
                
                // Set the amount
                if (amount) {
                    $('#amount_due').val(amount);
                }
                
                // Set description
                if (description) {
                    $('#description').val(description);
                }
            }
        });
        
        // Calculate total when amount fields change
        $('#amount_due, #additional_charges, #discount, #amount_paid').on('input', function() {
            updatePaymentStatus();
        });
        
        // Set payment status based on payment amount
        function updatePaymentStatus() {
            var amountDue = parseFloat($('#amount_due').val()) || 0;
            var additionalCharges = parseFloat($('#additional_charges').val()) || 0;
            var discount = parseFloat($('#discount').val()) || 0;
            var amountPaid = parseFloat($('#amount_paid').val()) || 0;
            
            var totalDue = amountDue + additionalCharges - discount;
            
            if (amountPaid <= 0) {
                $('#payment_status').val('Pending');
            } else if (amountPaid < totalDue) {
                $('#payment_status').val('Partial');
            } else {
                $('#payment_status').val('Paid');
            }
        }
    });
</script>
@endpush
@endsection