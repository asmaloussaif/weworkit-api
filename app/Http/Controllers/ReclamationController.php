<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index()
    {
        return response()->json(Reclamation::with('user')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $reclamation = Reclamation::create($request->all());

        return response()->json($reclamation, 201);
    }

    public function show(Reclamation $reclamation)
    {
        return response()->json($reclamation->load('user'));
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        $request->validate([
            'sujet' => 'string|max:255',
            'description' => 'string',
        ]);

        $reclamation->update($request->all());

        return response()->json($reclamation);
    }

    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();
        return response()->json(null, 204);
    }
    public function updateStatus(Request $request, $id)
{
    $reclamation = Reclamation::findOrFail($id);
    $reclamation->statut = $request->input('statut');
    $reclamation->save();

    return response()->json($reclamation);
}
}
