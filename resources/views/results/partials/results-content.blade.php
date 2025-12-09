<!-- My Results Tab -->
<div id="my-results" class="tab-content {{ $activeTab !== 'my-results' ? 'hidden' : '' }}">
  @if($myResults->isEmpty())
    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-12">
      <div class="text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-3">Nog geen resultaten</h3>
        <p class="text-slate-600 text-lg mb-6">Voltooi een quiz om je eerste resultaten te zien!</p>
        <a href="{{ route('quizzes.list') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Start een Quiz
        </a>
      </div>
    </div>
  @else
    <div class="grid gap-6">
      @foreach($myResults as $result)
        @php
          $total = max(1, ($result->correct_answers + $result->wrong_answers));
          $score = $result->correct_answers . '/' . $total;
          $percentage = round(($result->correct_answers / $total) * 100);
        @endphp
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-4 sm:p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-4 sm:mb-6 gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <h2 class="text-lg sm:text-xl font-bold text-slate-800 truncate">{{ $result->category?->pretty_folder ?? 'Onbekend' }}</h2>
                  <div class="flex items-center gap-2 mt-1">
                    @if($result->is_private)
                      <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-semibold">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        Privé
                      </span>
                    @else
                      <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-semibold">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path>
                        </svg>
                        Openbaar
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Score Circle - Hidden on mobile -->
            <div class="hidden sm:block relative w-20 h-20 flex-shrink-0">
              <svg class="w-20 h-20" viewBox="0 0 36 36">
                <path class="stroke-slate-200" stroke-width="3" fill="transparent" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                @if($percentage > 0)
                <path class="stroke-indigo-500" stroke-width="3" fill="transparent" 
                      stroke-dasharray="{{ $percentage }}, 100" 
                      stroke-dashoffset="0" 
                      stroke-linecap="round"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                @endif
              </svg>
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                  <div class="text-lg font-bold text-slate-800">{{ $score }}</div>
                  <div class="text-xs text-slate-500">{{ $percentage }}%</div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Stats Row -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2 sm:gap-4">
              <div class="flex items-center gap-2 px-2 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-emerald-100 text-emerald-700">
                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500"></div>
                <span class="text-xs sm:text-sm font-semibold">Goed: {{ $result->correct_answers }}</span>
              </div>
              <div class="flex items-center gap-2 px-2 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-red-100 text-red-700">
                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-red-500"></div>
                <span class="text-xs sm:text-sm font-semibold">Fout: {{ $result->wrong_answers }}</span>
              </div>
              <div class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">{{ \App\Http\Controllers\ResultController::formatDate($result->created_at) }}</span>
              </div>
            </div>
            <a href="{{ route('results.show', ['id' => $result->id, 'from_tab' => 'my-results']) }}" 
               class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              Bekijk
            </a>
          </div>
        </div>
      @endforeach
      
      <!-- Pagination for My Results -->
      <div class="mt-6">
        {{ $myResults->withPath(route('results.index'))->appends(['tab' => 'my-results'])->links() }}
      </div>
    </div>
  @endif
</div>

<!-- Shared Results Tab -->
<div id="shared-results" class="tab-content {{ $activeTab !== 'shared-results' ? 'hidden' : '' }}">
  @if($sharedResults->isEmpty())
    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-12">
      <div class="text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-6">
          <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-3">Geen gedeelde resultaten</h3>
        <p class="text-slate-600 text-lg">Andere gebruikers hebben nog geen resultaten met jou gedeeld.</p>
      </div>
    </div>
  @else
    <div class="grid gap-6">
      @foreach($sharedResults as $result)
        @php
          $total = max(1, ($result->correct_answers + $result->wrong_answers));
          $score = $result->correct_answers . '/' . $total;
          $percentage = round(($result->correct_answers / $total) * 100);
        @endphp
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-4 sm:p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-4 sm:mb-6 gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <h2 class="text-lg sm:text-xl font-bold text-slate-800 truncate">{{ $result->category?->pretty_folder ?? 'Onbekend' }}</h2>
                  <div class="flex items-center gap-2 mt-1">
                    <div class="flex items-center gap-2 px-2 sm:px-3 py-1 rounded-lg bg-slate-100 text-slate-600">
                      @if($result->user->avatar)
                        <img src="{{ asset('images/avatars/' . $result->user->avatar) }}" 
                             alt="{{ $result->user->name }}" 
                             class="w-4 h-4 sm:w-5 sm:h-5 rounded-full">
                      @else
                        <div class="w-4 h-4 sm:w-5 sm:h-5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                          {{ strtoupper(substr($result->user->name, 0, 1)) }}
                        </div>
                      @endif
                      <span class="text-xs sm:text-sm font-medium">Gedeeld door {{ $result->user->name }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Score Circle - Hidden on mobile -->
            <div class="hidden sm:block relative w-20 h-20 flex-shrink-0">
              <svg class="w-20 h-20" viewBox="0 0 36 36">
                <path class="stroke-slate-200" stroke-width="3" fill="transparent" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                @if($percentage > 0)
                <path class="stroke-emerald-500" stroke-width="3" fill="transparent" 
                      stroke-dasharray="{{ $percentage }}, 100" 
                      stroke-dashoffset="0" 
                      stroke-linecap="round"
                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                @endif
              </svg>
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center">
                  <div class="text-lg font-bold text-slate-800">{{ $score }}</div>
                  <div class="text-xs text-slate-500">{{ $percentage }}%</div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Stats Row -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2 sm:gap-4">
              <div class="flex items-center gap-2 px-2 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-emerald-100 text-emerald-700">
                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500"></div>
                <span class="text-xs sm:text-sm font-semibold">Goed: {{ $result->correct_answers }}</span>
              </div>
              <div class="flex items-center gap-2 px-2 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-red-100 text-red-700">
                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-red-500"></div>
                <span class="text-xs sm:text-sm font-semibold">Fout: {{ $result->wrong_answers }}</span>
              </div>
              <div class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">{{ \App\Http\Controllers\ResultController::formatDate($result->created_at) }}</span>
              </div>
            </div>
            <a href="{{ route('results.show', ['id' => $result->id, 'from_tab' => 'shared-results']) }}" 
               class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              Bekijk
            </a>
          </div>
        </div>
      @endforeach
      
      <!-- Pagination for Shared Results -->
      <div class="mt-6">
        {{ $sharedResults->withPath(route('results.index'))->appends(['tab' => 'shared-results'])->links() }}
      </div>
    </div>
  @endif
</div>
