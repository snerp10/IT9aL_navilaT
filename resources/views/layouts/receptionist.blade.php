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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/receptionist-custom.css') }}?v={{ time() }}">
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <!-- Top Navbar - Fixed -->
    <header class="navbar-header">
        <nav class="navbar navbar-expand-lg shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="{{ asset('navilat.png') }}" alt="Logo" width="40" height="40" class="me-2 rounded-circle">
                    <span class="fw-bold">Dental Clinic Receptionist</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#receptionistNavbar" aria-controls="receptionistNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="receptionistNavbar">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('receptionist.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Receptionist
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
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
                        <a class="nav-link" href="{{ route('receptionist.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
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
                                <a class="nav-link" href="{{ route('receptionist.patients.index') }}">
                                    <i class="bi bi-list-ul"></i> All Patients
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('receptionist.patients.create') }}">
                                    <i class="bi bi-plus-circle"></i> Add Patient
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('receptionist.patient-search') }}">
                                    <i class="bi bi-search"></i> Search Patient
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Appointment Management -->
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center collapsed" data-bs-toggle="collapse" href="#appointmentSubmenu" role="button" aria-expanded="false" aria-controls="appointmentSubmenu">
                            <i class="bi bi-calendar-check"></i>
                            <span class="menu-title">Appointments</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu" id="appointmentSubmenu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('receptionist.appointments.index') }}">
                                    <i class="bi bi-calendar-week"></i> All Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('receptionist.appointments.create') }}">
                                    <i class="bi bi-plus-circle"></i> Schedule Appointment
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('receptionist.appointments.index', ['view' => 'today']) }}">
                                    <i class="bi bi-calendar-day"></i> Today's Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('receptionist.appointments.index', ['view' => 'week']) }}">
                                    <i class="bi bi-calendar-week"></i> This Week
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('receptionist.patient-check-in') }}">
                                    <i class="bi bi-clipboard-check"></i> Patient Check-in
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Payment Processing -->
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="{{ route('receptionist.payments') }}">
                            <i class="bi bi-credit-card"></i> Payment Processing
                        </a>
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