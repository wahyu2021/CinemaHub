<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TmdbService
{
    private string $apiKey;
    private string $baseUrl;
    private string $imageBaseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
        $this->imageBaseUrl = config('services.tmdb.image_base_url');
    }

    public function getPopularMovies(int $page = 1)
    {
        return $this->makeRequest('/movie/popular', ['page' => $page]);
    }

    public function getNowPlaying(int $page = 1)
    {
        return $this->makeRequest('/movie/now_playing', ['page' => $page]);
    }

    public function getTopRated(int $page = 1)
    {
        return $this->makeRequest('/movie/top_rated', ['page' => $page]);
    }

    public function getUpcoming(int $page = 1)
    {
        return $this->makeRequest('/movie/upcoming', ['page' => $page]);
    }

    public function getTrending(string $timeWindow = 'day')
    {
        return $this->makeRequest("/trending/movie/{$timeWindow}");
    }

    public function searchMovies(string $query, int $page = 1)
    {
        return $this->makeRequest('/search/movie', [
            'query' => $query,
            'page' => $page
        ]);
    }

    public function getMovieDetails(int $movieId)
    {
        return $this->makeRequest("/movie/{$movieId}", [
            'append_to_response' => 'videos,credits,recommendations,similar'
        ]);
    }

    public function getGenres()
    {
        return Cache::remember('tmdb_genres', 86400, function () {
            return $this->makeRequest('/genre/movie/list');
        });
    }

    public function discoverMovies(array $filters = [])
    {
        $defaultFilters = [
            'sort_by' => 'popularity.desc',
            'page' => 1
        ];

        return $this->makeRequest('/discover/movie', array_merge($defaultFilters, $filters));
    }

    public function getImageUrl(string $path, string $size = 'w500'): string
    {
        if (empty($path)) {
            return asset('images/no-poster.png');
        }
        return $this->imageBaseUrl . "/{$size}{$path}";
    }

    private function makeRequest(string $endpoint, array $params = [])
    {
        $params['api_key'] = $this->apiKey;
        $params['language'] = 'id-ID';

        try {
            $response = Http::timeout(10)
                ->get($this->baseUrl . $endpoint, $params);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            logger()->error('TMDB API Error: ' . $e->getMessage());
            return null;
        }
    }
}
