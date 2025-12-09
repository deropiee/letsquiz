<x-app-layout :hideNavigation="true">
    <x-errors.error-card
        text="Dit resultaat is privé"
        link_text="Terug naar resultaten"
        url="/results"
        error_code="403"
    ></x-errors.error-card>
</x-app-layout>