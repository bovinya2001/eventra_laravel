<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Venue Pass — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-950 px-6 text-white">
    <main class="w-full max-w-lg rounded-3xl border {{ $isValid ? 'border-green-500/40' : 'border-red-500/40' }} bg-slate-900 p-8 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full {{ $isValid ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }} text-3xl">
            {{ $isValid ? '✓' : '×' }}
        </div>
        <h1 class="mt-5 text-3xl font-bold">{{ $isValid ? 'Valid venue pass' : 'Invalid venue pass' }}</h1>
        <p class="mt-2 text-slate-400">{{ $registration->event->title }}</p>
        <dl class="mt-7 space-y-4 rounded-2xl bg-slate-800 p-5 text-left text-sm">
            <div><dt class="text-slate-500">Attendee</dt><dd class="mt-1 font-semibold">{{ $registration->user->name }}</dd></div>
            <div><dt class="text-slate-500">Email</dt><dd class="mt-1">{{ $registration->user->email }}</dd></div>
            <div><dt class="text-slate-500">Event date</dt><dd class="mt-1">{{ $registration->event->event_date->format('F d, Y · g:i A') }}</dd></div>
            <div><dt class="text-slate-500">Payment status</dt><dd class="mt-1">{{ ucfirst($registration->payment_status) }}</dd></div>
            <div><dt class="text-slate-500">Pass UUID</dt><dd class="mt-1 break-all font-mono text-xs">{{ $registration->uuid }}</dd></div>
        </dl>
    </main>
</body>
</html>
