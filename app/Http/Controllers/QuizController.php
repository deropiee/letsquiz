<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // Overzicht van alle quizmappen
    public function list($chapterParam = null)
    {
        // Haal alle mappen op uit public/quizzes
        $quizFolders = Storage::disk('public')->directories('quizzes');

        // Alleen de mapnamen (zonder 'quizzes/')
        $quizFolders = array_map('basename', $quizFolders);

        // Groepeer op hoofdstuk: eerste segment voor '-' (fallback '_')
        $groupedRaw = [];
        foreach ($quizFolders as $folder) {
            $base = $folder;
            $chapter = $base;
            $sep = null;
            if (str_contains($base, '-')) {
                $chapter = explode('-', $base)[0];
                $sep = '-';
            } elseif (str_contains($base, '_')) {
                $chapter = explode('_', $base)[0];
                $sep = '_';
            }
            $groupedRaw[$chapter][] = [
                'folder' => $folder,
                'sep' => $sep,
            ];
        }

        // Sorteer hoofdstukken en items alfabetisch/natuurlijk
        ksort($groupedRaw, SORT_NATURAL | SORT_FLAG_CASE);
        $chapters = [];
        $index = 1;
        foreach ($groupedRaw as $chapterKey => $items) {
            // sort items by folder naturally
            usort($items, function ($a, $b) {
                return strnatcasecmp($a['folder'], $b['folder']);
            });
            // maak schone titels (zonder hoofdstuk- prefix)
            $cleanItems = [];
            foreach ($items as $it) {
                $sep = $it['sep'] ?? '-';
                $prefix = $chapterKey . $sep;
                $name = $it['folder'];
                if (str_starts_with($name, $prefix)) {
                    $name = substr($name, strlen($prefix));
                }
                $cleanItems[] = [
                    'folder' => $it['folder'],
                    'title' => ucwords(str_replace(['-', '_'], ' ', $name)),
                ];
            }

            $chapters[] = [
                'key' => $chapterKey,
                'index' => $index++,
                'title' => ucwords(str_replace(['-', '_'], ' ', $chapterKey)),
                'items' => $cleanItems,
            ];
        }

        // Bepaal geselecteerd hoofdstuk
        $selectedChapterKey = null;
        if ($chapterParam) {
            // vind dichtstbijzijnde chapter: support zowel index als key in URL
            foreach ($chapters as $ch) {
                if ((string)$ch['index'] === (string)$chapterParam || $ch['key'] === $chapterParam) {
                    $selectedChapterKey = $ch['key'];
                    break;
                }
            }
        }
        if (!$selectedChapterKey && count($chapters)) {
            $selectedChapterKey = $chapters[0]['key'];
        }

        // Vind geselecteerd hoofdstuk object
        $selectedChapter = null;
        foreach ($chapters as $ch) {
            if ($ch['key'] === $selectedChapterKey) {
                $selectedChapter = $ch;
                break;
            }
        }

        // Bepaal welke quizzes (folders) de ingelogde gebruiker al afgerond heeft
        $completedQuizzes = [];
        if (Auth::check() && count($quizFolders)) {
            $completedQuizzes = Category::query()
                ->whereIn('folder_guid', $quizFolders)
                ->whereHas('results', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->pluck('folder_guid')
                ->toArray();
        }

        return view('quiz.list', [
            'quizFolders' => $quizFolders,
            'grouped' => $groupedRaw,
            'chapters' => $chapters,
            'selectedChapterKey' => $selectedChapterKey,
            'selectedChapter' => $selectedChapter,
            'completedQuizzes' => $completedQuizzes,
        ]);
    }

    // Detailpagina voor één quizmap
    public function show($slug)
    {
        $category = Category::where('folder_guid', $slug)->firstOrFail();

        $questions = Question::with('choices')
            ->where('category_id', $category->id)
            ->get();

        return view('quiz.show', compact('questions', 'slug'));;
    }

    public function complete(Request $request, $slug)
    {
        $category = Category::where('folder_guid', $slug)->firstOrFail();

        $correctAnswers = (int) $request->input('correct_answers', 0);
        $wrongAnswers = (int) $request->input('wrong_answers', 0);
        $timeTaken = (int) $request->input('time_taken', 0); // in seconden
        $gemsEarned = (int) $request->input('gems_earned', 0);

        $result = Result::create([
            'user_id' => Auth::id(),
            'category_id' => $category->id,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'time_taken' => $timeTaken,
            'gems_earned' => $gemsEarned,
            'is_private' => true, // Standaard privé
        ]);

        return response()->json([
            'success' => true,
            'message' => "Quiz afgerond! Resultaat opgeslagen.",
            'result_id' => $result->id,
        ]);
    }
}
