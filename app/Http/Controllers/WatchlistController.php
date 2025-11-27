<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlist = Auth::user()->watchlist()->orderBy('created_at', 'desc')->get();
        return view('watchlist.index', compact('watchlist'));
    }

    public function store(Request $request)
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

    public function destroy($movie_id)
    {
        Auth::user()->watchlist()->where('movie_id', $movie_id)->delete();
        return back()->with('success', 'Film dihapus dari Watchlist.');
    }
}