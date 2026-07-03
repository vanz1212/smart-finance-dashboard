@props(['title' => '', 'icon' => '', 'padding' => '28px'])

<x-card :title="$title" :icon="$icon" :padding="$padding" {{ $attributes }}>
    @isset($headerActions)
        <x-slot:headerActions>
            {{ $headerActions }}
        </x-slot:headerActions>
    @endisset

    {{ $slot }}
</x-card>
