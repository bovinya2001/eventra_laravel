<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Notification;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Event $event)
    {
        if ($event->remaining_capacity <= 0) {
            return back()->with('error', 'This event is fully booked.');
        }
        if (auth()->user()->events()->where('event_id', $event->id)->exists()) {
            return back()->with('error', 'You are already registered.');
        }
        Registration::create([
            'user_id'  => auth()->id(),
            'event_id' => $event->id,
            'status'   => 'confirmed',
        ]);

        // Send notification
        Notification::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'type' => 'registration_confirmation',
            'title' => 'Registration Confirmed',
            'message' => "You've successfully registered for {$event->title}",
            'icon' => 'check-circle',
            'color' => 'green',
        ]);

        return back()->with('success', 'Registered successfully!');
    }

    public function destroy(Event $event)
    {
        Registration::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->delete();
        return back()->with('success', 'Registration cancelled.');
    }

    public function myEvents()
    {
        $registrations = auth()->user()->registrations()->with('event')->latest()->get();
        return view('user.my-events', compact('registrations'));
    }

    public function favorites()
    {
        $favorites = auth()->user()->favorites()->with('event')->latest()->get();
        return view('user.favorites', compact('favorites'));
    }
}