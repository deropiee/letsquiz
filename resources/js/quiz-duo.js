document.addEventListener('DOMContentLoaded', () => {
    const questions = Array.from(document.querySelectorAll('.duo-question'));
    if (questions.length === 0) return;

    // Gradient gebruikt nu direct --user-theme-color uit layout, geen runtime detectie nodig.

    // Exit-knop interceptie
    const exitBtn = document.querySelector('.duo-close');
    if (exitBtn) {
        exitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Custom confirm popup
            showExitConfirm();
        });
    }

    // Exit confirm popup
    function showExitConfirm() {
        // Simpele custom modal
        let modal = document.getElementById('duo-exit-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'duo-exit-modal';
            modal.style.position = 'fixed';
            modal.style.left = '0';
            modal.style.top = '0';
            modal.style.width = '100vw';
            modal.style.height = '100vh';
            modal.style.background = 'rgba(0,0,0,0.25)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '9999';
            modal.innerHTML = `
                <div style="background:#fff;padding:2.2em 2em 1.5em 2em;border-radius:1.2em;box-shadow:0 8px 32px #0002;max-width:95vw;width:340px;text-align:center;">
                    <div style="font-size:1.15em;font-weight:600;margin-bottom:1em;">Weet je zeker dat je wilt stoppen?</div>
                    <div style="color:#666;font-size:0.98em;margin-bottom:1.5em;">Je voortgang wordt niet opgeslagen als je verlaat via deze knop.</div>
                    <button id="duo-exit-confirm" style="background:#ef4444;color:#fff;font-weight:600;padding:0.6em 1.5em;border:none;border-radius:0.7em;margin-right:1em;cursor:pointer;">Verlaten</button>
                    <button id="duo-exit-cancel" style="background:#eee;color:#222;font-weight:500;padding:0.6em 1.5em;border:none;border-radius:0.7em;cursor:pointer;">Annuleren</button>
                </div>
            `;
            document.body.appendChild(modal);
        } else {
            modal.style.display = 'flex';
        }
        // Button handlers
        modal.querySelector('#duo-exit-confirm').onclick = function() {
            // Verwijder voortgang en ga terug
            localStorage.removeItem(LS_KEY);
            modal.style.display = 'none';
            // Sta daadwerkelijke terugnavigatie toe (verwijder interceptie)
            allowBackNavigation = true;
            window.removeEventListener('popstate', onPopStateIntercept);
            try {
                // We hebben één extra state gepusht; ga daarom 2 stappen terug
                if (pushedInitialState) {
                    history.go(-2);
                } else {
                    history.back();
                }
            } catch (_) {
                history.back();
            }
        };
        modal.querySelector('#duo-exit-cancel').onclick = function() {
            modal.style.display = 'none';
        };
    }

    // Unieke quiz-id (slug uit body attribuut of fallback)
    let quizId = document.body.dataset.quizId || window.location.pathname;
    if (!quizId) quizId = 'default-quiz';
    const LS_KEY = 'quizduo-progress-' + quizId;

    // Bepaal quiz slug voor backend route /quiz/complete/{slug}
    function getQuizSlug() {
        const fromBody = document.body.dataset.quizId;
        if (fromBody) return fromBody;
        const parts = (window.location.pathname || '').split('/').filter(Boolean);
        const slugIndex = parts.findIndex(p => p === 'quiz');
        if (slugIndex !== -1 && parts[slugIndex + 1]) return parts[slugIndex + 1];
        // fallback: laatste segment
        return parts[parts.length - 1] || 'unknown';
    }

    // Tijdregistratie
    const quizStartMs = Date.now();

    // Interceptie voor browser-terugknop (alleen om exit te bevestigen)
    let allowBackNavigation = false;
    let pushedInitialState = false;
    const onPopStateIntercept = (e) => {
        if (allowBackNavigation) return; // laat natuurlijke back als gebruiker bevestigt
        // Toon exit confirm en herstel state zodat we op de pagina blijven
        showExitConfirm();
        // Push direct weer een state zodat de gebruiker op de pagina blijft
        try { history.pushState({ quizDuo: true }, '', window.location.href); } catch (_) {}
    };
    // Plaats een extra history state zodat de eerste 'Back' een popstate triggert
    try {
        history.replaceState({ quizDuoRoot: true }, '');
        history.pushState({ quizDuo: true }, '');
        pushedInitialState = true;
        window.addEventListener('popstate', onPopStateIntercept);
    } catch (_) {}

    // Helpers voor localStorage
    function saveProgress() {
        const queueIds = queue.map(q => q.dataset.id);
        const repeated = {};
        questions.forEach(q => {
            if (q.dataset.repeated) repeated[q.dataset.id] = q.dataset.repeated;
        });
        const data = {
            queue: queueIds,
            repeated,
            correctCount
        };
        localStorage.setItem(LS_KEY, JSON.stringify(data));
    }
    function loadProgress() {
        try {
            const raw = localStorage.getItem(LS_KEY);
            if (!raw) return false;
            const data = JSON.parse(raw);
            if (!data || !Array.isArray(data.queue)) return false;
            // Herstel queue
            const idToQ = {};
            questions.forEach(q => { idToQ[q.dataset.id] = q; });
            queue = data.queue.map(id => idToQ[id]).filter(Boolean);
            // Herstel repeated
            Object.entries(data.repeated || {}).forEach(([id, val]) => {
                if (idToQ[id]) idToQ[id].dataset.repeated = val;
            });
            correctCount = data.correctCount || 0;
            return true;
        } catch(e) { return false; }
    }

    // Gems element
    const gemsEl = document.getElementById('duo-gems');

    // Helper: get gems from element
    function getGemsFromEl(el) {
        if (!el) return 0;
        const dataVal = el.getAttribute && el.getAttribute('data-gems');
        if (dataVal != null) return parseInt(dataVal, 10) || 0;
        const digits = (el.textContent || '').replace(/\D/g, '');
        const parsed = parseInt(digits || '0', 10);
        return Number.isNaN(parsed) ? 0 : parsed;
    }

    // Helper: set gems in element
    function setGemsInEl(el, value) {
        if (!el) return;
        const num = Math.max(0, Math.round(Number(value) || 0));
        el.textContent = '💎' + String(num);
        if (el.setAttribute) el.setAttribute('data-gems', num);
    }

    // Helper: animate gems number (zoals bij wheel)
    function animateGems(el, from, to, duration = 800, showBadge = 0) {
        const parsedStart = Number(from) || 0;
        const parsedEnd = Number(to) || 0;
        if (parsedStart === parsedEnd) {
            setGemsInEl(el, parsedEnd);
            return;
        }
        if (showBadge && parsedEnd > parsedStart) {
            showGemsBadge('+' + (parsedEnd - parsedStart));
        }
        const startTime = performance.now();
        function step(now) {
            const elapsed = now - startTime;
            const t = Math.min(1, elapsed / duration);
            const eased = 1 - Math.pow(1 - t, 3);
            const current = Math.round(parsedStart + (parsedEnd - parsedStart) * eased);
            setGemsInEl(el, current);
            if (t < 1) {
                requestAnimationFrame(step);
            } else {
                setGemsInEl(el, parsedEnd);
            }
        }
        requestAnimationFrame(step);
    }

    // Helper: laat tijdelijk een badge zien met het aantal verdiende gems
    function showGemsBadge(text) {
        let badge = document.getElementById('duo-gems-badge');
        const gemsEl = document.getElementById('duo-gems');
        if (!badge) {
            badge = document.createElement('span');
            badge.id = 'duo-gems-badge';
            badge.style.display = 'inline-block';
            badge.style.position = 'absolute';
            badge.style.left = '-6.1em';
            badge.style.top = '50%';
            badge.style.transform = 'translateY(-50%) scale(0.82)';
            badge.style.display = 'flex';
            badge.style.alignItems = 'center';
            badge.style.justifyContent = 'center';
            badge.style.fontWeight = 'bold';
            badge.style.fontSize = '0.92rem';
            badge.style.padding = '0.08em 0.45em 0.08em 0.32em';
            badge.style.borderRadius = '1em';
            badge.style.background = 'linear-gradient(90deg,#00ff7b 60%,#00e6e6 100%)';
            badge.style.color = '#0a3d1a';
            badge.style.boxShadow = '0 0 12px 2px #00ff7b88, 0 2px 8px rgba(0,0,0,0.10)';
            badge.style.textShadow = '0 1px 6px #fff, 0 0 2px #00ff7b';
            badge.style.transition = 'opacity 0.4s, transform 0.5s cubic-bezier(.2,1.5,.5,1)';
            badge.style.opacity = '0';
            badge.style.pointerEvents = 'none';
            if (gemsEl && gemsEl.parentElement) {
                gemsEl.parentElement.style.position = 'relative';
                gemsEl.parentElement.appendChild(badge);
            }
        }
        // Maak de inhoud: getal en gem-emoji apart voor betere uitlijning
        badge.innerHTML = '';
        const numSpan = document.createElement('span');
        numSpan.textContent = text;
        numSpan.style.display = 'inline-block';
        numSpan.style.marginRight = '0.18em';
                        // Gebruik weer de emoji 💎
                        const gemSpan = document.createElement('span');
                        gemSpan.textContent = '💎';
                        gemSpan.style.display = 'inline-block';
                        gemSpan.style.fontSize = '1.1em';
                        gemSpan.style.lineHeight = '1';
                        gemSpan.style.verticalAlign = 'middle';
                    gemSpan.style.marginLeft = '0.08em';
                    gemSpan.style.marginBottom = '0.18em';
                        badge.appendChild(numSpan);
                        badge.appendChild(gemSpan);
        badge.style.opacity = '1';
        badge.style.transform = 'translateY(-50%) scale(1.18)';
        // Badge links van gemsEl, totaal blijft op z'n plek
        setTimeout(() => {
            badge.style.transform = 'translateY(-90%) scale(1.0)';
            badge.style.opacity = '0';
        }, 1200);
    }

    // helpers voor gems API
    async function addGems(amount) {
        if (!amount || amount <= 0) return;
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = tokenMeta ? tokenMeta.getAttribute('content') : null;
        try {
            const routeMeta = document.querySelector('meta[name="route-gems-add"]');
            const gemsAddUrl = routeMeta ? routeMeta.getAttribute('content') : '/gems/add';
            const res = await fetch(gemsAddUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    ...(csrf ? {'X-CSRF-TOKEN': csrf} : {}),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ amount })
            });
            if (!res.ok) return;
            const data = await res.json();
            if (data && typeof data.gems !== 'undefined') {
                // Gebruik animatie zoals bij wheel, en badge
                const visible = getGemsFromEl(gemsEl);
                const from = (typeof data.previous === 'number') ? Math.max(visible, data.previous) : visible;
                animateGems(gemsEl, from, Number(data.gems || 0), 900, true);
            }
        } catch (e) {
            // optioneel: feedback
        }
    }

    // queue of DOM nodes
    let queue = questions.slice();
    const total = questions.length;
    let correctCount = 0; // totaal goed beantwoord (einde)
    let firstTryCorrectCount = 0; // goed op eerste poging
    const wrongQuestionIds = new Set(); // unieke vragen die ooit fout gingen
    let totalGemsEarned = 0; // totaal verdiende gems tijdens quiz

    // Probeer voortgang te laden
    loadProgress();

    const progressEl = document.getElementById('duo-progress');
    const progressText = document.getElementById('duo-progress-text');
    const finishedEl = document.getElementById('duo-finished');

    function updateProgress() {
        const pct = Math.round((correctCount / total) * 100);
        if (progressEl) progressEl.style.width = pct + '%';
        if (progressText) progressText.textContent = (total - queue.length) + '/' + total;
    }

    function hideAll() { questions.forEach(q => { q.style.display = 'none'; q.classList.remove('duo-visible'); }); }

    function showCurrent() {
        hideAll();
        if (queue.length === 0) {
            finish();
            // verberg herhaling-indicator als quiz klaar
            const globalHelper = document.getElementById('duo-global-helper');
            if (globalHelper) { globalHelper.textContent = ''; globalHelper.classList.remove('duo-repeated'); }
            // wis voortgang bij afronden
            localStorage.removeItem(LS_KEY);
            return;
        }
        const cur = queue[0];
    cur.style.display = '';
        // force reflow before adding visibility class for animation
        const core = cur.querySelector('.duo-question-core');
        if (core) void core.offsetWidth;
        cur.classList.add('duo-visible');
        // reset visuals
        cur.querySelectorAll('.duo-choice').forEach(c => {
            c.classList.remove('selected','bg-green','bg-red');
            c.disabled = false;
            c.style.pointerEvents = '';
        });
        const feedback = cur.querySelector('.feedback');
        if (feedback) { feedback.classList.add('hidden'); feedback.textContent = ''; }
        // Toon herhaling-indicator alleen in globale helper
        const globalHelper = document.getElementById('duo-global-helper');
        if (globalHelper) {
            if (cur.dataset.repeated) {
                globalHelper.textContent = 'herhaling';
                globalHelper.classList.add('duo-repeated');
            } else {
                globalHelper.textContent = '';
                globalHelper.classList.remove('duo-repeated');
            }
        }
    const check = cur.querySelector('.duo-check, #duo-check');
    const next = cur.querySelector('.duo-next, #duo-next');
    if (check) { check.disabled = true; check.classList.add('duo-btn-disabled'); }
    if (next) { next.classList.add('hidden'); }
        updateProgress();
    }

    function finish() {
        hideAll();
        if (finishedEl) finishedEl.classList.remove('hidden');
        if (progressEl) progressEl.style.width = '100%';
        // Geen automatische opslag/redirect; gebruiker klikt op "Opslaan resultaat"
        const saveBtn = document.getElementById('duo-save-result');
        if (saveBtn) {
            let saving = false;
            saveBtn.addEventListener('click', async () => {
                if (saving) return;
                saving = true;
                saveBtn.disabled = true;
                const originalText = saveBtn.textContent;
                saveBtn.textContent = 'Opslaan...';
                const ok = await submitResultToServer();
                if (ok && typeof ok.result_id !== 'undefined') {
                    saveBtn.textContent = 'Opgeslagen! Terug naar dashboard...';
                    saveBtn.disabled = true;
                    try { window.location.assign('/dashboard'); } catch(_) {}
                } else {
                    saveBtn.textContent = 'Opslaan mislukt, opnieuw proberen';
                    saveBtn.disabled = false;
                }
                localStorage.removeItem(LS_KEY);
            });
        }
    }

    async function submitResultToServer() {
        try {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = tokenMeta ? tokenMeta.getAttribute('content') : null;
            const slug = getQuizSlug();
            const timeTakenSeconds = Math.max(0, Math.round((Date.now() - quizStartMs) / 1000));

            const url = `/quiz/complete/${encodeURIComponent(slug)}`;
            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    correct_answers: firstTryCorrectCount, // alleen 1e-poging goed telt als goed
                    wrong_answers: wrongQuestionIds.size, // uniek aantal fout-beantwoorde vragen
                    time_taken: timeTakenSeconds,
                    gems_earned: totalGemsEarned
                })
            });
            if (!res.ok) return false;
            const data = await res.json().catch(() => null);
            return data || true;
        } catch (e) {
            return false;
        }
    }

    // handlers for each question block
    questions.forEach(q => {
        const choices = Array.from(q.querySelectorAll('.duo-choice'));
        const checkBtn = q.querySelector('.duo-check, #duo-check');
        const nextBtn = q.querySelector('.duo-next, #duo-next');
        const feedback = q.querySelector('.feedback');

        // choice click: select (no feedback)
        choices.forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (queue.length === 0) return;
                if (q !== queue[0]) return;
                // mark selected
                choices.forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                // enable check
                if (checkBtn) {
                    checkBtn.disabled = false;
                    checkBtn.classList.remove('duo-btn-disabled');
                }
            });
        });

        // CHECK: show feedback, update queue, gems, save progress
        if (checkBtn) {
            checkBtn.addEventListener('click', async () => {
                if (queue.length === 0) return;
                if (q !== queue[0]) return;
                const current = queue[0];
                const selected = current.querySelector('.duo-choice.selected');
                if (!selected) return;
                // lock choices (prevent further clicks while showing feedback)
                current.querySelectorAll('.duo-choice').forEach(c => c.style.pointerEvents = 'none');

                const isCorrect = selected.dataset.isCorrect === 'true';

                let gemsToAdd = 0;
                if (isCorrect) {
                    // Bepaal poging: geen repeated = 1e keer, repeated==1 = 2e keer, anders 0
                    if (!current.dataset.repeated) {
                        gemsToAdd = 100;
                        firstTryCorrectCount += 1; // 1e poging goed
                    } else if (current.dataset.repeated === '1') {
                        gemsToAdd = 50;
                    } else {
                        gemsToAdd = 0;
                    }
                    if (gemsToAdd > 0) {
                        totalGemsEarned += gemsToAdd;
                        await addGems(gemsToAdd);
                    }

                    selected.classList.remove('selected');
                    selected.classList.add('bg-green');
                    queue.shift();
                    correctCount++;
                    delete current.dataset.repeated;
                } else {
                    const correct = current.querySelector('.duo-choice[data-is-correct="true"]');
                    if (feedback) {
                        // Geen tekst meer tonen om hoogteverspringing te voorkomen
                        feedback.classList.add('hidden');
                    }
                    selected.classList.remove('selected');
                    selected.classList.add('bg-red');
                    if (correct) correct.classList.add('bg-green');
                    // move to the end en markeer repeated; noteer uniek fout
                    const moved = queue.shift();
                    wrongQuestionIds.add(moved.dataset.id);
                    if (!moved.dataset.repeated) {
                        moved.dataset.repeated = '1';
                    } else {
                        moved.dataset.repeated = '2';
                    }
                    queue.push(moved);
                }

                // show next button
                if (nextBtn) {
                    nextBtn.classList.remove('hidden');
                    nextBtn.style.opacity = '1';
                    nextBtn.textContent = queue.length === 0 ? 'VOLTOOIEN' : 'VOLGENDE';
                }
                // disable check
                checkBtn.disabled = true;
                checkBtn.classList.add('duo-btn-disabled');
                updateProgress();
                saveProgress();
            });
        }

        // NEXT: show next current (which may be the same if question was moved)
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (queue.length === 0) return finish();
                showCurrent();
                saveProgress();
            });
        }
    });

    // initial gems tonen (indien nodig)
    if (gemsEl) {
        setGemsInEl(gemsEl, getGemsFromEl(gemsEl));
    }

    // initial show
    showCurrent();
});
