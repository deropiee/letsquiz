<head>
    <title>LetsQuiz — Dashboard</title>
    <style>
        /* Zelfde background-gedrag als cosmetics */
        html, body { overflow-x: hidden; }
        #dashboard-page-wrapper { padding-bottom: 3rem; }
        #dashboard-page-wrapper .bg-decor-wrapper { pointer-events: none; inset: 0; overflow: hidden; }
        .avoid-edge-overflow { transform: translateZ(0); }
    </style>
</head>
<x-app-layout>
    <div id="dashboard-page-wrapper" class="mt-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- decorative background (zelfde als cosmetics) -->
        <div class="bg-decor-wrapper absolute -z-10 avoid-edge-overflow">
            <svg class="absolute right-0 top-0 w-64 h-64 opacity-20 transform rotate-45 blur-lg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <defs><linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#6366f1"/><stop offset="1" stop-color="#06b6d4"/></linearGradient></defs>
                <circle cx="40" cy="40" r="80" fill="url(#g1)"/>
            </svg>
            <svg class="absolute left-0 bottom-0 w-56 h-56 opacity-15 transform -rotate-12 blur-md" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="100" fill="#f97316"/>
            </svg>
        </div>

        <!-- Hero card -->
        <div class="bg-white/70 rounded-xl p-4 shadow-sm border border-gray-100 backdrop-blur-sm">
            <div class="flex items-center gap-4">
                @php
                    $user = Auth::user();
                    $initial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user?->name ?? 'U', 0, 1));
                    $avatarPath = $user?->avatar ? public_path('images/avatars/' . $user->avatar) : null;
                    $hasAvatar = $avatarPath && file_exists($avatarPath);
                @endphp
                <div class="relative w-14 h-14 flex items-center justify-center">
                    @if($hasAvatar)
                        <img id="dashboard-avatar-img" src="{{ asset('images/avatars/' . $user->avatar) }}?v={{ filemtime($avatarPath) }}" alt="Avatar" class="absolute inset-0 w-14 h-14 rounded-full object-cover border border-gray-300">
                    @else
                        <div id="dashboard-avatar-fallback" class="absolute inset-0 w-14 h-14 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold border border-gray-300">
                            {{ $initial }}
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Welkom, {{ $user?->name ?? 'speler' }}!</h1>
                    <p class="text-sm text-gray-600 mt-1">Kies een activiteit om te beginnen.</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('quizzes.list') }}"
                   class="group block w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg px-5 py-4 transform transition duration-300 hover:-translate-y-2 hover:scale-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-indigo-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- fixed quiz icon: document with lines -->
                            <svg class="w-6 h-6 text-white transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 8h8M8 12h8M8 16h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-lg">Quiz</span>
                        </div>
                        <span class="text-sm opacity-90">Start</span>
                    </div>
                </a>

                <a href="{{ route('wheelspin') }}"
                   class="group block w-full bg-gradient-to-r from-green-500 to-teal-500 text-white font-semibold rounded-xl shadow-lg px-5 py-4 transform transition duration-300 hover:-translate-y-2 hover:scale-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- fixed wheel icon: circle with spokes -->
                            <svg class="w-6 h-6 text-white transition-transform group-hover:rotate-12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 6v4M12 12l4 2M12 12l-4 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-lg">Wheelspin</span>
                        </div>
                        <span class="text-sm opacity-90">Draai</span>
                    </div>
                </a>

                <a href="{{ route('cosmetics.show') }}"
                   class="group block w-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold rounded-xl shadow-lg px-5 py-4 transform transition duration-300 hover:-translate-y-2 hover:scale-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-orange-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- fixed shop icon: bag with handle -->
                            <svg class="w-6 h-6 text-white transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M6 7h12l-1 11H7L6 7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 7a3 3 0 016 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="text-lg">Cosmetics</span>
                        </div>
                        <span class="text-sm opacity-90">Bekijk</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recente spellen + spins -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white/70 rounded-xl p-4 shadow-sm border border-gray-100 backdrop-blur-sm">
                <h3 class="text-sm font-semibold text-gray-700">Recente quizzen</h3>
                @php
                    $recent = $recentResults ?? [];
                @endphp

                {{-- Als er geen resultaten gevonden zijn, zeg dat dan. In plaats van een leeg resultaat. --}}
                @if (empty($recent))
                    <div class="text-sm text-gray-500 mt-2">Geen resultaten gevonden.</div>
                @else    
                    <ul class="mt-3 space-y-2 text-sm text-gray-600">
                    @foreach(array_slice($recent, 0, 3) as $r)
                        <li class="flex items-center justify-between">
                            <div>
                                <a href="{{ $r['url'] }}" class="font-medium hover:underline">{{ $r['title'] }}</a>
                                <div class="text-xs text-gray-400">{{ $r['date'] }} — {{ $r['score'] }}</div>
                            </div>
                            <div class="text-xs text-gray-500">💎 {{ $r['gems'] ?? 0 }}</div>
                        </li>
                    @endforeach
                    </ul>
                @endif
            </div>

            <div class="bg-white/70 rounded-xl p-4 shadow-sm border border-gray-100 backdrop-blur-sm">
                <h3 class="text-sm font-semibold text-gray-700">Recente spins</h3>
                @php
                    $spins = $recentSpins ?? [];
                @endphp

                @if(empty($spins))
                    <div class="text-sm text-gray-500 mt-2">Geen spins gevonden.</div>
                @else
                    <ul class="mt-3 space-y-3 text-sm text-gray-600">
                        @foreach(array_slice($spins, 0, 3) as $s)
                            <li class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-medium">{{ $s['name'] ?? 'Wheelspin' }}</div>
                                        <div class="text-xs text-gray-400">{{ $s['date'] ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">{{ $s['result'] ?? '—' }}</div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>