<x-filament::page>
    <form wire:submit.prevent="submit" class="grid grid-cols-1 gap-4 md:grid-cols-3">
        {{ $this->form }}
    </form>
</x-filament::page>
