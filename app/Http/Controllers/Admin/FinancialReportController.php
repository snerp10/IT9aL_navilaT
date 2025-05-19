<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use App\Models\Billing;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FinancialReportController extends Controller
{
    /**
     * Display a listing of the financial reports.
     */
    public function index()
    {
        // Get the reports for the listing
        $reports = FinancialReport::latest()->paginate(10);
        
        // Get current month's data for the summary cards
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        
        // Get monthly data for the current month
        $monthlyData = $this->getFinancialDataForPeriod($startOfMonth, $endOfMonth);
        
        // Create the monthlySummary array
        $monthlySummary = [
            'treatment_revenue' => $monthlyData['treatment_revenue'],
            'product_revenue' => $monthlyData['product_revenue'],
            'total_revenue' => $monthlyData['total_revenue'],
            'total_expenses' => $monthlyData['expenses'],
            'net_profit' => $monthlyData['profit'],
            'period' => $startOfMonth->format('F Y')
        ];
        
        return view('admin.financial-reports.index', compact('reports', 'monthlySummary'));
    }

    /**
     * Show the form for creating a new financial report.
     */
    public function create()
    {
        return view('admin.financial-reports.create');
    }

    /**
     * Store a newly created financial report in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'total_product_cost' => 'required|numeric',
            'revenue_from_services' => 'required|numeric',
            'total_expenses' => 'required|numeric',
            'report_date' => 'required|date',
        ]);

        // net_profit is calculated automatically via the storedAs attribute in the migration
        FinancialReport::create($validated);

        return redirect()->route('financial-reports.index')
            ->with('success', 'Financial report created successfully');
    }

    /**
     * Display the specified financial report.
     */
    public function show(FinancialReport $financialReport)
    {
        return view('admin.financial-reports.show', compact('financialReport'));
    }

    /**
     * Generate a financial report for a specific period.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|string|in:daily,weekly,monthly,annual,custom',
        ]);

        try {
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);

            // Make sure dates were properly parsed
            if (!$startDate || !$endDate) {
                return back()->with('error', 'Invalid date format provided.');
            }

            // Get treatment revenue by joining with treatments table
            $treatmentRevenue = Billing::join('treatments', 'billing.treatment_id', '=', 'treatments.treatment_id')
                ->whereBetween('billing.created_at', [$startDate, $endDate])
                ->sum('billing.amount_due');

            // For now, assuming all revenue is treatment revenue
            $productRevenue = 0;

            // Calculate total expenses (this is a placeholder - adjust for your business logic)
            $totalExpenses = $treatmentRevenue * 0.4; // Example calculation

            // Create report - ensure all required fields are explicitly set
            $report = new FinancialReport();
            $report->total_product_cost = $productRevenue; // Ensure this is explicitly set
            $report->revenue_from_services = $treatmentRevenue; // Ensure this is explicitly set
            $report->total_expenses = $totalExpenses;
            $report->report_date = $endDate->format('Y-m-d'); // Ensure this is a string date format
            $report->report_type = $validated['report_type']; // Add the report type
            $report->user_id = Auth::id(); // Add the user ID
            $report->save();

            return redirect()->route('financial-reports.show', $report)
                ->with('success', 'Financial report generated successfully');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error generating financial report: ' . $e->getMessage());
            
            // Return a user-friendly error message
            return back()->with('error', 'Unable to generate financial report. Please check your date inputs.');
        }
    }

    /**
     * Display monthly summary report.
     */
    public function monthlySummary(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Get monthly data
        $monthlyData = $this->getFinancialDataForPeriod($startDate, $endDate);
        
        // Get daily breakdown
        $dailyBreakdown = [];
        $daysInMonth = $endDate->day;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            try {
                $date = Carbon::createFromDate($year, $month, $day);
                if ($date) {
                    $dayData = $this->getFinancialDataForPeriod($date->copy()->startOfDay(), $date->copy()->endOfDay());
                    
                    $dailyBreakdown[] = [
                        'date' => $date->format('Y-m-d'),
                        'day_name' => $date->format('l'),
                        'treatment_revenue' => $dayData['treatment_revenue'],
                        'product_revenue' => $dayData['product_revenue'],
                        'total_revenue' => $dayData['total_revenue'],
                        'expenses' => $dayData['expenses'],
                        'net_profit' => $dayData['profit']
                    ];
                }
            } catch (\Exception $e) {
                // Skip this day if there's an error creating the date
                continue;
            }
        }
        
        // Prepare summary data
        $summary = [
            'treatment_revenue' => $monthlyData['treatment_revenue'],
            'product_revenue' => $monthlyData['product_revenue'],
            'total_revenue' => $monthlyData['total_revenue'],
            'total_expenses' => $monthlyData['expenses'],
            'net_profit' => $monthlyData['profit'],
            'period' => $startDate ? $startDate->format('F Y') : date('F Y', mktime(0, 0, 0, $month, 1, $year))
        ];
        
        // Get available years for the dropdown
        $years = $this->getAvailableYears();
        
        // Pass the monthlySummary variable as well to fix the undefined variable error
        $monthlySummary = $dailyBreakdown;
        
        return view('admin.financial-reports.monthly-summary', compact(
            'monthlyData', 
            'dailyBreakdown', 
            'summary', 
            'year', 
            'month', 
            'years',
            'monthlySummary'
        ));
    }

    /**
     * Display annual summary report.
     */
    public function annualSummary(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        
        // Get monthly breakdown for the selected year
        $monthlyBreakdown = [];
        $totalTreatmentRevenue = 0;
        $totalProductRevenue = 0;
        $totalRevenue = 0;
        $totalExpenses = 0;
        $totalProfit = 0;
        
        for ($month = 1; $month <= 12; $month++) {
            try {
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
                
                if ($startDate && $endDate) {
                    $monthData = $this->getFinancialDataForPeriod($startDate, $endDate);
                    
                    // Format the month name
                    $monthName = date('F', mktime(0, 0, 0, $month, 1));
                    
                    $treatmentRevenue = $monthData['treatment_revenue'];
                    $productRevenue = $monthData['product_revenue'];
                    $revenue = $monthData['total_revenue'];
                    $expenses = $monthData['expenses'];
                    $profit = $monthData['profit'];
                    
                    // Add to totals
                    $totalTreatmentRevenue += $treatmentRevenue;
                    $totalProductRevenue += $productRevenue;
                    $totalRevenue += $revenue;
                    $totalExpenses += $expenses;
                    $totalProfit += $profit;
                    
                    $monthlyBreakdown[] = [
                        'month_name' => $monthName,
                        'month_num' => $month,
                        'treatment_revenue' => $treatmentRevenue,
                        'product_revenue' => $productRevenue,
                        'total_revenue' => $revenue,
                        'expenses' => $expenses,
                        'net_profit' => $profit
                    ];
                }
            } catch (\Exception $e) {
                // Skip this month if there's an error creating the date
                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                
                $monthlyBreakdown[] = [
                    'month_name' => $monthName,
                    'month_num' => $month,
                    'treatment_revenue' => 0,
                    'product_revenue' => 0,
                    'total_revenue' => 0,
                    'expenses' => 0,
                    'net_profit' => 0
                ];
            }
        }
        
        // Prepare summary data in the format expected by the view
        $summary = [
            'treatment_revenue' => $totalTreatmentRevenue,
            'product_revenue' => $totalProductRevenue,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_profit' => $totalProfit,
            'year' => $year
        ];
        
        // Get available years for the dropdown
        $years = $this->getAvailableYears();
        
        return view('admin.financial-reports.annual-summary', compact(
            'monthlyBreakdown', 
            'summary', 
            'year', 
            'years'
        ));
    }

    /**
     * Download PDF of a financial report.
     */
    public function downloadPdf(FinancialReport $financialReport)
    {
        // Logic to generate a PDF from the financial report
        // This would typically use a PDF library like Dompdf or Snappy
        
        return response('PDF Generation not implemented yet', 501);
    }

    /**
     * Remove the specified financial report from storage.
     */
    public function destroy(FinancialReport $financialReport)
    {
        try {
            $reportId = $financialReport->report_id;
            
            // Log the delete attempt for debugging
            \Log::info('Attempting to delete financial report #' . $reportId);
            
            // Check if the report exists
            if (!$financialReport->exists) {
                \Log::error('Financial report not found: ' . $reportId);
                return redirect()->route('financial-reports.index')
                    ->with('error', 'Financial report not found.');
            }
            
            // Perform the deletion
            $deleted = $financialReport->delete();
            
            if ($deleted) {
                \Log::info('Successfully deleted financial report #' . $reportId);
                return redirect()->route('financial-reports.index')
                    ->with('success', "Financial report #{$reportId} has been deleted successfully.");
            } else {
                \Log::error('Failed to delete financial report #' . $reportId);
                return redirect()->route('financial-reports.index')
                    ->with('error', 'Unable to delete the financial report. Please try again.');
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting financial report: ' . $e->getMessage());
            
            return redirect()->route('financial-reports.index')
                ->with('error', 'Unable to delete the financial report: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to get financial data for a specific period.
     */
    private function getFinancialDataForPeriod($startDate, $endDate)
    {
        // Get treatment revenue by joining with treatments table
        // Only count billings that are associated with dental treatments
        $treatmentRevenue = Billing::join('treatments', 'billing.treatment_id', '=', 'treatments.treatment_id')
            ->whereBetween('billing.created_at', [$startDate, $endDate])
            ->sum('billing.amount_due');
            
        // Get product revenue - for now, assuming all revenue is treatment revenue
        // This will need to be adjusted once you have product sales tracking
        $productRevenue = 0;
            
        // Calculate total revenue
        $totalRevenue = $treatmentRevenue + $productRevenue;
            
        // Get expenses from existing financial reports
        $expenses = FinancialReport::whereBetween('report_date', [$startDate, $endDate])
            ->sum('total_expenses');
            
        if ($expenses == 0) {
            // If no expenses recorded in financial_reports, use a default calculation
            $expenses = $treatmentRevenue * 0.4; // Example calculation
        }
        
        return [
            'treatment_revenue' => $treatmentRevenue,
            'product_revenue' => $productRevenue,
            'total_revenue' => $totalRevenue,
            'expenses' => $expenses,
            'profit' => $totalRevenue - $expenses,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
    
    /**
     * Helper method to get available years for report dropdowns.
     */
    private function getAvailableYears()
    {
        // Get years from billing records (using created_at instead of billing_date)
        $billingYears = Billing::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
            
        // Get years from financial reports using report_date
        $reportYears = FinancialReport::selectRaw('YEAR(report_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
            
        // Merge and get unique years
        $years = array_unique(array_merge($billingYears, $reportYears));
        
        // If no years found, use current year
        if (empty($years)) {
            $years = [Carbon::now()->year];
        }
        
        return $years;
    }
}