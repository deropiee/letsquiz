@props([
    'text' => 'Er is iets misgegaan',
    'link_text' => 'Terug naar dashboard',
    'url' => '/dashboard',
    'error_code' => '404',
])

<div class="flex items-center justify-center min-h-screen">
    <div class="card-body text-center m-4">
        <p style="font-size: 50px; color: rgba(204, 204, 204, 1);" class="mb-4">{{ $error_code }} | {{ $text }}</p>
        <hr class="border-t border-gray-300 my-4">
        <a href="{{ $url }}" class="error-button inline-flex items-center px-3 py-1.5 rounded-lg text-white font-medium hover:text-black transition-colors duration-700 ease-in-out">
            {{ $link_text }}
        </a>
    </div>
</div>