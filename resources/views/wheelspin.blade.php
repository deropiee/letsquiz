<head>
    <title>LetsQuiz — Wheelspin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="spins-store-url" content="{{ route('spins.store') }}">
</head>
<x-app-layout>
    @vite(['resources/css/wheelspin.css','resources/js/wheel.js','resources/js/lever.js'])

    <div class="ws-wrapper">

        <!-- Layout: wheel + side info -->
        <div class="ws-layout ws-layout-center mt-5">
            <div class="ws-row">
                <div class="ws-card ws-wheel-card" aria-labelledby="wheel-heading">
                    <div class="ws-card-head center">
                        <h2 id="wheel-heading" class="ws-card-title">Dagelijkse Draai</h2>
                        <p class="ws-card-sub">Trek de hendel</p>
                    </div>
                    <div class="ws-wheel-wrapper">
                        <div class="ws-wheel-ratio">
                            <canvas id="wheel" class="ws-wheel" width="600" height="600" aria-hidden="true"></canvas>
                            <div id="pointer" class="ws-pointer" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="ws-pointer-icon"><path d="M12 22 L2 6 L22 6 Z"/></svg>
                            </div>
                            <!-- Cooldown overlay over the wheel -->
                            <div id="wheel-cooldown-overlay" class="ws-wheel-cooldown" aria-hidden="true">
                                <div class="ws-cd-inner">
                                    <div id="wheel-cd-time" class="ws-cd-time">00:00</div>
                                    <div class="ws-cd-label">Volgende draai</div>
                                </div>
                            </div>
                        </div>
                        <div class="ws-wheel-footer">
                            <button id="spin-btn" type="button" class="ws-hidden-spin" aria-hidden="true">Draai</button>
                            <div id="spin-glow" class="ws-spin-glow" aria-hidden="true"></div>
                            <p id="timer" class="ws-timer" role="status" aria-live="polite"></p>
                        </div>
                    </div>
                </div>
                <div class="ws-side-stack">
                    <div class="ws-card ws-lever-card" aria-labelledby="lever-heading">
                        <h2 id="lever-heading" class="ws-card-title-small">Hendel</h2>
                        <p class="ws-card-sub">Sleep / Trek naar beneden</p>
                        <div class="ws-lever-stage mt-8">
                            <div class="ws-lever-column">
                                <div class="ws-lever-bar" aria-hidden="true"></div>
                                <button id="lever-small" aria-label="Trek hendel" title="Trek hendel" type="button" class="ws-lever-btn cursor-grab">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="ws-lever-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="ws-card ws-info-card">
                        <h2 class="ws-card-title-small">Uitleg</h2>
                        <ul class="ws-list" role="list">
                            <li>Gewichten bepalen kans op grotere prijzen</li>
                            <li>Cooldown voorkomt onbeperkt draaien</li>
                            <li>Winst wordt direct bij je gems opgeteld</li>
                            <li>Trek de hendel om het rad te draaien</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Globale confetti container (fixed full screen) -->
        <div id="confetti-global" class="ws-confetti-global" aria-hidden="true"></div>

        <!-- Modal -->
        <div id="reward-modal" class="ws-modal hidden" role="dialog" aria-modal="true" aria-labelledby="reward-title" aria-hidden="true">
            <div class="ws-modal-backdrop" data-close></div>
            <div class="ws-modal-panel">
                <h2 id="reward-title" class="ws-modal-title">🎉 Je hebt gewonnen!</h2>
                <p id="reward-text" class="ws-modal-text"></p>
                <button id="close-modal" type="button" class="ws-btn-primary">Sluit</button>
            </div>
        </div>
    </div>
</x-app-layout>
