<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event — Eventra Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex">

    @include('admin.partials.sidebar')

    <main class="ml-64 flex-1 p-8">
        <div class="mb-8">
            <a href="{{ route('admin.events.index') }}" class="text-slate-400 hover:text-white text-sm transition-colors">← Back to Events</a>
            <h1 class="text-2xl font-bold mt-2">Create New Event</h1>
        </div>

        <div class="max-w-2xl bg-slate-800/50 border border-white/10 rounded-2xl p-8">
            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                @foreach([
                    ['name'=>'title','label'=>'Event Title','type'=>'text','placeholder'=>'e.g. Tech Summit 2025'],
                    ['name'=>'location','label'=>'Location','type'=>'text','placeholder'=>'e.g. Colombo, Sri Lanka'],
                    ['name'=>'event_date','label'=>'Date & Time','type'=>'datetime-local','placeholder'=>''],
                    ['name'=>'capacity','label'=>'Capacity','type'=>'number','placeholder'=>'e.g. 100'],
                    ['name'=>'price','label'=>'Price (LKR)','type'=>'number','placeholder'=>'0 for free'],
                ] as $field)
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">{{ $field['label'] }}</label>
                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                        value="{{ old($field['name']) }}"
                        placeholder="{{ $field['placeholder'] }}"
                        class="w-full bg-slate-900/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error($field['name']) <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                    <textarea name="description" rows="4" placeholder="Describe your event..."
                        class="w-full bg-slate-900/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select name="status" class="w-full bg-slate-900/60 border border-slate-600 text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        @foreach(['upcoming','ongoing','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', 'upcoming') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Event Image (optional)</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full bg-slate-900/60 border border-slate-600 text-slate-400 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="flex gap-4 pt-2">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                        Create Event
                    </button>
                    <a href="{{ route('admin.events.index') }}"
                        class="border border-slate-600 text-slate-400 hover:text-white px-6 py-3 rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>