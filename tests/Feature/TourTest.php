<?php

namespace Tests\Feature;

use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TourTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_listing_page_loads(): void
    {
        Tour::factory()->count(3)->create(['is_active' => true]);

        $response = $this->get(route('tours.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('Tours/Index'));
    }

    public function test_tour_detail_page_shows_active_tour(): void
    {
        $tour = Tour::factory()->create(['is_active' => true]);

        $response = $this->get(route('tours.show', $tour->slug));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Tours/Show')
            ->where('tour.name', $tour->name)
        );
    }

    public function test_inactive_tour_returns_404(): void
    {
        $tour = Tour::factory()->create(['is_active' => false]);

        $response = $this->get(route('tours.show', $tour->slug));

        $response->assertNotFound();
    }

    public function test_tours_can_be_filtered_by_city(): void
    {
        Tour::factory()->create(['is_active' => true, 'city' => 'Luanda']);
        Tour::factory()->create(['is_active' => true, 'city' => 'Benguela']);

        $response = $this->get(route('tours.index', ['city' => 'Luanda']));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Tours/Index')
            ->has('tours')
        );
    }

    public function test_home_page_shows_featured_tours(): void
    {
        Tour::factory()->count(3)->create(['is_active' => true, 'is_featured' => true]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
    }
}
