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
    <link rel="stylesheet" href="{{ asset('css/dentist-custom.css') }}?v={{ time() }}">
    @vite(['resources/js/app.js'])
    @stack('styles')
    
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f8fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .page-container {
            display: flex;
            flex: 1;
        }
        
        .navbar-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #fff;
        }
        
        .sidebar {
            width: 260px;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            left: 0;
            top: 56px;
            bottom: 0;
            overflow-y: auto;
            z-index: 999;
            transition: all 0.3s;
        }
        
        .content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
            overflow-y: auto;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.2s;
            padding: 8px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .nav-link i {
            margin-right: 10px;
            font-size: 18px;
            width: 20px;
        }
        
        .sidebar-submenu {
            padding-left: 30px;
        }
        
        .sidebar-submenu .nav-link {
            padding: 6px 10px;
            font-size: 0.9rem;
        }
        
        .submenu-toggle {
            transition: transform 0.3s;
        }
        
        [aria-expanded="true"] .submenu-toggle {
            transform: rotate(90deg);
        }
    </style>
</head>
<body>
    <!-- Top Navbar - Fixed -->
    <header class="navbar-header">
        <nav class="navbar navbar-expand-lg shadow-sm bg-white">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="{{ asset('navilat.png') }}" alt="Logo" width="40" height="40" class="me-2 rounded-circle">
                    <span class="fw-bold">Dental Clinic</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dentistNavbar" aria-controls="dentistNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="dentistNavbar">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dentist.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::check() && Auth::user()->employee)
                                    Dr. {{ Auth::user()->employee->first_name }} {{ Auth::user()->employee->last_name }}
                                @else
                                    {{ Auth::user()->name ?? 'User' }}
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @if(Auth::check())
                                <li><a class="dropdown-item" href="{{ route('users.edit', Auth::user()->user_id) }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                                @endif
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
            <div class="sidebar-container py-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('dentist.dashboard') ? 'active' : '' }}" href="{{ route('dentist.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    
                    <!-- Appointment Management -->
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center {{ request()->is('dentist/appointments*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#appointmentSubmenu" role="button" aria-expanded="{{ request()->is('dentist/appointments*') ? 'true' : 'false' }}" aria-controls="appointmentSubmenu">
                            <i class="bi bi-calendar-check"></i>
                            <span class="menu-title">Appointments</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu {{ request()->is('dentist/appointments*') ? 'show' : '' }}" id="appointmentSubmenu">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.appointments.index') && !request()->has('status') ? 'active' : '' }}" href="{{ route('dentist.appointments.index') }}">
                                    <i class="bi bi-calendar-week"></i> All Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.appointments.today') ? 'active' : '' }}" href="{{ route('dentist.appointments.today') }}">
                                    <i class="bi bi-calendar-day"></i> Today's Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.appointments.upcoming') ? 'active' : '' }}" href="{{ route('dentist.appointments.upcoming') }}">
                                    <i class="bi bi-calendar-date"></i> Upcoming Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.appointments.calendar') ? 'active' : '' }}" href="{{ route('dentist.appointments.calendar') }}">
                                    <i class="bi bi-calendar-month"></i> Calendar View
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Patient Management -->
                    <li class="nav-item mb-3 has-submenu">
                        <a class="nav-link d-flex align-items-center {{ request()->is('dentist/patients*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#patientSubmenu" role="button" aria-expanded="{{ request()->is('dentist/patients*') ? 'true' : 'false' }}" aria-controls="patientSubmenu">
                            <i class="bi bi-person-vcard"></i>
                            <span class="menu-title">Patient Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu {{ request()->is('dentist/patients*') ? 'show' : '' }}" id="patientSubmenu">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.patients.index') && !request()->has('recent') ? 'active' : '' }}" href="{{ route('dentist.patients.index') }}">
                                    <i class="bi bi-list-ul"></i> All Patients
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.patients.index') && request()->has('recent') ? 'active' : '' }}" href="{{ route('dentist.patients.index', ['recent' => 'true']) }}">
                                    <i class="bi bi-person-check"></i> Recent Patients
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Treatment Management -->
                    <li class="nav-item mb-2 has-submenu">
                        <a class="nav-link d-flex align-items-center {{ request()->is('dentist/treatments*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#treatmentSubmenu" role="button" aria-expanded="{{ request()->is('dentist/treatments*') ? 'true' : 'false' }}" aria-controls="treatmentSubmenu">
                            <i class="bi bi-file-medical"></i>
                            <span class="menu-title">Treatment Management</span>
                            <i class="bi bi-caret-right-fill ms-auto submenu-toggle"></i>
                        </a>
                        <ul class="collapse sidebar-submenu {{ request()->is('dentist/treatments*') ? 'show' : '' }}" id="treatmentSubmenu">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.treatments.index') ? 'active' : '' }}" href="{{ route('dentist.treatments.index') }}">
                                    <i class="bi bi-list-ul"></i> All Treatments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dentist.treatments.create') ? 'active' : '' }}" href="{{ route('dentist.treatments.create') }}">
                                    <i class="bi bi-plus-circle"></i> Add Treatment
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