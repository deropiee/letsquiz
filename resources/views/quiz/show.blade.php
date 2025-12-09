<head>
    <title>LetsQuiz — Quiz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<x-app-layout hideNavigation="true">
    @vite(['resources/css/quiz-duo.css', 'resources/js/quiz-duo.js'])
    <meta name="route-gems-add" content="{{ route('gems.add') }}">

    <div class="duo-shell w-full max-w-3xl mx-auto flex flex-col items-stretch pt-6 pb-0 px-6 relative z-10" data-quiz-id="{{ $slug }}">

        <!-- top bar with close (left), progress (center), and gems (right) -->
        <div class="duo-top flex items-center justify-between mb-6">
            <button class="duo-close text-black" type="button" aria-label="Sluiten">✕</button>
            <div class="w-2/3">
                <div class="progress-rail rounded-full bg-gray-200 h-2 overflow-hidden">
                    <div id="duo-progress" class="bg-green-500 h-2 w-0 transition-all"></div>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <div class="w-12 text-right text-sm text-black" id="duo-progress-text">1/{{ count($questions) }}</div>
                <div class="mt-1 text-right">
                    <span id="duo-gems" class="inline-block bg-yellow-100 text-yellow-800 rounded px-2 py-1 text-xs font-semibold" data-gems="{{ Auth::user()->gems ?? 0 }}">💎{{ Auth::user()->gems ?? 0 }}</span>
                </div>
            </div>
        </div>

    <main class="duo-stage relative flex-1">
        <!-- Herhaling-indicator (rechtsboven) -->
        <div id="duo-global-helper" class="duo-helper text-sm text-yellow-700 font-bold" style="position:absolute;right:0;top:0;z-index:30;min-width:70px;text-align:right;pointer-events:none;"></div>

        @foreach($questions as $index => $question)
            <section class="duo-question" data-index="{{ $index }}" data-id="{{ $question->id }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
                <div class="duo-question-core">
                    <h2 class="duo-instruction font-semibold text-xl mb-6">{{ $question->question_text }}</h2>
                    <div class="duo-choices">
                        @foreach($question->choices as $choice)
                            <button type="button"
                                    class="duo-choice"
                                    data-value="{{ $choice->identifier }}"
                                    data-is-correct="{{ $choice->is_correct ? 'true' : 'false' }}">
                                <span class="choice-text">{{ $choice->choice_text }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Bottom action / feedback bar (vast onderaan) -->
                <div class="duo-actions-bar">
                    <p class="feedback hidden" aria-live="polite"></p>
                    <div class="duo-actions-inner">
                        <div class="duo-status" id="duo-status"></div>
                        <div class="duo-actions-buttons">
                            <button class="duo-check duo-btn duo-btn-primary duo-btn-disabled" disabled>CHECK</button>
                            <button class="duo-next duo-btn duo-btn-next duo-btn-secondary hidden">VOLGENDE</button>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach

        <section id="duo-finished" class="duo-finished hidden">
            <h3 class="text-2xl font-semibold mb-2">Je hebt de quiz afgerond!</h3>
            <p class="text-gray-500 mb-6">Goed gedaan 🎉</p>
            <div class="flex items-center justify-center gap-3">
                <button id="duo-save-result" class="duo-btn">Opslaan en terug naar dashboard</button>
            </div>
        </section>
    </main>
    </div>
</x-app-layout>
