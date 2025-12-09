<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Result;
use App\Http\Controllers\WheelSpinController;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $recentResults = [];

        if ($user) {
            // haal 2 nieuwste resultaten op voor deze gebruiker
            $rows = \App\Models\Result::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(3)
                ->get();

            foreach ($rows as $r) {
                // titel: gebruik pretty_folder van category
                $title = $r->category ? $r->category->pretty_folder : 'Quiz #' . ($r->id ?? '');

                // Pak het id van het resultaat en maak er de juiste url van
                $url = url('/results/' . $r->id);

                // datum
                $date = $r->created_at ? $r->created_at->diffForHumans() : ($r->date ?? '');

                if (isset($r->correct_answers) || isset($r->wrong_answers)) {
                    $total = ($r->correct_answers ?? 0) + ($r->wrong_answers ?? 0);
                    $score = $total > 0 ? (($r->correct_answers ?? 0) . '/' . $total) : ($r->score ?? '—');
                } else {
                    $score = $r->score ?? ($r->result ?? '—');
                }

                $gems = (int) ($r->gems_earned ?? $r->gems ?? 0);

                $recentResults[] = [
                    'title' => (string) $title,
                    'date'  => (string) $date,
                    'score' => (string) $score,
                    'gems'  => $gems,
                    'url' => $url,
                ];
            }
        }

        // nieuwe: recente spins
        $recentSpins = $user ? WheelSpinController::getRecentForUser($user->id, 4) : [];

        return view('dashboard', compact('recentResults', 'recentSpins'));
    }
}