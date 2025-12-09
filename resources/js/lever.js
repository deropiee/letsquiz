// detached lever behavior (moved from blade)
document.addEventListener('DOMContentLoaded', function () {
    const lever = document.getElementById('lever-small');
    const spinBtn = document.getElementById('spin-btn');
    if (!lever) return;

    // vergroot bereik zodat de hendel verder naar beneden gaat
    const MAX = 90;
    // trigger als 80% bereikt is
    const THRESH = 80;
    let dragging = false, startY = 0, current = 0, visual = 0, rafId = null;

    // perform spin: prefer clicking spinBtn if present, otherwise redirect via lever data-redirect
    function performSpin() {
        if (spinBtn && typeof spinBtn.click === 'function' && !spinBtn.disabled) {
            try { spinBtn.click(); return; } catch (_) {}
        }
        const url = lever.dataset && lever.dataset.redirect;
        if (url) {
            window.location.href = url;
        }
    }

    function applyTransform(v) {
        lever.style.transform = `translateY(${v}px)`;
    }

    function rafLoop() {
        visual += (current - visual) * 0.25;
        applyTransform(visual);
        if (Math.abs(visual - current) > 0.5) {
            rafId = requestAnimationFrame(rafLoop);
        } else {
            applyTransform(current);
            cancelAnimationFrame(rafId || 0);
            rafId = null;
        }
    }

    function startDrag(clientY, pointerId) {
        if (spinBtn && spinBtn.disabled) return;
        dragging = true; startY = clientY; current = 0; visual = 0;
        // add Tailwind utility classes for dragging state
        lever.classList.add('shadow-[0_10px_30px_rgba(0,0,0,0.22)]', '[transition:none]');
        lever.classList.remove('cursor-grab');
        lever.style.cursor = 'none';

        if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
        try { if (pointerId) lever.setPointerCapture(pointerId); } catch (_) {}
    }

    function moveDrag(clientY) {
        if (!dragging) return;
        const dy = clientY - startY;
        current = Math.max(0, Math.min(MAX, dy));
        applyTransform(current);
    }

    function endDrag(pointerId) {
        if (!dragging) return;
        dragging = false;
        // remove dragging utilities and restore default cursor
        lever.classList.remove('shadow-[0_10px_30px_rgba(0,0,0,0.22)]', '[transition:none]');
        lever.classList.add('cursor-grab');
        lever.style.cursor = '';

        if (current >= THRESH) {
            current = MAX; applyTransform(current);
            try { if (navigator.vibrate) navigator.vibrate(30); } catch (_) {}
            setTimeout(() => {
                performSpin();
                current = 0; if (!rafId) rafId = requestAnimationFrame(rafLoop);
            }, 140);
        } else {
            current = -8;
            if (!rafId) rafId = requestAnimationFrame(rafLoop);
            setTimeout(() => { current = 0; if (!rafId) rafId = requestAnimationFrame(rafLoop); }, 120);
        }
        try { if (pointerId) lever.releasePointerCapture(pointerId); } catch (_) {}
    }

    lever.addEventListener('pointerdown', (e) => { e.preventDefault(); startDrag(e.clientY, e.pointerId); });
    window.addEventListener('pointermove', (e) => { moveDrag(e.clientY); }, { passive: true });
    window.addEventListener('pointerup', (e) => { endDrag(e.pointerId); });
    lever.addEventListener('pointercancel', (e) => { endDrag(e.pointerId); });

    lever.addEventListener('touchstart', (e) => { if (e.touches && e.touches[0]) { e.preventDefault(); startDrag(e.touches[0].clientY); } }, { passive:false });
    window.addEventListener('touchmove', (e) => { if (e.touches && e.touches[0]) moveDrag(e.touches[0].clientY); }, { passive:true });
    window.addEventListener('touchend', () => endDrag());

    lever.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault(); current = MAX; applyTransform(current);
            setTimeout(() => {
                performSpin();
                current = 0; if (!rafId) rafId = requestAnimationFrame(rafLoop);
            }, 140);
        }
    });
 });
