<head>
    <title>LetsQuiz — Quiz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<x-app-layout>
    <!-- Page-specific assets (Vite) -->
    @vite(['resources/css/quiz-list.css', 'resources/js/quiz-list.js'])

    <div class="ql-wrapper mt-16">
        <!-- Mobile toggle button for sidebar -->
        <button class="ql-mobile-toggle" type="button" aria-controls="ql-sidebar" aria-expanded="false">📋 Quizzes</button>
        <div class="ql-mobile-overlay" data-close-sidebar></div>
        @if(isset($chapters) && count($chapters))
            <div class="ql-layout">
                <!-- Left: sidebar met hoofdstukken en quizzes (gefilterd op selectie) -->
                <aside id="ql-sidebar" class="ql-sidebar text-center" aria-label="Quizlijst" tabindex="-1">
                    <div class="ql-side-top">
                        <h1 class="text-black text-xl font-bold mb-3">Kies quiz:</h1>
                        <div class="ql-dropdown">
                            <button class="ql-drop-btn" aria-haspopup="listbox" aria-expanded="false">Opleiding: {{ isset($selectedChapterKey) ? Str::title(str_replace(['-','_'], ' ', $selectedChapterKey)) : 'Alle' }}</button>
                            <ul class="ql-drop-menu" role="listbox">
                                @foreach($chapters as $ch)
                                    <li>
                                        <a href="{{ route('quizzes.list', $ch['key']) }}" role="option">{{ $ch['index'] < 10 ? '0'.$ch['index'] : $ch['index'] }} — {{ $ch['title'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @php($chaptersToShow = isset($selectedChapter) ? [$selectedChapter] : $chapters)
                    @foreach($chaptersToShow as $chapter)
                        <div class="ql-chapter" id="chapter-{{ $chapter['index'] }}">
                            <span class="sr-only">{{ $chapter['title'] }}</span>
                            <ul class="ql-list">
                                @foreach($chapter['items'] as $item)
                                    <li>
                                        @php($isDone = isset($completedQuizzes) && in_array($item['folder'], $completedQuizzes))
                                        <a class="ql-quiz-pill {{ $isDone ? 'is-completed' : '' }}" href="#" data-href="{{ route('quiz.show', $item['folder']) }}" role="button" tabindex="0"
                                           data-num="{{ str_pad((string)$loop->iteration, 2, '0', STR_PAD_LEFT) }}"
                                           data-title="{{ $item['title'] }}"
                                           data-chapter="{{ $chapter['title'] }}">
                                            <span class="ql-pill-num">
                                                @if($isDone)
                                                    <span class="ql-check" aria-label="Voltooid">✓</span>
                                                @else
                                                    {{ str_pad((string)$loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                                @endif
                                            </span>
                                            <span class="ql-pill-label">{{ $item['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </aside>

                <!-- Right content: preview panel -->
                <section class="ql-content">
                    <div class="ql-preview" aria-live="polite">
                        <div class="ql-preview-top-left">
                            <div class="ql-preview-quiznum">Quiz <span id="ql-prev-num">--</span></div>
                            <div class="ql-preview-chapter" id="ql-prev-chapter"></div>
                        </div>
                        <div class="ql-preview-left-mid">
                            <h2 class="ql-preview-title" id="ql-prev-title">Selecteer een quiz</h2>
                        </div>
                        <div class="ql-preview-right-mid">
                            <a id="ql-prev-link" href="#" class="ql-start">Start Quiz!</a>
                        </div>
                    </div>
                </section>
            </div>
        @else
            <div class="ql-empty">
                <p>Er zijn nog geen quizzes beschikbaar.</p>
            </div>
        @endif
    </div>
</x-app-layout>
