<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Appointment;
use App\Models\Billing;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check user role and redirect accordingly
        $user = Auth::user();
        
        if ($user->role === 'Receptionist') {
            return redirect()->route('receptionist.dashboard');
        } elseif ($user->role === 'Dentist') {
            // Check if the user has an associated employee record before redirecting
            if ($user->employee) {
                return redirect()->route('dentist.dashboard');
            } else {
                // If no employee record exists, show an error message
                return view('dashboard.error', [
                    'message' => 'Your dentist profile is not properly set up. Please contact the administrator.',
                    'title' => 'Profile Error'
                ]);
            }
        } elseif ($user->role !== 'Admin') {
            // If role is not Admin, Receptionist, or Dentist, redirect to welcome page
            return redirect('/');
        }
        
        // Proceed with admin dashboard for admin users
        
        // User counts
        $totalUsers = User::count();
        $totalPatients = Patient::count();
        $totalDentists = Employee::where('role', 'Dentist')->count();
        $totalReceptionists = Employee::where('role', 'Receptionist')->count();
        $totalAdmins = Employee::where('role', 'Admin')->count();
        $totalEmployees = Employee::count();

        // Financial summary
        $totalRevenue = Billing::sum('amount_paid');
        $outstandingBalance = Billing::sum('amount_due') - $totalRevenue;

        // Upcoming appointments (next 7 days)
        $upcomingAppointments = Appointment::where('appointment_date', '>=', Carbon::today())
            ->where('appointment_date', '<', Carbon::today()->addDays(7))
            ->orderBy('appointment_date', 'asc')
            ->with(['patient', 'dentist'])
            ->get();

        // Low stock products (threshold: 10)
        $lowStockProducts = Product::with(['inventory' => function($query) {
            $query->where('quantity', '<', 10);
        }])->get()->filter(function($product) {
            return $product->inventory && $product->inventory->quantity < 10;
        });

        // Recent activity (last 10 users)
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPatients',
            'totalDentists',
            'totalReceptionists',
            'totalAdmins',
            'totalEmployees',
            'totalRevenue',
            'outstandingBalance',
            'upcomingAppointments',
            'lowStockProducts',
            'recentUsers'
        ));
    }
}
