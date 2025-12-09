<head>
  <title>LetsQuiz — Resultaten</title>
</head>
<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8 relative z-10">
  <!-- Header Section -->
  <div class="text-center mb-8">
    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-2">
      Resultaten
    </h1>
    <p class="text-slate-600 text-lg">Bekijk je quiz prestaties en gedeelde resultaten</p>
  </div>

  <!-- Modern Tabs -->
  <div class="mb-8">
    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/20 p-2">
      <nav class="flex space-x-2">
        <button class="tab-btn flex-1 px-6 py-4 rounded-2xl text-sm font-semibold transition-all duration-200 {{ $activeTab === 'my-results' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}" data-tab="my-results">
          <div class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Mijn Resultaten
          </div>
        </button>
        <button class="tab-btn flex-1 px-6 py-4 rounded-2xl text-sm font-semibold transition-all duration-200 {{ $activeTab === 'shared-results' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}" data-tab="shared-results">
          <div class="flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Gedeeld Met Mij
          </div>
        </button>
      </nav>
    </div>
  </div>


  <!-- Results Content Container -->
  <div id="results-content">
    @include('results.partials.results-content')
  </div>
</div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
  let isLoading = false;

  // Seamless tab switching with AJAX
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();
      
      if (isLoading) return;
      
      const targetTab = btn.getAttribute('data-tab');
      const currentTab = document.querySelector('.tab-btn.active')?.getAttribute('data-tab');
      
      // Don't do anything if clicking the same tab
      if (targetTab === currentTab) return;
      
      isLoading = true;
      
      // Update button states immediately for better UX
      updateTabButtons(targetTab);
      
      try {
        // Show loading state
        const contentContainer = document.getElementById('results-content');
        contentContainer.innerHTML = `
          <div class="flex items-center justify-center py-12">
            <div class="flex items-center gap-3">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
              <span class="text-slate-600 font-medium">Laden...</span>
            </div>
          </div>
        `;
        
        // Fetch new content (reset to page 1 when switching tabs)
        const response = await fetch(`{{ route('results.ajax.index') }}?tab=${targetTab}&${targetTab === 'shared-results' ? 'shared_page=1' : 'my_page=1'}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Update content
        contentContainer.innerHTML = data.html;
        
        // Update URL without page refresh
        const url = new URL(window.location);
        url.searchParams.set('tab', targetTab);
        // Reset to page 1 when switching tabs and clean up page parameters
        url.searchParams.delete('page');
        url.searchParams.delete('my_page');
        url.searchParams.delete('shared_page');
        history.pushState({ tab: targetTab, page: '1' }, '', url);
        
        // Re-attach event listeners to new content
        attachEventListeners();
        
      } catch (error) {
        console.error('Error loading tab content:', error);
        // Fallback to page reload
        window.location.href = `{{ route('results.index') }}?tab=${targetTab}`;
      } finally {
        isLoading = false;
      }
    });
  });

  // Handle pagination clicks
  document.addEventListener('click', async (e) => {
    if (e.target.closest('.pagination a')) {
      e.preventDefault();
      
      if (isLoading) return;
      
      const link = e.target.closest('.pagination a');
      const url = new URL(link.href);
      
      // Ensure we're not trying to access the AJAX endpoint directly
      if (url.pathname.includes('/ajax')) {
        console.error('Pagination link incorrectly points to AJAX endpoint');
        window.location.href = link.href.replace('/ajax', '');
        return;
      }
      
      const tab = url.searchParams.get('tab') || '{{ $activeTab }}';
      
      // Get the correct page parameter based on tab
      let pageParam = '';
      if (tab === 'shared-results') {
        const sharedPage = url.searchParams.get('shared_page') || '1';
        pageParam = `&shared_page=${sharedPage}`;
      } else {
        const myPage = url.searchParams.get('my_page') || '1';
        pageParam = `&my_page=${myPage}`;
      }
      
      isLoading = true;
      
      try {
        // Show loading state
        const contentContainer = document.getElementById('results-content');
        contentContainer.innerHTML = `
          <div class="flex items-center justify-center py-12">
            <div class="flex items-center gap-3">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
              <span class="text-slate-600 font-medium">Laden...</span>
            </div>
          </div>
        `;
        
        // Fetch new content with correct parameters
        const response = await fetch(`{{ route('results.ajax.index') }}?tab=${tab}${pageParam}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Update content
        contentContainer.innerHTML = data.html;
        
        // Update URL with correct parameters
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('tab', tab);
        
        // Set the correct page parameter and remove others
        currentUrl.searchParams.delete('page');
        currentUrl.searchParams.delete('my_page');
        currentUrl.searchParams.delete('shared_page');
        
        if (tab === 'shared-results') {
          const sharedPage = url.searchParams.get('shared_page') || '1';
          currentUrl.searchParams.set('shared_page', sharedPage);
        } else {
          const myPage = url.searchParams.get('my_page') || '1';
          currentUrl.searchParams.set('my_page', myPage);
        }
        
        history.pushState({ tab, page: url.searchParams.get(tab === 'shared-results' ? 'shared_page' : 'my_page') || '1' }, '', currentUrl);
        
        // Re-attach event listeners
        attachEventListeners();
        
      } catch (error) {
        console.error('Error loading page:', error);
        window.location.href = link.href;
      } finally {
        isLoading = false;
      }
    }
  });

  // Handle browser back/forward
  window.addEventListener('popstate', async (e) => {
    const currentUrl = new URL(window.location);
    const tab = currentUrl.searchParams.get('tab') || '{{ $activeTab }}';
    const currentTab = document.querySelector('.tab-btn.active')?.getAttribute('data-tab');
    
    if (tab && tab !== currentTab) {
      updateTabButtons(tab);
    }
    
    // Load content for the state
    if (!isLoading) {
      isLoading = true;
      try {
        // Build the correct query string
        let queryString = `tab=${tab}`;
        if (tab === 'shared-results') {
          const sharedPage = currentUrl.searchParams.get('shared_page') || '1';
          queryString += `&shared_page=${sharedPage}`;
        } else {
          const myPage = currentUrl.searchParams.get('my_page') || '1';
          queryString += `&my_page=${myPage}`;
        }
        
        const response = await fetch(`{{ route('results.ajax.index') }}?${queryString}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        document.getElementById('results-content').innerHTML = data.html;
        attachEventListeners();
      } catch (error) {
        window.location.reload();
      } finally {
        isLoading = false;
      }
    }
  });

  function updateTabButtons(activeTab) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
      const tab = btn.getAttribute('data-tab');
      if (tab === activeTab) {
        btn.classList.add('active', 'bg-indigo-600', 'text-white', 'shadow-lg');
        btn.classList.remove('text-slate-600', 'hover:text-slate-800', 'hover:bg-slate-100');
      } else {
        btn.classList.remove('active', 'bg-indigo-600', 'text-white', 'shadow-lg');
        btn.classList.add('text-slate-600', 'hover:text-slate-800', 'hover:bg-slate-100');
      }
    });
  }

  function attachEventListeners() {
  // Share link functionality
  document.querySelectorAll('.share-link').forEach(btn => {
    btn.addEventListener('click', async () => {
      const url = btn.getAttribute('data-url');
      try {
        if (navigator.share) {
          await navigator.share({title: 'LetsQuiz resultaat', url});
        } else if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(url);
          const old = btn.textContent; btn.textContent = 'Gekopieerd!'; setTimeout(()=>btn.textContent=old, 1200);
        } else {
          throw new Error('no share');
        }
      } catch (_) {
        const old = btn.textContent; btn.textContent = 'Mislukt'; setTimeout(()=>btn.textContent=old, 1200);
      }
    });
  });
  }

  // Initial event listener attachment
  attachEventListeners();
});
</script>

