@php
    $moduleLinks = [
        [
            'label' => __('app.module_smart_finance'),
            'href' => route('finance.index'),
            'active' => request()->is('smart-finance*') || request()->routeIs('finance.*'),
        ],
        [
            'label' => __('app.module_perpajakan'),
            'href' => route('perpajakan.index'),
            'active' => request()->is('perpajakan*') || request()->routeIs('perpajakan.*'),
        ],
        [
            'label' => __('targets.page_title'),
            'href' => route('targets.index'),
            'active' => request()->is('targets*') || request()->routeIs('targets.*'),
        ],
        [
            'label' => 'Stata',
            'href' => route('stata'),
            'active' => request()->is('stata*') || request()->routeIs('stata*'),
        ],
    ];
@endphp

<div class="module-switcher" aria-label="Navigasi modul finansial">
    <a class="module-switcher-brand" href="{{ route('dashboard.user') }}" wire:navigate>
        <img src="{{ asset('images/nexio_logo.png') }}" alt="Nexio Logo" style="height: 28px; width: auto; border-radius: 6px;">
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
