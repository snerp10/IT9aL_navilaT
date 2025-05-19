<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request)
    {
        $role = $request->query('role');
        $query = Employee::query();
        
        // Filter by role if provided
        if ($role && in_array($role, ['Admin', 'Dentist', 'Receptionist', 'Assistant'])) {
            $query->where('role', $role);
        }
        
        // Add search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by employment status
        if ($request->filled('status')) {
            $query->where('employment_status', $request->input('status'));
        }
        
        // Sort results
        $sort = $request->input('sort', 'employee_id');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($sort, $direction);
        
        $employees = $query->get();
        
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('admin.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'birth_date' => 'required|date',
            'email' => 'required|string|email|max:255|unique:employees,email|unique:users,email',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'role' => 'required|in:Admin,Dentist,Receptionist,Assistant',
            'employment_status' => 'required|in:Active,Inactive,On Leave',
            'hire_date' => 'required|date',
            'specialization' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'education' => 'nullable|string',
            'certifications' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'password' => 'required|string|min:6|confirmed', // Password is always required
        ]);

        DB::beginTransaction();
        try {
            // First create user account (required for all staff)
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($request->password),
                'role' => $validated['role'], // Keep the capitalized role format consistently
            ]);
            
            // Then create employee record with user_id
            $employee = Employee::create([
                'user_id' => $user->user_id, // Set user_id field
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'contact_number' => $validated['contact_number'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'role' => $validated['role'],
                'employment_status' => $validated['employment_status'],
                'hire_date' => $validated['hire_date'],
                'specialization' => $validated['specialization'] ?? null,
                'years_of_experience' => $validated['years_of_experience'] ?? null,
                'education' => $validated['education'] ?? null,
                'certifications' => $validated['certifications'] ?? null,
                'salary' => $validated['salary'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating employee: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'birth_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('employees')->ignore($employee->employee_id, 'employee_id'),
            ],
            'address' => 'required|string',
            'role' => 'required|in:Admin,Dentist,Receptionist,Assistant',
            'employment_status' => 'required|in:Active,Inactive,On Leave',
            'hire_date' => 'required|date',
            'specialization' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'education' => 'nullable|string',
            'certifications' => 'nullable|string',
            'salary' => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            // Update employee information
            $employee->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'contact_number' => $validated['contact_number'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'role' => $validated['role'],
                'employment_status' => $validated['employment_status'],
                'hire_date' => $validated['hire_date'],
                'specialization' => $validated['specialization'] ?? null,
                'years_of_experience' => $validated['years_of_experience'] ?? null,
                'education' => $validated['education'] ?? null,
                'certifications' => $validated['certifications'] ?? null,
                'salary' => $validated['salary'] ?? null,
            ]);

            // Update user role if there is a linked user
            if ($employee->user_id && $employee->user && $employee->user->role !== $validated['role']) {
                $employee->user->update(['role' => $validated['role']]);
            }

            DB::commit();
            return redirect()->route('employees.show', $employee)->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        // Check for dependencies before deletion
        $appointmentCount = $employee->appointments()->count();
        
        if ($appointmentCount > 0) {
            return redirect()->route('employees.show', $employee)->with('error', 
                'Cannot delete this employee because they have associated appointments. Set their status to inactive instead.');
        }
        
        try {
            DB::beginTransaction();
            
            // Delete associated user if exists
            if ($employee->user_id && $employee->user) {
                $employee->user->delete();
            }
            
            $employee->delete();
            
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('employees.index')->with('error', 'Error deleting employee: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the list of dentists.
     */
    public function dentists()
    {
        $employees = Employee::where('role', 'Dentist')->get();
        return view('admin.employees.dentists', compact('employees'));
    }
    
    /**
     * Display the list of receptionists.
     */
    public function receptionists()
    {
        $employees = Employee::where('role', 'Receptionist')->get();
        return view('admin.employees.receptionists', compact('employees'));
    }
    
    /**
     * Display the list of assistants.
     */
    public function assistants()
    {
        $employees = Employee::where('role', 'Assistant')->get();
        return view('admin.employees.assistants', compact('employees'));
    }
    
    /**
     * Create user account for employee.
     */
    public function createUserAccount(Request $request, Employee $employee)
    {
        // Check if employee already has a user account
        if ($employee->user_id) {
            return redirect()->route('employees.show', $employee)->with('error', 'Employee already has a user account.');
        }
        
        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create user account
            $user = User::create([
                'email' => $employee->email,
                'password' => Hash::make($validated['password']),
                'role' => ucfirst(strtolower($employee->role)), // Maintain consistent case for roles
            ]);
            
            // Link user to employee
            $employee->user_id = $user->user_id;
            $employee->save();
            
            DB::commit();
            return redirect()->route('employees.show', $employee)->with('success', 'User account created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating user account: ' . $e->getMessage());
        }
    }
}
