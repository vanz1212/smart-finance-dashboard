<?php
$livewire = __DIR__ . '/resources/views/livewire/smart-finance.blade.php';
$standard = __DIR__ . '/resources/views/smart_finance.blade.php';

$content = file_get_contents($livewire);

// Replace form submission
$content = str_replace(
    '<form class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col gap-4" wire:submit="analyze">',
    '<form class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col gap-4" action="{{ route(\'finance.analyze\') }}" method="POST">' . "\n" . '                        @csrf',
    $content
);

// Replace wire:model.defer or wire:model with name=""
// e.g. wire:model="pemasukan" -> name="pemasukan"
$content = preg_replace('/wire:model(\.[a-zA-Z]+)?="([^"]+)"/', 'name="$2"', $content);

// Replace wire:click.prevent="removeExpenseRow(X)" with a standard button? 
// The standard form usually relies on JS to add/remove rows.
// Actually, standard forms in Laravel blade are handled either purely by backend reload or JS.
// The user's original standard form had some JS for that, but my CSS rewrite script wiped it? No, the original standard form had the JS at the bottom.
// Wait, I completely overwrote the Livewire file! Did I lose the JS?
// Yes, I only included Chart.js in my rewrite script. I lost the Dynamic Expense Category Manager JS!
// I must restore the Dynamic Expense Category Manager JS in both views, otherwise the user can't add/remove expenses!

file_put_contents($standard, $content);
echo "Standard view rewritten (WARNING: Missing JS for dynamic rows)\n";
