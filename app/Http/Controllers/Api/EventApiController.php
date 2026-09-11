<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\WeatherForecastService;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    /**
     * List all events with filters
     * GET /api/v1/events?status=upcoming&sort=event_date&per_page=10
     */
    public function index(Request $request)
    {
        $events = Event::query()
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->location, fn($q) =>
                $q->where('location', 'like', "%{$request->location}%")
            )
            ->when($request->min_price, fn($q) =>
                $q->where('price', '>=', $request->min_price)
            )
            ->when($request->max_price, fn($q) =>
                $q->where('price', '<=', $request->max_price)
            )
            ->orderBy($request->sort ?? 'event_date', $request->order ?? 'asc')
            ->withCount('registrations')
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'status' => 'success',
            'data'   => $events->map(fn($e) => $this->formatEvent($e)),
            'meta'   => [
                'current_page' => $events->currentPage(),
                'last_page'    => $events->lastPage(),
                'per_page'     => $events->perPage(),
                'total'        => $events->total(),
            ]
        ]);
    }

    /**
     * Get single event details
     * GET /api/v1/events/{id}
     */
    public function show(Event $event, WeatherForecastService $weatherService)
    {
        $event->loadCount('registrations');

        return response()->json([
            'status' => 'success',
            'data'   => array_merge($this->formatEvent($event, detailed: true), [
                'weather' => $weatherService->forEvent($event),
            ]),
        ]);
    }

    /**
     * Search events by keyword
     * GET /api/v1/events/search/{query}
     */
    public function search(string $query)
    {
        $events = Event::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->withCount('registrations')
            ->get();

        return response()->json([
            'status'  => 'success',
            'query'   => $query,
            'results' => $events->count(),
            'data'    => $events->map(fn($e) => $this->formatEvent($e)),
        ]);
    }

    /**
     * Get upcoming events only
     * GET /api/v1/events/upcoming/list
     */
    public function upcoming()
    {
        $events = Event::where('status', 'upcoming')
            ->where('event_date', '>', now())
            ->orderBy('event_date')
            ->withCount('registrations')
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $events->map(fn($e) => $this->formatEvent($e)),
        ]);
    }

    /**
     * Get platform stats
     * GET /api/v1/events/stats/summary
     */
    public function stats()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_events'        => Event::count(),
                'upcoming_events'     => Event::where('status', 'upcoming')->count(),
                'ongoing_events'      => Event::where('status', 'ongoing')->count(),
                'completed_events'    => Event::where('status', 'completed')->count(),
                'total_registrations' => \App\Models\Registration::count(),
                'free_events'         => Event::where('price', 0)->count(),
                'paid_events'         => Event::where('price', '>', 0)->count(),
            ]
        ]);
    }

    // Format event for API response
    private function formatEvent(Event $event, bool $detailed = false): array
    {
        $data = [
            'id'                 => $event->id,
            'title'              => $event->title,
            'location'           => $event->location,
            'event_date'         => $event->event_date->toISOString(),
            'event_date_human'   => $event->event_date->format('F d, Y · g:i A'),
            'status'             => $event->status,
            'price'              => $event->price,
            'price_formatted'    => $event->price > 0 ? 'LKR ' . number_format($event->price, 2) : 'Free',
            'capacity'           => $event->capacity,
            'spots_taken'        => $event->registrations_count ?? 0,
            'spots_remaining'    => $event->capacity - ($event->registrations_count ?? 0),
            'is_full'            => ($event->capacity - ($event->registrations_count ?? 0)) <= 0,
            'image_url'          => $event->image ? asset('storage/' . $event->image) : null,
            'coordinates'        => $event->latitude !== null ? [
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
            ] : null,
            'map_embed_url'      => $event->map_embed_url,
        ];

        if ($detailed) {
            $data['description'] = $event->description;
            $data['created_at']  = $event->created_at->toISOString();
        }

        return $data;
    }
}
