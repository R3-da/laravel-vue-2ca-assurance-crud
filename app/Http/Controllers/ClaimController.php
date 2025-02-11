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
                'subject' => ['required', 'string', 'max:255', 'unique:claims,subject'],
                'detailed_description' => ['required', 'string'],
                'user.id' => ['required', 'exists:users,id'], // Validating the nested user.id
                'category' => ['required', 'string'],
                'status' => ['required', 'string'],
                'broker_id' => ['nullable', 'exists:users,id'], // Optional field
            ]);

            // Create a new claim
            $claim = new Claim();

            // Manually assign user_id instead of user.id
            $claim->user_id = $validated['user']['id']; // Correctly assign the user_id field

            // Assign other validated fields
            foreach ($validated as $key => $val) {
                // Avoid setting 'user.id' as it is handled separately
                if ($key !== 'user') {
                    $claim->{$key} = $val;
                }
            }

            // Save the claim
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
            'subject' => 'required|string',
            'detailed_description' => 'required|string',
            'category' => 'required|string|in:Refund,Contract Issue,Billing Error,Other',
            'status' => 'required|string|in:Open,In Progress,Resolved,Closed'
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