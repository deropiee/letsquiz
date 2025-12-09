<head>
  <title>{{ $result->category?->pretty_folder ?? 'Onbkened' }} Resultaat</title>
</head>
<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8 relative z-10">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-2">
                        {{ $result->category?->pretty_folder ?? 'Onbekend' }}
                    </h1>
                    <p class="text-slate-600 text-lg">Quiz Resultaat</p>
                </div>
            </div>

            @php $total = max(1, ($result->correct_answers + $result->wrong_answers)); @endphp
            
            <!-- Main Stats Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-4 sm:p-6 lg:p-8 mb-6">
                <div class="flex flex-col items-center gap-6 lg:gap-8">
                    <!-- Enhanced Donut Chart -->
                    <div class="relative">
                        @php
                            $good = (int) $result->correct_answers;
                            $bad = (int) $result->wrong_answers;
                            $sum = max(1, $good + $bad);
                            $pct = max(0, min(100, round(($good / $sum) * 100)));
                            $deg = round(360 * $pct / 100);
                        @endphp
                        
                        <div class="relative w-32 h-32 sm:w-40 sm:h-40">
                            <!-- Outer glow effect -->
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-emerald-400/20 to-red-400/20 blur-xl"></div>
                            
                            <!-- Background donut -->
                            <svg class="w-32 h-32 sm:w-40 sm:h-40 drop-shadow-2xl" viewBox="0 0 36 36">
                                <path class="stroke-slate-200" stroke-width="3" fill="transparent"
                                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            
                            <!-- Progress donut -->
                            <svg class="absolute top-0 left-0 w-32 h-32 sm:w-40 sm:h-40 drop-shadow-2xl" viewBox="0 0 36 36">
                                <defs>
                                    <linearGradient id="correctGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
                                    </linearGradient>
                                    <linearGradient id="wrongGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#ef4444;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#dc2626;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                
                                <!-- Correct answers (green) -->
                                <path class="stroke-[url(#correctGradient)]" stroke-width="3" fill="transparent"
                                      stroke-dasharray="{{ $pct }}, {{ 100 - $pct }}"
                                      stroke-dashoffset="0"
                                      stroke-linecap="round"
                                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                
                                @if($pct < 100)
                                <!-- Wrong answers (red) -->
                                <path class="stroke-[url(#wrongGradient)]" stroke-width="3" fill="transparent"
                                      stroke-dasharray="{{ 100 - $pct }}, {{ $pct }}"
                                      stroke-dashoffset="{{ -$pct }}"
                                      stroke-linecap="round"
                                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                @endif
                            </svg>
                            
                            <!-- Center content -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">{{ $result->correct_answers }}/{{ $total }}</div>
                                    <div class="text-xs sm:text-sm font-bold text-slate-600 uppercase tracking-wide">Correct</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $pct }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="w-full grid grid-cols-2 gap-3 sm:gap-4">
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-4 sm:p-6 border border-emerald-200">
                            <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-emerald-500"></div>
                                <span class="text-xs sm:text-sm font-semibold text-emerald-700 uppercase tracking-wide">Correct</span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-800">{{ $result->correct_answers }}</div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-4 sm:p-6 border border-red-200">
                            <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-red-500"></div>
                                <span class="text-xs sm:text-sm font-semibold text-red-700 uppercase tracking-wide">Fout</span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-bold text-red-800">{{ $result->wrong_answers }}</div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-4 sm:p-6 border border-blue-200">
                            <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-blue-500"></div>
                                <span class="text-xs sm:text-sm font-semibold text-blue-700 uppercase tracking-wide">Tijd</span>
                            </div>
                            <div class="text-lg sm:text-2xl font-bold text-blue-800">{{ gmdate('H:i:s', $result->time_taken) }}</div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-4 sm:p-6 border border-amber-200">
                            <div class="flex items-center gap-2 sm:gap-3 mb-2">
                                <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-amber-500"></div>
                                <span class="text-xs sm:text-sm font-semibold text-amber-700 uppercase tracking-wide">Gems verdiend</span>
                            </div>
                            <div class="text-lg sm:text-2xl font-bold text-amber-800">💎 {{ $result->gems_earned }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center gap-4 p-4 bg-slate-50/50 rounded-2xl">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-600">Datum & Tijd</div>
                        <div class="text-lg font-semibold text-slate-800">{{ $result->created_at->addHours(2)->format('d-m-Y H:i') }}</div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 p-4 bg-slate-50/50 rounded-2xl">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden">
                        @if($result->user->avatar)
                            <img src="{{ asset('images/avatars/' . $result->user->avatar) }}" 
                                 alt="{{ $result->user->name }}" 
                                 class="w-12 h-12 rounded-xl object-cover">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($result->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-600">Gebruiker</div>
                        <div class="text-lg font-semibold text-slate-800">{{ $result->user->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->id() === $result->user_id)
            <!-- Controls Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-4 sm:p-6 mb-6">
                <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-4 sm:mb-6">Resultaat Instellingen</h3>
                
                <!-- Privacy Controls -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-6 bg-gradient-to-r from-slate-50 to-slate-100 rounded-2xl mb-4 sm:mb-6 gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-slate-200 rounded-xl flex items-center justify-center">
                            <span class="privacy-status-icon text-xl sm:text-2xl">{{ $result->is_private ? '🔒' : '🌍' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-base sm:text-lg font-semibold text-slate-800">Zichtbaarheid</div>
                            <div class="text-xs sm:text-sm text-slate-600">
                                {{ $result->is_private ? 'Alleen jij kunt dit resultaat zien' : 'Iedereen kan dit resultaat zien' }}
                            </div>
                        </div>
                    </div>
                    <button type="button" 
                            class="privacy-toggle relative inline-flex h-8 w-14 items-center rounded-full transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-opacity-50 flex-shrink-0 {{ $result->is_private ? 'bg-red-500 focus:ring-red-300' : 'bg-emerald-500 focus:ring-emerald-300' }}"
                            data-result-id="{{ $result->id }}"
                            data-current-state="{{ $result->is_private ? 'true' : 'false' }}">
                        <span class="inline-block h-6 w-6 transform rounded-full bg-white shadow-lg transition-transform duration-300 {{ $result->is_private ? 'translate-x-7' : 'translate-x-1' }}"></span>
                    </button>
                </div>
                
                <!-- Share Controls -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 sm:p-6 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-2xl gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-indigo-200 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-base sm:text-lg font-semibold text-slate-800">Delen</div>
                            <div class="text-xs sm:text-sm text-slate-600">Deel dit resultaat met andere gebruikers</div>
                        </div>
                    </div>
                    <button type="button" 
                            class="share-result-btn inline-flex items-center justify-center gap-2 sm:gap-3 px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base w-full sm:w-auto"
                            data-result-id="{{ $result->id }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Deel resultaat
                    </button>
                </div>
                
                @if($result->sharedUsers->count() > 0)
                    <!-- Shared Users Section -->
                    <div class="mt-4 sm:mt-6 p-4 sm:p-6 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-2xl">
                        <h4 class="text-base sm:text-lg font-semibold text-slate-800 mb-3 sm:mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Gedeeld met
                        </h4>
                        <div class="flex flex-wrap gap-2 sm:gap-3">
                            @foreach($result->sharedUsers as $sharedUser)
                                <div class="shared-user-item group relative inline-flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-3 bg-white/80 backdrop-blur-sm rounded-xl text-xs sm:text-sm hover:bg-red-50 transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md"
                                     data-user-id="{{ $sharedUser->id }}" 
                                     data-user-name="{{ $sharedUser->name }}">
                                    @if($sharedUser->avatar && $sharedUser->avatar !== 'default.png')
                                        <img src="{{ asset('images/avatars/' . $sharedUser->avatar) }}" 
                                             alt="{{ $sharedUser->name }}" 
                                             class="w-6 h-6 sm:w-8 sm:h-8 rounded-full border-2 border-white shadow-sm"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-bold border-2 border-white shadow-sm" style="display: none;">{{ strtoupper(substr($sharedUser->name, 0, 1)) }}</div>
                                    @else
                                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-bold border-2 border-white shadow-sm">{{ strtoupper(substr($sharedUser->name, 0, 1)) }}</div>
                                    @endif
                                    <span class="text-slate-700 group-hover:text-red-700 transition-colors font-medium truncate max-w-20 sm:max-w-none">{{ $sharedUser->name }}</span>
                                    
                                    <!-- Unshare button -->
                                    <button type="button" 
                                            class="unshare-btn absolute -top-1 -right-1 sm:-top-2 sm:-right-2 w-5 h-5 sm:w-6 sm:h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-red-600 flex items-center justify-center shadow-lg"
                                            data-user-id="{{ $sharedUser->id }}" 
                                            data-user-name="{{ $sharedUser->name }}"
                                            title="Delen intrekken">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('results.index', ['tab' => $fromTab]) }}" 
               class="inline-flex items-center gap-3 px-6 py-3 rounded-xl bg-white/80 backdrop-blur-sm hover:bg-white text-slate-700 hover:text-slate-900 font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Terug naar resultaten
            </a>
        </div>
    </div>
</div>

<!-- Sharing Modal -->
<div id="shareModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-hidden border border-white/20">
        <div class="p-6 border-b border-slate-200/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Deel resultaat</h3>
                </div>
                <button type="button" class="close-modal w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-800 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Zoek gebruiker</label>
                    <div class="relative">
                        <input type="text" 
                               id="userSearchInput" 
                               placeholder="Typ een gebruikersnaam..."
                               class="w-full px-4 py-4 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-slate-50/50 backdrop-blur-sm">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div id="searchResults" class="max-h-48 overflow-y-auto space-y-3 hidden">
                    <!-- Search results will be populated here -->
                </div>
                
                <div id="noResults" class="text-center py-12 text-slate-500 hidden">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium">Geen gebruikers gevonden</p>
                    <p class="text-xs text-slate-400 mt-1">Probeer een andere zoekterm</p>
                </div>
                
                <div id="loadingSpinner" class="text-center py-8 hidden">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-3 border-indigo-600 border-t-transparent"></div>
                    <p class="text-sm text-slate-500 mt-3 font-medium">Zoeken...</p>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-200/50">
            <div class="flex justify-end">
                <button type="button" class="close-modal px-6 py-3 text-slate-600 hover:text-slate-800 font-semibold rounded-xl hover:bg-slate-100 transition-all duration-200">
                    Annuleren
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Unshare Confirmation Modal -->
<div id="unshareModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl max-w-sm w-full mx-4 border border-white/20">
        <div class="p-8 text-center">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 bg-red-100 rounded-2xl">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-3">Delen intrekken</h3>
            <p class="text-slate-600 mb-8 leading-relaxed">
                Weet je zeker dat je het delen van dit resultaat wilt intrekken voor <span id="unshareUserName" class="font-semibold text-slate-800"></span>? 
                Deze persoon zal het resultaat niet meer kunnen bekijken.
            </p>
            <div class="flex gap-3">
                <button type="button" id="cancelButton" class="flex-1 px-6 py-3 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 font-semibold transition-all duration-200" onclick="closeUnshareModal()">
                    Annuleren
                </button>
                <button type="button" id="confirmUnshareBtn" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    Intrekken
                </button>
            </div>
        </div>
    </div>
</div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Share link functionality
  document.querySelectorAll('.share-link').forEach(btn => {
    btn.addEventListener('click', async () => {
      const url = btn.getAttribute('data-url');
      try {
        if (navigator.share) {
          await navigator.share({title: 'LetsQuiz resultaat', url});
        } else if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(url);
          const old = btn.textContent; btn.textContent = 'Gekopieerd!'; setTimeout(()=>btn.textContent=old, 1200);
        } else {
          throw new Error('no share');
        }
      } catch (_) {
        const old = btn.textContent; btn.textContent = 'Mislukt'; setTimeout(()=>btn.textContent=old, 1200);
      }
    });
  });

  // Privacy toggle functionality
  document.querySelectorAll('.privacy-toggle').forEach(toggle => {
    toggle.addEventListener('click', async () => {
      const resultId = toggle.getAttribute('data-result-id');
      const currentState = toggle.getAttribute('data-current-state') === 'true';
      const newState = !currentState;
      
      // Optimistic UI update
      const isPrivate = newState;
      toggle.classList.remove('bg-red-500', 'bg-emerald-500');
      toggle.classList.add(isPrivate ? 'bg-red-500' : 'bg-emerald-500');
      
      const toggleKnob = toggle.querySelector('span');
      toggleKnob.classList.toggle('translate-x-7', isPrivate);
      toggleKnob.classList.toggle('translate-x-1', !isPrivate);
      
      const statusIcon = document.querySelector('.privacy-status-icon');
      if (statusIcon) {
        statusIcon.textContent = isPrivate ? '🔒' : '🌍';
      }
      
      // Update data attribute
      toggle.setAttribute('data-current-state', newState.toString());
      
      // Send request to server
      try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
        formData.append('_method', 'PATCH');
        formData.append('is_private', newState ? '1' : '0');
        
        const response = await fetch(`/results/${resultId}/visibility`, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        
        if (!response.ok) {
          throw new Error('Update failed');
        }
        
        // Show success feedback - keep the current color, just flash it
        const currentColor = newState ? 'bg-red-500' : 'bg-emerald-500';
        const flashColor = newState ? 'bg-red-400' : 'bg-emerald-400';
        toggle.classList.remove('bg-red-500', 'bg-emerald-500');
        toggle.classList.add(flashColor);
        setTimeout(() => {
          toggle.classList.remove(flashColor);
          toggle.classList.add(currentColor);
        }, 200);
        
      } catch (error) {
        console.error('Failed to update privacy setting:', error);
        
        // Revert optimistic update
        toggle.classList.remove('bg-red-500', 'bg-emerald-500');
        toggle.classList.add(currentState ? 'bg-red-500' : 'bg-emerald-500');
        
        const toggleKnob = toggle.querySelector('span');
        toggleKnob.classList.toggle('translate-x-7', currentState);
        toggleKnob.classList.toggle('translate-x-1', !currentState);
        
        if (statusIcon) {
          statusIcon.textContent = currentState ? '🔒' : '🌍';
        }
        
        toggle.setAttribute('data-current-state', currentState.toString());
        
        // Show error feedback
        toggle.classList.remove('bg-red-500', 'bg-emerald-500');
        toggle.classList.add('bg-red-400');
        setTimeout(() => {
          toggle.classList.remove('bg-red-400');
          toggle.classList.add(currentState ? 'bg-red-500' : 'bg-emerald-500');
        }, 300);
      }
    });
  });

  // Sharing functionality
  let currentResultId = null;
  let searchTimeout = null;
  let sharedUserIds = new Set();

  // Get already shared user IDs
  @if($result->sharedUsers->count() > 0)
    sharedUserIds = new Set([@foreach($result->sharedUsers as $user){{ $user->id }},@endforeach]);
  @endif

  // Open modal
  document.querySelectorAll('.share-result-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      currentResultId = btn.getAttribute('data-result-id');
      document.getElementById('shareModal').classList.remove('hidden');
      document.getElementById('shareModal').classList.add('flex');
      document.getElementById('userSearchInput').focus();
      
      // Load recent shares when modal opens
      await loadRecentShares();
    });
  });

  // Close modal
  document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', closeModal);
  });

  // Close modal on backdrop click
  document.getElementById('shareModal').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) {
      closeModal();
    }
  });

  // Close modal on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeModal();
    }
  });

  function closeModal() {
    document.getElementById('shareModal').classList.add('hidden');
    document.getElementById('shareModal').classList.remove('flex');
    document.getElementById('userSearchInput').value = '';
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('noResults').classList.add('hidden');
    document.getElementById('loadingSpinner').classList.add('hidden');
  }

  // Display search results function
  function displaySearchResults(users, isRecent = false) {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '';
    
    users.forEach(user => {
      const userElement = document.createElement('div');
      userElement.className = 'flex items-center gap-4 p-4 hover:bg-slate-50 rounded-xl cursor-pointer transition-all duration-200 border border-slate-200/50 hover:border-slate-300/50';
      
      // Create avatar HTML with fallback
      const avatarHtml = user.avatar && user.avatar !== 'default.png' 
        ? `<img src="/images/avatars/${user.avatar}" alt="${user.name}" class="w-10 h-10 rounded-full border-2 border-white shadow-sm" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
           <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold border-2 border-white shadow-sm" style="display: none;">${user.name.charAt(0).toUpperCase()}</div>`
        : `<div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold border-2 border-white shadow-sm">${user.name.charAt(0).toUpperCase()}</div>`;
      
      // Add recent indicator if it's a recent share
      const recentIndicator = isRecent ? '<span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full font-medium">Recent</span>' : '';
      
      userElement.innerHTML = `
        ${avatarHtml}
        <div class="flex-1">
          <p class="font-semibold text-slate-800">${user.name}</p>
          ${recentIndicator}
        </div>
        <button class="share-with-user-btn px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all duration-200 text-sm font-semibold shadow-sm hover:shadow-md" data-user-id="${user.id}" data-username="${user.name}">
          Deel
        </button>
      `;
      
      userElement.addEventListener('click', (e) => {
        if (!e.target.classList.contains('share-with-user-btn')) {
          e.target.closest('.share-with-user-btn').click();
        }
      });
      
      resultsContainer.appendChild(userElement);
    });
    
    resultsContainer.classList.remove('hidden');
  }

  // Load recent shares function
  async function loadRecentShares() {
    try {
      const response = await fetch('/users/search?query=');
      const users = await response.json();
      
      if (users.length === 0) {
        document.getElementById('searchResults').classList.add('hidden');
        document.getElementById('noResults').classList.add('hidden');
        return;
      }

      // Filter out already shared users
      const availableUsers = users.filter(user => !sharedUserIds.has(user.id));
      
      if (availableUsers.length === 0) {
        document.getElementById('searchResults').classList.add('hidden');
        document.getElementById('noResults').classList.add('hidden');
        return;
      }

      displaySearchResults(availableUsers, true);
      
    } catch (error) {
      console.error('Recent shares error:', error);
      document.getElementById('searchResults').classList.add('hidden');
      document.getElementById('noResults').classList.add('hidden');
    }
  }

  // User search with debouncing
  document.getElementById('userSearchInput').addEventListener('input', (e) => {
    const query = e.target.value.trim();
    
    clearTimeout(searchTimeout);
    
    if (query.length === 0) {
      // Load recent shares when field is empty
      loadRecentShares();
      return;
    }
    
    if (query.length < 1) {
      document.getElementById('searchResults').classList.add('hidden');
      document.getElementById('noResults').classList.add('hidden');
      document.getElementById('loadingSpinner').classList.add('hidden');
      return;
    }

    document.getElementById('loadingSpinner').classList.remove('hidden');
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('noResults').classList.add('hidden');

    searchTimeout = setTimeout(async () => {
      try {
        const response = await fetch(`/users/search?query=${encodeURIComponent(query)}`);
        const users = await response.json();
        
        document.getElementById('loadingSpinner').classList.add('hidden');
        
        if (users.length === 0) {
          document.getElementById('noResults').classList.remove('hidden');
          return;
        }

        // Filter out already shared users
        const availableUsers = users.filter(user => !sharedUserIds.has(user.id));
        
        if (availableUsers.length === 0) {
          document.getElementById('noResults').classList.remove('hidden');
          return;
        }

        displaySearchResults(availableUsers, false);
        
      } catch (error) {
        console.error('Search error:', error);
        document.getElementById('loadingSpinner').classList.add('hidden');
        document.getElementById('noResults').classList.remove('hidden');
      }
    }, 300);
  });

  // Share with user
  document.addEventListener('click', async (e) => {
    if (e.target.classList.contains('share-with-user-btn')) {
      const btn = e.target;
      const userId = btn.getAttribute('data-user-id');
      const username = btn.getAttribute('data-username');
      
      btn.disabled = true;
      btn.textContent = 'Bezig...';
      
      try {
        const response = await fetch(`/results/${currentResultId}/share`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ username: username })
        });
        
        const result = await response.json();
        
        if (result.success) {
          // Add to shared users set
          sharedUserIds.add(parseInt(userId));
          
          // Show success message
          btn.textContent = 'Gedeeld!';
          btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
          btn.classList.add('bg-green-600');
          
          // Update the shared users list on the page
          location.reload();
        } else {
          throw new Error(result.error || 'Er is een fout opgetreden');
        }
        
      } catch (error) {
        console.error('Share error:', error);
        btn.textContent = 'Fout';
        btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        btn.classList.add('bg-red-600');
        
        setTimeout(() => {
          btn.textContent = 'Deel';
          btn.classList.remove('bg-red-600');
          btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
          btn.disabled = false;
        }, 2000);
      }
    }
  });

  // Unshare functionality
  let unshareUserId = null;
  let unshareUserName = null;

  function closeUnshareModal() {
    console.log("closeUnshareModal called");
    document.getElementById('unshareModal').classList.add('hidden');
    document.getElementById('unshareModal').classList.remove('flex');
    unshareUserId = null;
    unshareUserName = null;
  }
  document.getElementById('cancelButton').addEventListener('click', closeUnshareModal);

  // Handle unshare button clicks
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('unshare-btn') || e.target.closest('.unshare-btn')) {
      e.preventDefault();
      e.stopPropagation();
      
      const btn = e.target.closest('.unshare-btn');
      unshareUserId = btn.getAttribute('data-user-id');
      unshareUserName = btn.getAttribute('data-user-name');
      
      document.getElementById('unshareUserName').textContent = unshareUserName;
      document.getElementById('unshareModal').classList.remove('hidden');
      document.getElementById('unshareModal').classList.add('flex');
    }
  });

  // Handle unshare confirmation
  document.getElementById('confirmUnshareBtn').addEventListener('click', async () => {
    if (!unshareUserId) return;
    
    const btn = document.getElementById('confirmUnshareBtn');
    btn.disabled = true;
    btn.textContent = 'Bezig...';
    
    try {
      const response = await fetch(`/results/{{ $result->id }}/unshare/${unshareUserId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      });
      
      const result = await response.json();
      
      if (result.success) {
        closeUnshareModal();
        location.reload(); // Refresh to update the shared users list
      } else {
        throw new Error(result.error || 'Er is een fout opgetreden');
      }
      
    } catch (error) {
      console.error('Unshare error:', error);
      btn.textContent = 'Fout';
      btn.classList.remove('bg-red-600', 'hover:bg-red-700');
      btn.classList.add('bg-red-400');
      
      setTimeout(() => {
        btn.textContent = 'Intrekken';
        btn.classList.remove('bg-red-400');
        btn.classList.add('bg-red-600', 'hover:bg-red-700');
        btn.disabled = false;
      }, 2000);
    }
  });

  // Close unshare modal on backdrop click
  document.getElementById('unshareModal').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) {
      closeUnshareModal();
    }
  });

  // Close unshare modal on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !document.getElementById('unshareModal').classList.contains('hidden')) {
      closeUnshareModal();
    }
  });
});
</script>
