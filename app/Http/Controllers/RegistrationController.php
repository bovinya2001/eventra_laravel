<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
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
        $isFree = (float) $event->price <= 0;
        $registration = Registration::create([
            'user_id'  => auth()->id(),
            'event_id' => $event->id,
            'status'   => $isFree ? 'confirmed' : 'pending',
            'payment_status' => $isFree ? 'paid' : 'pending',
            'payment_method' => $isFree ? 'free' : null,
            'payment_amount' => $event->price,
            'payment_reference' => $isFree ? 'FREE-'.$event->id.'-'.auth()->id() : null,
            'paid_at' => $isFree ? now() : null,
        ]);

        return $registration->canGeneratePass()
            ? redirect()->route('registrations.pass', $registration)
            : redirect()->route('registrations.checkout', $registration);
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
