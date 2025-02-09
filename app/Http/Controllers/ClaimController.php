<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = Claim::with('user', 'broker')->get();
        return response()->json($claims);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $claim = Claim::create($validated);
        return response()->json($claim);
    }

    public function update(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        $claim->update($validated);
        return response()->json($claim);
    }

    public function destroy(Claim $claim)
    {
        $claim->delete();
        return response()->json(null, 204);
    }
}