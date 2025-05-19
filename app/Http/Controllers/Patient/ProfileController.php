<?php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;

class ProfileController extends Controller
{
    public function completeForm()
    {
        $user = Auth::user();
        if ($user->patient) {
            return redirect()->route('patient.dashboard')->with('info', 'Your profile is already complete.');
        }
        return view('patient.complete-profile', ['user' => $user]);
    }

    public function completeSave(Request $request)
    {
        $user = Auth::user();
        if ($user->patient) {
            return redirect()->route('patient.dashboard')->with('info', 'Your profile is already complete.');
        }
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_number' => 'required|string|max:20',
        ]);
        $validated['user_id'] = $user->user_id;
        $validated['email'] = $user->email;
        Patient::create($validated);
        return redirect()->route('patient.dashboard')->with('success', 'Profile completed successfully!');
    }
}
