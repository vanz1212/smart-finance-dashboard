@props(['title' => '', 'icon' => '', 'padding' => '28px'])

<div {{ $attributes->merge(['class' => 'glass-panel nexio-card']) }}>
    @if($title || $icon)
    <div class="nexio-card-header">
        @if($icon)
        <div class="nexio-card-icon">
            {!! $icon !!}
        </div>
        @endif
        @if($title)
        <h2 class="nexio-card-title">{{ $title }}</h2>
        @endif

        @if(isset($headerActions))
            <div class="nexio-card-actions">
                {{ $headerActions }}
            </div>
        @endif
    </div>
    @endif

    <div class="nexio-card-body" style="--card-padding: {{ $padding }};">
        {{ $slot }}
    </div>
</div>
