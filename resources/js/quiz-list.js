document.addEventListener('DOMContentLoaded', () => {
    const pills = Array.from(document.querySelectorAll('.ql-quiz-pill'));
    const numEl = document.getElementById('ql-prev-num');
    const titleEl = document.getElementById('ql-prev-title');
    const chapEl = document.getElementById('ql-prev-chapter');
    const linkEl = document.getElementById('ql-prev-link');
    const dropdown = document.querySelector('.ql-dropdown');
    const dropBtn = document.querySelector('.ql-drop-btn');
    const mobileToggle = document.querySelector('.ql-mobile-toggle');
    const sidebar = document.getElementById('ql-sidebar');
    const overlay = document.querySelector('.ql-mobile-overlay');

    function updateFrom(el) {
        if (!el) return;
        const num = el.getAttribute('data-num') || '--';
        const title = el.getAttribute('data-title') || '';
        const chapter = el.getAttribute('data-chapter') || '';
        if (numEl) numEl.textContent = num;
        if (titleEl) titleEl.textContent = title;
        if (chapEl) chapEl.textContent = chapter;
        if (linkEl) linkEl.href = el.getAttribute('data-href') || '#';
    }

    // prevent navigation on side pills; they are selectors only
    pills.forEach(p => {
        p.addEventListener('click', (e) => { e.preventDefault(); updateFrom(p); });
        p.addEventListener('keydown', (e) => {
            // Enter or Space updates preview
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                updateFrom(p);
            }
        });
        p.addEventListener('focus', () => updateFrom(p));
    });

    if (pills.length) updateFrom(pills[0]);

    // Dropdown open/close on click only
    if (dropdown && dropBtn) {
        dropBtn.addEventListener('click', (e) => {
            e.preventDefault();
            dropdown.classList.toggle('open');
        });
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target)) dropdown.classList.remove('open');
        });
    }

    // Mobile sidebar toggle
    function toggleSidebar(force) {
        const open = typeof force === 'boolean' ? force : !document.body.classList.contains('ql-sidebar-open');
        document.body.classList.toggle('ql-sidebar-open', open);
        if (mobileToggle) mobileToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open && sidebar) sidebar.focus();
    }
    if (mobileToggle) mobileToggle.addEventListener('click', () => toggleSidebar());
    if (overlay) overlay.addEventListener('click', () => toggleSidebar(false));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.body.classList.contains('ql-sidebar-open')) toggleSidebar(false);
    });
    // Close sidebar when selecting a quiz on mobile
    pills.forEach(p => p.addEventListener('click', () => {
        if (window.innerWidth < 860) toggleSidebar(false);
    }));
});


