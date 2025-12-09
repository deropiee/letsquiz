<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        // Get active tab from URL parameter, default to 'my-results'
        $activeTab = $request->get('tab', 'my-results');
        
        // Get page parameters for pagination - handle both regular page and specific page parameters
        $myPage = $request->get('my_page', 1);
        $sharedPage = $request->get('shared_page', 1);
        $page = $request->get('page', 1); // fallback
        
        // Initialize pagination variables
        $myResults = null;
        $sharedResults = null;
        
        if ($activeTab === 'shared-results') {
            // Only load shared results if that tab is active
            $sharedResults = Auth::user()->sharedResults()
                ->with('user', 'category')
                ->orderByDesc('result_user_shares.created_at')
                ->paginate(5, ['*'], 'shared_page', $sharedPage);
                
            // Create empty paginator for my results to avoid errors
            $myResults = Result::where('user_id', Auth::id())
                ->paginate(5, ['*'], 'my_page', 1);
        } else {
            // Only load my results if that tab is active (default)
            $myResults = Result::where('user_id', Auth::id())
                ->with('user', 'category')
                ->orderByDesc('created_at')
                ->paginate(5, ['*'], 'my_page', $myPage);
                
            // Create empty paginator for shared results to avoid errors
            $sharedResults = Auth::user()->sharedResults()
                ->paginate(5, ['*'], 'shared_page', 1);
        }

        // Set the correct base URL for pagination links
        $myResults->withPath(route('results.index'));
        $sharedResults->withPath(route('results.index'));

        return view('results.index', compact('myResults', 'sharedResults', 'activeTab'));
    }

    public function ajaxIndex(Request $request)
    {
        // Only allow AJAX requests - check multiple indicators
        $isAjax = $request->ajax() || 
                  $request->wantsJson() || 
                  $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                  str_contains($request->header('Accept', ''), 'application/json');
                  
        if (!$isAjax) {
            abort(404, 'Page not found');
        }

        // Get active tab from URL parameter, default to 'my-results'
        $activeTab = $request->get('tab', 'my-results');
        
        // Validate tab parameter
        if (!in_array($activeTab, ['my-results', 'shared-results'])) {
            $activeTab = 'my-results';
        }
        
        // Get page parameters for pagination - handle both regular page and specific page parameters
        $myPage = $request->get('my_page', 1);
        $sharedPage = $request->get('shared_page', 1);
        $page = $request->get('page', 1); // fallback
        
        // Initialize pagination variables
        $myResults = null;
        $sharedResults = null;
        
        if ($activeTab === 'shared-results') {
            // Only load shared results if that tab is active
            $sharedResults = Auth::user()->sharedResults()
                ->with('user', 'category')
                ->orderByDesc('result_user_shares.created_at')
                ->paginate(5, ['*'], 'shared_page', $sharedPage);
                
            // Create empty paginator for my results to avoid errors
            $myResults = Result::where('user_id', Auth::id())
                ->paginate(5, ['*'], 'my_page', 1);
        } else {
            // Only load my results if that tab is active (default)
            $myResults = Result::where('user_id', Auth::id())
                ->with('user', 'category')
                ->orderByDesc('created_at')
                ->paginate(5, ['*'], 'my_page', $myPage);
                
            // Create empty paginator for shared results to avoid errors
            $sharedResults = Auth::user()->sharedResults()
                ->paginate(5, ['*'], 'shared_page', 1);
        }

        // Set the correct base URL for pagination links
        $myResults->withPath(route('results.index'));
        $sharedResults->withPath(route('results.index'));

        // Return JSON response with HTML content
        return response()->json([
            'html' => view('results.partials.results-content', compact('myResults', 'sharedResults', 'activeTab'))->render(),
            'activeTab' => $activeTab
        ]);
    }

    /**
     * Format date for display - just show the date with 2 hour offset
     */
    public static function formatDate($date)
    {
        $date = Carbon::parse($date)->addHours(2); // Fix timezone issue
        return $date->format('d-m-Y');
    }

    public function show(Request $request, $id)
    {
        $result = Result::with('user', 'category', 'sharedUsers')->findOrFail($id);

        // Check toegang: eigenaar, publiek, of gedeeld met jou
        $canView = $result->user_id === Auth::id() || 
                   !$result->is_private || 
                   $result->sharedUsers->contains(Auth::id());

        if (!$canView) {
            return response()
                ->view('errors.private', ['message' => 'Dit resultaat is privé.']);
        }

        // Get the tab parameter to know which tab to return to
        $fromTab = $request->get('from_tab', 'my-results');

        return view('results.show', compact('result', 'fromTab'));
    }

    public function updateVisibility(Request $request, $id)
    {
        $request->validate([
            'is_private' => 'required|boolean',
        ]);

        $result = Result::findOrFail($id);
        if ($result->user_id !== Auth::id()) {
            abort(403);
        }

        $result->is_private = (bool) $request->boolean('is_private');
        $result->save();

        return redirect()->route('results.show', $result->id)->with('status', 'Zichtbaarheid bijgewerkt');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'correct_answers' => 'required|integer',
            'wrong_answers' => 'required|integer',
            'time_taken' => 'required|integer',
            'gems_earned' => 'required|integer',
            'is_private' => 'required|boolean'
        ]);

        $data['user_id'] = Auth::id();

        $result = Result::create($data);

        return redirect()->route('results.show', $result->id);
    }

    public function share(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $result = Result::findOrFail($id);
        
        // Alleen de eigenaar kan delen
        if ($result->user_id !== Auth::id()) {
            return response()->json(['error' => 'Je kunt alleen je eigen resultaten delen.'], 403);
        }

        // Zoek gebruiker op username
        $user = User::where('name', $request->username)->first();
        
        if (!$user) {
            return response()->json(['error' => 'Gebruiker niet gevonden.'], 404);
        }

        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Je kunt je resultaat niet met jezelf delen.'], 400);
        }

        // Voeg toe aan gedeelde gebruikers (update timestamp als al gedeeld)
        $result->sharedUsers()->detach($user->id);
        $result->sharedUsers()->attach($user->id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => "Resultaat gedeeld met {$user->name}.",
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar ?? 'default.png'
            ]
        ]);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->get('query', '');
        
        // Als er geen query is, toon recente shares
        if (empty($query)) {
            $recentShares = \DB::table('result_user_shares')
                ->join('results', 'result_user_shares.result_id', '=', 'results.id')
                ->join('users', 'result_user_shares.user_id', '=', 'users.id')
                ->where('results.user_id', Auth::id())
                ->select('users.id', 'users.name', 'users.avatar', 'result_user_shares.created_at')
                ->orderBy('result_user_shares.created_at', 'desc')
                ->get()
                ->unique('id') // Remove duplicate users
                ->take(2) // Take only 2 unique users
                ->values() // Reset array keys
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar ?? 'default.png',
                        'is_recent' => true
                    ];
                });

            return response()->json($recentShares->toArray());
        }
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->where('id', '!=', Auth::id()) // Exclude current user
            ->select('id', 'name', 'avatar')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar ?? 'default.png',
                    'is_recent' => false
                ];
            });

        return response()->json($users);
    }

    public function unshare(Request $request, $id, $userId)
    {
        $result = Result::findOrFail($id);
        
        // Alleen de eigenaar kan delen intrekken
        if ($result->user_id !== Auth::id()) {
            return response()->json(['error' => 'Je kunt alleen het delen van je eigen resultaten intrekken.'], 403);
        }

        // Verwijder gebruiker uit gedeelde gebruikers
        $result->sharedUsers()->detach($userId);

        return response()->json([
            'success' => true,
            'message' => 'Delen is ingetrokken.',
        ]);
    }
}
