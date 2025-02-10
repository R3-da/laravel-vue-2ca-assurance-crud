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

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $stats = [
            'totalClaims' => $query->count(),
            'approvedClaims' => (clone $query)->where('status', 'approved')->count(),
            'pendingClaims' => (clone $query)->where('status', 'pending')->count(),
            'rejectedClaims' => (clone $query)->where('status', 'rejected')->count(),
            'openClaims' => (clone $query)->where('status', 'open')->count(),
        ];

        return response()->json($stats);
    }
}
