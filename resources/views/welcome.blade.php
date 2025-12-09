<x-app-layout>
   <div class="relative overflow-hidden">
       <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
           <svg class="absolute right-0 top-0 w-64 h-64 opacity-20 transform rotate-45 blur-lg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
               <defs>
                   <linearGradient id="g1" x1="0" x2="1">
                       <stop offset="0" stop-color="#6366f1"/><stop offset="1" stop-color="#06b6d4"/>
                   </linearGradient>
               </defs>
               <circle cx="40" cy="40" r="80" fill="url(#g1)"/>
           </svg>
           <svg class="absolute left-0 bottom-0 w-56 h-56 opacity-15 transform -rotate-12 blur-md" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
               <circle cx="100" cy="100" r="100" fill="#f97316"/>
           </svg>
       </div>
        <main class="flex-1 flex items-center justify-center px-6 mt-20">
            <div class="w-full max-w-xl">
                <div class="bg-white/70 rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="flex-1 text-center md:text-left">
                            <div class="inline-flex items-center gap-3">
                                <div class="text-left">
                                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Welkom bij LetsQuiz</h1>
                                    <p class="mt-2 text-sm md:text-base text-slate-600 max-w-lg">
                                        Test je kennis in korte rondes, verdien gems en wissel die om voor cosmetics. Gebruik de hendel aan de rechterkant om snel naar je dashboard te gaan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- hendel (exacte markup ongewijzigd) -->
                        <div class="flex justify-center md:justify-end">
                            <div class="bg-white rounded-2xl p-3 shadow-md border border-gray-100 flex flex-col items-center">
                                <div class="text-sm font-semibold mb-0.5">Hendel</div>
                                <div class="text-xs text-gray-500 mb-2">Sleep omlaag</div>
                                <div class="relative w-full h-36 flex items-start justify-center">
                                    <div class="relative w-12 h-40 flex items-start justify-center">
                                        <div class="absolute top-6 left-1/2" style="width:6px;height:90px;transform:translateX(-50%);border-radius:999px;background:linear-gradient(180deg,#d1d5db,#6b7280);box-shadow: inset 0 2px 6px rgba(255,255,255,0.12),0 6px 18px rgba(2,6,23,0.06);"></div>
                                        <button id="lever-small"
                                                aria-label="Trek hendel"
                                                title="Trek hendel"
                                                data-redirect="{{ route('dashboard') }}"
                                                class="relative z-20 w-12 h-12 bg-red-600 rounded-full shadow-lg flex items-center justify-center [touch-action:none] [will-change:transform] transition-transform duration-[260ms] [transition-timing-function:cubic-bezier(.2,.9,.2,1)] cursor-grab focus:outline-none"
                                                type="button">
                                         <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/></svg>
                                     </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
         </main>
   </div>

    @vite(['resources/js/lever.js'])
</x-app-layout>
