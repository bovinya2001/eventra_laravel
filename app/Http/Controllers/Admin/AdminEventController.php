<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Support\VenueLocations;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('registrations')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create', ['venues' => VenueLocations::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location_key' => ['required', Rule::in(array_keys(VenueLocations::all()))],
            'event_date'  => 'required|date|after:now',
            'capacity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:upcoming,ongoing,completed,cancelled',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $this->addLocationData($data);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Event created!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', ['event' => $event, 'venues' => VenueLocations::all()]);
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location_key' => ['required', Rule::in(array_keys(VenueLocations::all()))],
            'event_date'  => 'required|date',
            'capacity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:upcoming,ongoing,completed,cancelled',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $this->addLocationData($data);

        if ($request->hasFile('image')) {
            if ($event->image) Storage::disk('public')->delete($event->image);
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted!');
    }

    private function addLocationData(array $data): array
    {
        $venue = VenueLocations::find($data['location_key']);
        $data['location'] = $venue['name'].', '.$venue['district'];
        $data['latitude'] = $venue['lat'];
        $data['longitude'] = $venue['lng'];

        return $data;
    }
}
