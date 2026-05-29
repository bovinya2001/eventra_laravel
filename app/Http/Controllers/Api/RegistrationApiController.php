<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationApiController extends Controller
{
    /**
     * Get all my registrations
     * GET /api/v1/my-registrations
     */
    public function index(Request $request)
    {
        $registrations = $request->user()
            ->registrations()
            ->with('event')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'total'  => $registrations->count(),
            'data'   => $registrations->map(fn($r) => $this->formatRegistration($r)),
        ]);
    }

    /**
     * Get single registration
     * GET /api/v1/my-registrations/{id}
     */
    public function show(Request $request, Registration $registration)
    {
        // Make sure user owns this registration
        if ($registration->user_id !== $request->user()->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Not authorized.',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $this->formatRegistration($registration->load('event')),
        ]);
    }

    /**
     * Register for an event
     * POST /api/v1/events/{event}/register
     */
    public function store(Request $request, Event $event)
    {
        // Check event is upcoming
        if ($event->status !== 'upcoming') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Registration is only available for upcoming events.',
            ], 422);
        }

        // Check capacity
        if ($event->remaining_capacity <= 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sorry, this event is fully booked.',
            ], 422);
        }

        // Check already registered
        if ($request->user()->events()->where('event_id', $event->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You are already registered for this event.',
            ], 422);
        }

        $registration = Registration::create([
            'user_id'  => $request->user()->id,
            'event_id' => $event->id,
            'status'   => 'confirmed',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully registered for ' . $event->title,
            'data'    => $this->formatRegistration($registration->load('event')),
        ], 201);
    }

    /**
     * Cancel registration
     * DELETE /api/v1/events/{event}/cancel
     */
    public function destroy(Request $request, Event $event)
    {
        $registration = Registration::where('user_id', $request->user()->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$registration) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You are not registered for this event.',
            ], 404);
        }

        if ($event->status !== 'upcoming') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Cannot cancel registration for a non-upcoming event.',
            ], 422);
        }

        $registration->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Registration for ' . $event->title . ' has been cancelled.',
        ]);
    }

    // Format registration for API response
    private function formatRegistration(Registration $registration): array
    {
        return [
            'id'         => $registration->id,
            'status'     => $registration->status,
            'registered_at' => $registration->created_at->toISOString(),
            'registered_at_human' => $registration->created_at->diffForHumans(),
            'event'      => $registration->event ? [
                'id'         => $registration->event->id,
                'title'      => $registration->event->title,
                'location'   => $registration->event->location,
                'event_date' => $registration->event->event_date->toISOString(),
                'event_date_human' => $registration->event->event_date->format('F d, Y · g:i A'),
                'status'     => $registration->event->status,
                'price_formatted' => $registration->event->price > 0
                    ? 'LKR ' . number_format($registration->event->price, 2)
                    : 'Free',
            ] : null,
        ];
    }
}