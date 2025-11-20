<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DriverIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverIssueController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:mechanical,accident,other',
            'description' => 'required|string',
            'bus_id' => 'nullable|exists:buses,id',
        ]);

        $issue = DriverIssue::create([
            'driver_id' => Auth::id(),
            'bus_id' => $request->bus_id,
            'type' => $request->type,
            'description' => $request->description,
            'status' => 'open',
        ]);

        return response()->json([
            'message' => 'Issue reported successfully',
            'data' => $issue,
        ], 201);
    }
}
