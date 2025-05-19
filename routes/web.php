<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppointmentController;
// Import the non-admin TreatmentController for dentist routes
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\Admin\TreatmentController as AdminTreatmentController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\FinancialReportController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DentalServiceController;
// Import Dentist Controllers
use App\Http\Controllers\Dentist\DashboardController as DentistDashboardController;
use App\Http\Controllers\Dentist\AppointmentController as DentistAppointmentController;
use App\Http\Controllers\Dentist\PatientController as DentistPatientController;
use App\Http\Controllers\Dentist\TreatmentController as DentistTreatmentController;
// Import Patient Controllers
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\TreatmentController as PatientTreatmentController;
use App\Http\Controllers\Patient\BillingController as PatientBillingController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Debug Route
|--------------------------------------------------------------------------
*/

// Debug route for financial report routes - moved outside the middleware group
Route::get('/debug-routes', function() {
    $routes = Route::getRoutes();
    $routeList = [];
    
    foreach ($routes as $route) {
        if (strpos($route->uri, 'financial-reports') !== false) {
            $routeList[] = [
                'uri' => $route->uri,
                'name' => $route->getName(),
                'methods' => implode('|', $route->methods()),
                'action' => $route->getActionName(),
                'middleware' => implode('|', $route->middleware()),
            ];
        }
    }
    
    echo "<pre>";
    print_r($routeList);
    echo "</pre>";
    return "Route debugging complete";
});

/*
|--------------------------------------------------------------------------
| Public Financial Report Routes
|--------------------------------------------------------------------------
*/

Route::get('/financial-reports/monthly-summary', [FinancialReportController::class, 'monthlySummary'])->name('financial-reports.monthly-summary');
Route::get('/financial-reports/annual-summary', [FinancialReportController::class, 'annualSummary'])->name('financial-reports.annual-summary');

