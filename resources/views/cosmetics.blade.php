<head>
    <title>LetsQuiz — Cosmetics</title>
    <style>
        /* Voorkom horizontaal scrollen door kleine overflow van decoratieve SVG's */
        html, body { overflow-x: hidden; }
        /* Kleinere max-breedte correct uitlijnen en wat extra ruimte onderaan zodat er geen verticale scrollbar ontstaat door schaduw/blur */
        #cosmetics-page-wrapper { padding-bottom: 3rem; }
        /* Zorg dat decoratieve elementen niet buiten hun container scrollbars forceren */
        #cosmetics-page-wrapper .bg-decor-wrapper { pointer-events: none; inset: 0; overflow: hidden; }
        /* Fix voor Safari/Windows rendering waarbij blur soms 1px overflow veroorzaakt */
        .avoid-edge-overflow { transform: translateZ(0); }
    </style>
</head>
<x-app-layout>
    <div id="cosmetics-page-wrapper" class="mt-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- decorative background -->
        <div class="bg-decor-wrapper absolute -z-10 avoid-edge-overflow">
            <svg class="absolute right-0 top-0 w-64 h-64 opacity-20 transform rotate-45 blur-lg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <defs><linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#6366f1"/><stop offset="1" stop-color="#06b6d4"/></linearGradient></defs>
                <circle cx="40" cy="40" r="80" fill="url(#g1)"/>
            </svg>
            <svg class="absolute left-0 bottom-0 w-56 h-56 opacity-15 transform -rotate-12 blur-md" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="100" fill="#f97316"/>
            </svg>
        </div>
        <!-- Card -->
    <div id="cosmetics-section" class="bg-white/70 rounded-xl p-5 shadow-sm border border-gray-100 backdrop-blur-sm">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-200 rounded-full opacity-20 blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-yellow-300 rounded-full opacity-20 blur-2xl"></div>
            <h2 class="text-2xl font-extrabold mb-1 text-gray-900">Cosmetics aanpassen</h2>
            <p class="mb-2 text-sm text-gray-600">Kies een profielfoto en een kleurthema voor jouw quizervaring.</p>
            <div id="cosmetics-messages" class="space-y-2 mb-4"></div>
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 text-rose-700 text-sm px-4 py-2">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 text-rose-700 text-sm px-4 py-2">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-700 text-sm px-4 py-2">{{ session('success') }}</div>
            @endif
            <!-- Upload modal verplaatst BUITEN deze container zodat de blur de volledige pagina dekt -->

            <form method="POST" action="{{ route('cosmetics.update') }}" id="cosmetics-form" data-ajax="true" data-user-id="{{ $user->id }}" data-avatar-prices='@json($avatarPrices)' data-theme-color-prices='@json($themeColorPrices)'>
                @csrf
                <div class="mb-8">
                    <label class="block font-semibold mb-3 text-base tracking-tight">Profielfoto kiezen</label>
                    <div id="avatar-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @php
                            // Huidige avatar mapping: null => '__default'
                            $effectiveUserAvatar = $user->avatar === null ? '__default' : $user->avatar;
                            $standardAvatars = [];
                            $customAvatars = [];
                            foreach($avatars as $av) {
                                if($av !== '__default' && str_starts_with($av, 'user_' . $user->id . '_')) {
                                    $customAvatars[] = $av; // custom van deze user
                                } else {
                                    $standardAvatars[] = $av; // default + shop avatars
                                }
                            }
                        @endphp
                        @php $addedUploadCard = false; @endphp
                        @foreach(array_merge($standardAvatars, $customAvatars) as $avatar)
                            @php
                                $isDefault = $avatar === '__default';
                                $isCustom = !$isDefault && str_starts_with($avatar, 'user_' . $user->id . '_');
                                $isOwned = $isDefault ? true : ($isCustom ? true : in_array($avatar, $purchasedAvatars ?? []));
                                $isEquipped = old('avatar', $effectiveUserAvatar) === $avatar;
                                $price = $avatarPrices[$avatar] ?? 0;
                                $dataPrice = $isDefault ? 0 : $price;
                            @endphp
                            @if(!$addedUploadCard && $loop->first)
                                @php $addedUploadCard = true; @endphp
                                <div>
                                    <button type="button" id="open-upload-modal" class="group w-full cursor-pointer select-none">
                                        <div class="relative flex flex-col items-center rounded-2xl p-3 bg-white/60 backdrop-blur-sm border border-gray-200 hover:shadow-lg transition-all duration-300">
                                            <div class="relative">
                                                <div class="w-16 h-16 rounded-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-cyan-100 text-indigo-600 font-semibold text-lg ring-2 ring-indigo-300 shadow-inner group-hover:ring-indigo-400 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="M6 10l6-6 6 6"/><path d="M20 16v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2"/></svg>
                                                </div>
                                            </div>
                                            <div class="mt-2 h-6 flex items-center badge-slot text-[10px]">
                                                <span class="px-2 py-0.5 text-[11px] rounded-full bg-indigo-500/90 text-white font-medium shadow-sm group-hover:bg-indigo-600">Upload</span>
                                            </div>
                                            <div class="preview-panel hidden w-full mt-2 text-[11px]"></div>
                                        </div>
                                    </button>
                                </div>
                            @endif
                            <label class="group cursor-pointer select-none" data-type="avatar" data-value="{{ $avatar }}" data-price="{{ $dataPrice }}" data-owned="{{ $isOwned ? '1':'0' }}">
                                <input type="radio" name="avatar" value="{{ $avatar }}" class="hidden auto-submit" @if($isEquipped) checked @endif data-price="{{ $dataPrice }}" data-owned="{{ $isOwned ? '1':'0' }}">
                                <div class="relative flex flex-col items-center rounded-2xl p-3 bg-white/60 backdrop-blur-sm border
                                    @if($isEquipped) border-indigo-400 ring-2 ring-indigo-300 shadow-md @elseif($isOwned) border-emerald-300 @else border-gray-200 @endif
                                    hover:shadow-lg transition-all duration-300">
                                    <div class="relative">
                                        @if($isDefault)
                                            @php $initial = strtoupper(substr($user->name ?? 'U',0,1)); @endphp
                                            <div class="w-16 h-16 rounded-full flex items-center justify-center bg-indigo-100 text-indigo-700 font-semibold text-lg ring-2 ring-indigo-300 select-none">{{ $initial }}</div>
                                        @else
                                            <img src="/images/avatars/{{ $avatar }}" alt="Avatar {{ $avatar }}"
                                                 class="w-16 h-16 rounded-full object-cover shadow-sm
                                                 @if($isEquipped) ring-4 ring-indigo-500 @elseif($isOwned) ring-2 ring-emerald-400 @else ring-2 ring-transparent @endif
                                                 @unless($isOwned) opacity-70 group-hover:opacity-100 @endunless transition-all duration-300">
                                        @endif
                                        @if(!$isOwned && !$isDefault)
                                            <div class="price-overlay absolute inset-0 rounded-full bg-gradient-to-br from-black/40 to-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white tracking-wide transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 10V8a4 4 0 118 0v2"/><rect x="5" y="10" width="14" height="11" rx="2"/><circle cx="12" cy="16" r="1.5"/></svg>
                                            </div>
                                        @endif
                                        @if($isEquipped)
                                            <span class="actief-badge absolute -top-2 -right-2 rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white text-[10px] px-1.5 py-0.5 shadow">Actief</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 h-6 flex items-center badge-slot text-[10px]">
                                        @if($isEquipped)
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold shadow">Gekozen</span>
                                        @elseif($isDefault)
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-400/90 text-white font-medium shadow-sm">Standaard</span>
                                        @elseif($isCustom)
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-indigo-500/90 text-white font-medium shadow-sm">Eigen</span>
                                        @elseif($isOwned)
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-500/90 text-white font-medium shadow-sm">Gekocht</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-amber-500/90 text-white font-semibold shadow flex items-center gap-0.5"><span class='text-[10px]'>💎</span>{{ $price }}</span>
                                        @endif
                                    </div>
                                    <div class="preview-panel hidden w-full mt-2 text-[11px]"></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-2">
                    <label class="block font-semibold mb-3 text-base tracking-tight">Kleurthema</label>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                        @foreach($themeColors as $color)
                            @php
                                $isDefaultColor = $color === '#f3f4f6';
                                $isOwned = in_array($color, $purchasedThemeColors ?? []);
                                // Gebruik effectiveThemeColor zodat null (standaard) gematcht wordt op '#f3f4f6'
                                $isEquipped = ($effectiveThemeColor ?? '#f3f4f6') === $color;
                                $price = $themeColorPrices[$color] ?? 0;
                            @endphp
                            <label class="group cursor-pointer select-none" data-type="theme_color" data-value="{{ $color }}" data-price="{{ $price }}" data-owned="{{ $isOwned ? '1':'0' }}">
                                <input type="radio" name="theme_color" value="{{ $color }}" class="hidden auto-submit" @if($isEquipped) checked @endif data-price="{{ $price }}" data-owned="{{ $isOwned ? '1':'0' }}">
                                <div class="relative flex flex-col items-center rounded-2xl p-2.5 bg-white/60 backdrop-blur-sm border
                                    @if($isEquipped) border-indigo-400 ring-2 ring-indigo-300 shadow-md @elseif($isDefaultColor) border-slate-300 @elseif($isOwned) border-emerald-300 @else border-gray-200 @endif
                                    hover:shadow-lg transition-all duration-300">
                                    <div class="relative w-12 h-12">
                                        <span class="color-circle w-12 h-12 rounded-full block shadow-sm transition-all duration-300
                                            @if($isEquipped) ring-4 ring-indigo-500 shadow-lg @elseif($isDefaultColor) ring-2 ring-slate-400 @elseif($isOwned) ring-2 ring-emerald-400 @else ring-2 ring-transparent @endif
                                            @unless($isOwned) opacity-70 group-hover:opacity-100 @endunless" style="background: {{ $color }}"></span>
                                        @if(!$isOwned)
                                            <div class="price-overlay absolute inset-0 rounded-full bg-gradient-to-br from-black/40 to-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white tracking-wide transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M8 10V8a4 4 0 118 0v2"/>
                                                    <rect x="5" y="10" width="14" height="11" rx="2"/>
                                                    <circle cx="12" cy="15.5" r="1.5"/>
                                                </svg>
                                            </div>
                                        @endif
                                        @if($isEquipped)
                                            <span class="actief-badge absolute -top-2 -right-2 rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white text-[10px] px-1.5 py-0.5 shadow">Actief</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 h-6 flex items-center badge-slot text-[10px]">
                                        @if($isEquipped)
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold shadow">Gekozen</span>
                                        @elseif($isDefaultColor)
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-400/90 text-white font-medium shadow-sm">Standaard</span>
                                        @elseif($isOwned)
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-500/90 text-white font-medium shadow-sm">Gekocht</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[11px] rounded-full bg-amber-500/90 text-white font-semibold shadow flex items-center gap-0.5"><span class='text-[10px]'>💎</span>{{ $price }}</span>
                                        @endif
                                    </div>
                                    <div class="preview-panel hidden w-full mt-2 text-[11px]"></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </form>
            {{-- Scripts verplaatst naar resources/js/cosmetics.js --}}
        </div>
    </div>
