@extends('layouts.patient')

@section('title', 'Billing & Payments')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Billing & Payments</h5>
        </div>
        <div class="card-body">
            @if($billings->isEmpty())
                <p>You don't have any billing records yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billings as $billing)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($billing->created_at)->format('M d, Y') }}</td>
                                    <td>{{ $billing->description ?? 'N/A' }}</td>
                                    <td>${{ number_format($billing->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $billing->payment_status === 'Paid' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $billing->payment_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $billings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
