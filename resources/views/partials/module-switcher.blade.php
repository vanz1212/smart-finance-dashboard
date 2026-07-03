@php
    $moduleLinks = [
        [
            'label' => __('app.module_smart_finance'),
            'href' => route('finance.index'),
            'active' => request()->routeIs('finance.*'),
        ],
        [
            'label' => __('app.module_perpajakan'),
            'href' => route('perpajakan.index'),
            'active' => request()->routeIs('perpajakan.*'),
        ],
        [
            'label' => 'Stata',
            'href' => route('stata'),
            'active' => request()->routeIs('stata'),
        ],
        [
            'label' => __('targets.page_title'),
            'href' => route('targets.index'),
            'active' => request()->routeIs('targets.*'),
        ],
    ];
@endphp

<div class="module-switcher" aria-label="Navigasi modul finansial">
    <a class="module-switcher-brand" href="{{ route('dashboard.user') }}" wire:navigate>
        <span>N</span>
        <strong>NEXIO</strong>
    </a>

    <nav class="module-switcher-nav">
        @foreach ($moduleLinks as $link)
            <a href="{{ $link['href'] }}" wire:navigate class="{{ $link['active'] ? 'is-active' : '' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
</div>
