<nav x-data="{ open: false }" class="sticky top-4 z-40 px-4 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="relative flex items-center justify-between h-16">
            <!-- translucent card background -->
            <div class="absolute inset-0 -z-10 rounded-2xl bg-white/70 backdrop-blur-lg border border-gray-100 shadow-md"></div>

            <!-- Left: logo -->
            <div class="flex items-center gap-4 pl-3">
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="block h-9 w-auto fill-current text-indigo-600 rounded-lg shadow" />
                    <div class="hidden sm:flex flex-col leading-tight">
                        <span class="text-sm font-semibold text-gray-800">LetsQuiz</span>
                        <span class="text-xs text-gray-500 -mt-0.5">Speel & Verdien</span>
                    </div>
                </a>
            </div>

            <!-- Center: nav links (locked absolute center) -->
            <div class="px-4 items-center">
                <!-- absolutely centered container (pointer-events-none to avoid shifting clicks) -->
                <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                    <div class="hidden lg:flex items-center gap-3 pointer-events-auto">
                        <nav class="flex items-center gap-2">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium">
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            <x-nav-link :href="route('quizzes.list')" :active="request()->routeIs('quizzes.list')"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium">
                                {{ __('Quiz') }}
                            </x-nav-link>

                            <x-nav-link :href="route('wheelspin')" :active="request()->routeIs('wheelspin')"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium">
                                {{ __('Wheelspin') }}
                            </x-nav-link>

                            <x-nav-link :href="route('cosmetics.show')" :active="request()->routeIs('cosmetics.show')"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium">
                                {{ __('Cosmetics') }}
                            </x-nav-link>

                            <x-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')"
                                        class="px-3 py-1.5 rounded-full text-sm font-medium">
                                {{ __('Resultaten') }}
                            </x-nav-link>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Right: different view for authenticated users and guests -->
            <div class="flex items-center gap-3 pr-3">
                @auth
                    <!-- Authenticated: show gems (desktop) + profile dropdown -->
                    <div id="gems" class="hidden lg:inline-flex flex-shrink-0 items-center gap-2 px-2 py-1 rounded-full text-indigo-700 font-semibold select-none max-w-[220px] overflow-hidden"
                         data-gems="{{ auth()->user()->gems ?? 0 }}" title="Je gems" role="status" aria-live="polite">
                        <span class="text-lg">💎</span>
                        <span class="text-sm font-bold truncate" data-gems-val>{{ auth()->user()->gems ?? 0 }}</span>
                    </div>

                    <div class="hidden lg:flex lg:items-center">
                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-3 px-3 py-1 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition">
                                    <div id="nav-avatar-container" class="relative w-9 h-9 flex items-center justify-center">
                                        @php($initial = strtoupper(substr(Auth::user()->name ?? 'U',0,1)))
                                        <div id="nav-avatar-fallback" class="absolute inset-0 w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold select-none border border-gray-300 leading-none @if(Auth::user()->avatar) hidden @endif">
                                            {{ $initial }}
                                        </div>
                                        <img id="nav-avatar-img" src="@if(Auth::user()->avatar)/images/avatars/{{ Auth::user()->avatar }}?v={{ file_exists(public_path('images/avatars/' . Auth::user()->avatar)) ? filemtime(public_path('images/avatars/' . Auth::user()->avatar)) : time() }}@endif" alt="Avatar" class="absolute inset-0 w-9 h-9 rounded-full object-cover border border-gray-300 transition @if(!Auth::user()->avatar) hidden @endif">
                                    </div>
                                    <div class="hidden lg:flex flex-col items-start leading-tight">
                                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                        <span class="text-xs text-gray-400">{{ Auth::user()->email }}</span>
                                    </div>
                                    <svg class="ms-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.12 1l-4.25 4.65a.75.75 0 01-1.11 0L5.21 8.28a.75.75 0 01.02-1.07z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profiel') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Uit') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Mobile: gems + hamburger -->
                    <div class="flex items-center lg:hidden gap-2">
                        <div id="gems-mobile" class="inline-flex items-center gap-2 px-2 py-1 rounded-full text-indigo-700 font-semibold select-none max-w-[220px] overflow-hidden"
                             data-gems="{{ auth()->user()->gems ?? 0 }}" title="Je gems" role="status" aria-live="polite">
                            <span class="text-lg">💎</span>
                            <span class="text-sm font-bold truncate" data-gems-val>{{ auth()->user()->gems ?? 0 }}</span>
                        </div>

                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endauth

                {{-- Guests: no login/register buttons in navbar --}}
                @guest
                    <!-- keep only the hamburger on mobile for navigation (no direct login/register buttons here) -->
                    <div class="flex items-center lg:hidden gap-2">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endguest
            </div>
         </div>
     </div>

     <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden mt-3 mx-4">
         <div class="rounded-2xl bg-white/80 backdrop-blur-lg border border-gray-100 shadow-md p-4">
             <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                 {{ __('Dashboard') }}
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('quizzes.list')" :active="request()->routeIs('quizzes.list')">
                 {{ __('Quiz') }}
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('wheelspin')" :active="request()->routeIs('wheelspin')">
                 {{ __('Wheelspin') }}
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('cosmetics.show')" :active="request()->routeIs('cosmetics.show')">
                 {{ __('Cosmetics') }}
             </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('results.index')" :active="request()->routeIs('results.*')">
                 {{ __('Resultaten') }}
             </x-responsive-nav-link>

             <div class="pt-4 border-t border-gray-100 mt-3">
                @auth
                     <div class="flex items-center gap-3">
                         @php($initial = strtoupper(substr(Auth::user()->name ?? 'U',0,1)))
                         <div id="nav-avatar-container-mobile" class="relative w-10 h-10 flex items-center justify-center">
                             <div id="nav-avatar-fallback-mobile" class="absolute inset-0 w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold select-none border border-gray-300 leading-none @if(Auth::user()->avatar) hidden @endif">
                                 {{ $initial }}
                             </div>
                             <img id="nav-avatar-img-mobile" src="@if(Auth::user()->avatar)/images/avatars/{{ Auth::user()->avatar }}?v={{ file_exists(public_path('images/avatars/' . Auth::user()->avatar)) ? filemtime(public_path('images/avatars/' . Auth::user()->avatar)) : time() }}@endif" alt="Avatar" class="absolute inset-0 w-10 h-10 rounded-full object-cover border border-gray-300 transition @if(!Auth::user()->avatar) hidden @endif">
                         </div>
                         <div>
                             <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                             <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                         </div>
                     </div>

                     <div class="mt-3 space-y-2">
                         <x-responsive-nav-link :href="route('profile.edit')">
                             {{ __('Profiel') }}
                         </x-responsive-nav-link>

                         <form method="POST" action="{{ route('logout') }}">
                             @csrf
                             <x-responsive-nav-link :href="route('logout')"
                                     onclick="event.preventDefault();
                                                 this.closest('form').submit();">
                                 {{ __('Log Uit') }}
                             </x-responsive-nav-link>
                         </form>
                     </div>
                @endauth
             </div>
         </div>
     </div>
 </nav>
