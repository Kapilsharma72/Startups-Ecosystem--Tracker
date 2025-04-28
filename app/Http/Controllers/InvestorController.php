<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $investors = Investor::with('startups')->get();
        return response()->json($investors);
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
            'type' => 'required|string|max:255',
            'focus_areas' => 'required|string',
            'portfolio_size' => 'required|integer',
            'total_investment' => 'required|numeric',
            'avg_check' => 'nullable|numeric',
            'description' => 'nullable|string',
            'founded' => 'nullable|integer|min:1900|max:' . date('Y'),
            'website' => 'nullable|string|max:255',
            'team_members' => 'nullable|string',
            'investment_criteria' => 'nullable|string',
            'portfolio_companies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $investor = Investor::create($request->all());
            return response()->json([
                'message' => 'Investor created successfully',
                'investor' => $investor
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating investor: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create investor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Investor $investor)
    {
        $investor->load('startups', 'newsArticles');
        return response()->json($investor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Investor $investor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'focus_areas' => 'sometimes|required|string',
            'portfolio_size' => 'sometimes|required|integer',
            'total_investment' => 'sometimes|required|numeric',
            'avg_check' => 'nullable|numeric',
            'description' => 'nullable|string',
            'founded' => 'nullable|integer|min:1900|max:' . date('Y'),
            'website' => 'nullable|string|max:255',
            'team_members' => 'nullable|string',
            'investment_criteria' => 'nullable|string',
            'portfolio_companies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $investor->update($request->all());
            return response()->json([
                'message' => 'Investor updated successfully',
                'investor' => $investor
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating investor: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update investor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investor $investor)
    {
        try {
            $investor->delete();
            return response()->json(['message' => 'Investor deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting investor: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete investor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get enhanced investor list data with additional calculated metrics
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function listData()
    {
        // Use cache with 1-minute expiration for better performance
        $investorListData = Cache::remember('investor_list_data', 60, function () {
            $investors = Investor::all();
            
            return $investors->map(function ($investor) {
                // Generate a random growth percentage if not available
                $growth = rand(-10, 40);
                
                return [
                    'id' => $investor->id,
                    'name' => $investor->name,
                    'logo' => $investor->logo ?? 'https://via.placeholder.com/150',
                    'location' => $investor->location,
                    'type' => $investor->type,
                    'focus_areas' => $investor->focus_areas,
                    'portfolio_size' => $investor->portfolio_size,
                    'total_investment' => $investor->total_investment,
                    'avg_check' => $investor->avg_check,
                    'founded' => $investor->founded,
                    'description' => $investor->description,
                    'website' => $investor->website,
                    'team_members' => $investor->team_members,
                    'investment_criteria' => $investor->investment_criteria,
                    'portfolio_companies' => $investor->portfolio_companies,
                    'growth' => $growth
                ];
            });
        });
        
        return response()->json($investorListData);
    }
}