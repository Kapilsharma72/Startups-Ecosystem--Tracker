<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use App\Models\Investor;
use App\Exports\StartupsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class StartupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startups = Startup::with('investors')->get();
        return response()->json($startups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'stage' => 'required|string|max:255',
            'funding' => 'required|numeric',
            'funding_round' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'emp_numbers' => 'nullable|integer', // Changed from team_members
            'technologies' => 'nullable|string', // Changed from array to string
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $startup = Startup::create($request->all());
            return response()->json([
                'message' => 'Startup created successfully',
                'startup' => $startup
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating startup: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create startup',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Startup $startup)
    {
        $startup->load('investors', 'newsArticles');
        return response()->json($startup);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Startup $startup)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'industry' => 'sometimes|required|string|max:255',
            'stage' => 'sometimes|required|string|max:255',
            'funding' => 'sometimes|required|numeric',
            'funding_round' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'emp_numbers' => 'nullable|integer', // Changed from team_members
            'technologies' => 'nullable|string', // Changed from array to string
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $startup->update($request->all());
            return response()->json([
                'message' => 'Startup updated successfully',
                'startup' => $startup
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating startup: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update startup',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Startup $startup)
    {
        try {
            $startup->delete();
            return response()->json(['message' => 'Startup deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting startup: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete startup',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard data including stats and charts data
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardData()
    {
        // Use cache with 1-minute expiration
        $dashboardData = Cache::remember('dashboard_data', 60, function () {
            $now = Carbon::now();
            $lastMonth = Carbon::now()->subMonth();

            // Calculate statistics
            $currentMonthStartups = Startup::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();
            
            $lastMonthStartups = Startup::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count();
            
            // Calculate startup growth percentage
            $startupGrowth = $lastMonthStartups > 0 
                ? round((($currentMonthStartups - $lastMonthStartups) / $lastMonthStartups) * 100, 1)
                : 0;
            
            // Calculate total funding
            $totalFunding = Startup::sum('funding') * 1000000; // Convert to actual amount
            
            // Calculate funding growth
            $currentMonthFunding = Startup::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->sum('funding');
            
            $lastMonthFunding = Startup::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->sum('funding');
            
            $fundingGrowth = $lastMonthFunding > 0 
                ? round((($currentMonthFunding - $lastMonthFunding) / $lastMonthFunding) * 100, 1)
                : 0;
            
            // Calculate active investors
            $activeInvestors = Investor::count();
            
            // Calculate investor growth
            $currentMonthInvestors = Investor::whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();
            
            $lastMonthInvestors = Investor::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count();
            
            $investorGrowth = $lastMonthInvestors > 0 
                ? round((($currentMonthInvestors - $lastMonthInvestors) / $lastMonthInvestors) * 100, 1)
                : 0;
            
            // Calculate success rate (startups with funding > $5M)
            $successfulStartups = Startup::where('funding', '>=', 5)->count(); // $5M or more
            $totalStartups = Startup::count();
            
            $successRate = $totalStartups > 0 ? round(($successfulStartups / $totalStartups) * 100, 1) : 0;
            
            // Calculate success growth
            // Define success in the previous month
            $successfulLastMonth = Startup::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->where('funding', '>=', 5)
                ->count();
            
            $totalLastMonth = Startup::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count();
            
            $successRateLastMonth = $totalLastMonth > 0 ? ($successfulLastMonth / $totalLastMonth) * 100 : 0;
            
            $successGrowth = $successRateLastMonth > 0 
                ? round(($successRate - $successRateLastMonth), 1)
                : 0;
            
            // Get funding trends data (last 12 months)
            $fundingTrends = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthFunding = Startup::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('funding');
                
                $fundingTrends[] = [
                    'month' => $date->format('M'),
                    'funding' => $monthFunding
                ];
            }
            
            // Get industry distribution
            $industries = Startup::select('industry', DB::raw('count(*) as total'))
                ->groupBy('industry')
                ->orderByDesc('total')
                ->limit(6) // Top 6 industries
                ->get()
                ->toArray();
            
            // Get top startups
            $topStartups = Startup::orderByDesc('funding')
                ->limit(5)
                ->get()
                ->map(function ($startup) {
                    // Calculate growth based on previous period (placeholder - you may need to adjust this logic)
                    // In a real system, you might have historical data to calculate actual growth
                    $growth = rand(-10, 30); // For demonstration only - replace with actual growth calculation
                    
                    return [
                        'id' => $startup->id,
                        'name' => $startup->name,
                        'logo' => $startup->logo ?: 'https://via.placeholder.com/150',
                        'location' => $startup->location,
                        'industry' => $startup->industry,
                        'funding' => $startup->funding * 1000000, // Convert to actual amount
                        'growth' => $growth
                    ];
                });
            
            // Assemble data
            return [
                'stats' => [
                    'totalStartups' => $totalStartups,
                    'startupGrowth' => $startupGrowth,
                    'totalFunding' => $totalFunding,
                    'fundingGrowth' => $fundingGrowth,
                    'activeInvestors' => $activeInvestors,
                    'investorGrowth' => $investorGrowth,
                    'successRate' => $successRate,
                    'successGrowth' => $successGrowth
                ],
                'fundingTrends' => $fundingTrends,
                'industries' => $industries,
                'topStartups' => $topStartups
            ];
        });
        
        return response()->json($dashboardData);
    }

    /**
     * Get enhanced startup list data with additional calculated metrics
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function listData()
    {
        // Use cache with 1-minute expiration for better performance
        $startupListData = Cache::remember('startup_list_data', 60, function () {
            $startups = Startup::all();
            
            return $startups->map(function ($startup) {
                // Get founding date or generate a random one if not available
                $foundedDate = $startup->created_at 
                    ? $startup->created_at->format('Y') 
                    : (2015 + rand(0, 10)); // Random year between 2015-2025
                
                // Generate a random growth percentage if not available
                $growth = rand(-10, 40);
                
                // Get employee count or use emp_numbers if available
                $employees = $startup->emp_numbers ?? rand(5, 500);
                
                return [
                    'id' => $startup->id,
                    'name' => $startup->name,
                    'logo' => $startup->logo ?? 'https://via.placeholder.com/150',
                    'location' => $startup->location,
                    'industry' => $startup->industry,
                    'stage' => $startup->stage,
                    'funding' => $startup->funding,
                    'fundingRound' => $startup->funding_round,
                    'founded' => $foundedDate,
                    'employees' => $employees,
                    'growth' => $growth,
                    'description' => $startup->description,
                    'website' => $startup->website,
                    'technologies' => $startup->technologies
                ];
            });
        });
        
        return response()->json($startupListData);
    }

    /**
     * Export startups data to Excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        try {
            return Excel::download(new StartupsExport, 'startups-'.date('Y-m-d').'.xlsx');
        } catch (\Exception $e) {
            Log::error('Error exporting startups: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to export startups',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}