</x-app-layout>
<!-- Globale upload modal (nu buiten de cosmetica wrapper om overflow clipping te voorkomen) -->
<div id="avatar-upload-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true" aria-modal="true" role="dialog">
    <!-- Donkere overlay met sterkere backdrop blur over de volledige viewport -->
    <div class="absolute inset-0 bg-black/35 backdrop-blur-md"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-200 rounded-full opacity-25 blur-2xl pointer-events-none"></div>
            <h3 class="text-xl font-bold mb-1 text-gray-800">Nieuwe avatar uploaden</h3>
            <p class="text-sm text-gray-600 mb-2">Kosten: <span class="font-semibold text-indigo-600">💎 {{ number_format($customAvatarUploadPrice,0,',','.') }}</span></p>
            <p class="text-xs text-gray-500 mb-4">Bestand moet vierkant (1:1) zijn. We verkleinen automatisch naar 256px. Toegestaan: PNG / JPG / WEBP.</p>
            <form id="modal-upload-form" method="POST" action="{{ route('cosmetics.uploadAvatar') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex flex-col gap-2">
                    <input id="modal-avatar-input" type="file" name="custom_avatar" accept="image/png,image/jpeg,image/webp" class="text-sm" required>
                    <div id="modal-preview" class="hidden mt-2 items-center gap-3"></div>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <button type="button" data-close-modal class="px-4 py-2 text-sm font-medium rounded-full bg-gray-200 hover:bg-gray-300 text-gray-800">Annuleren</button>
                    <button type="submit" class="px-5 py-2 text-sm font-medium rounded-full bg-indigo-600 text-white hover:bg-indigo-700 flex items-center gap-2" id="modal-upload-btn">
                        <span class="flex items-center gap-1"><span class="text-[11px]">💎</span>{{ number_format($customAvatarUploadPrice,0,',','.') }} Uploaden</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('avatar-upload-modal');
    const openBtn = document.getElementById('open-upload-modal');
    const closeEls = modal?.querySelectorAll('[data-close-modal]');
    const form = document.getElementById('modal-upload-form');
    const fileInput = document.getElementById('modal-avatar-input');
    const preview = document.getElementById('modal-preview');
    const uploadBtn = document.getElementById('modal-upload-btn');
    const globalMessages = document.getElementById('cosmetics-messages');

    function renderMessage(container, type, text) {
        if (!container) return;
        const div = document.createElement('div');
        div.className = `rounded-lg border px-4 py-2 text-sm ${type==='error' ? 'border-rose-300 bg-rose-50 text-rose-700':'border-emerald-300 bg-emerald-50 text-emerald-700'}`;
        div.setAttribute('role','alert');
        div.innerHTML = text;
        container.appendChild(div);
        setTimeout(()=>{ div.classList.add('opacity-0','transition'); setTimeout(()=>div.remove(), 4000); }, 4000);
    }
    function showError(msg) { renderMessage(globalMessages, 'error', msg); }
    function showSuccess(msg) { renderMessage(globalMessages, 'success', msg); }
    function openModal(){ modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
    function closeModal(){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); if (form) form.reset(); if (preview){ preview.classList.add('hidden'); preview.innerHTML=''; } }
    openBtn?.addEventListener('click', openModal);
    closeEls?.forEach(el=> el.addEventListener('click', closeModal));
    modal?.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });
    if (fileInput) {
        fileInput.addEventListener('change', () => {
            const f = fileInput.files?.[0];
            if (!f) { preview.classList.add('hidden'); preview.classList.remove('flex'); preview.innerHTML=''; return; }
            if (!/^image\//.test(f.type)) { showError('Geen geldige afbeelding.'); fileInput.value=''; return; }
            const url = URL.createObjectURL(f);
            const img = new Image();
            img.onload = () => {
                if (img.width !== img.height) { showError('Afbeelding moet vierkant (1:1) zijn.'); fileInput.value=''; URL.revokeObjectURL(url); preview.classList.add('hidden'); preview.classList.remove('flex'); preview.innerHTML=''; return; }
                preview.classList.remove('hidden'); preview.classList.add('flex');
                preview.innerHTML = `<div class='flex items-center gap-3'><img src='${url}' class='w-16 h-16 rounded-full object-cover ring-2 ring-indigo-400 shadow'><span class='text-xs text-gray-600'>Voorbeeld (${img.width}×${img.height})</span></div>`;
            };
            img.src = url;
        });
    }
    if (form) {
        form.addEventListener('submit', ev => {
            ev.preventDefault();
            if (!fileInput?.files?.length) return;
            const fd = new FormData(form);
            uploadBtn.disabled = true; uploadBtn.classList.add('opacity-70','cursor-wait');
            uploadBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" class="opacity-30"/><path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" class="opacity-90"/></svg>';
            fetch(form.action, { method:'POST', headers:{'Accept':'application/json, text/plain, */*','X-Requested-With':'XMLHttpRequest'}, body: fd })
                    .then(r=>r.json().catch(()=>null))
                    .then(data => {
                        if(!data || data.status!=='ok') { showError(data?.message || 'Upload mislukt'); return; }
                        // success path: update gems counters & show success
                        if (typeof data.gems !== 'undefined') {
                            document.querySelectorAll('#gems [data-gems-val], #gems-mobile [data-gems-val]').forEach(span => span.textContent = data.gems);
                        }
                        showSuccess('Nieuwe avatar geüpload en ingesteld.');
                        // Voeg nieuwe kaart ALS LAATSTE toe (custom avatars stapelen achteraan)
                        const grid = document.getElementById('avatar-grid');
                        if (grid) {
                            // Demote huidige actieve avatar (verwijder actieve styling & badge herstellen)
                            const formEl = document.getElementById('cosmetics-form');
                            const userId = formEl?.dataset.userId || '';
                            let avatarPrices = {};
                            try { avatarPrices = JSON.parse(formEl?.dataset.avatarPrices || '{}'); } catch(e) {}
                            const activeBadges = document.querySelectorAll('label[data-type="avatar"] .actief-badge');
                            activeBadges.forEach(badge => {
                                const label = badge.closest('label[data-type="avatar"]');
                                if (!label) return;
                                badge.remove();
                                const container = label.querySelector(':scope > div.relative.flex.flex-col');
                                // Fallback: broader match
                                const blocks = label.querySelectorAll(':scope > div');
                                const wrapperDiv = container || blocks[0];
                                if (wrapperDiv) {
                                    wrapperDiv.classList.remove('border-indigo-400','ring-2','ring-indigo-300','shadow-md');
                                }
                                // Image ring aanpassen
                                const img = label.querySelector('img');
                                const value = label.dataset.value || '';
                                const owned = label.dataset.owned === '1';
                                const isDefault = value === '__default';
                                const isCustom = !isDefault && value.startsWith('user_'+userId+'_');
                                if (img) {
                                    img.classList.remove('ring-4','ring-indigo-500');
                                    img.classList.remove('ring-2','ring-emerald-400','ring-transparent');
                                    if (owned) img.classList.add('ring-2','ring-emerald-400'); else img.classList.add('ring-2','ring-transparent');
                                }
                                if (wrapperDiv) {
                                    // Bepaal nieuwe border
                                    if (isDefault) wrapperDiv.classList.add('border-slate-300');
                                    else if (owned) wrapperDiv.classList.add('border-emerald-300');
                                    else wrapperDiv.classList.add('border-gray-200');
                                }
                                // Badge-slot resetten
                                const badgeSlot = label.querySelector('.badge-slot');
                                if (badgeSlot) {
                                    let newBadge = '';
                                    if (isDefault) newBadge = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-slate-400/90 text-white font-medium shadow-sm">Standaard</span>';
                                    else if (isCustom) newBadge = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-indigo-500/90 text-white font-medium shadow-sm">Eigen</span>';
                                    else if (owned) newBadge = '<span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-500/90 text-white font-medium shadow-sm">Gekocht</span>';
                                    else {
                                        const price = avatarPrices[value] ?? 0;
                                        newBadge = `<span class='px-2 py-0.5 text-[11px] rounded-full bg-amber-500/90 text-white font-semibold shadow flex items-center gap-0.5'><span class='text-[10px]'>💎</span>${price}</span>`;
                                    }
                                    badgeSlot.innerHTML = newBadge;
                                }
                            });
                            const wrapper = document.createElement('label');
                            wrapper.className='group cursor-pointer select-none';
                            wrapper.dataset.type='avatar'; wrapper.dataset.value=data.avatar; wrapper.dataset.price='0'; wrapper.dataset.owned='1';
                            wrapper.innerHTML = `<input type="radio" name="avatar" value="${data.avatar}" class="hidden auto-submit" checked data-price="0" data-owned="1"><div class="relative flex flex-col items-center rounded-2xl p-3 bg-white/60 backdrop-blur-sm border border-indigo-400 ring-2 ring-indigo-300 shadow-md hover:shadow-lg transition-all duration-300"><div class="relative"><img src="/images/avatars/${data.avatar}?v=${Date.now()}" alt="Avatar ${data.avatar}" class="w-16 h-16 rounded-full object-cover shadow-sm ring-4 ring-indigo-500"><span class="actief-badge absolute -top-2 -right-2 rounded-full bg-gradient-to-r from-indigo-600 to-cyan-500 text-white text-[10px] px-1.5 py-0.5 shadow">Actief</span></div><div class="mt-2 h-6 flex items-center badge-slot text-[10px]"><span class="px-2 py-0.5 text-[11px] rounded-full bg-indigo-500/90 text-white font-medium shadow-sm">Eigen</span></div><div class="preview-panel hidden w-full mt-2 text-[11px]"></div></div>`;
                            grid.appendChild(wrapper);
                            // Uncheck andere avatars
                            document.querySelectorAll('input[name="avatar"]').forEach(r=> r.checked=false);
                            wrapper.querySelector('input[name="avatar"]').checked = true;
                            // Update nav
                            const navImg=document.getElementById('nav-avatar-img'); const navFallback=document.getElementById('nav-avatar-fallback');
                            if (navImg){ navImg.src='/images/avatars/'+data.avatar+'?v='+Date.now(); navImg.classList.remove('hidden'); }
                            if (navFallback){ navFallback.classList.add('hidden'); }
                        }
                    })
                    .catch(()=> showError('Netwerkfout'))
                    .finally(()=>{ uploadBtn.disabled=false; uploadBtn.classList.remove('opacity-70','cursor-wait'); uploadBtn.innerHTML='<span class="flex items-center gap-1"><span class="text-[11px]">💎</span>{{ number_format($customAvatarUploadPrice,0,',','.') }} Uploaden</span>'; closeModal(); });
        });
    }
});
</script>

