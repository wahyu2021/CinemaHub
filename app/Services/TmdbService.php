<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

/**
 * Service class for interacting with The Movie Database (TMDB) API.
 * Handles data fetching, caching strategies, and localization.
 */
class TmdbService
{
    /**
     * Cache durations in seconds.
     */
    private const CACHE_TTL = 86400; // 24 hours (Static data like details)
    private const CACHE_SHORT = 3600; // 1 hour (Lists like Now Playing)
    private const CACHE_SEARCH = 1800; // 30 minutes (Search results)
    private const REQUEST_TIMEOUT = 10;
    
    private const ENDPOINTS = [
        'POPULAR' => '/movie/popular',
        'NOW_PLAYING' => '/movie/now_playing',
        'TOP_RATED' => '/movie/top_rated',
        'UPCOMING' => '/movie/upcoming',
        'TRENDING' => '/trending/movie',
        'SEARCH' => '/search/movie',
        'MOVIE_DETAILS' => '/movie',
        'GENRES' => '/genre/movie/list',
        'DISCOVER' => '/discover/movie',
    ];

    private string $apiKey;
    private string $baseUrl;
    private string $imageBaseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key') ?? '';
        $this->baseUrl = config('services.tmdb.base_url') ?? '';
        $this->imageBaseUrl = config('services.tmdb.image_base_url') ?? '';
    }

    /**
     * Fetch a list of popular movies.
     *
     * @param int $page
     * @return array|null
     */
    public function getPopularMovies(int $page = 1): ?array
    {
        return $this->getCachedResponse("popular_movies_page_{$page}", self::CACHE_SHORT, self::ENDPOINTS['POPULAR'], ['page' => $page]);
    }

    /**
     * Fetch movies currently playing in theaters.
     *
     * @param int $page
     * @return array|null
     */
    public function getNowPlaying(int $page = 1): ?array
    {
        return $this->getCachedResponse("now_playing_movies_page_{$page}", self::CACHE_SHORT, self::ENDPOINTS['NOW_PLAYING'], ['page' => $page]);
    }

    /**
     * Fetch top-rated movies.
     *
     * @param int $page
     * @return array|null
     */
    public function getTopRated(int $page = 1): ?array
    {
        return $this->getCachedResponse("top_rated_movies_page_{$page}", self::CACHE_TTL, self::ENDPOINTS['TOP_RATED'], ['page' => $page]);
    }

    /**
     * Fetch upcoming movies.
     *
     * @param int $page
     * @return array|null
     */
    public function getUpcoming(int $page = 1): ?array
    {
        return $this->getCachedResponse("upcoming_movies_page_{$page}", self::CACHE_SHORT, self::ENDPOINTS['UPCOMING'], ['page' => $page]);
    }

    /**
     * Fetch trending movies for a specific time window.
     *
     * @param string $timeWindow 'day' or 'week'
     * @return array|null
     */
    public function getTrending(string $timeWindow = 'day'): ?array
    {
        return $this->getCachedResponse("trending_movies_{$timeWindow}", self::CACHE_SHORT, self::ENDPOINTS['TRENDING'] . "/{$timeWindow}");
    }

    /**
     * Search for movies by query string.
     *
     * @param string $query
     * @param int $page
     * @return array|null
     */
    public function searchMovies(string $query, int $page = 1): ?array
    {
        return $this->getCachedResponse("search_{$query}_page_{$page}", self::CACHE_SEARCH, self::ENDPOINTS['SEARCH'], [
            'query' => $query,
            'page' => $page
        ]);
    }

    /**
     * Get detailed information for a specific movie.
     * Includes videos, credits, recommendations, and similar movies.
     *
     * @param int $movieId
     * @return array|null
     */
    public function getMovieDetails(int $movieId): ?array
    {
        return $this->getCachedResponse("movie_details_{$movieId}", self::CACHE_TTL, self::ENDPOINTS['MOVIE_DETAILS'] . "/{$movieId}", [
            'append_to_response' => 'videos,credits,recommendations,similar'
        ]);
    }

    /**
     * Fetch videos (trailers, clips) for a movie.
     *
     * @param int $movieId
     * @return array|null
     */
    public function getMovieVideos(int $movieId): ?array
    {
        return $this->getCachedResponse("movie_videos_{$movieId}", self::CACHE_TTL, self::ENDPOINTS['MOVIE_DETAILS'] . "/{$movieId}/videos");
    }

    /**
     * Get the list of official movie genres.
     *
     * @return array
     */
    public function getGenres(): array
    {
        return $this->getCachedResponse("tmdb_genres", self::CACHE_TTL, self::ENDPOINTS['GENRES']) ?? [];
    }

    /**
     * Discover movies based on advanced filters (sort, genre, year).
     *
     * @param array $filters
     * @return array|null
     */
    public function discoverMovies(array $filters = []): ?array
    {
        $cacheKey = 'discover_' . md5(json_encode($filters));
        $defaultFilters = ['sort_by' => 'popularity.desc', 'page' => 1];
        
        return $this->getCachedResponse($cacheKey, self::CACHE_SHORT, self::ENDPOINTS['DISCOVER'], array_merge($defaultFilters, $filters));
    }

    /**
     * Generate a full image URL.
     *
     * @param string $path
     * @param string $size
     * @return string
     */
    public function getImageUrl(string $path, string $size = 'w500'): string
    {
        if (empty($path)) {
            return asset('images/no-poster.png');
        }
        return $this->imageBaseUrl . "/{$size}{$path}";
    }

    /**
     * Determine the language code based on the app's current locale.
     *
     * @return string
     */
    private function getLanguage(): string
    {
        return app()->getLocale() === 'id' ? 'id-ID' : 'en-US';
    }

    /**
     * Helper to handle caching and request execution.
     * Automatically appends the current locale to the cache key.
     *
     * @param string $keyBase
     * @param int $ttl
     * @param string $endpoint
     * @param array $params
     * @return array|null
     */
    private function getCachedResponse(string $keyBase, int $ttl, string $endpoint, array $params = []): ?array
    {
        $locale = app()->getLocale();
        return Cache::remember("{$keyBase}_{$locale}", $ttl, function () use ($endpoint, $params) {
            return $this->makeRequest($endpoint, $params);
        });
    }

    /**
     * Execute the HTTP request to TMDB.
     *
     * @param string $endpoint
     * @param array $params
     * @return array|null
     */
    private function makeRequest(string $endpoint, array $params = []): ?array
    {
        $params['api_key'] = $this->apiKey;
        $params['language'] = $this->getLanguage();

        try {
            $response = Http::timeout(self::REQUEST_TIMEOUT)
                ->get($this->baseUrl . $endpoint, $params);

            if ($response->successful()) {
                return $response->json();
            }

            logger()->warning('TMDB API request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status()
            ]);
            
            return null;
        } catch (\Exception $e) {
            logger()->error('TMDB API Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
