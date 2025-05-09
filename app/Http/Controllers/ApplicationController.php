<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'motivation' => 'nullable|string',
        ]);

        $application = Application::create([ 
            'freelancer_id' => Auth::id(),
            'project_id' => $request->project_id,
            'motivation' => $request->motivation,
            'statut' => 'on hold',
        ]);

        return response()->json($application, 201);
    }
    public function myApplications()
    {
        $freelancerId = Auth::id();
    
        $applications = Application::with('project.client') 
            ->where('freelancer_id', $freelancerId)
            ->get();
    
        return response()->json($applications);
    }
    public function index($project_id)
    {
        $applications = Application::where('project_id', $project_id)->with('freelancer')->get();
        return response()->json($applications);
    }

    public function updateStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update(['statut' => $request->statut]);
    
        $project = Project::where('id', $application->project_id)
                          ->where('client_id', auth()->id())
                          ->firstOrFail();
    
        $project->update(['statut' => 'in_progress']);
    
        return response()->json([
            'application' => $application,
            'project' => $project,
        ]);
    }
    
    public function applicationsByProject(Request $request)
    {
        $clientId = Auth::id();
        $projectIds = Project::where('client_id', $clientId)
        ->where('statut', 'open')
        ->pluck('id');
    
        Log::info('Project IDs for client:', ['projects' => $projectIds]);
   
        $applications = Application::with(['project.client', 'freelancer'])
            ->whereIn('project_id', $projectIds)
            ->get();
            Log::info('applications IDs for client:', ['projects' => $applications]);
     
        $grouped = $applications->groupBy('project_id')->map(function ($apps, $projectId) {
            return [
                'project_id' => $projectId,
                'freelancer_count' => $apps->pluck('freelancer_id')->unique()->count(),
                'applications' => $apps->map(function ($app) {
                    return [
                        'id'=>$app->id,
                        'freelancer_id' => $app->freelancer_id,
                        'status' => $app->statut,
                        'freelancer' => $app->freelancer,
                    ];
                }),
                'project' => $apps->first()->project ?? null,
            ];
        })->values();
    
        return response()->json($grouped);
    }
    
    
}
