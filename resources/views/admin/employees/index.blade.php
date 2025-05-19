@extends('layouts.admin')

@section('title', 'Employee Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Employee Management</h1>
        <a href="{{ route('employees.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add Employee
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <div class="row g-3">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('employees.index') }}" class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search by name or email">
                        </div>
                        <div class="col-md-3">
                            <select name="role" class="form-select">
                                <option value="">All Roles</option>
                                <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Dentist" {{ request('role') == 'Dentist' ? 'selected' : '' }}>Dentist</option>
                                <option value="Receptionist" {{ request('role') == 'Receptionist' ? 'selected' : '' }}>Receptionist</option>
                                <option value="Assistant" {{ request('role') == 'Assistant' ? 'selected' : '' }}>Assistant</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="On Leave" {{ request('status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary {{ !request('view') || request('view') == 'card' ? 'active' : '' }}">
                            <i class="bi bi-grid"></i>
                        </a>
                        <a href="{{ route('employees.index') }}?view=list{{ request('search') ? '&search='.request('search') : '' }}{{ request('role') ? '&role='.request('role') : '' }}{{ request('status') ? '&status='.request('status') : '' }}" class="btn btn-outline-secondary {{ request('view') == 'list' ? 'active' : '' }}">
                            <i class="bi bi-list"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(request('view') == 'list')
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Hire Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2 bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0,4)] }} rounded-circle text-white">
                                                <span>{{ substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                                @if($employee->role == 'Dentist' && $employee->specialization)
                                                <div class="small text-muted">{{ $employee->specialization }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->role }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->contact_number }}</td>
                                    <td>{{ date('M d, Y', strtotime($employee->hire_date)) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->employment_status == 'Active' ? 'success' : ($employee->employment_status == 'Inactive' ? 'secondary' : 'warning') }}">
                                            {{ $employee->employment_status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('employees.show', $employee->employee_id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->employee_id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('payroll.employee-history', $employee->employee_id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-cash-stack"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-person-x fs-1 d-block mb-3"></i>
                                            No employees found
                                        </div>
                                        <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary mt-3">Add Employee</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="row">
                    @forelse($employees as $employee)
                        <div class="col-md-4 col-xl-3 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="avatar mx-auto mb-3 bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][rand(0,4)] }} rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                        <span>{{ substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1) }}</span>
                                    </div>
                                    <h5 class="card-title mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                                    <p class="text-muted mb-3">{{ $employee->role }}</p>
                                    <span class="badge bg-{{ $employee->employment_status == 'Active' ? 'success' : ($employee->employment_status == 'Inactive' ? 'secondary' : 'warning') }} mb-3">
                                        {{ $employee->employment_status }}
                                    </span>

                                    <div class="d-flex justify-content-between mt-3">
                                        <a href="{{ route('employees.show', $employee->employee_id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Details
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->employee_id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="{{ route('payroll.employee-history', $employee->employee_id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-cash-stack"></i> Payroll
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted">
                                    <small>Hired {{ \Carbon\Carbon::parse($employee->hire_date)->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-person-x fs-1 d-block mb-3 text-muted"></i>
                            <h4>No employees found</h4>
                            <p class="text-muted">There are no employees matching your search criteria.</p>
                            <a href="{{ route('employees.create') }}" class="btn btn-primary mt-3">Add Employee</a>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
        @if(isset($employees) && method_exists($employees, 'links'))
            <div class="card-footer bg-white">
                {{ $employees->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Staff Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="employeeRoleChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Total Employees</h6>
                                        <h3 class="mt-2">{{ $totalEmployees ?? count($employees) }}</h3>
                                    </div>
                                    <div class="fs-1 text-primary">
                                        <i class="bi bi-people"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">Active Dentists</h6>
                                        <h3 class="mt-2">{{ $activeDentists ?? $employees->where('role', 'Dentist')->where('employment_status', 'Active')->count() }}</h3>
                                    </div>
                                    <div class="fs-1 text-info">
                                        <i class="bi bi-hospital"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">New This Month</h6>
                                        <h3 class="mt-2">{{ $newThisMonth ?? $employees->where('hire_date', '>=', date('Y-m-01'))->count() }}</h3>
                                    </div>
                                    <div class="fs-1 text-success">
                                        <i class="bi bi-calendar-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="text-muted">On Leave</h6>
                                        <h3 class="mt-2">{{ $onLeave ?? $employees->where('employment_status', 'On Leave')->count() }}</h3>
                                    </div>
                                    <div class="fs-1 text-warning">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Staff distribution chart
        const ctx = document.getElementById('employeeRoleChart').getContext('2d');
        
        // Calculate role counts from the server-side data
        const roles = {
            'Dentist': {{ $employees->where('role', 'Dentist')->count() }},
            'Admin': {{ $employees->where('role', 'Admin')->count() }},
            'Receptionist': {{ $employees->where('role', 'Receptionist')->count() }},
            'Assistant': {{ $employees->where('role', 'Assistant')->count() }}
        };
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(roles),
                datasets: [{
                    data: Object.values(roles),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',  // Dentist - Blue
                        'rgba(255, 99, 132, 0.7)',  // Admin - Red
                        'rgba(75, 192, 192, 0.7)',  // Receptionist - Green
                        'rgba(255, 206, 86, 0.7)'   // Assistant - Yellow
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Initialize avatar styling for list view
        document.querySelectorAll('.avatar').forEach(avatar => {
            avatar.style.width = '40px';
            avatar.style.height = '40px';
            avatar.style.display = 'flex';
            avatar.style.alignItems = 'center';
            avatar.style.justifyContent = 'center';
            avatar.style.fontSize = '1rem';
        });
    });
</script>
@endpush
@endsection
