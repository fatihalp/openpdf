<x-filament-panels::page>
    {{ $this->table }}

    <x-filament::modal id="display-token" width="md" :close-by-clicking-away="false">
        <x-slot name="heading">
            API Token Generated
        </x-slot>

        <x-slot name="description">
            Please copy this token now. For security reasons, it will not be shown again.
        </x-slot>

        <div class="mt-4 p-4 bg-gray-100 rounded-lg break-all font-mono text-sm text-gray-800 border border-gray-200">
            {{ $plainTextToken }}
        </div>

        <x-slot name="footerActions">
            <x-filament::button color="gray" x-on:click="$dispatch('close-modal', { id: 'display-token' })">
                Close
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>