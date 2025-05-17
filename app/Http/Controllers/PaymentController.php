<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:1',
        ]);

        $payment = Transaction::create([
            'client_id' => $request->client_id,
            'freelancer_id' =>  auth()->id(),
            'montant' => $request->montant,
            'statut' => 'Unpaid',
            'description'=> $request->description,
            'project_id' => $request->project_id,
           
        ]);

        return response()->json($payment, 201);
    }

    public function index()
    {
        $transactions = Transaction::with('freelancer','client','project')
             ->where('client_id', auth()->id())
            ->orWhere('freelancer_id', auth()->id())
            ->orderBy('created_at', 'desc')

            ->get();

        return response()->json($transactions);
    }

    public function updateStatus(Request $request, $id)
    {

        $transaction = Transaction::where('client_id', auth()->id())
            ->orWhere('freelancer_id', auth()->id())
            ->findOrFail($id);

        $transaction->update(['statut' => $request->statut]);

        return response()->json($transaction);
    }
    public function show($id)
{
    $transaction = Transaction::where(function ($query) {
        $query->where('client_id', auth()->id())
              ->orWhere('freelancer_id', auth()->id());
    })->findOrFail($id);

    return response()->json($transaction);
}
public function showByProject($projectId)
{
    $transaction = Transaction::where('project_id', $projectId)
        ->first();

    if (!$transaction) {
        return response()->json(['message' => 'Transaction not found'], 404);
    }

    return response()->json($transaction);
}
}
