<?php
namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'upcoming')->latest()->paginate(9);
        return view('user.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $isRegistered = auth()->check()
            ? auth()->user()->events()->where('event_id', $event->id)->exists()
            : false;
        return view('user.events.show', compact('event', 'isRegistered'));
    }
}