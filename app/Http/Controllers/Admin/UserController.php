<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Initialize query
        $query = User::query();
        
        // Filter by role if specified
        if ($request->filled('role')) {
            $role = $request->get('role');
            $query->where('role', $role);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('email', 'like', "%{$searchTerm}%")
                  ->orWhere('user_id', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by status (active/inactive)
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Sort users
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        // Eager load relationships
        $query->with(['patient', 'employee']);
        
        // Paginate results
        $users = $query->paginate(10)->withQueryString();
        
        // Set filter variables for the view
        $role = $request->get('role');
        
        return view('admin.users.index', compact('users', 'role'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:Admin,Dentist,Receptionist,Patient',
            'is_active' => 'required|boolean',
        ]);

        // Generate a unique user ID
        $userId = 'USR-' . Str::random(8);
        
        // Hash the password
        $validated['password'] = Hash::make($validated['password']);
        $validated['user_id'] = $userId;

        // Create the base user account
        $user = User::create($validated);

        // Redirect based on role
        if ($validated['role'] === 'Patient') {
            return redirect()->route('patients.create', ['user_id' => $user->user_id])
                ->with('info', 'User account created. Please complete the patient profile.');
        } elseif (in_array($validated['role'], ['Admin', 'Dentist', 'Receptionist'])) {
            return redirect()->route('employees.create', ['user_id' => $user->user_id])
                ->with('info', 'User account created. Please complete the employee profile.');
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'role' => 'required|in:Admin,Dentist,Receptionist,Patient',
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        if ($user->role === 'Patient') {
            // Check if patient record exists
            if ($user->patient) {
                $user->patient->update([
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'gender' => $request->input('gender'),
                    'birth_date' => $request->input('birth_date'),
                    'contact_number' => $request->input('contact_number'),
                    'email' => $user->email,
                    'address' => $request->input('address'),
                    'emergency_contact_name' => $request->input('emergency_contact_name', ''),
                    'emergency_contact_number' => $request->input('emergency_contact_number', ''),
                    'blood_type' => $request->input('blood_type'),
                    'allergies' => $request->input('allergies'),
                    'medical_history' => $request->input('medical_history'),
                    'current_medications' => $request->input('current_medications'),
                    'insurance_provider' => $request->input('insurance_provider'),
                    'insurance_policy_number' => $request->input('insurance_policy_number'),
                ]);
            } else {
                // Create patient record if it doesn't exist
                Patient::create([
                    'patient_id' => $user->user_id,
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'gender' => $request->input('gender'),
                    'birth_date' => $request->input('birth_date'),
                    'contact_number' => $request->input('contact_number'),
                    'email' => $user->email,
                    'address' => $request->input('address'),
                    'emergency_contact_name' => $request->input('emergency_contact_name', ''),
                    'emergency_contact_number' => $request->input('emergency_contact_number', ''),
                    'blood_type' => $request->input('blood_type'),
                    'allergies' => $request->input('allergies'),
                    'medical_history' => $request->input('medical_history'),
                    'current_medications' => $request->input('current_medications'),
                    'insurance_provider' => $request->input('insurance_provider'),
                    'insurance_policy_number' => $request->input('insurance_policy_number'),
                ]);
            }
        } elseif (in_array($user->role, ['Admin', 'Dentist', 'Receptionist'])) {
            // Check if employee record exists
            if ($user->employee) {
                $user->employee->update([
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'gender' => $request->input('gender'),
                    'birth_date' => $request->input('birth_date'),
                    'contact_number' => $request->input('contact_number'),
                    'email' => $user->email,
                    'address' => $request->input('address'),
                    'role' => $validated['role'],
                    'specialization' => $request->input('specialization'),
                    'years_of_experience' => $request->input('years_of_experience'),
                    'education' => $request->input('education'),
                    'certifications' => $request->input('certifications'),
                    'salary' => $request->input('salary'),
                    'hire_date' => $request->input('hire_date'),
                    'employment_status' => $request->input('employment_status'),
                ]);
            } else {
                // Create employee record if it doesn't exist
                Employee::create([
                    'employee_id' => $user->user_id,
                    'first_name' => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name' => $request->input('last_name'),
                    'gender' => $request->input('gender'),
                    'birth_date' => $request->input('birth_date'),
                    'contact_number' => $request->input('contact_number'),
                    'email' => $user->email,
                    'address' => $request->input('address'),
                    'role' => $validated['role'],
                    'specialization' => $request->input('specialization'),
                    'years_of_experience' => $request->input('years_of_experience'),
                    'education' => $request->input('education'),
                    'certifications' => $request->input('certifications'),
                    'salary' => $request->input('salary'),
                    'hire_date' => $request->input('hire_date', now()),
                    'employment_status' => $request->input('employment_status', 'Active'),
                ]);
            }
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Delete associated records
        if ($user->role === 'Patient' && $user->patient) {
            $user->patient->delete();
        } elseif (in_array($user->role, ['Admin', 'Dentist', 'Receptionist']) && $user->employee) {
            $user->employee->delete();
        }
        
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}