<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventra — Where Events Come Alive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 70%, #0f172a 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 60px rgba(99,102,241,0.3); }
        .glow-text { text-shadow: 0 0 80px rgba(99,102,241,0.5); }
        @keyframes pulse-slow { 0%,100%{opacity:0.4} 50%{opacity:0.7} }
        .pulse-slow { animation: pulse-slow 4s ease-in-out infinite; }
        @keyframes slide-up { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
        .slide-up { animation: slide-up 0.8s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-900 text-white">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 bg-slate-900/80 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-bold text-xl">Eventify</span>
            </div>
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-slate-400 hover:text-white transition-colors text-sm">Features</a>
                <a href="{{ route('events.index') }}" class="text-slate-400 hover:text-white transition-colors text-sm">Events</a>
                <a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition-colors text-sm">Login</a>
                <a href="{{ route('register') }}"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-sm font-medium transition-all">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-bg min-h-screen flex items-center relative overflow-hidden pt-16">
        <!-- Decorative blobs -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-600 rounded-full filter blur-[120px] opacity-20 pulse-slow"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-600 rounded-full filter blur-[120px] opacity-20 pulse-slow"></div>

        <div class="max-w-7xl mx-auto px-6 py-20 text-center slide-up">
            <div class="inline-flex items-center gap-2 bg-indigo-500/20 border border-indigo-500/30 rounded-full px-4 py-2 mb-6">
                <span class="w-2 h-2 bg-indigo-400 rounded-full animate-pulse"></span>
                <span class="text-indigo-300 text-sm font-medium">Sri Lanka's #1 Event Platform</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight glow-text">
                Where Events<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Come Alive</span>
            </h1>
            <p class="text-slate-400 text-xl max-w-2xl mx-auto mb-10">
                Discover, register, and experience extraordinary events. From concerts to conferences — your next unforgettable moment starts here.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}"
                    class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-8 py-4 rounded-2xl text-lg transition-all transform hover:scale-105 hover:shadow-[0_0_30px_rgba(99,102,241,0.5)]">
                    Browse Events →
                </a>
                <a href="{{ route('register') }}"
                    class="border border-white/20 hover:border-white/40 text-white font-bold px-8 py-4 rounded-2xl text-lg transition-all">
                    Create Account
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-20 grid grid-cols-3 gap-8 max-w-lg mx-auto">
                <div class="text-center">
                    <div class="text-3xl font-black text-white">500+</div>
                    <div class="text-slate-500 text-sm mt-1">Events Hosted</div>
                </div>
                <div class="text-center border-x border-white/10">
                    <div class="text-3xl font-black text-white">10K+</div>
                    <div class="text-slate-500 text-sm mt-1">Happy Attendees</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-white">50+</div>
                    <div class="text-slate-500 text-sm mt-1">Cities</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-24 px-6 bg-slate-900">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Everything you need</h2>
                <p class="text-slate-400">A complete event management experience</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z','title'=>'Instant Registration','desc'=>'Register for events in one click. No hassle, no paperwork.','color'=>'indigo'],
                    ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','title'=>'Event Management','desc'=>'Powerful admin tools to create and manage events effortlessly.','color'=>'purple'],
                    ['icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9','title'=>'Live Updates','desc'=>'Get notified about event changes, capacity updates, and more.','color'=>'pink'],
                ] as $f)
                <div class="card-hover bg-slate-800/50 border border-slate-700 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-{{ $f['color'] }}-600/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-{{ $f['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ $f['title'] }}</h3>
                    <p class="text-slate-400">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 px-6 bg-gradient-to-br from-indigo-900/50 to-purple-900/50">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl font-bold mb-4">Ready to get started?</h2>
            <p class="text-slate-400 mb-8">Join thousands of event-goers who trust Eventify</p>
            <a href="{{ route('register') }}"
                class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-10 py-4 rounded-2xl text-lg transition-all transform hover:scale-105">
                Sign Up Free →
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/10 py-8 px-6 text-center text-slate-500 text-sm">
        © {{ date('Y') }} Eventify. Built with Laravel & ❤️
        <span class="mx-3">|</span>
        <a href="{{ route('admin.login') }}" class="hover:text-slate-300 transition-colors">Admin Portal</a>
    </footer>

</body>
</html>