<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

/**
 * Controller for handling movie-related actions.
 */
class MovieController extends Controller
{
    private TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Display the movie listing page (Home/Discover).
     * Handles filtering, pagination, and rich home page sections.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $category = $request->get('category', 'popular');
        $page = $request->get('page', 1);
        $genre = $request->get('genre');
        $sortBy = $request->get('sort_by', 'popularity.desc');
        $year = $request->get('year');

        $genres = $this->tmdbService->getGenres()['genres'] ?? [];

        // Default values for extra sections
        $trendingMovies = [];
        $topRatedMovies = [];
        $upcomingMovies = [];

        if ($genre || $year || $sortBy !== 'popularity.desc') {
            $filters = [
                'page' => $page,
                'sort_by' => $sortBy
            ];
            
            if ($genre) {
                $filters['with_genres'] = $genre;
            }
            
            if ($year) {
                $filters['primary_release_year'] = $year;
            }

            $movies = $this->tmdbService->discoverMovies($filters);
        } else {
            $movies = match($category) {
                'now_playing' => $this->tmdbService->getNowPlaying($page),
                'top_rated' => $this->tmdbService->getTopRated($page),
                'upcoming' => $this->tmdbService->getUpcoming($page),
                default => $this->tmdbService->getPopularMovies($page)
            };

            // Only fetch extra sections if we are on the default "Home" view (Page 1, Popular, No Filters)
            if ($category === 'popular' && $page == 1 && !$genre && !$year) {
                $trendingMovies = $this->tmdbService->getTrending('day')['results'] ?? [];
                $topRatedMovies = $this->tmdbService->getTopRated(1)['results'] ?? [];
                $upcomingMovies = $this->tmdbService->getUpcoming(1)['results'] ?? [];
            }
        }

        return view('movies.index', [
            'movies' => $movies['results'] ?? [],
            'total_pages' => $movies['total_pages'] ?? 1,
            'current_page' => $page,
            'category' => $category,
            'genres' => $genres,
            'selected_genre' => $genre,
            'selected_sort' => $sortBy,
            'selected_year' => $year,
            'trendingMovies' => $trendingMovies,
            'topRatedMovies' => $topRatedMovies,
            'upcomingMovies' => $upcomingMovies,
        ]);
    }

    /**
     * Display the details of a specific movie.
     *
     * @param int $id
     * @return View
     */
    public function show($id): View
    {
        $movie = $this->tmdbService->getMovieDetails((int) $id);

        if (!$movie) {
            abort(404, __('messages.not_found_title'));
        }

        // Fetch videos separately if not included in main response (fallback)
        if (!isset($movie['videos']) || empty($movie['videos']['results'])) {
            $videos = $this->tmdbService->getMovieVideos((int) $id);
            if ($videos) {
                $movie['videos'] = $videos;
            }
        }

        $inWatchlist = false;
        if (Auth::check()) {
            $inWatchlist = Auth::user()->watchlist()->where('movie_id', $id)->exists();
        }

        return view('movies.show', [
            'movie' => $movie,
            'inWatchlist' => $inWatchlist
        ]);
    }

    /**
     * Handle search requests and display the results page.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function search(Request $request): View|RedirectResponse
    {
        $query = $request->get('q');
        $page = $request->get('page', 1);

        if (empty($query)) {
            return redirect()->route('movies.index');
        }

        $results = $this->tmdbService->searchMovies($query, $page);

        return view('movies.search', [
            'movies' => $results['results'] ?? [],
            'total_pages' => $results['total_pages'] ?? 1,
            'current_page' => $page,
            'query' => $query
        ]);
    }

    /**
     * Handle AJAX live search requests.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchJson(Request $request): JsonResponse
    {
        $query = $request->get('q');
        if (empty($query)) {
            return response()->json(['results' => []]);
        }
        
        $results = $this->tmdbService->searchMovies($query, 1);
        
        return response()->json([
            'results' => array_slice($results['results'] ?? [], 0, 5) // Limit to top 5 for dropdown
        ]);
    }

    /**
     * Display the trending movies page.
     *
     * @return View
     */
    public function trending(): View
    {
        $trending = $this->tmdbService->getTrending('week');

        return view('movies.trending', [
            'movies' => $trending['results'] ?? []
        ]);
    }
}
