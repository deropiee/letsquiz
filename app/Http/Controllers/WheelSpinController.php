<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Spin;

class WheelSpinController extends Controller
{
    public static function getRecentForUser(int $userId, int $limit = 4): array
    {
        if (!Schema::hasTable('spins')) return [];
        $rows = DB::table('spins')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        $out = [];
        foreach ($rows as $s) {
            $resultText = $s->result
                ?? ($s->amount !== null ? ('+' . (int)$s->amount . ' 💎') : ($s->is_jackpot ? 'Jackpot' : '—'));

            $out[] = [
                'name'   => $s->name ?? 'Wheelspin',
                'result' => $resultText,
                'date'   => $s->created_at ? Carbon::parse($s->created_at)->diffForHumans() : '',
            ];
        }
        return $out;
    }

    // JSON endpoint (optioneel)
    public function recent(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['data' => []]);
        $limit = (int) $request->query('limit', 4);
        return response()->json(['data' => self::getRecentForUser($user->id, $limit)]);
    }

    // Opslaan van een spin-resultaat
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:128',
            'amount' => 'nullable|numeric',
            'result' => 'nullable|string|max:255',
            'is_jackpot' => 'nullable|boolean',
        ]);

        $spin = Spin::create([
            'user_id'    => Auth::id(),
            'name'       => $request->input('name', 'Wheelspin'),
            'amount'     => $request->input('amount'),
            'result'     => $request->input('result'),
            'is_jackpot' => (bool) $request->input('is_jackpot', false),
        ]);

        return response()->json([
            'success' => true,
            'id' => $spin->id,
            'created_at' => $spin->created_at->toDateTimeString(),
        ]);
    }
}