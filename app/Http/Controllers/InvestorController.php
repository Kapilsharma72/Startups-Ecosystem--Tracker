<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'focus_areas' => 'required|array',
            'portfolio_size' => 'required|integer',
            'total_investment' => 'required|numeric',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'team_members' => 'nullable|array',
            'investment_criteria' => 'nullable|array',
            'portfolio_companies' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $investor = Investor::create($request->all());
        return response()->json($investor, 201);
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
            'focus_areas' => 'sometimes|required|array',
            'portfolio_size' => 'sometimes|required|integer',
            'total_investment' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'team_members' => 'nullable|array',
            'investment_criteria' => 'nullable|array',
            'portfolio_companies' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $investor->update($request->all());
        return response()->json($investor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Investor $investor)
    {
        $investor->delete();
        return response()->json(null, 204);
    }
} 