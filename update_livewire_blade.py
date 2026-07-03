import re

path = 'resources/views/livewire/smart-finance.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace <form ... action=... method="POST"> with <form wire:submit="analyze">
content = re.sub(r'<form[^>]*action="[^"]*"[^>]*method="POST"[^>]*>', '<form class="workspace-panel workspace-panel-inner" wire:submit="analyze">', content)

# Remove @csrf
content = content.replace('@csrf', '')

# Replace name="pemasukan" with wire:model="pemasukan"
fields = ['periode', 'pemasukan', 'tabungan', 'saldo_tabungan', 'setoran_tabungan', 'investasi', 'dana_darurat', 'target_tabungan']
for field in fields:
    content = re.sub(rf'name="{field}"', f'wire:model="{field}"', content)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
