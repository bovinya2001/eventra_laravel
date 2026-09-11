<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event — Eventra Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex">

    @include('admin.partials.sidebar')

    <main class="ml-64 flex-1 p-8">
        <div class="mb-8">
            <a href="{{ route('admin.events.index') }}" class="text-slate-400 hover:text-white text-sm transition-colors">← Back to Events</a>
            <h1 class="text-2xl font-bold mt-2">Edit Event</h1>
            <p class="text-slate-400 text-sm mt-1">{{ $event->title }}</p>
        </div>

        <div class="max-w-2xl bg-slate-800/50 border border-white/10 rounded-2xl p-8">
            <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Event Title</label>
                    <input type="text" name="title"
                        value="{{ old('title', $event->title) }}"
                        placeholder="e.g. Tech Summit 2025"
                        class="w-full bg-slate-900/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Venue location</label>
                    <select id="location_key" name="location_key" required
                        class="w-full bg-slate-900/60 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select one of {{ count($venues) }} locations</option>
                        @foreach($venues as $key => $venue)
                            <option value="{{ $key }}" data-lat="{{ $venue['lat'] }}" data-lng="{{ $venue['lng'] }}"
                                {{ old('location_key', $event->location_key) === $key ? 'selected' : '' }}>
                                {{ $venue['name'] }} — {{ $venue['district'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_key') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    <iframe id="venue-map" title="Selected venue map" class="mt-4 hidden h-72 w-full rounded-xl border border-slate-600" loading="lazy"></iframe>
                    <p class="mt-2 text-xs text-slate-500">Map data © OpenStreetMap contributors</p>
                </div>

                <!-- Date & Time -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Date & Time</label>
                    <input type="datetime-local" name="event_date"
                        value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                        class="w-full bg-slate-900/60 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('event_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Capacity & Price side by side -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Capacity</label>
                        <input type="number" name="capacity"
                            value="{{ old('capacity', $event->capacity) }}"
                            placeholder="e.g. 100" min="1"
                            class="w-full bg-slate-900/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('capacity') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Price (LKR)</label>
                        <input type="number" name="price"
                            value="{{ old('price', $event->price) }}"
                            placeholder="0 for free" min="0" step="0.01"
                            class="w-full bg-slate-900/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                    <textarea name="description" rows="4"
                        placeholder="Describe your event..."
                        class="w-full bg-slate-900/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $event->description) }}</textarea>
                    @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select name="status" required
                        class="w-full bg-slate-900/60 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach(['upcoming', 'ongoing', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $event->status) === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Current Image -->
                @if($event->image)
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Current Image</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ Storage::url($event->image) }}"
                            class="w-24 h-24 object-cover rounded-xl border border-slate-600"
                            alt="Current event image">
                        <p class="text-slate-400 text-sm">Upload a new image below to replace this</p>
                    </div>
                </div>
                @endif

                <!-- New Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        {{ $event->image ? 'Replace Image (optional)' : 'Event Image (optional)' }}
                    </label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full bg-slate-900/60 border border-slate-600 text-slate-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                    @error('image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-2">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-6 py-3 rounded-xl transition-all hover:shadow-[0_0_20px_rgba(99,102,241,0.4)]">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.events.index') }}"
                        class="border border-slate-600 text-slate-400 hover:text-white hover:border-slate-400 px-6 py-3 rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
    @include('admin.events.partials.location-map-script')
</body>
</html>
