<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieTest extends TestCase
{
    // We use RefreshDatabase if we need to interact with DB, 
    // but for movie listing (external API), we mostly test the response status.
    
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_movie_listing_page_loads()
    {
        $response = $this->get('/movies');
        $response->assertStatus(200);
        $response->assertSee('CinemaHub'); // Check for brand name
    }

    public function test_search_page_loads()
    {
        $response = $this->get('/search?q=marvel');
        $response->assertStatus(200);
        $response->assertSee('marvel', false); // Case insensitive check
    }

    public function test_trending_page_loads()
    {
        $response = $this->get('/trending');
        $response->assertStatus(200);
    }
}