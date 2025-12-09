(function () {
    if (window._wheelInitialized) return;
    window._wheelInitialized = true;

    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('wheel');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const spinBtn = document.getElementById('spin-btn');
        const timerEl = document.getElementById('timer');
    const cdOverlay = document.getElementById('wheel-cooldown-overlay');
    const cdTimeEl = document.getElementById('wheel-cd-time');
    const modal = document.getElementById('reward-modal');
        const rewardText = document.getElementById('reward-text');
        const closeModal = document.getElementById('close-modal');
    const modalBackdrop = modal ? modal.querySelector('[data-close]') : null;
        const gemsEl = document.getElementById('gems');

        // realistic segments based on ~1000 gems per quiz (10 q × 100g)
        const segments = [
            { label: "💎500",   amount: 500,   weight: 25 },
            { label: "💎1000",  amount: 1000,  weight: 20 },
            { label: "💎2000",  amount: 2000,  weight: 15 },
            { label: "💎4000",  amount: 4000,  weight: 12 },
            { label: "💎8000",  amount: 8000,  weight: 10 },
            { label: "💎10000", amount:10000,  weight: 8  }, // ~1 quiz worth
            { label: "💎25000", amount:25000,  weight: 3  }, // rare big win
            { label: "💎100000",amount:100000, weight: 1  }  // very rare jackpot
        ];
        const colors = ["#f44336","#e91e63","#9c27b0","#673ab7","#2196f3","#4caf50","#ff9800","#9c27b0"];
        const wheelRadius = canvas.width / 2;
    // achtergrond (cosmetics overlay techniek)
    const themeOverlay = document.getElementById('theme-overlay');
    const originalThemeColor = getComputedStyle(document.documentElement).getPropertyValue('--user-theme-color').trim();
    let spinBgRevertTimeout = null;

        // audio
        const spinSound = new Audio('/sounds/spin.mp3');
        spinSound.volume = 0.5;
        const rewardSound = new Audio('/sounds/yayyy.mp3');
        rewardSound.volume = 0.5;

        let spinning = false;
        let sendingGems = false;
        let cooldown = 5; // seconds for testing; change to 24*3600 in production
        let cooldownInterval;

        function getNumericValueFromEl(el) {
            if (!el) return 0;
            // prefer explicit inner value element
            const valSpan = el.querySelector && el.querySelector('[data-gems-val]');
            if (valSpan) {
                const digits = (valSpan.textContent || '').replace(/\D/g, '');
                const parsed = parseInt(digits || '0', 10);
                return Number.isNaN(parsed) ? 0 : parsed;
            }
            // fallback to data-gems attribute on container
            const dataVal = el.getAttribute && el.getAttribute('data-gems');
            if (dataVal != null) {
                const n = parseInt(String(dataVal).replace(/\D/g, ''), 10);
                return Number.isNaN(n) ? 0 : n;
            }
            const digits = (el.textContent || '').replace(/\D/g, '');
            const parsed = parseInt(digits || '0', 10);
            return Number.isNaN(parsed) ? 0 : parsed;
        }

        function setNumericText(el, value) {
            if (!el) return;
            const num = Math.max(0, Math.round(Number(value) || 0));
            // update container attribute
            if (el.setAttribute) el.setAttribute('data-gems', String(num));
            // if there's a dedicated inner span, update that and keep surrounding markup
            const valSpan = el.querySelector && el.querySelector('[data-gems-val]');
            if (valSpan) {
                // keep diamond span or other siblings intact
                valSpan.textContent = String(num);
                valSpan.setAttribute('data-gems', String(num));
                return;
            }
            // fallback: update whole element textContent (keeps previous behaviour for simple elements)
            el.textContent = '💎' + String(num);
            if (el.setAttribute) el.setAttribute('data-gems', String(num));
        }

        if (gemsEl) {
            const initial = getNumericValueFromEl(gemsEl);
            setNumericText(gemsEl, initial);
        }

        function drawWheelBase() {
            const segmentAngle = 2 * Math.PI / segments.length;
            for (let i = 0; i < segments.length; i++) {
                const startAngle = i * segmentAngle;
                const endAngle = startAngle + segmentAngle;

                ctx.beginPath();
                ctx.moveTo(wheelRadius, wheelRadius);
                ctx.arc(wheelRadius, wheelRadius, wheelRadius, startAngle, endAngle);
                ctx.fillStyle = colors[i % colors.length];
                ctx.fill();
                ctx.strokeStyle = "#fff";
                ctx.lineWidth = 2;
                ctx.stroke();

                ctx.save();
                ctx.translate(wheelRadius, wheelRadius);
                ctx.rotate(startAngle + segmentAngle / 2);
                ctx.textAlign = "center";
                ctx.fillStyle = "#fff";
                // grotere, vettere tekst voor labels
                ctx.font = "bold 18px Arial";
                // draw label (trim if too long) — verhoogde max width en lineHeight
                const label = segments[i].label;
                wrapText(ctx, label, wheelRadius * 0.7, 0, 110, 18);
                ctx.restore();
            }
        }

        // helper to draw multi-line text on canvas
        function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
            const words = text.split(' ');
            let line = '';
            const lines = [];
            for (let n = 0; n < words.length; n++) {
                const testLine = line + words[n] + ' ';
                const metrics = ctx.measureText(testLine);
                if (metrics.width > maxWidth && n > 0) {
                    lines.push(line.trim());
                    line = words[n] + ' ';
                } else {
                    line = testLine;
                }
            }
            lines.push(line.trim());
            const offset = (lines.length - 1) * - (lineHeight / 2);
            for (let i = 0; i < lines.length; i++) {
                ctx.fillText(lines[i], x, y + offset + i * lineHeight);
            }
        }

        function drawRotatedWheel(angle) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.translate(wheelRadius, wheelRadius);
            ctx.rotate(angle);
            ctx.translate(-wheelRadius, -wheelRadius);
            drawWheelBase();
            ctx.restore();
        }

        drawWheelBase();

        function animateNumber(el, start, end, duration = 800) {
            const parsedStart = Number(start) || 0;
            const parsedEnd = Number(end) || 0;
            if (parsedStart === parsedEnd) {
                setNumericText(el, parsedEnd);
                return;
            }
            const startTime = performance.now();
            function step(now) {
                const elapsed = now - startTime;
                const t = Math.min(1, elapsed / duration);
                const eased = 1 - Math.pow(1 - t, 3);
                const current = Math.round(parsedStart + (parsedEnd - parsedStart) * eased);
                setNumericText(el, current);
                if (t < 1) requestAnimationFrame(step);
                else setNumericText(el, parsedEnd);
            }
            requestAnimationFrame(step);
        }

        // weighted random selection
        function weightedIndex(items) {
            const total = items.reduce((s, it) => s + (it.weight || 0), 0);
            const r = Math.random() * total;
            let acc = 0;
            for (let i = 0; i < items.length; i++) {
                acc += items[i].weight || 0;
                if (r <= acc) return i;
            }
            return items.length - 1;
        }

        function spinWheel() {
            if (spinning) return;
            spinning = true;
            if (spinBtn) spinBtn.disabled = true;

            // pick winning segment by weight (ensures realistic odds)
            const winningIndex = weightedIndex(segments);

            // compute finalAngle so pointer lands on winningIndex
            const segmentAngle = 2 * Math.PI / segments.length;
            const spins = Math.floor(Math.random() * 3) + 4; // 4..6 full rotations
            // choose offset inside segment (avoid exact edges)
            const offset = segmentAngle * (0.25 + Math.random() * 0.5);
            // angle such that indexFromAngle(finalAngle) === winningIndex
            const finalAngle = spins * 2 * Math.PI + (2 * Math.PI - (winningIndex * segmentAngle + offset));

            // determine animation duration
            const audioMs = (spinSound.duration && !Number.isNaN(spinSound.duration) && spinSound.duration > 0)
                ? spinSound.duration * 1000
                : 4200;
            const duration = Math.max(3000, audioMs);

            try { spinSound.currentTime = 0; spinSound.play().catch(()=>{}); } catch(e){}

            // Start achtergrond-updates gebaseerd op actuele segment kleur (smooth interpolation)
            spinBgActive = true;
            spinBgEndTime = performance.now() + duration;
            displayedHue = null;
            lastHueTime = performance.now();

            const startTime = performance.now();
            function animateFrame(now) {
                const elapsed = now - startTime;
                const easeOut = 1 - Math.pow(1 - Math.min(1, elapsed / duration), 3);
                const angle = finalAngle * easeOut;
                drawRotatedWheel(angle);
                updateSpinBackgroundFromAngle(angle, easeOut, now);
                if (elapsed < duration) requestAnimationFrame(animateFrame);
                else finishSpin(finalAngle, winningIndex);
            }
            requestAnimationFrame(animateFrame);
        }

        // original indexFromAngle kept for robustness
        function indexFromAngle(angle) {
            const segmentAngle = 2 * Math.PI / segments.length;
            const a = ((2 * Math.PI - (angle % (2 * Math.PI))) + (2 * Math.PI)) % (2 * Math.PI);
            const idx = Math.floor(a / segmentAngle) % segments.length;
            return idx;
        }

        function finishSpin(finalAngle, forcedIndex) {
            spinning = false;
            if (spinBtn) spinBtn.disabled = true;

            drawRotatedWheel(finalAngle);

            const winningIndex = (typeof forcedIndex === 'number') ? forcedIndex : indexFromAngle(finalAngle);
            const seg = segments[winningIndex];
            const reward = seg.label;
            const amount = seg.amount;

            rewardText.textContent = reward;
            try { rewardSound.currentTime = 0; rewardSound.play().catch(()=>{}); } catch(e){}

            // Opslaan van de spin in de database
            try {
                window.recordSpinResult?.({
                    name: 'Wheelspin',
                    amount: amount,
                    result: reward,
                    is_jackpot: typeof amount === 'number' ? amount >= 100000 : false
                });
            } catch (_) {}

            if (modal) {
                modal.classList.remove('hidden');
                modal.removeAttribute('aria-hidden');
                setTimeout(() => { try { closeModal && closeModal.focus(); } catch(_){} }, 40);
            }
            showRewardEffects();

            if (!Number.isNaN(amount) && amount > 0) {
                sendGemsToServer(amount);
            } else {
                console.warn('Geen valide amount in segment', seg);
            }

            startCooldown();
            updateSpinBackgroundFromAngle(finalAngle, 1, performance.now(), true);
            stopSpinBackgroundCycle();
        }

        function showRewardEffects() {
            // Vibratie (extra feedback)
            try { if (navigator.vibrate) navigator.vibrate([25, 40, 25]); } catch(_) {}

            const container = document.getElementById('confetti-global');
            if (!container) return;
            container.innerHTML = '';
            const colors = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444'];
            const pieces = 42;
            for (let i = 0; i < pieces; i++) {
                const el = document.createElement('div');
                el.className = 'ws-confetti-piece';
                const color = colors[i % colors.length];
                el.style.backgroundColor = color;
                el.style.left = (Math.random() * 100) + '%';
                el.style.animationDelay = (Math.random() * 0.35) + 's';
                el.style.animationDuration = (4 + Math.random() * 2) + 's';
                el.style.setProperty('--rz', (Math.random()*720 - 360) + 'deg');
                el.style.width = (6 + Math.random()*6) + 'px';
                el.style.height = (10 + Math.random()*10) + 'px';
                container.appendChild(el);
            }
        }

        // Nieuwe achtergrondlogica: continue hue interpolatie op basis van segment onder de pijl
        let spinBgActive = false;
        let spinBgEndTime = 0;
        let displayedHue = null; // huidige weergegeven hue (float)
        let lastHueTime = 0;
        function updateSpinBackgroundFromAngle(angle, progress, now, force=false){
            if (!spinBgActive || !themeOverlay) return;
            if (!now) now = performance.now();
            // Huidig segment → basiskleur
            const idx = indexFromAngle(angle);
            const segColor = colors[idx % colors.length];
            const baseHue = rgbToHslHue(segColor);
            // Extra dynamiek: kleine shift die afneemt naar einde
            const dynamicShift = (1 - progress) * 55; // groter bereik aan begin
            const targetHue = (baseHue + dynamicShift) % 360;
            if (displayedHue === null || force) {
                displayedHue = targetHue;
            }
            // tijd since last frame
            const dt = Math.min(120, now - lastHueTime || 16);
            lastHueTime = now;
            // adaptiesnelheid (sneller aan begin, trager aan eind voor stabiele landing)
            const baseFactor = 0.38 + (1 - progress) * 0.42; // 0.8 aan begin -> 0.38 aan eind
            // fps-onafhankelijke smoothing
            const factor = 1 - Math.pow(1 - baseFactor, dt / 16.6667);
            // kortste kant op draaien over de kleurcirkel
            let delta = ((targetHue - displayedHue + 540) % 360) - 180; // bereik [-180,180]
            displayedHue = (displayedHue + delta * factor + 360) % 360;

            const hue = displayedHue;
            const sat = 80 + (1 - progress) * 8; // iets meer saturatie aan begin
            const light = 55 + Math.sin(now/1500)*2; // subtiele luminantie oscillatie
            const baseHex = hslToHex(hue, sat, light);
            const lighter = hslToHex((hue+14)%360, sat+5, Math.min(72, light+10));
            const darker = hslToHex((hue+320)%360, sat+10, Math.max(38, light-14));
            themeOverlay.style.transition = 'none';
            themeOverlay.style.background = `radial-gradient(circle at 42% 32%, ${lighter} 0%, ${baseHex} 55%, ${darker} 96%)`;
            // zachtere, subtielere adem (amplitude omlaag, langere periode)
            themeOverlay.style.opacity = (0.50 + Math.sin(now/1800)*0.02).toFixed(3);
            document.documentElement.style.setProperty('--user-theme-color', baseHex);
        }
        function stopSpinBackgroundCycle(){
            if (!themeOverlay) { spinBgActive = false; return; }
            // Start een langzame fade terug naar originele thema kleur door hue interpolatie
            spinBgActive = false;
            const finalHue = displayedHue != null ? displayedHue : rgbToHslHue(originalThemeColor || '#f3f4f6');
            const targetHue = rgbToHslHue(originalThemeColor || '#f3f4f6');
            const startSat = 80; const endSat = 70;
            const startLight = 55; const endLight = 60;
            const fadeDuration = 1600; // ms
            const startTime = performance.now();
            function lerp(a,b,t){ return a + (b-a)*t; }
            function shortestHue(a,b){ let d=(b-a)%360; if(d<-180)d+=360; else if(d>180)d-=360; return a + d; }
            const hueTargetAdj = shortestHue(finalHue, targetHue);
            function fade(now){
                const t = Math.min(1, (now - startTime)/fadeDuration);
                const ease = t<0.5 ? 4*t*t*t : 1-Math.pow(-2*t+2,3)/2;
                const h = (finalHue + (hueTargetAdj - finalHue)*ease + 360) % 360;
                const sat = lerp(startSat,endSat,ease);
                const l = lerp(startLight,endLight,ease);
                const baseHex = hslToHex(h, sat, l);
                const lighter = hslToHex((h+14)%360, sat+4, Math.min(75, l+8));
                const darker = hslToHex((h+320)%360, sat+6, Math.max(40, l-10));
                themeOverlay.style.transition = 'none';
                themeOverlay.style.background = `radial-gradient(circle at 42% 32%, ${lighter} 0%, ${baseHex} 55%, ${darker} 96%)`;
                themeOverlay.style.opacity = (0.50 + (1-ease)*0.04).toFixed(3);
                document.documentElement.style.setProperty('--user-theme-color', baseHex);
                if (t < 1) requestAnimationFrame(fade); else {
                    // finalize naar exacte originele kleur
                    document.documentElement.style.setProperty('--user-theme-color', originalThemeColor || '#f3f4f6');
                    themeOverlay.style.transition = 'background .4s ease, opacity .4s ease';
                    themeOverlay.style.background = originalThemeColor || '#f3f4f6';
                    themeOverlay.style.opacity = '.55';
                }
            }
            requestAnimationFrame(fade);
        }
        function easeInOut(x){ return x<0.5 ? 4*x*x*x : 1-Math.pow(-2*x+2,3)/2; }
        function hexToRgb(h){ h = h.replace('#',''); if (h.length===3) h = h.split('').map(c=>c+c).join(''); const num = parseInt(h,16); return {r:(num>>16)&255,g:(num>>8)&255,b:num&255}; }
        function rgbToHex(r,g,b){ return '#'+[r,g,b].map(v=> v.toString(16).padStart(2,'0')).join(''); }
        function lightenColor(hex, amt){ const c=hexToRgb(hex); const r=Math.min(255,Math.round(c.r+ (255-c.r)*amt)); const g=Math.min(255,Math.round(c.g+ (255-c.g)*amt)); const b=Math.min(255,Math.round(c.b+ (255-c.b)*amt)); return rgbToHex(r,g,b); }
        function hslToHex(h,s,l){ // s,l in %
            s/=100; l/=100;
            const k=n=> (n + h/30)%12;
            const a=s*Math.min(l,1-l);
            const f=n=> l - a*Math.max(-1, Math.min(k(n)-3, Math.min(9-k(n),1)));
            return '#'+[f(0),f(8),f(4)].map(v=>{ const val=Math.round(v*255).toString(16).padStart(2,'0'); return val; }).join('');
        }
        function rgbToHslHue(hex){
            const {r,g,b} = hexToRgb(hex);
            const rn=r/255, gn=g/255, bn=b/255;
            const max=Math.max(rn,gn,bn), min=Math.min(rn,gn,bn);
            let h=0; const d=max-min;
            if (d===0) h=0; else if (max===rn) h=((gn-bn)/d)%6; else if (max===gn) h=((bn-rn)/d)+2; else h=((rn-gn)/d)+4;
            h=Math.round(h*60); if (h<0) h+=360; return h;
        }

        function sendGemsToServer(amount) {
            if (sendingGems) {
                console.warn('sendingGems in progress, skipping');
                return;
            }
            sendingGems = true;

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = tokenMeta ? tokenMeta.getAttribute('content') : null;

            fetch('/gems/add', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    ...(csrf ? {'X-CSRF-TOKEN': csrf} : {}),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ amount })
            }).then(async res => {
                const data = await res.json().catch(()=>null);
                if (!res.ok) {
                    console.error('Server fout bij opslaan gems', res.status, data);
                    rewardText.textContent += ' (Kon niet worden opgeslagen)';
                    return;
                }
                if (data && data.success) {
                    rewardText.textContent = `${data.added} Gems toegevoegd!`;
                    if (gemsEl) {
                        const visible = getNumericValueFromEl(gemsEl);
                        const from = (typeof data.previous === 'number') ? Math.max(visible, data.previous) : visible;
                        animateNumber(gemsEl, from, Number(data.gems || 0), 900);
                    }
                } else {
                    console.warn('Onverwacht antwoord van server', data);
                }
            }).catch(err => {
                console.error('Netwerkfout bij sturen gems:', err);
                rewardText.textContent += ' (Netwerkfout)';
            }).finally(() => {
                sendingGems = false;
            });
        }

        function hideModal() {
            if (!modal) return;
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden','true');
        }
        if (closeModal) {
            closeModal.addEventListener('click', hideModal);
            closeModal.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); hideModal(); }
            });
        }
        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', hideModal);
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                hideModal();
            }
        });

        function formatHMS(totalSeconds){
            const h = Math.floor(totalSeconds/3600);
            const m = Math.floor((totalSeconds%3600)/60);
            const s = totalSeconds%60;
            const hh = String(h).padStart(2,'0');
            const mm = String(m).padStart(2,'0');
            const ss = String(s).padStart(2,'0');
            return {text:`${hh}:${mm}:${ss}`, h,m,s};
        }

        function setCooldownUI(active, secondsLeft){
            if (active){
                if (cdOverlay){ cdOverlay.classList.add('active'); }
                if (canvas){ canvas.classList.add('is-dimmed'); }
                if (typeof secondsLeft === 'number'){
                    const {text,h,m,s} = formatHMS(secondsLeft);
                    if (cdTimeEl) cdTimeEl.textContent = text;
                    // small textual fallback below wheel (existing timerEl)
                    if (timerEl) timerEl.textContent = `Volgende draai in: ${h}h ${m}m ${s}s`;
                }
            } else {
                if (cdOverlay){ cdOverlay.classList.remove('active'); }
                if (canvas){ canvas.classList.remove('is-dimmed'); }
                if (cdTimeEl) cdTimeEl.textContent = '';
                if (timerEl) timerEl.textContent = '';
            }
        }

        function runCooldown(endTime) {
            clearInterval(cooldownInterval);
            cooldownInterval = setInterval(() => {
                const now = Date.now();
                let timeLeft = Math.max(0, Math.ceil((endTime - now) / 1000));
                if (timeLeft <= 0) {
                    clearInterval(cooldownInterval);
                    if (spinBtn) spinBtn.disabled = false;
                    setCooldownUI(false);
                    localStorage.removeItem('wheelCooldownEnd');
                } else {
                    if (spinBtn) spinBtn.disabled = true;
                    setCooldownUI(true, timeLeft);
                }
            }, 1000);
            // immediate paint
            const firstLeft = Math.max(0, Math.ceil((endTime - Date.now())/1000));
            setCooldownUI(true, firstLeft);
        }

        function startCooldown() {
            const endTime = Date.now() + cooldown * 1000;
            localStorage.setItem('wheelCooldownEnd', String(endTime));
            runCooldown(endTime);
        }

        (function checkOnLoad() {
            const endTime = parseInt(localStorage.getItem('wheelCooldownEnd') || '0', 10);
            if (endTime && endTime > Date.now()) {
                runCooldown(endTime);
            } else {
                if (spinBtn) spinBtn.disabled = false;
                localStorage.removeItem('wheelCooldownEnd');
                setCooldownUI(false);
            }
        })();

        if (spinBtn) spinBtn.addEventListener('click', spinWheel);
    });

    // Record spin helper en event-bridge
    document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const url = document.querySelector('meta[name="spins-store-url"]')?.getAttribute('content') || '/spins';

    window.recordSpinResult = function (payload) {
        fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload || {})
        })
        .then(r => r.json())
        .then(j => console.log('Spin opgeslagen', j))
        .catch(err => console.error('Spin opslaan fout', err));
    };

    // Luister op custom event van je wheel script
    window.addEventListener('wheelspin:result', (e) => {
        if (e?.detail) window.recordSpinResult(e.detail);
    });
    });
})();
