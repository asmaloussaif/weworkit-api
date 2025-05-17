<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'statut' => 'open',
            'categorie'=> $request->categorie,
            'budget'=> $request->budget,
            'date_limite'=> $request->date_limite,
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
    public function myProjects()
    {
        $clientId = Auth::id();
    
        $projects = Project::with(['selectedApplication.freelancer']) 
            ->where('client_id', $clientId)
            ->get();
    
        return response()->json($projects);
    }
    public function update(Request $request, $id)
{
    $project = Project::where('client_id', auth()->id())->findOrFail($id);

    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'categorie' => 'nullable|string',
        'budget' => 'nullable|numeric',
        'date_limite' => 'nullable|date',
    ]);

    $project->update($validated);

    return response()->json([
        'message' => 'Project updated successfully.',
        'project' => $project,
    ]);
}
public function destroy($id)
{
    $project = Project::where('client_id', auth()->id())->findOrFail($id);

    $project->delete();

    return response()->json([
        'message' => 'Project deleted successfully.',
    ]);
}
public function getSummary()
{
    $summary = DB::table('projects')
        ->select('statut', DB::raw('count(*) as total'))
        ->groupBy('statut')
        ->get();

    return response()->json($summary);
}
public function getUnpaidProjectsWithClient()
{
    $projects = Project::with('client') 
        ->where('statut', 'in_progress')  
        ->get();

    return response()->json($projects);
}
}
