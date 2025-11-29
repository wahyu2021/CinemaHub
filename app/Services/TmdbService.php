<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TmdbService
{
    private const CACHE_TTL = 86400; // 24 hours
    private const CACHE_SHORT = 3600; // 1 hour
    private const CACHE_SEARCH = 1800; // 30 minutes
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
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
        $this->imageBaseUrl = config('services.tmdb.image_base_url');
    }

    public function getPopularMovies(int $page = 1): ?array
    {
        return $this->getCachedResponse("popular_movies_page_{$page}", self::CACHE_SHORT, self::ENDPOINTS['POPULAR'], ['page' => $page]);
    }

    public function getNowPlaying(int $page = 1): ?array
    {
        return $this->getCachedResponse("now_playing_movies_page_{$page}", self::CACHE_SHORT, self::ENDPOINTS['NOW_PLAYING'], ['page' => $page]);
    }

    public function getTopRated(int $page = 1): ?array
    {
        return $this->getCachedResponse("top_rated_movies_page_{$page}", self::CACHE_TTL, self::ENDPOINTS['TOP_RATED'], ['page' => $page]);
    }

    public function getUpcoming(int $page = 1): ?array
    {
        return $this->getCachedResponse("upcoming_movies_page_{$page}", self::CACHE_SHORT, self::ENDPOINTS['UPCOMING'], ['page' => $page]);
    }

    public function getTrending(string $timeWindow = 'day'): ?array
    {
        return $this->getCachedResponse("trending_movies_{$timeWindow}", self::CACHE_SHORT, self::ENDPOINTS['TRENDING'] . "/{$timeWindow}");
    }

    public function searchMovies(string $query, int $page = 1): ?array
    {
        return $this->getCachedResponse("search_{$query}_page_{$page}", self::CACHE_SEARCH, self::ENDPOINTS['SEARCH'], [
            'query' => $query,
            'page' => $page
        ]);
    }

    public function getMovieDetails(int $movieId): ?array
    {
        return $this->getCachedResponse("movie_details_{$movieId}", self::CACHE_TTL, self::ENDPOINTS['MOVIE_DETAILS'] . "/{$movieId}", [
            'append_to_response' => 'videos,credits,recommendations,similar'
        ]);
    }

    public function getMovieVideos(int $movieId): ?array
    {
        return $this->getCachedResponse("movie_videos_{$movieId}", self::CACHE_TTL, self::ENDPOINTS['MOVIE_DETAILS'] . "/{$movieId}/videos");
    }

    public function getGenres(): array
    {
        return $this->getCachedResponse("tmdb_genres", self::CACHE_TTL, self::ENDPOINTS['GENRES']) ?? [];
    }

    public function discoverMovies(array $filters = []): ?array
    {
        $cacheKey = 'discover_' . md5(json_encode($filters));
        $defaultFilters = ['sort_by' => 'popularity.desc', 'page' => 1];
        
        return $this->getCachedResponse($cacheKey, self::CACHE_SHORT, self::ENDPOINTS['DISCOVER'], array_merge($defaultFilters, $filters));
    }

    public function getImageUrl(string $path, string $size = 'w500'): string
    {
        if (empty($path)) {
            return asset('images/no-poster.png');
        }
        return $this->imageBaseUrl . "/{$size}{$path}";
    }

    private function getLanguage(): string
    {
        return app()->getLocale() === 'id' ? 'id-ID' : 'en-US';
    }

    private function getCachedResponse(string $keyBase, int $ttl, string $endpoint, array $params = []): ?array
    {
        $locale = app()->getLocale();
        return Cache::remember("{$keyBase}_{$locale}", $ttl, function () use ($endpoint, $params) {
            return $this->makeRequest($endpoint, $params);
        });
    }

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