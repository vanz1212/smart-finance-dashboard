<?php
$files = [
    __DIR__ . '/resources/views/livewire/smart-finance.blade.php',
    __DIR__ . '/resources/views/smart_finance.blade.php'
];

foreach ($files as $filepath) {
    if (!file_exists($filepath)) continue;
    $content = file_get_contents($filepath);
    
    // Replace main wrapper
    $content = str_replace(
        '<main class="finance-workspace">', 
        '<main class="flex-1 overflow-y-auto p-4 md:p-10 bg-background text-on-surface font-body-md pb-32">', 
        $content
    );
    
    $content = str_replace(
        '<div class="workspace-inner">', 
        '<div class="max-w-7xl mx-auto space-y-8">', 
        $content
    );

    // Hero section
    $content = str_replace(
        '<section class="workspace-hero module-hero">', 
        '<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">', 
        $content
    );
    $content = str_replace(
        '<div class="module-hero-panel module-hero-copy">', 
        '<div>', 
        $content
    );
    $content = str_replace(
        '<span class="workspace-kicker">Finance Intelligence</span>', 
        '<h2 class="font-display-lg-mobile md:font-display-lg text-primary">Financial Planning</h2>', 
        $content
    );
    $content = preg_replace(
        '/<h1[^>]*>.*?<\/h1>/', 
        '<p class="font-body-lg text-on-surface-variant mt-2">{{ __(\'finance.hero_desc\') }}</p>', 
        $content
    );
    $content = preg_replace(
        '/<p[^>]*>{{ __\(\'finance\.hero_desc\'\).*?<\/p>/', 
        '', 
        $content
    );

    // Form and Grid
    $content = str_replace(
        '<section class="workspace-grid">', 
        '<div class="grid grid-cols-1 md:grid-cols-12 gap-6">', 
        $content
    );

    $content = str_replace(
        '<form class="workspace-panel workspace-panel-inner" wire:submit="analyze">', 
        '<form class="md:col-span-5 bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col gap-4" wire:submit="analyze">', 
        $content
    );

    $content = str_replace(
        '<form class="workspace-panel workspace-panel-inner" action="{{ route(\'finance.analyze\') }}" method="POST">', 
        '<form class="md:col-span-5 bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col gap-4" action="{{ route(\'finance.analyze\') }}" method="POST">', 
        $content
    );
    
    $content = str_replace(
        '<div class="workspace-panel workspace-panel-inner">', 
        '<div class="md:col-span-7 flex flex-col gap-6">', 
        $content
    );

    // Metric Grid
    $content = str_replace(
        '<div class="metric-grid">', 
        '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">', 
        $content
    );
    $content = str_replace(
        '<div class="metric-tile">', 
        '<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-sm">', 
        $content
    );

    // Insight Box
    $content = str_replace(
        '<div class="insight-box">', 
        '<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm">', 
        $content
    );

    $content = str_replace(
        '<section class="workspace-panel workspace-panel-inner breakdown-panel">', 
        '<section class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm mt-6">', 
        $content
    );

    $content = str_replace(
        '<section class="workspace-panel workspace-panel-inner comparison-panel">', 
        '<section class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm mt-6">', 
        $content
    );
    
    // Panel heading h2
    $content = preg_replace(
        '/<h2[^>]*>(.*?)<\/h2>/', 
        '<h3 class="font-headline-sm text-primary">$1</h3>', 
        $content
    );

    file_put_contents($filepath, $content);
    echo "Replaced HTML structure in " . basename($filepath) . "\n";
}
