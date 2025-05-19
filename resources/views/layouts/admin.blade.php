<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'navilaT')</title>
    
    <!-- Load Open Sans font with all needed weights -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}?v={{ time() }}">
    @vite(['resources/js/app.js'])
</head>
<body>
    <!-- Top Navbar - Fixed -->
    <header class="navbar-header">
        <nav class="navbar navbar-expand-lg shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="{{ asset('navilat.png') }}" alt="Logo" width="40" height="40" class="me-1 rounded-circle">
                    <span class="fw-bold">Dental Clinic Admin</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('users.edit', Auth::user()->user_id) }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <div class="page-container">
        <!-- Sidebar - Fixed, Non-scrollable -->
        <aside class="sidebar">
            <div class="sidebar-container">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- User Management Single Link -->
                    <li class="nav-item mb-3 user-management-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="bi bi-people-fill"></i> User Management
                        </a>
                    </li>
                    
                    <!-- Patient Management -->
                    <li class="nav-item mb-3 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#patientSubmenu" role="button" aria-expanded="false" aria-controls="patientSubmenu">
                            <i class="bi bi-person-vcard"></i>
                            <span class="menu-title">Patient Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="patientSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patients.index') }}">
                                    <i class="bi bi-list-ul"></i> All Patients
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patients.create') }}">
                                    <i class="bi bi-plus-circle"></i> Add Patient
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patients.index', ['status' => 'active']) }}">
                                    <i class="bi bi-person-check"></i> Active Patients
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index', ['view' => 'patient']) }}">
                                    <i class="bi bi-calendar-event"></i> Patient Appointments
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Employee Management -->
                    <li class="nav-item mb-3 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#employeeSubmenu" role="button" aria-expanded="false" aria-controls="employeeSubmenu">
                            <i class="bi bi-person-badge"></i>
                            <span class="menu-title">Employee Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="employeeSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.index', ['role' => 'Dentist']) }}">
                                    <i class="bi bi-person-badge-fill"></i> Dentists
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.index', ['role' => 'Receptionist']) }}">
                                    <i class="bi bi-person-workspace"></i> Receptionists
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.index', ['role' => 'Admin']) }}">
                                    <i class="bi bi-person-lock"></i> Admins
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employees.create') }}">
                                    <i class="bi bi-plus-circle"></i> Add Employee
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('payroll.index') }}">
                                    <i class="bi bi-cash-coin"></i> Payroll Management
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#appointmentSubmenu" role="button" aria-expanded="false" aria-controls="appointmentSubmenu">
                            <i class="bi bi-calendar-check"></i>
                            <span class="menu-title">Appointment Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="appointmentSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index') }}">
                                    <i class="bi bi-calendar-week"></i> All Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index', ['status' => 'upcoming']) }}">
                                    <i class="bi bi-clock"></i> Upcoming Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index', ['view' => 'dentist']) }}">
                                    <i class="bi bi-person-badge"></i> Dentist Schedules
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Treatment Management -->
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#treatmentSubmenu" role="button" aria-expanded="false" aria-controls="treatmentSubmenu">
                            <i class="bi bi-file-medical"></i>
                            <span class="menu-title">Treatment Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="treatmentSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('treatments.index') }}">
                                    <i class="bi bi-list-ul"></i> All Treatments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('treatments.create') }}">
                                    <i class="bi bi-plus-circle"></i> Add Treatment
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('services.index') }}">
                                    <i class="bi bi-card-checklist"></i> Dental Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('services.create') }}">
                                    <i class="bi bi-plus-circle"></i> Add Dental Service
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#billingSubmenu" role="button" aria-expanded="false" aria-controls="billingSubmenu">
                            <i class="bi bi-receipt"></i>
                            <span class="menu-title">Billing Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="billingSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('billing.index') }}">
                                    <i class="bi bi-list-ul"></i> All Invoices
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('billing.create') }}">
                                    <i class="bi bi-plus-circle"></i> Create Invoice
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('billing.payments') }}">
                                    <i class="bi bi-credit-card"></i> Payment Processing
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('billing.pending') }}">
                                    <i class="bi bi-hourglass-split"></i> Pending Payments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('billing.completed') }}">
                                    <i class="bi bi-check-circle"></i> Completed Payments
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Product & Inventory Management -->
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#productSubmenu" role="button" aria-expanded="false" aria-controls="productSubmenu">
                            <i class="bi bi-box-seam"></i>
                            <span class="menu-title">Product Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="productSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.index') }}">
                                    <i class="bi bi-box"></i> All Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.create') }}">
                                    <i class="bi bi-plus-circle"></i> Add Product
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('categories.index') }}">
                                    <i class="bi bi-tags"></i> Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('suppliers.index') }}">
                                    <i class="bi bi-truck"></i> Suppliers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('inventory.index') }}">
                                    <i class="bi bi-archive"></i> Inventory
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('products.low-stock') }}">
                                    <i class="bi bi-exclamation-triangle"></i> Low Stock Items
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Financial Management -->
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#financialSubmenu" role="button" aria-expanded="false" aria-controls="financialSubmenu">
                            <i class="bi bi-bar-chart"></i>
                            <span class="menu-title">Financial Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="financialSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('financial-reports.index') }}">
                                    <i class="bi bi-file-earmark-bar-graph"></i> Financial Reports
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('financial-reports.monthly-summary') }}">
                                    <i class="bi bi-calendar-month"></i> Monthly Summary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('financial-reports.annual-summary') }}">
                                    <i class="bi bi-calendar-year"></i> Annual Summary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('financial-reports.create') }}">
                                    <i class="bi bi-plus-circle"></i> Generate New Report
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- Main Content - Only Scrollable Area -->
        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>
