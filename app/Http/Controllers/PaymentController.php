<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'freelancer_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:1',
        ]);

        $payment = Transaction::create([
            'client_id' => auth()->id(),
            'freelancer_id' => $request->freelancer_id,
            'montant' => $request->montant,
            'statut' => 'en attente',
        ]);

        return response()->json($payment, 201);
    }

    public function index()
    {
        $transactions = Transaction::where('client_id', auth()->id())
            ->orWhere('freelancer_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($transactions);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en attente,terminé,annulé',
        ]);

        $transaction = Transaction::where('client_id', auth()->id())
            ->orWhere('freelancer_id', auth()->id())
            ->findOrFail($id);

        $transaction->update(['statut' => $request->statut]);

        return response()->json($transaction);
    }
}
