<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Reclamation;
use App\Models\Transaction;
class AdminController extends Controller
{
    public function dashboardSummary()
{
    $totalUsers = User::whereHas('roles', function ($query) {
        $query->where('name', '!=', 'admin');
    })->count();
    $totalProjects = Project::count();
    $totalClaims = Reclamation::count();
    $totalInvoices = Transaction::count();
    $pendingApprovals = Project::where('statut', 'pending')->count(); 

    return response()->json([
        'totalUsers' => $totalUsers,
        'totalProjects' => $totalProjects,
        'totalClaims' => $totalClaims,
        'totalInvoices' => $totalInvoices,
        'pendingApprovals' => $pendingApprovals,
    ]);
}

}
