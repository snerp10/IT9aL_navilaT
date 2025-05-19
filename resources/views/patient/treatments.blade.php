@extends('layouts.patient')

@section('title', 'Treatment History')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Treatment History</h5>
        </div>
        <div class="card-body">
            @if($treatments->isEmpty())
                <p>You don't have any treatment records yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Treatment</th>
                                <th>Dentist</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($treatments as $treatment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($treatment->treatment_date)->format('M d, Y') }}</td>
                                    <td>{{ $treatment->service->name ?? $treatment->treatment_name }}</td>
                                    <td>Dr. {{ $treatment->dentist->user->name ?? 'Not Available' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($treatment->notes, 50) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $treatments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
