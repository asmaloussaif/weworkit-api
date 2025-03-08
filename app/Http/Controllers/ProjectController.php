<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string',
        ]);

        $project = Project::create([
            'client_id' => auth()->id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'statut' => 'ouvert',
        ]);

        return response()->json($project, 201);
    }

    public function index()
    {
        return response()->json(Project::with('client')->get());
    }

    public function show($id)
    {
        return response()->json(Project::with('client')->findOrFail($id));
    }

    public function updateStatus(Request $request, $id)
    {
        $project = Project::where('client_id', auth()->id())->findOrFail($id);
        $project->update(['statut' => $request->statut]);

        return response()->json($project);
    }
}
