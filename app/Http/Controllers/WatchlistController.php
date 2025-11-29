<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controller handling user's movie watchlist.
 */
class WatchlistController extends Controller
{
    /**
     * Display the user's watchlist.
     *
     * @return View
     */
    public function index(): View
    {
        $watchlist = Auth::user()->watchlist()->orderBy('created_at', 'desc')->get();
        return view('watchlist.index', compact('watchlist'));
    }

    /**
     * Add a movie to the watchlist.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'movie_id' => 'required|integer',
            'title' => 'required|string',
            'poster_path' => 'nullable|string',
        ]);

        // Check if already in watchlist
        $exists = Auth::user()->watchlist()->where('movie_id', $request->movie_id)->exists();

        if (!$exists) {
            Auth::user()->watchlist()->create([
                'movie_id' => $request->movie_id,
                'title' => $request->title,
                'poster_path' => $request->poster_path,
            ]);
            return back()->with('success', 'Film ditambahkan ke Watchlist!');
        }

        return back()->with('info', 'Film sudah ada di Watchlist.');
    }

    /**
     * Remove a movie from the watchlist.
     *
     * @param int $movie_id
     * @return RedirectResponse
     */
    public function destroy(int $movie_id): RedirectResponse
    {
        Auth::user()->watchlist()->where('movie_id', $movie_id)->delete();
        return back()->with('success', 'Film dihapus dari Watchlist.');
    }
}
