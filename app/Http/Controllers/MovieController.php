<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    private TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index(Request $request)
    {
        $category = $request->get('category', 'popular');
        $page = $request->get('page', 1);
        $genre = $request->get('genre');
        $sortBy = $request->get('sort_by', 'popularity.desc');
        $year = $request->get('year');

        $genres = $this->tmdbService->getGenres()['genres'] ?? [];

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
        }

        return view('movies.index', [
            'movies' => $movies['results'] ?? [],
            'total_pages' => $movies['total_pages'] ?? 1,
            'current_page' => $page,
            'category' => $category,
            'genres' => $genres,
            'selected_genre' => $genre,
            'selected_sort' => $sortBy,
            'selected_year' => $year
        ]);
    }

    public function show(int $id)
    {
        $movie = $this->tmdbService->getMovieDetails($id);

        if (!$movie) {
            abort(404, 'Film tidak ditemukan');
        }

        // Fetch videos separately if not included in main response
        if (!isset($movie['videos']) || empty($movie['videos']['results'])) {
            $videos = $this->tmdbService->getMovieVideos($id);
            if ($videos) {
                $movie['videos'] = $videos;
            }
        }

        return view('movies.show', ['movie' => $movie]);
    }

    public function search(Request $request)
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

    public function trending()
    {
        $trending = $this->tmdbService->getTrending('week');

        return view('movies.trending', [
            'movies' => $trending['results'] ?? []
        ]);
    }
}
