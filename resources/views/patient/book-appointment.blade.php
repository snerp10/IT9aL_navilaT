@extends('layouts.patient')

@section('title', 'Book Appointment')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Schedule a New Appointment</h5>
            </div>
            <div class="card-body">
                <!-- Direct form with no JavaScript interference -->
                <form method="POST" action="{{ url('/patient/appointments/book') }}">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="service" class="form-label">Service</label>
                        <select class="form-select" id="service" name="service" required>
                            <option value="">Select a service</option>
                            <option value="regular-checkup">Regular Checkup</option>
                            <option value="teeth-cleaning">Teeth Cleaning</option>
                            <option value="tooth-extraction">Tooth Extraction</option>
                            <option value="filling">Dental Filling</option>
                            <option value="root-canal">Root Canal</option>
                            <option value="orthodontics">Orthodontics Consultation</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Preferred Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label for="time_slot" class="form-label">Preferred Time</label>
                        <select class="form-select" id="time_slot" name="time_slot" required>
                            <option value="">Select a time slot</option>
                            <option value="morning">Morning (9:00 AM - 12:00 PM)</option>
                            <option value="afternoon">Afternoon (1:00 PM - 5:00 PM)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Please describe your issue briefly..."></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('patient.appointments') }}" class="btn btn-secondary">Back to Appointments</a>
                        <button type="submit" class="btn btn-primary">Request Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection