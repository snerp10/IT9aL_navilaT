<?php
namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Receptionist dashboard view
        return view('receptionist.dashboard');
    }
}
