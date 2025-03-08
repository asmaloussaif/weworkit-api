<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'statut' => 'en attente',
        ]);

        return response()->json($application, 201);
    }

    public function index($project_id)
    {
        $applications = Application::where('project_id', $project_id)->with('freelancer')->get();
        return response()->json($applications);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en attente,accepté,refusé',
        ]);

        $application = Application::findOrFail($id);
        $application->update(['statut' => $request->statut]);

        return response()->json($application);
    }
}
