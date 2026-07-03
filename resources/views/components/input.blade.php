@props([
    'label' => '',
    'id' => '',
    'type' => 'text',
    'icon' => null,
    'prefix' => null
])

<div class="nexio-input-group">
    @if($label)
        <label for="{{ $id }}" class="nexio-input-label">
            {{ $label }}
        </label>
    @endif

    <div class="nexio-input-wrapper {{ $icon ? 'has-icon' : '' }} {{ $prefix ? 'has-prefix' : '' }}">
        @if($icon)
            <div class="input-icon">
                {!! $icon !!}
            </div>
        @endif

        @if($prefix)
            <div class="input-prefix">
                {{ $prefix }}
            </div>
        @endif

        @if($type === 'select')
            <select id="{{ $id }}" {{ $attributes->merge(['class' => 'nexio-input']) }}>
                {{ $slot }}
            </select>
        @else
            <input id="{{ $id }}" type="{{ $type }}" {{ $attributes->merge(['class' => 'nexio-input']) }}>
        @endif
    </div>
</div>
