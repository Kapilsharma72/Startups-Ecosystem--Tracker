<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'team_members' => 'nullable|array',
            'technologies' => 'nullable|array',
            'metrics' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $startup = Startup::create($request->all());
        return response()->json($startup, 201);
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
            'team_members' => 'nullable|array',
            'technologies' => 'nullable|array',
            'metrics' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $startup->update($request->all());
        return response()->json($startup);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Startup $startup)
    {
        $startup->delete();
        return response()->json(null, 204);
    }
} 