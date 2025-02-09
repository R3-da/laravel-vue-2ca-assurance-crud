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
        try {

            \Illuminate\Support\Facades\Log::info('New claim created', $request->all());
            // Validate the request data
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:claims,name'], // Ensure the name is unique in the claims table
                'description' => ['required', 'string'],
                'user_id' => ['required', 'exists:users,id'], // Ensure the user_id exists in the users table
            ]);

            // Create a new claim
            $claim = new Claim();
            foreach ($validated as $key => $val) {
                $claim->{$key} = $val;
            }
            $claim->save();

            // Return the created claim as a JSON response
            return response()->json($claim);
        } catch (\Exception $error) {
            // Log the error and return an error response
            \Illuminate\Support\Facades\Log::error('Error creating claim: ' . $error->getMessage());
            return $this->errorResponse($error);
        }
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