/*
|--------------------------------------------------------------------------
| Protected Routes - All Authenticated Users
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard - redirects to role-based dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:Admin'])->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        
        // Patient Management
        Route::resource('patients', PatientController::class);
        
        // Employee Management
        Route::resource('employees', EmployeeController::class);
        Route::post('/employees/{employee}/create-user-account', [EmployeeController::class, 'createUserAccount'])
            ->name('employees.create-user-account');
        
        // Dental Services - New routes
        Route::resource('services', DentalServiceController::class);
        
        // Treatment Management - Adding here for Admin access
        Route::resource('treatments', AdminTreatmentController::class);
        Route::get('/patient/{patient}/treatments', [AdminTreatmentController::class, 'patientTreatments'])
            ->name('treatments.patient-treatments');
        Route::get('/appointment/{appointment}/treatments', [AdminTreatmentController::class, 'appointmentTreatments'])
            ->name('treatments.appointment-treatments');
        Route::get('/treatments/service-details', [AdminTreatmentController::class, 'getServiceDetails'])
            ->name('treatments.service-details');
        
        // Financial Reports
        Route::resource('financial-reports', FinancialReportController::class);
        Route::post('/financial-reports/generate', [FinancialReportController::class, 'generate'])
            ->name('financial-reports.generate');
        Route::get('/financial-reports/{financialReport}/pdf', [FinancialReportController::class, 'downloadPdf'])
            ->name('financial-reports.download-pdf');
        // Add a POST route that will redirect to the delete method for situations where DELETE isn't working
        Route::post('/financial-reports/{financialReport}/delete', [FinancialReportController::class, 'destroy'])
            ->name('financial-reports.delete');
            
        // Payroll Management
        Route::resource('payroll', PayrollController::class);
        Route::post('/payroll/generate', [PayrollController::class, 'generatePayroll'])
            ->name('payroll.generate');
        Route::get('/employee/{employee}/payroll-history', [PayrollController::class, 'employeePayrollHistory']) 
            ->name('payroll.employee-history');
        Route::get('/payroll/report', [PayrollController::class, 'report'])
            ->name('payroll.report');
            
        // Inventory & Product Management
        // Specific routes first (before resource routes)
        Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock'])
            ->name('inventory.low-stock');
        Route::get('/inventory/out-of-stock', [InventoryController::class, 'outOfStock'])
            ->name('inventory.out-of-stock');
        Route::get('/inventory/export', [InventoryController::class, 'export'])
            ->name('inventory.export');
        Route::match(['get', 'post'], '/inventory/adjust/{productId}', [InventoryController::class, 'adjust'])
            ->name('inventory.adjust');
        
        Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])
            ->name('products.by-category');
        Route::get('/products/supplier/{supplier}', [ProductController::class, 'bySupplier'])
            ->name('products.by-supplier');
        Route::get('/products/low-stock', [ProductController::class, 'lowStock'])
            ->name('products.low-stock');
        Route::post('/products/import', [ProductController::class, 'import'])
            ->name('products.import');
        
        // Resource routes after specific routes
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('inventory', InventoryController::class);
    });
    
    /*
    |--------------------------------------------------------------------------
    | Admin & Receptionist Routes
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:Admin,Receptionist'])->group(function () {
        // Appointment Management
        Route::resource('appointments', AppointmentController::class);
            
        // Billing Management - Specific routes first, then resource route
        Route::get('/billing/payments', [BillingController::class, 'paymentsManagement'])
            ->name('billing.payments');
        Route::get('/billing/pending', [BillingController::class, 'pendingPayments'])
            ->name('billing.pending');
        Route::get('/billing/completed', [BillingController::class, 'completedPayments'])
            ->name('billing.completed');
        Route::post('/billing/{billing}/process-payment', [BillingController::class, 'processPayment'])
            ->name('billing.process-payment');
        Route::get('/billing/{billing}/invoice', [BillingController::class, 'generateInvoice'])
            ->name('billing.generate-invoice');
        Route::resource('billing', BillingController::class);
    });
    
    /*
    |--------------------------------------------------------------------------
    | Dentist Routes
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:Dentist,Admin'])->group(function () {
        // Appointment Schedule
        Route::get('/dentist/{dentist}/schedule', [AppointmentController::class, 'dentistSchedule'])
            ->name('appointments.dentist-schedule');
            
        // Removed conflicting treatment routes since they're already defined in the Admin section
    });

    /*
    |--------------------------------------------------------------------------
    | Dentist-only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:Dentist'])->prefix('dentist')->name('dentist.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DentistDashboardController::class, 'dashboard'])->name('dashboard');
        
        // Data Access - Special route to ensure dentist can access their data
        Route::get('/data-access', [DentistDashboardController::class, 'ensureDataAccess'])->name('data-access');
        
        // Appointments
        Route::get('/appointments/today', [DentistAppointmentController::class, 'todayAppointments'])->name('appointments.today');
        Route::get('/appointments/upcoming', [DentistAppointmentController::class, 'upcomingAppointments'])->name('appointments.upcoming');
        Route::get('/appointments/calendar', [DentistAppointmentController::class, 'calendar'])->name('appointments.calendar');
        Route::get('/appointments/calendar-events', [DentistAppointmentController::class, 'calendarEvents'])->name('appointments.calendar-events');
        Route::get('/appointments/{appointment}/details', [DentistAppointmentController::class, 'getAppointmentDetails']);
        Route::patch('/appointments/{appointment}/cancel', [DentistAppointmentController::class, 'cancelAppointment'])->name('appointments.cancel');
        Route::post('/appointments/{appointment}/update-status', [DentistAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::resource('appointments', DentistAppointmentController::class);
        
        // Patients
        Route::resource('patients', DentistPatientController::class);
        Route::get('/patients/{patient}/dental-chart', [DentistPatientController::class, 'dentalChart'])->name('patients.dental-chart');
        Route::post('/patients/{patient}/add-note', [DentistPatientController::class, 'addNote'])->name('patients.add-note');
        Route::get('/patients/{patient}/treatments', [DentistTreatmentController::class, 'patientTreatments'])->name('patients.treatments');
        Route::get('/patients/{patient}/appointments', [DentistAppointmentController::class, 'patientAppointments'])->name('patients.appointments');
        
        // Treatments
        Route::resource('treatments', DentistTreatmentController::class);
    });
    
    /*
    |--------------------------------------------------------------------------
    | Patient Routes
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['role:Patient'])->group(function () {
        // Patient's appointments & billing history
        Route::get('/patient/{patient}/appointments', [AppointmentController::class, 'patientHistory'])
            ->name('appointments.patient-history');
        Route::get('/patient/{patient}/billings', [BillingController::class, 'patientBillings'])
            ->name('billing.patient-billings');
    });

    Route::middleware(['auth', 'role:Patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'dashboard'])->name('dashboard');
        // Appointments
        Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments');
        Route::get('/appointments/book', [PatientAppointmentController::class, 'showBookForm'])->name('appointments.book-form');
        Route::post('/appointments/book', [PatientAppointmentController::class, 'book'])->name('appointments.book');
        Route::get('/appointments/{appointment}', [PatientAppointmentController::class, 'show'])->name('appointments.show');
        Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
        // Treatments
        Route::get('/treatments', [PatientTreatmentController::class, 'index'])->name('treatments');
        // Billings
        Route::get('/billings', [PatientBillingController::class, 'index'])->name('billings');
        // Profile
        Route::get('/complete-profile', [PatientProfileController::class, 'completeForm'])->name('complete-profile');
        Route::post('/complete-profile', [PatientProfileController::class, 'completeSave'])->name('complete-profile.save');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Receptionist Routes
    |--------------------------------------------------------------------------
    */
    
    Route::middleware(['auth', 'role:Receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Receptionist\ReceptionistController::class, 'dashboard'])->name('dashboard');
        
        // Patient Check-in
        Route::get('/patient-check-in', [App\Http\Controllers\Receptionist\ReceptionistController::class, 'patientCheckIn'])->name('patient-check-in');
        Route::post('/process-check-in/{appointment}', [App\Http\Controllers\Receptionist\ReceptionistController::class, 'processCheckIn'])->name('process-check-in');
        
        // Patients Management
        Route::resource('patients', App\Http\Controllers\Receptionist\PatientController::class);
        Route::get('/patient-search', [App\Http\Controllers\Receptionist\ReceptionistController::class, 'patientSearch'])->name('patient-search');
        Route::post('/search-patient', [App\Http\Controllers\Receptionist\ReceptionistController::class, 'searchPatient'])->name('search-patient');
        Route::get('/patient-details/{patient}', [App\Http\Controllers\Receptionist\ReceptionistController::class, 'patientDetails'])->name('patient-details');
        
        // Appointments Management
        Route::resource('appointments', App\Http\Controllers\Receptionist\AppointmentController::class);
        Route::post('/appointments/{appointment}/cancel', [App\Http\Controllers\Receptionist\AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::post('/appointments/{appointment}/check-in', [App\Http\Controllers\Receptionist\AppointmentController::class, 'checkIn'])->name('appointments.check-in');
        Route::get('/patient/{patient}/appointments', [App\Http\Controllers\Receptionist\AppointmentController::class, 'patientHistory'])->name('appointments.patient-history');
        
        // Billing Management
        Route::get('/billing', [App\Http\Controllers\Receptionist\BillingController::class, 'index'])->name('billing.index');
        Route::get('/billing/create', [App\Http\Controllers\Receptionist\BillingController::class, 'create'])->name('billing.create');
        Route::post('/billing', [App\Http\Controllers\Receptionist\BillingController::class, 'store'])->name('billing.store');
        Route::get('/billing/{billing}', [App\Http\Controllers\Receptionist\BillingController::class, 'show'])->name('billing.show');
        Route::get('/billing/{billing}/edit', [App\Http\Controllers\Receptionist\BillingController::class, 'edit'])->name('billing.edit');
        Route::put('/billing/{billing}', [App\Http\Controllers\Receptionist\BillingController::class, 'update'])->name('billing.update');
        Route::delete('/billing/{billing}', [App\Http\Controllers\Receptionist\BillingController::class, 'destroy'])->name('billing.destroy');
        Route::get('/billing/{billing}/process', [App\Http\Controllers\Receptionist\BillingController::class, 'processPaymentForm'])->name('billing.process-payment');
        Route::post('/billing/{billing}/process', [App\Http\Controllers\Receptionist\BillingController::class, 'processPayment'])->name('billing.process');
        Route::get('/patient/{patient}/billings', [App\Http\Controllers\Receptionist\BillingController::class, 'patientBilling'])->name('billing.patient');
        Route::get('/billing/{billing}/invoice', [App\Http\Controllers\Receptionist\BillingController::class, 'printInvoice'])->name('billing.print-invoice');
        Route::get('/billing/{billing}/receipt', [App\Http\Controllers\Receptionist\BillingController::class, 'showReceipt'])->name('billing.receipt');
        
        // General Payments
        Route::get('/payments', [App\Http\Controllers\Receptionist\ReceptionistController::class, 'payments'])->name('payments');
    });
});

