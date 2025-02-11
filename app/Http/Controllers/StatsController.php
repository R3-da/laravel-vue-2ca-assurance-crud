<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // log the start and end dates
        \Illuminate\Support\Facades\Log::info('Start date: ' . $startDate);
        \Illuminate\Support\Facades\Log::info('End date: ' . $endDate);
        \Illuminate\Support\Facades\Log::info('Query Parameters:', $request->all());

        $query = Claim::query();

        // Apply date range filter if provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Fetch the stats for the different claim statuses
        $stats = [
            'totalClaims' => $query->count(), // Total claims
            'openClaims' => (clone $query)->where('status', 'open')->count(),
            'inProgressClaims' => (clone $query)->where('status', 'in progress')->count(),
            'resolvedClaims' => (clone $query)->where('status', 'resolved')->count(),
            'closedClaims' => (clone $query)->where('status', 'closed')->count(),
        ];

        return response()->json($stats);
    }
}