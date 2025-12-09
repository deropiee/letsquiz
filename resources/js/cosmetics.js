// Extracted from cosmetics.blade.php inline script
// This file attaches behaviour only if the cosmetics form exists on the page.

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('cosmetics-form');
  if (!form) return; // Not on cosmetics page

  // User id (voor onderscheid custom uploads)
  const userId = form.getAttribute('data-user-id') || '';

  // Read pricing data passed via data-* attributes (eliminates need for @json in JS bundle)
  let avatarPrices = {};
  let colorPrices = {};
  try { avatarPrices = JSON.parse(form.getAttribute('data-avatar-prices') || '{}'); } catch(e) {}
  try { colorPrices = JSON.parse(form.getAttribute('data-theme-color-prices') || '{}'); } catch(e) {}

  let lastAppliedThemeColor = (getComputedStyle(document.documentElement).getPropertyValue('--user-theme-color') || '').trim();
  let currentEquippedColor = (document.querySelector('input[name="theme_color"][checked]')?.value) || lastAppliedThemeColor;
  let currentEquippedAvatar = (() => {
    const checked = document.querySelector('input[name="avatar"][checked]');
    if (checked) return checked.value === '__default' ? null : checked.value;
    const any = document.querySelector('input[name="avatar"]');
    return any ? (any.value === '__default' ? null : any.value) : null;
  })();

  let previewState = null;
  let csrf = '';
  const meta = document.querySelector('meta[name="csrf-token"]');
  if (meta) csrf = meta.getAttribute('content');
  if (!csrf) {
    const hidden = form.querySelector('input[name=_token]');
    if (hidden) csrf = hidden.value;
  }

  const gemsDesktop = document.querySelector('#gems [data-gems-val]');
  const gemsMobile = document.querySelector('#gems-mobile [data-gems-val]');
  const lockSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 10V8a4 4 0 118 0v2"/><rect x="5" y="10" width="14" height="11" rx="2"/><circle cx="12" cy="15.5" r="1.5"/></svg>';

  // helpers (gekopieerd uit wheel.js voor consistente animatie)
  function setNumericText(containerEl, value) {
    if (!containerEl) return;
    const num = Math.max(0, Math.round(Number(value) || 0));
    // container attribute
    if (containerEl.setAttribute) containerEl.setAttribute('data-gems', String(num));
    // inner span
    const span = containerEl.querySelector && containerEl.querySelector('[data-gems-val]');
    if (span) {
      span.textContent = String(num);
      span.setAttribute('data-gems', String(num));
      return;
    }
    containerEl.textContent = '💎' + String(num);
  }

  function animateNumber(containerEl, start, end, duration = 800) {
    const parsedStart = Number(start) || 0;
    const parsedEnd = Number(end) || 0;
    if (parsedStart === parsedEnd) { setNumericText(containerEl, parsedEnd); return; }
    const startTime = performance.now();
    function step(now) {
      const elapsed = now - startTime;
      const t = Math.min(1, elapsed / duration);
      const eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
      const current = Math.round(parsedStart + (parsedEnd - parsedStart) * eased);
      setNumericText(containerEl, current);
      if (t < 1) requestAnimationFrame(step); else setNumericText(containerEl, parsedEnd);
    }
    requestAnimationFrame(step);
  }

  function showFlash() { /* notifications disabled */ }

  function toggleActiefBadge(targetEl, isActive) {
    if (!targetEl) return;
    const container = targetEl.closest('.relative');
    if (!container) return;
    let badge = container.querySelector('.actief-badge');
    if (isActive) {
      if (!badge) {
        badge = document.createElement('span');
        badge.className = 'actief-badge absolute -top-2 -right-2 rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white text-[10px] px-1.5 py-0.5 shadow';
        badge.textContent = 'Actief';
        container.appendChild(badge);
      }
    } else if (badge) {
      badge.remove();
    }
  }

  function applyThemeColor(color) {
    if (!color || color === lastAppliedThemeColor) return;
    document.documentElement.style.setProperty('--user-theme-color', color);
    const overlay = document.getElementById('theme-overlay');
    if (overlay) {
      overlay.animate([{ opacity:.35 }, { opacity:.55 }], { duration:450, easing:'ease-out' });
    }
    lastAppliedThemeColor = color;
  }

  function updateUI(data) {
    if (data.gems !== undefined) {
      const newVal = Number(data.gems) || 0;
      if (gemsDesktop) {
        const current = Number(gemsDesktop.getAttribute('data-gems') || gemsDesktop.textContent || 0) || 0;
        animateNumber(gemsDesktop.closest('#gems') || gemsDesktop.parentElement || gemsDesktop, current, newVal, 700);
      }
      if (gemsMobile) {
        const currentM = Number(gemsMobile.getAttribute('data-gems') || gemsMobile.textContent || 0) || 0;
        animateNumber(gemsMobile.closest('#gems-mobile') || gemsMobile.parentElement || gemsMobile, currentM, newVal, 700);
      }
    }
  const navImg = document.getElementById('nav-avatar-img');
  const navFallback = document.getElementById('nav-avatar-fallback');
  const navImgMobile = document.getElementById('nav-avatar-img-mobile');
  const navFallbackMobile = document.getElementById('nav-avatar-fallback-mobile');
    if (data.avatar) {
      if (navImg) {
        if (!navImg.src.endsWith('/'+data.avatar)) navImg.src = '/images/avatars/' + data.avatar;
        navImg.classList.remove('hidden');
      }
      if (navFallback) navFallback.classList.add('hidden');
      if (navImgMobile) {
        if (!navImgMobile.src.endsWith('/'+data.avatar)) navImgMobile.src = '/images/avatars/' + data.avatar;
        navImgMobile.classList.remove('hidden');
      }
      if (navFallbackMobile) navFallbackMobile.classList.add('hidden');
    } else {
      if (navImg) navImg.classList.add('hidden');
      if (navFallback) navFallback.classList.remove('hidden');
      if (navImgMobile) navImgMobile.classList.add('hidden');
      if (navFallbackMobile) navFallbackMobile.classList.remove('hidden');
    }
    const purchasedAvatars = data.purchased_avatars || [];
    const purchasedColors = data.purchased_theme_colors || [];
    const currentAvatar = data.avatar;
    const currentColor = data.theme_color; // can be null
    const effectiveColor = currentColor || '#f3f4f6';
    if (effectiveColor && effectiveColor !== lastAppliedThemeColor) {
      applyThemeColor(effectiveColor);
    }
    currentEquippedColor = effectiveColor;
    currentEquippedAvatar = (currentAvatar === null || currentAvatar === '' ? null : currentAvatar);
    if (previewState) closePreview(false);

    // Avatars
    document.querySelectorAll('label[data-type="avatar"]').forEach(label => {
      const val = label.dataset.value;
      const img = label.querySelector('img');
      const isDefault = val === '__default';
      const isCustom = !isDefault && userId && val.startsWith('user_' + userId + '_');
      const badgeContainer = label.querySelector('.badge-slot');
      const overlay = label.querySelector('.price-overlay');
      const effectiveCurrent = currentAvatar === undefined ? null : currentAvatar;
      const active = (isDefault && (effectiveCurrent === null || effectiveCurrent === '' )) || (!isDefault && val === effectiveCurrent);
      const owned = isDefault ? true : purchasedAvatars.includes(val);
      const card = (img || label.querySelector('div.w-16.h-16'))?.closest('.relative.flex');
      if (img) {
        img.className = img.className.replace(/ring-[^ ]+|opacity-70/g,'');
        img.classList.remove('ring-4','ring-2','ring-indigo-500','ring-emerald-400','ring-transparent','opacity-70');
        if (active) img.classList.add('ring-4','ring-indigo-500');
        else if (owned) img.classList.add('ring-2','ring-emerald-400');
        else img.classList.add('ring-2','ring-transparent','opacity-70');
      }
      if (card) {
        card.classList.remove('border-indigo-400','ring-2','ring-indigo-300','shadow-md','border-emerald-300','border-gray-200');
        if (active) card.classList.add('border-indigo-400','ring-2','ring-indigo-300','shadow-md');
        else if (owned) card.classList.add('border-emerald-300');
        else card.classList.add('border-gray-200');
      }
      if (badgeContainer) {
        if (active) {
          badgeContainer.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold shadow">Gekozen</span>';
        } else if (isDefault) {
          badgeContainer.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-400/90 text-white font-medium shadow-sm">Standaard</span>';
        } else if (isCustom) {
          badgeContainer.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-indigo-500/90 text-white font-medium shadow-sm">Eigen</span>';
        } else if (owned) {
          badgeContainer.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-500/90 text-white font-medium shadow-sm">Gekocht</span>';
        } else {
          badgeContainer.innerHTML = `<span class=\"px-2 py-0.5 text-[11px] rounded-full bg-amber-500/90 text-white font-semibold shadow flex items-center gap-0.5\"><span class='text-[10px]'>💎</span>${avatarPrices[val] ?? ''}</span>`;
        }
      }
      if (owned && overlay) overlay.remove();
      if (!owned && !overlay && !isDefault && img) {
        const o = document.createElement('div');
        o.className = 'price-overlay absolute inset-0 rounded-full bg-gradient-to-br from-black/40 to-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white tracking-wide transition-all';
        o.innerHTML = lockSvg;
        img.parentElement.appendChild(o);
      }
      toggleActiefBadge(img || label.querySelector('div.w-16.h-16'), active);
    });

    // Theme colors
    document.querySelectorAll('label[data-type="theme_color"]').forEach(label => {
      const val = label.dataset.value;
      const circle = label.querySelector('.color-circle');
      const badgeContainer = label.querySelector('.badge-slot');
      const overlay = label.querySelector('.price-overlay');
      const active = val === effectiveColor;
      const isDefaultColor = val === '#f3f4f6';
      const owned = purchasedColors.includes(val);
      const card = circle.closest('.relative.flex');
      circle.classList.remove('ring-4','ring-indigo-500','ring-emerald-400','ring-slate-400','ring-transparent','opacity-70','ring-2');
      if (active) circle.classList.add('ring-4','ring-indigo-500');
      else if (isDefaultColor) circle.classList.add('ring-2','ring-slate-400');
      else if (owned) circle.classList.add('ring-2','ring-emerald-400');
      else circle.classList.add('ring-2','ring-transparent','opacity-70');
      if (card) {
        card.classList.remove('border-indigo-400','ring-2','ring-indigo-300','shadow-md','border-emerald-300','border-gray-200','border-slate-300');
        if (active) card.classList.add('border-indigo-400','ring-2','ring-indigo-300','shadow-md');
        else if (isDefaultColor) card.classList.add('border-slate-300');
        else if (owned) card.classList.add('border-emerald-300');
        else card.classList.add('border-gray-200');
      }
      if (badgeContainer) {
        if (active) badgeContainer.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold shadow">Gekozen</span>';
        else if (isDefaultColor) badgeContainer.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-400/90 text-white font-medium shadow-sm">Standaard</span>';
        else if (owned) badgeContainer.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-500/90 text-white font-medium shadow-sm">Gekocht</span>';
        else badgeContainer.innerHTML = `<span class=\"px-2 py-0.5 text-[11px] rounded-full bg-amber-500/90 text-white font-semibold shadow flex items-center gap-0.5\"><span class='text-[10px]'>💎</span>${colorPrices[val] ?? ''}</span>`;
      }
      if (!isDefaultColor && owned && overlay) overlay.remove();
      if (!isDefaultColor && !owned && !overlay) {
        const o = document.createElement('div');
        o.className = 'price-overlay absolute inset-0 rounded-full bg-gradient-to-br from-black/40 to-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white tracking-wide transition-all';
        o.innerHTML = lockSvg;
        circle.parentElement.appendChild(o);
      }
      toggleActiefBadge(circle, active);
    });
  }

  function submitAjax(changedInput) {
    const fd = new FormData();
    if (changedInput && changedInput.name === 'avatar') fd.append('avatar', changedInput.value);
    if (changedInput && changedInput.name === 'theme_color') fd.append('theme_color', changedInput.value);
    fd.append('_token', csrf);
    fetch(form.action, {
      method: 'POST',
      headers: { 'Accept': 'application/json, text/plain, */*', 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    }).then(async res => {
      const ct = res.headers.get('Content-Type') || '';
      let data;
      if (ct.includes('application/json')) data = await res.json();
      else { showFlash('Opgeslagen', 'success'); return; }
      if (!res.ok || data.status === 'error') {
        showFlash(data.message || 'Fout', 'error');
        restoreLoadingButton();
      } else {
        updateUI(data);
        showFlash(data.message || 'Opgeslagen', 'success');
        markOwnedFromResponse(data);
        if (previewState && previewState.value && (data.avatar === previewState.value || data.theme_color === previewState.value)) closePreview(false);
        restoreLoadingButton();
      }
    }).catch(e => { console.warn('AJAX fout', e); showFlash('Netwerkfout', 'error'); restoreLoadingButton(); });
  }

  function purchaseItem(type, value) {
    const fd = new FormData();
    fd.append('_token', csrf);
    if (type === 'avatar') fd.append('avatar', value);
    if (type === 'theme_color') fd.append('theme_color', value);
    fetch(form.action, {
      method: 'POST', headers: { 'Accept': 'application/json, text/plain, */*', 'X-Requested-With':'XMLHttpRequest' }, body: fd
    }).then(async res => {
      const ct = res.headers.get('Content-Type')||''; let data=null; if (ct.includes('application/json')) data = await res.json();
      if (!data || !res.ok || data.status === 'error') { showFlash(data?.message || 'Fout bij kopen', 'error'); restoreLoadingButton(); }
      else { updateUI(data); showFlash(data.message || 'Gekocht', 'success'); markOwnedFromResponse(data); if (previewState && previewState.value === value) closePreview(false); restoreLoadingButton(); }
    }).catch(err => { console.warn(err); showFlash('Netwerkfout', 'error'); restoreLoadingButton(); });
  }

  function markOwnedFromResponse(data) {
    if (data.purchased_avatars) data.purchased_avatars.forEach(v => { const lbl = document.querySelector(`label[data-type="avatar"][data-value='${v}']`); if (lbl) lbl.dataset.owned = '1'; });
    if (data.purchased_theme_colors) data.purchased_theme_colors.forEach(v => { const lbl = document.querySelector(`label[data-type="theme_color"][data-value='${v}']`); if (lbl) lbl.dataset.owned = '1'; });
  }

  function restoreLoadingButton() {
    const btn = document.querySelector('.confirm-purchase[disabled][data-loading="1"]');
    if (btn) { btn.disabled = false; btn.classList.remove('opacity-80','cursor-wait'); btn.innerHTML = btn.dataset.originalText || '<span class="flex items-center gap-1"><span class="text-[10px]">💎</span>...</span>'; delete btn.dataset.loading; }
  }

  function optimisticVisualUpdate(type, value) {
    if (type === 'avatar') {
      document.querySelectorAll('label[data-type="avatar"]').forEach(l => {
        const val = l.dataset.value; const img = l.querySelector('img'); const badge = l.querySelector('.badge-slot'); const isDefault = val === '__default'; const isCustom = !isDefault && userId && val.startsWith('user_' + userId + '_'); const owned = isDefault ? true : l.dataset.owned === '1'; const active = val === value || (isDefault && value === '__default');
        if (img) {
          img.classList.remove('ring-4','ring-indigo-500','ring-emerald-400','ring-transparent','opacity-70');
          if (active) { img.classList.add('ring-4','ring-indigo-500'); if (badge) badge.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold shadow">Gekozen</span>'; }
          else if (owned && !isDefault) {
            img.classList.add('ring-2','ring-emerald-400');
            if (badge) badge.innerHTML = isCustom ? '<span class="px-2 py-0.5 text-[11px] rounded-full bg-indigo-500/90 text-white font-medium shadow-sm">Eigen</span>' : '<span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-500/90 text-white font-medium shadow-sm">Gekocht</span>';
          }
          else if (!isDefault) { img.classList.add('ring-2','ring-transparent','opacity-70'); }
          toggleActiefBadge(img, active);
        } else if (isDefault) {
          if (badge) {
            if (active) badge.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold shadow">Gekozen</span>';
            else badge.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-400/90 text-white font-medium shadow-sm">Standaard</span>';
          }
        }
      });
  const navImg = document.getElementById('nav-avatar-img');
  const navFallback = document.getElementById('nav-avatar-fallback');
  const navImgMobile = document.getElementById('nav-avatar-img-mobile');
  const navFallbackMobile = document.getElementById('nav-avatar-fallback-mobile');
  if (navImg && value && value !== '__default') { navImg.src = '/images/avatars/' + value; navImg.classList.remove('hidden'); if (navFallback) navFallback.classList.add('hidden'); }
  else if (navImg) { navImg.classList.add('hidden'); if (navFallback) navFallback.classList.remove('hidden'); }
  if (navImgMobile && value && value !== '__default') { navImgMobile.src = '/images/avatars/' + value; navImgMobile.classList.remove('hidden'); if (navFallbackMobile) navFallbackMobile.classList.add('hidden'); }
  else if (navImgMobile) { navImgMobile.classList.add('hidden'); if (navFallbackMobile) navFallbackMobile.classList.remove('hidden'); }
      currentEquippedAvatar = (value === '__default' ? null : value);
    }
    if (type === 'theme_color') {
      document.querySelectorAll('label[data-type="theme_color"]').forEach(l => {
        const circle = l.querySelector('.color-circle'); const badge = l.querySelector('.badge-slot'); const val = l.dataset.value; const isDefaultColor = val === '#f3f4f6'; const owned = l.dataset.owned === '1'; const active = val === value;
        circle.classList.remove('ring-4','ring-indigo-500','ring-emerald-400','ring-slate-400','ring-transparent','opacity-70');
        if (active) { circle.classList.add('ring-4','ring-indigo-500'); if (badge) badge.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold shadow">Gekozen</span>'; }
        else if (isDefaultColor) { circle.classList.add('ring-2','ring-slate-400'); if (badge) badge.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-400/90 text-white font-medium shadow-sm">Standaard</span>'; }
        else if (owned) { circle.classList.add('ring-2','ring-emerald-400'); if (badge) badge.innerHTML = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-500/90 text-white font-medium shadow-sm">Gekocht</span>'; }
        else { circle.classList.add('ring-2','ring-transparent','opacity-70'); }
        toggleActiefBadge(circle, active);
      });
      applyThemeColor(value);
    }
  }

  function openPreview(type, label, value, affordable, price, notEnoughGems) {
    if (previewState && previewState.label === label) { closePreview(); return; }
    if (previewState && previewState.label !== label) closePreview(false);
    const badgeSlot = label.querySelector('.badge-slot');
    if (!badgeSlot) return;
    const originalBadge = badgeSlot.innerHTML;
  let inner;
  const commonWrapCls = 'flex items-center justify-center gap-1 h-6 w-full animate-in fade-in';
    if (notEnoughGems) {
      // Duidelijke rode badge en geen 'X' knop ernaast, perfect gecentreerd
      // Gebruik inline style als fallback zodat kleur niet verdwijnt door Tailwind purge
      inner = `<div class='${commonWrapCls}'><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-white font-semibold shadow-sm whitespace-nowrap text-[11px] bg-rose-600" style="background:#e11d48;color:#fff">Te weinig 💎</span></div>`;
    } else {
      // Alleen een koopknop, geen 'X' cancel
      inner = `<div class='${commonWrapCls}'><button type='button' class='confirm-purchase h-6 leading-6 px-2 rounded-full bg-emerald-500 text-white font-semibold shadow hover:bg-emerald-600 transition text-[11px] inline-flex items-center gap-1'><span class='text-[10px]'>💎</span>${price}</button></div>`;
    }
  // Centreer binnen de badge-slot
  badgeSlot.classList.add('flex','items-center','justify-center');
  badgeSlot.innerHTML = inner;
    label.classList.add('preview');
    previewState = { type, value, label, affordable, price, originalBadge };
    if (type === 'theme_color') applyThemeColor(value);
    if (notEnoughGems) showFlash('Niet genoeg gems', 'error');
  }

  function closePreview(revert = true) {
    if (!previewState) return;
    const { label, type, originalBadge } = previewState;
    if (label && label.isConnected) {
  const badgeSlot = label.querySelector('.badge-slot');
  if (badgeSlot && originalBadge) badgeSlot.innerHTML = originalBadge;
  if (badgeSlot) badgeSlot.classList.remove('justify-center');
      label.classList.remove('preview');
    }
    if (revert && type === 'theme_color' && currentEquippedColor) applyThemeColor(currentEquippedColor);
    previewState = null;
  }

  // label click handling for non-owned
  document.querySelectorAll('label[data-type="theme_color"]').forEach(label => {
    label.addEventListener('click', e => {
      if (e.target.closest('.confirm-purchase') || e.target.closest('.cancel-preview')) return;
      const input = label.querySelector('input[name="theme_color"]'); if (!input) return;
      const owned = label.dataset.owned === '1'; if (owned) return;
      const price = parseInt(label.dataset.price||'0',10);
      const gemsText = (document.querySelector('#gems [data-gems-val]')?.textContent || '0').trim();
      const gems = parseInt(gemsText,10)||0;
      const affordable = price <= gems;
      e.preventDefault(); e.stopPropagation();
      openPreview('theme_color', label, input.value, affordable, price, !affordable);
    });
  });

  document.querySelectorAll('label[data-type="avatar"]').forEach(label => {
    label.addEventListener('click', e => {
      if (e.target.closest('.confirm-purchase') || e.target.closest('.cancel-preview')) return;
      const input = label.querySelector('input[name="avatar"]'); if (!input) return;
      const isDefault = label.dataset.value === '__default';
      const owned = isDefault ? true : label.dataset.owned === '1';
      if (owned) return;
      const price = parseInt(label.dataset.price||'0',10);
      const gemsText = (document.querySelector('#gems [data-gems-val]')?.textContent || '0').trim();
      const gems = parseInt(gemsText,10)||0;
      const affordable = price <= gems;
      e.preventDefault(); e.stopPropagation();
      openPreview('avatar', label, input.value, affordable, price, !affordable);
    });
  });

  // Delegated change handler (werkt ook voor dynamisch toegevoegde radios)
  form.addEventListener('change', e => {
    const input = e.target.closest('input.auto-submit');
    if (!input) return;
    const name = input.name;
    const label = input.closest('label[data-type]');
    const type = label?.dataset.type;
    const owned = label?.dataset.owned === '1';
    const price = parseInt(label?.dataset.price || '0', 10);
    const gemsText = (document.querySelector('#gems [data-gems-val]')?.textContent || '0').trim();
    const gems = parseInt(gemsText, 10) || 0;
    const affordable = price <= gems;

    if (owned) {
      document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = false);
      input.checked = true;
      optimisticVisualUpdate(name, input.value);
      submitAjax(input);
      closePreview(false);
      return;
    }
    if (type === 'avatar' && input.value === '__default') {
      document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = false);
      input.checked = true;
      optimisticVisualUpdate('avatar', '__default');
  const navImg = document.getElementById('nav-avatar-img');
  const navFallback = document.getElementById('nav-avatar-fallback');
  const navImgMobile = document.getElementById('nav-avatar-img-mobile');
  const navFallbackMobile = document.getElementById('nav-avatar-fallback-mobile');
  if (navImg) navImg.classList.add('hidden');
  if (navFallback) navFallback.classList.remove('hidden');
  if (navImgMobile) navImgMobile.classList.add('hidden');
  if (navFallbackMobile) navFallbackMobile.classList.remove('hidden');
      currentEquippedAvatar = null;
      submitAjax(input);
      closePreview(false);
      return;
    }

    document.querySelectorAll(`input[name="${name}"]`).forEach(r => r.checked = false);
    if (name === 'theme_color') {
      const equipped = document.querySelector(`input[name="theme_color"][value='${currentEquippedColor}']`);
      if (equipped) equipped.checked = true;
    } else if (name === 'avatar') {
      const equippedA = document.querySelector(`input[name="avatar"][value='${currentEquippedAvatar}']`);
      if (equippedA) equippedA.checked = true;
    }
    openPreview(type, label, input.value, affordable, price, !affordable);
  });

  document.addEventListener('click', ev => {
    const cancelBtn = ev.target.closest('.cancel-preview');
    if (cancelBtn) { ev.preventDefault(); ev.stopPropagation(); closePreview(); return; }
    const confirmBtn = ev.target.closest('.confirm-purchase');
    if (confirmBtn) {
      ev.preventDefault(); ev.stopPropagation();
      if (!previewState) return;
      if (confirmBtn.dataset.loading === '1') return;
      const { type, value } = previewState;
      confirmBtn.dataset.loading = '1'; confirmBtn.disabled = true; confirmBtn.dataset.originalText = confirmBtn.innerHTML;
      confirmBtn.innerHTML = '<span class="inline-flex items-center gap-1"><svg class="w-3 h-3 animate-spin" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" class="opacity-30"/><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" class="opacity-90"/></svg> Kopen...';
      confirmBtn.classList.add('opacity-80','cursor-wait');
      const radio = document.querySelector(`input[name='${type === 'avatar' ? 'avatar':'theme_color'}'][value='${value}']`);
      if (radio) {
        document.querySelectorAll(`input[name='${radio.name}']`).forEach(r => r.checked = false);
        radio.checked = true;
      }
      if (type === 'avatar') {
        const lbl = radio?.closest('label') || previewState.label;
        lbl?.querySelector('img')?.classList.add('ring-4','ring-indigo-500');
      } else if (type === 'theme_color') {
        applyThemeColor(value);
      }
      purchaseItem(type, value);
      setTimeout(()=>{ if (confirmBtn.dataset.loading==='1') { restoreLoadingButton(); } }, 10000);
    }
  });

  document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreview(); });

  // (Legacy upload form code verwijderd – modal upload wordt nu gebruikt)
});
