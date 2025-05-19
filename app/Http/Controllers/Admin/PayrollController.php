<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Display a listing of payroll records.
     */
    public function index()
    {
        $payrolls = Payroll::with('employee')->get();
        return view('admin.payroll.index', compact('payrolls'));
    }

    /**
     * Show the form for creating a new payroll record.
     */
    public function create()
    {
        $employees = Employee::where('employment_status', 'Active')->get();
        return view('admin.payroll.create', compact('employees'));
    }

    /**
     * Store a newly created payroll record in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'salary_amount' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'pay_date' => 'required|date',
        ]);

        // Set defaults if not provided
        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;

        Payroll::create($validated);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll record created successfully.');
    }

    /**
     * Display the specified payroll record.
     */
    public function show(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('admin.payroll.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified payroll record.
     */
    public function edit(Payroll $payroll)
    {
        $employees = Employee::where('employment_status', 'Active')->get();
        return view('admin.payroll.edit', compact('payroll', 'employees'));
    }

    /**
     * Update the specified payroll record in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'salary_amount' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'pay_date' => 'required|date',
        ]);

        // Set defaults if not provided
        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;

        $payroll->update($validated);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll record updated successfully.');
    }

    /**
     * Remove the specified payroll record from storage.
     */
    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll record deleted successfully.');
    }

    /**
     * Generate payroll for all active employees.
     */
    public function generatePayroll(Request $request)
    {
        $validated = $request->validate([
            'pay_date' => 'required|date',
        ]);

        $employees = Employee::where('employment_status', 'Active')->get();
        $count = 0;

        foreach ($employees as $employee) {
            // Check if payroll already exists for this employee on this date
            $existingPayroll = Payroll::where('employee_id', $employee->employee_id)
                ->where('pay_date', $validated['pay_date'])
                ->first();

            if (!$existingPayroll) {
                Payroll::create([
                    'employee_id' => $employee->employee_id,
                    'salary_amount' => $employee->salary,
                    'bonus' => 0,
                    'deductions' => 0,
                    'pay_date' => $validated['pay_date'],
                ]);
                $count++;
            }
        }

        return redirect()->route('payroll.index')
            ->with('success', "Generated {$count} new payroll records.");
    }

    /**
     * Show payroll history for a specific employee.
     */
    public function employeePayrollHistory(Employee $employee)
    {
        $payrolls = Payroll::where('employee_id', $employee->employee_id)
            ->orderBy('pay_date', 'desc')
            ->get();

        return view('admin.payroll.employee-history', compact('employee', 'payrolls'));
    }

    /**
     * Generate payroll report for a period.
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $payrolls = Payroll::whereBetween('pay_date', [$validated['start_date'], $validated['end_date']])
            ->with('employee')
            ->get();

        $totalSalary = $payrolls->sum('salary_amount');
        $totalBonus = $payrolls->sum('bonus');
        $totalDeductions = $payrolls->sum('deductions');
        $totalNetSalary = $payrolls->sum('net_salary');

        return view('admin.payroll.report', compact(
            'payrolls', 
            'totalSalary', 
            'totalBonus', 
            'totalDeductions', 
            'totalNetSalary'
        ));
    }
}