<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TmdbService
{
    private const LANGUAGE = 'id-ID';
    private const CACHE_TTL = 86400; // 24 hours
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
    
    private const IMAGE_SIZES = [
        'POSTER_SMALL' => 'w185',
        'POSTER_MEDIUM' => 'w342',
        'POSTER_LARGE' => 'w500',
        'BACKDROP' => 'original',
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
        return $this->makeRequest(self::ENDPOINTS['POPULAR'], ['page' => $page]);
    }

    public function getNowPlaying(int $page = 1): ?array
    {
        return $this->makeRequest(self::ENDPOINTS['NOW_PLAYING'], ['page' => $page]);
    }

    public function getTopRated(int $page = 1): ?array
    {
        return $this->makeRequest(self::ENDPOINTS['TOP_RATED'], ['page' => $page]);
    }

    public function getUpcoming(int $page = 1): ?array
    {
        return $this->makeRequest(self::ENDPOINTS['UPCOMING'], ['page' => $page]);
    }

    public function getTrending(string $timeWindow = 'day'): ?array
    {
        return $this->makeRequest(self::ENDPOINTS['TRENDING'] . "/{$timeWindow}");
    }

    public function searchMovies(string $query, int $page = 1): ?array
    {
        return $this->makeRequest(self::ENDPOINTS['SEARCH'], [
            'query' => $query,
            'page' => $page
        ]);
    }

    public function getMovieDetails(int $movieId): ?array
    {
        return $this->makeRequest(self::ENDPOINTS['MOVIE_DETAILS'] . "/{$movieId}", [
            'append_to_response' => 'videos,credits,recommendations,similar'
        ]);
    }

    public function getMovieVideos(int $movieId): ?array
    {
        return $this->makeRequest(self::ENDPOINTS['MOVIE_DETAILS'] . "/{$movieId}/videos");
    }

    public function getGenres(): array
    {
        return Cache::remember('tmdb_genres', self::CACHE_TTL, function () {
            return $this->makeRequest(self::ENDPOINTS['GENRES']) ?? [];
        });
    }

    public function discoverMovies(array $filters = []): ?array
    {
        $defaultFilters = [
            'sort_by' => 'popularity.desc',
            'page' => 1
        ];

        return $this->makeRequest(self::ENDPOINTS['DISCOVER'], array_merge($defaultFilters, $filters));
    }

    public function getImageUrl(string $path, string $size = 'w500'): string
    {
        if (empty($path)) {
            return asset('images/no-poster.png');
        }
        return $this->imageBaseUrl . "/{$size}{$path}";
    }

    private function makeRequest(string $endpoint, array $params = []): ?array
    {
        $params['api_key'] = $this->apiKey;
        $params['language'] = self::LANGUAGE;

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
