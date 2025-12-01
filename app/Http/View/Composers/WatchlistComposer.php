<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Watchlist;

class WatchlistComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $watchlistCount = Watchlist::where('user_id', Auth::id())->count();
            $view->with('watchlistCount', $watchlistCount);
        } else {
            $view->with('watchlistCount', 0);
        }
    }
}
