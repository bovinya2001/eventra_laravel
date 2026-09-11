<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Support\VenueLocations;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoEventSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'demo@eventra.test',
        ], [
            'name' => 'Demo Attendee',
            'password' => Hash::make('DemoPass123!'),
            'email_verified_at' => now(),
        ]);

        $venue = VenueLocations::find('bmich-colombo');

        Event::updateOrCreate([
            'title' => 'Eventra Innovation Summit 2026',
        ], [
            'description' => 'A demonstration event for Eventra registrations, payments, queued notifications, venue maps, weather forecasts, and QR access passes.',
            'location_key' => 'bmich-colombo',
            'location' => $venue['name'].', '.$venue['district'],
            'latitude' => $venue['lat'],
            'longitude' => $venue['lng'],
            'event_date' => now()->addDays(7)->setTime(18, 0),
            'capacity' => 250,
            'price' => 2500,
            'status' => 'upcoming',
        ]);
    }
}
