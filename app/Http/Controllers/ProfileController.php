<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'competences' => 'required|string',
            'experience' => 'required|string',
            'portfolio' => 'nullable|string',
            'tarif' => 'nullable|numeric',
        ]);

        $profile = Profile::create(array_merge($request->all(), ['user_id' => auth()->id()]));

        return response()->json($profile, 201);
    }

    public function show($id)
    {
        return response()->json(Profile::with('user')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $profile = Profile::where('user_id', auth()->id())->findOrFail($id);
        $profile->update($request->all());

        return response()->json($profile);
    }

    public function index()
    {
        return response()->json(Profile::with('user')->get());
    }
}
