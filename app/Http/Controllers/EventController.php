<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\WeatherForecastService;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'upcoming')->latest()->paginate(9);
        return view('user.events.index', compact('events'));
    }

    public function show(Event $event, WeatherForecastService $weatherService)
    {
        $isRegistered = auth()->check()
            ? auth()->user()->events()->where('event_id', $event->id)->exists()
            : false;
        $weather = $weatherService->forEvent($event);
        return view('user.events.show', compact('event', 'isRegistered', 'weather'));
    }
}
