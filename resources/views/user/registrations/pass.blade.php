<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Pass — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <main class="mx-auto max-w-3xl px-6 py-12">
        <a href="{{ route('my.events') }}" class="text-sm text-slate-400 hover:text-white">← My Events</a>

        @if(session('success'))
            <div class="mt-5 rounded-xl border border-green-500/30 bg-green-500/10 p-4 text-green-300">{{ session('success') }}</div>
        @endif

        <article class="mt-6 overflow-hidden rounded-3xl border border-indigo-500/30 bg-slate-900 shadow-2xl">
            <header class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                <p class="text-sm font-semibold uppercase tracking-widest text-indigo-100">Venue access pass</p>
                <h1 class="mt-1 text-3xl font-bold">{{ $registration->event->title }}</h1>
            </header>
            <div class="grid gap-8 p-8 md:grid-cols-2">
                <div class="flex items-center justify-center rounded-2xl bg-white p-5">
                    <img src="{{ route('registrations.qr', $registration) }}" alt="Scannable venue access QR code" class="w-full max-w-xs">
                </div>
                <dl class="space-y-4 text-sm">
                    <div><dt class="text-slate-500">Attendee</dt><dd class="mt-1 text-lg font-semibold">{{ $registration->user->name }}</dd></div>
                    <div><dt class="text-slate-500">Email</dt><dd class="mt-1">{{ $registration->user->email }}</dd></div>
                    <div><dt class="text-slate-500">Date</dt><dd class="mt-1">{{ $registration->event->event_date->format('F d, Y · g:i A') }}</dd></div>
                    <div><dt class="text-slate-500">Location</dt><dd class="mt-1">{{ $registration->event->location }}</dd></div>
                    <div><dt class="text-slate-500">Payment</dt><dd class="mt-1 text-green-400">Paid · LKR {{ number_format($registration->payment_amount, 2) }}</dd></div>
                    <div><dt class="text-slate-500">Pass UUID</dt><dd class="mt-1 break-all font-mono text-xs">{{ $registration->uuid }}</dd></div>
                </dl>
            </div>
            <footer class="border-t border-slate-800 px-8 py-5 text-center text-sm text-slate-400">
                Present this QR code at the venue entrance. The code opens a signed Eventra verification page.
            </footer>
        </article>
    </main>
</body>
</html>
