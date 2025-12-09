<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Account verwijderen') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Zodra je account is verwijderd, worden alle bijbehorende gegevens en bronnen permanent verwijderd. Download alle gegevens die je wilt bewaren voordat je je account verwijdert.') }}
        </p>
    </header>

    <div x-data="{ confirming: @json($errors->userDeletion->isNotEmpty()), showPw:false }" class="space-y-4">
        <template x-if="!confirming">
            <x-danger-button type="button" @click="confirming=true; $nextTick(()=> $refs.delPw.focus())">
                {{ __('Account verwijderen') }}
            </x-danger-button>
        </template>

        <form x-show="confirming" x-transition method="post" action="{{ route('profile.destroy') }}" class="flex flex-col gap-4 max-w-2xl border border-red-200 rounded-lg p-5 bg-red-50/40">
            @csrf
            @method('delete')
            <div class="flex flex-col gap-1">
                <h3 class="text-sm font-semibold text-red-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3Z"/></svg>
                    {{ __('Bevestig verwijderen') }}
                </h3>
                <p class="text-xs text-red-600">
                    {{ __('Voer je wachtwoord in om definitief je account te verwijderen. Dit kan niet ongedaan worden gemaakt.') }}
                </p>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                <div class="flex-1">
                    <x-input-label for="delete-password" value="{{ __('Wachtwoord') }}" class="text-xs" />
                    <div class="relative">
                        <x-text-input id="delete-password" x-ref="delPw" name="password" x-bind:type="showPw ? 'text':'password'" class="mt-1 block w-full pr-10" placeholder="{{ __('Wachtwoord') }}" autocomplete="current-password" required />
                        <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600" x-text="showPw ? '🙈' : '👁️'" aria-label="Toggle password"></button>
                    </div>
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
                </div>
                <div class="flex items-end gap-2 sm:flex-col sm:items-stretch sm:justify-end sm:w-48">
                    <x-secondary-button type="button" class="w-full" @click="confirming=false; showPw=false">
                        {{ __('Annuleren') }}
                    </x-secondary-button>
                    <x-danger-button class="w-full">
                        {{ __('Verwijder definitief') }}
                    </x-danger-button>
                </div>
            </div>
        </form>
    </div>
</section>
