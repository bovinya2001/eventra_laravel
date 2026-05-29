<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal — Eventra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%); }
        .glass { background: rgba(255,255,255,0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); }
        .glow { box-shadow: 0 0 40px rgba(99, 102, 241, 0.3); }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="gradient-bg min-h-screen flex flex-col">

    <!-- Top Nav -->
    <nav class="flex justify-between items-center px-8 py-4">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-white font-bold text-lg">Eventra</span>
        </div>
        <a href="{{ route('landing') }}" class="text-indigo-300 hover:text-white text-sm transition-colors">
            ← Switch to User Portal
        </a>
    </nav>

    <!-- Main Content -->
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Icon -->
            <div class="text-center mb-8 float">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-600 glow mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white">Admin Portal</h1>
                <p class="text-indigo-300 mt-1">Eventra Management System</p>
            </div>

            <!-- Card -->
            <div class="glass rounded-2xl p-8">
                <h2 class="text-xl font-semibold text-white mb-1">Administrator Login</h2>
                <p class="text-slate-400 text-sm mb-6">Enter your credentials to access the admin panel</p>

                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500/50 text-red-300 rounded-lg px-4 py-3 mb-4 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@eventify.com"
                            class="w-full bg-slate-800/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full bg-slate-800/60 border border-slate-600 text-white placeholder-slate-500 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 rounded-xl transition-all duration-200 transform hover:scale-[1.02] mt-2">
                        Login to Admin Panel
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>