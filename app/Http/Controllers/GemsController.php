<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GemsController extends Controller
{
    // Haal huidige gems op
    public function getGems()
    {
        $user = Auth::user();
        return response()->json(['gems' => $user->gems]);
    }

    // Voeg gems toe
    public function addGems(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $request->validate([
            'amount' => 'required|integer|min:1|max:1000000',
        ]);

        $amount = min($request->amount, 1000000);
        $previous = $user->gems; // voor debug
        $user->gems += $amount;
        $user->save();

        return response()->json([
            'success' => true,
            'gems' => $user->gems,
            'added' => $amount,
            'previous' => $previous
        ]);
    }

}
