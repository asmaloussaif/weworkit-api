<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function projectStatusStats()
    {
        $clientId = auth()->id();
    
        $stats = \App\Models\Project::where('client_id', $clientId)
            ->select('statut', \DB::raw('COUNT(*) as count'))
            ->groupBy('statut')
            ->get();
    
        return response()->json($stats);
    }
    public function projectDeadlineStats()
{
    $clientId = auth()->id();

    $stats = \App\Models\Project::where('client_id', $clientId)
        ->select(\DB::raw('DATE_FORMAT(date_limite, "%Y-%m") as month'), \DB::raw('COUNT(*) as count'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return response()->json($stats);
}
}
