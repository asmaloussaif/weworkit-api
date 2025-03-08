<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Review;
class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'freelancer_id' => 'required|exists:users,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string',
        ]);

        $review = Review::create([
            'client_id' => auth()->id(),
            'freelancer_id' => $request->freelancer_id,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
        ]);

        return response()->json($review, 201);
    }

    public function index($freelancer_id)
    {
        $reviews = Review::where('freelancer_id', $freelancer_id)->with('client')->get();
        return response()->json($reviews);
    }
}
