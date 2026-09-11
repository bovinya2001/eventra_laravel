<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Event;
use App\Services\WeatherForecastService;
use App\Support\VenueLocations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationWeatherIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_contains_fifty_sri_lankan_locations(): void
    {
        $this->assertCount(50, VenueLocations::all());
    }

    public function test_admin_location_selection_saves_trusted_coordinates(): void
    {
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'maps@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin, 'admin')->post(route('admin.events.store'), [
            'title' => 'Kandy Tech Day',
            'description' => 'Technology event in Kandy.',
            'location_key' => 'kandy',
            'event_date' => now()->addWeek()->format('Y-m-d H:i:s'),
            'capacity' => 100,
            'price' => 1000,
            'status' => 'upcoming',
        ])->assertRedirect(route('admin.events.index'));

        $this->assertDatabaseHas('events', [
            'location_key' => 'kandy',
            'location' => 'Kandy City Centre, Kandy',
            'latitude' => 7.2906000,
            'longitude' => 80.6337000,
        ]);
    }

    public function test_weather_forecast_is_fetched_and_cached_for_event_day(): void
    {
        config(['cache.default' => 'array']);
        Cache::flush();
        $date = now()->addDays(5)->toDateString();

        Http::fake([
            'api.open-meteo.com/*' => Http::response([
                'daily' => [
                    'time' => [$date],
                    'weather_code' => [61],
                    'temperature_2m_max' => [29.5],
                    'temperature_2m_min' => [24.1],
                    'precipitation_probability_max' => [65],
                    'wind_speed_10m_max' => [18.4],
                ],
            ]),
        ]);

        $event = new Event([
            'event_date' => $date.' 10:00:00',
            'latitude' => 6.9271,
            'longitude' => 79.8612,
        ]);
        $service = app(WeatherForecastService::class);

        $first = $service->forEvent($event);
        $second = $service->forEvent($event);

        $this->assertSame('Rain or drizzle', $first['condition']);
        $this->assertSame(65, $first['rain_probability']);
        $this->assertSame($first, $second);
        Http::assertSentCount(1);
    }
}
