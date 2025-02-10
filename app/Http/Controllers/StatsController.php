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

        $query = Claim::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $stats = [
            'totalClaims' => $query->count(),
            'approvedClaims' => $query->where('status', 'approved')->count(),
            'pendingClaims' => $query->where('status', 'pending')->count(),
            'rejectedClaims' => $query->where('status', 'rejected')->count(),
            'monthlyStats' => $query
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->get()
        ];

        return response()->json($stats);
    }
}
