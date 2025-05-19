<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DentalService;
use Illuminate\Http\Request;

class DentalServiceController extends Controller
{
    /**
     * Display a listing of dental services.
     */
    public function index(Request $request)
    {
        // Query building
        $query = DentalService::query();
        
        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Ordering
        $query->orderBy($request->get('sort', 'name'), $request->get('direction', 'asc'));
        
        // Get paginated results
        $services = $query->paginate(10)->withQueryString();
        
        // Get unique categories for filter dropdown
        $categories = DentalService::distinct()->pluck('category')->filter()->sort();
        
        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new dental service.
     */
    public function create()
    {
        // Get unique categories for datalist
        $categories = DentalService::distinct()->pluck('category')->filter()->sort();
        
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created dental service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'standard_cost' => 'required|numeric|min:0',
            'standard_duration' => 'nullable|integer|min:1',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Set default is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        DentalService::create($validated);
        
        return redirect()->route('services.index')
            ->with('success', 'Dental service created successfully.');
    }

    /**
     * Display the specified dental service.
     */
    public function show(DentalService $service)
    {
        // Eager load related treatments for this service
        $service->load(['treatments' => function($query) {
            $query->latest()->limit(10);
        }]);
        
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified dental service.
     */
    public function edit(DentalService $service)
    {
        // Get unique categories for datalist
        $categories = DentalService::distinct()->pluck('category')->filter()->sort();
        
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified dental service in storage.
     */
    public function update(Request $request, DentalService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'standard_cost' => 'required|numeric|min:0',
            'standard_duration' => 'nullable|integer|min:1',
            'category' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Check for is_active in request data
        if ($request->has('is_active')) {
            $validated['is_active'] = (bool) $request->is_active;
        } else {
            $validated['is_active'] = false;
        }

        $service->update($validated);
        
        return redirect()->route('services.index')
            ->with('success', 'Dental service updated successfully.');
    }

    /**
     * Remove the specified dental service from storage.
     */
    public function destroy(DentalService $service)
    {
        // Check if service is used in treatments
        $hasUsage = $service->treatments()->exists();
        
        if ($hasUsage) {
            return redirect()->route('services.show', $service)
                ->with('error', 'Cannot delete this service because it has been used in treatments. You can set it to inactive instead.');
        }
        
        $service->delete();
        
        return redirect()->route('services.index')
            ->with('success', 'Dental service deleted successfully.');
    }
}