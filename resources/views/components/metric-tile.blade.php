@props([
    'title' => '',
    'value' => '',
    'highlight' => false,
    'fullWidth' => false,
    'variant' => 'primary'
])

@php
    $baseClass = "metric-tile";
    if ($highlight) $baseClass .= " highlight";
    if ($fullWidth) $baseClass .= " full-width";
    if ($variant) $baseClass .= " variant-".$variant;
@endphp

<div class="{{ $baseClass }}">
    <span>{{ $title }}</span>
    <strong>{{ $value }}</strong>
</div>

<style>
    .metric-tile { 
        background: rgba(15, 23, 42, 0.4); 
        border: 1px solid rgba(255,255,255,.05); 
        border-radius: 16px; 
        padding: 20px; 
        transition: background 0.2s ease, transform 0.2s ease; 
        display: flex; 
        flex-direction: column; 
        gap: 8px; 
    }
    .metric-tile:hover { 
        background: rgba(15, 23, 42, 0.6); 
        transform: translateY(-2px);
    }
    .metric-tile span { 
        color: rgba(248,250,252,.5); 
        font-size: .85rem; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
    }
    .metric-tile strong { 
        color: #f8fafc; 
        font-size: 1.3rem; 
        font-weight: 800; 
        line-height: 1.2; 
    }
    .metric-tile.highlight { 
        background: linear-gradient(135deg, var(--nexio-primary-light), rgba(79, 70, 229, 0.1)); 
        border-color: rgba(99, 102, 241, 0.3); 
    }
    .metric-tile.highlight strong { 
        color: var(--nexio-primary); 
        font-size: 1.6rem; 
    }
    
    .metric-tile.variant-success.highlight {
        background: linear-gradient(135deg, var(--nexio-success-bg), rgba(16, 185, 129, 0.05));
        border-color: rgba(16, 185, 129, 0.3);
    }
    .metric-tile.variant-success.highlight strong { color: var(--nexio-success); }
    
    .metric-tile.variant-warning.highlight {
        background: linear-gradient(135deg, var(--nexio-warning-bg), rgba(245, 158, 11, 0.05));
        border-color: rgba(245, 158, 11, 0.3);
    }
    .metric-tile.variant-warning.highlight strong { color: var(--nexio-warning); }

    .metric-tile.variant-danger.highlight {
        background: linear-gradient(135deg, var(--nexio-danger-bg), rgba(239, 68, 68, 0.05));
        border-color: rgba(239, 68, 68, 0.3);
    }
    .metric-tile.variant-danger.highlight strong { color: var(--nexio-danger); }

    .metric-tile.full-width { grid-column: 1 / -1; }
</style>
