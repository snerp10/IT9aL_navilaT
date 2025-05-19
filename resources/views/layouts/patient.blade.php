<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dental Clinic</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/patient.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header/Navbar -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid px-4">
                <!-- Mobile sidebar toggle using checkbox hack - no JavaScript needed -->
                <input type="checkbox" id="sidebar-toggle-checkbox" class="d-none">
                <label for="sidebar-toggle-checkbox" class="btn btn-sm d-md-none me-2">
                    <i class="bi bi-list fs-5"></i>
                </label>
                
                <a class="navbar-brand" href="{{ route('patient.dashboard') }}">
                    <span class="text-primary fw-bold">NavilaT Dental</span>Care
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown user-profile-dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    {{ substr(Auth::user()->name ?? '', 0, 1) }}
                                </div>
                                <span class="d-none d-md-block">{{ Auth::user()->name ?? 'Patient' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                                <li>
                                    <div class="dropdown-item text-center p-3 border-bottom">
                                        <div class="avatar mx-auto mb-2 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                            {{ substr(Auth::user()->name ?? '', 0, 1) }}
                                        </div>
                                        <h6 class="mb-0">{{ Auth::user()->name ?? 'Patient' }}</h6>
                                        <p class="small text-muted mb-0">Patient</p>
                                    </div>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('patient.dashboard') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.appointments') }}"><i class="bi bi-calendar-check me-2"></i> My Appointments</a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.treatments') }}"><i class="bi bi-clipboard2-pulse me-2"></i> Treatment History</a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.billings') }}"><i class="bi bi-receipt me-2"></i> Billing & Payments</a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.complete-profile') }}"><i class="bi bi-person-lines-fill me-2"></i> Complete Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                        </a>
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
        <!-- Sidebar - Uses CSS-only toggle for mobile -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-container">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <form action="{{ route('patient.dashboard') }}" method="GET">
                            <button type="submit" class="nav-link w-100 text-start {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </button>
                        </form>
                    </li>
                    
                    <li class="nav-item mb-2">
                        <form action="{{ route('patient.appointments') }}" method="GET">
                            <button type="submit" class="nav-link w-100 text-start {{ request()->routeIs('patient.appointments') ? 'active' : '' }}">
                                <i class="bi bi-calendar-check"></i> My Appointments
                            </button>
                        </form>
                    </li>
                    
                    <li class="nav-item mb-2">
                        <form action="{{ route('patient.treatments') }}" method="GET">
                            <button type="submit" class="nav-link w-100 text-start {{ request()->routeIs('patient.treatments') ? 'active' : '' }}">
                                <i class="bi bi-clipboard2-pulse"></i> Treatment History
                            </button>
                        </form>
                    </li>
                    
                    <li class="nav-item mb-2">
                        <form action="{{ route('patient.billings') }}" method="GET">
                            <button type="submit" class="nav-link w-100 text-start {{ request()->routeIs('patient.billings') ? 'active' : '' }}">
                                <i class="bi bi-receipt"></i> Billing & Payments
                            </button>
                        </form>
                    </li>

                    <li class="nav-item mb-2">
                        <form action="{{ route('patient.complete-profile') }}" method="GET">
                            <button type="submit" class="nav-link w-100 text-start {{ request()->routeIs('patient.complete-profile') ? 'active' : '' }}">
                                <i class="bi bi-person-lines-fill"></i> Complete Profile
                            </button>
                        </form>
                    </li>
                </ul>
                
                <div class="mt-auto p-3">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body p-3">
                            <h6 class="mb-2"><i class="bi bi-telephone me-2 text-primary"></i> Need Help?</h6>
                            <p class="small mb-0">Contact our support team<br>
                                <a href="tel:+1234567890" class="text-decoration-none">+1 (234) 567-890</a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Changed from modal trigger to direct link -->
                    <a href="{{ route('patient.appointments.book-form') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-plus-circle me-2"></i> Book Appointment
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content - Only Scrollable Area -->
        <main class="content">
            <!-- Page Title -->
            <div class="page-title">
                <div class="container-fluid">
                    <h1>@yield('title')</h1>
                    @if(isset($breadcrumbs))
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @foreach($breadcrumbs as $breadcrumb)
                                    @if($loop->last)
                                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
                                    @else
                                        <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a></li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    @endif
                </div>
            </div>
            
            <!-- Page Content -->
            <div class="container-fluid mt-4">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Quick Appointment Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Book an Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Show validation errors if any -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Use a hard-coded absolute URL to avoid any routing issues -->
                    <form id="quickAppointmentForm" method="POST" action="/patient/appointments/book" accept-charset="UTF-8">
                        @csrf
                        <div class="mb-3">
                            <label for="appointmentService" class="form-label">Service</label>
                            <select class="form-select" id="appointmentService" name="service" required>
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
                            <label for="appointmentDate" class="form-label">Preferred Date</label>
                            <input type="date" class="form-control" id="appointmentDate" name="appointment_date" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label for="appointmentTime" class="form-label">Preferred Time</label>
                            <select class="form-select" id="appointmentTime" name="time_slot" required>
                                <option value="">Select a time slot</option>
                                <option value="morning">Morning (9:00 AM - 12:00 PM)</option>
                                <option value="afternoon">Afternoon (1:00 PM - 5:00 PM)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appointmentNotes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="appointmentNotes" name="notes" rows="3" placeholder="Please describe your issue briefly..."></textarea>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <!-- Use onclick with return false to prevent any default behavior -->
                            <button type="submit" class="btn btn-primary">Request Appointment</button>
                        </div>
                        <!-- Debug output to see where form is submitting -->
                        <input type="hidden" name="debug_url" value="/patient/appointments/book">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper - Required for dropdowns and modals -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>