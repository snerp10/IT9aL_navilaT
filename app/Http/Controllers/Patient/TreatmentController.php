<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Treatment;

class TreatmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient record not found. Please contact the clinic.');
        }
        $treatments = Treatment::where('patient_id', $patient->patient_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('patient.treatments', compact('treatments', 'patient'));
    }
}
