<div x-data="{ count: 0 }" class="text-center p-4">
    <button 
        @click="count++" 
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" style="background-color: purple;"
    >
        +1
    </button>
    <button 
        @click="count--" 
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" style="background-color: purple;"
    >
        -1
    </button>

    <p class="mt-2 text-lg">Counter: <span x-text="count"></span></p>

    <template x-if="count === 67">
        <img src="{{ asset('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTVXuq52L7puT_LMk9rGDIjNPHeW5DXzq0kHNBmZ8FkSFpqtPhLioAblgRb_wsI2sJ5Q9Q&usqp=CAU') }}" alt="Special" class="mt-4 mx-auto w-64">
        
    </template>
</div>
