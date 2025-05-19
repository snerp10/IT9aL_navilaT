@extends('layouts.patient')

@section('title', 'Appointment Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Appointment Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Date</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</dd>
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">{{ $appointment->status }}</dd>
                        <dt class="col-sm-4">Reason for Visit</dt>
                        <dd class="col-sm-8">{{ $appointment->reason_for_visit }}</dd>
                        <dt class="col-sm-4">Notes</dt>
                        <dd class="col-sm-8">{{ $appointment->notes ?? 'N/A' }}</dd>
                        <dt class="col-sm-4">Dentist</dt>
                        <dd class="col-sm-8">Dr. {{ $appointment->dentist->user->name ?? 'Not Assigned' }}</dd>
                    </dl>
                    <a href="{{ route('patient.appointments') }}" class="btn btn-secondary">Back to Appointments</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
