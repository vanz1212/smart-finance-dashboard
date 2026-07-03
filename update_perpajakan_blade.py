import re

path = 'resources/views/livewire/perpajakan.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace <form ... action=... method="POST"> with <form wire:submit="calculate">
content = re.sub(r'<form[^>]*action="[^"]*"[^>]*method="POST"[^>]*>', '<form class="workspace-panel workspace-panel-inner" wire:submit="calculate">', content)

# Remove @csrf
content = content.replace('@csrf', '')

# Replace name="..." with wire:model="..."
fields = ['tahun_pajak', 'metode_perhitungan', 'status_wajib_pajak', 'penghasilan_bulanan', 'penghasilan_tidak_teratur', 'iuran_pensiun', 'zakat', 'kredit_pajak']
for field in fields:
    content = re.sub(rf'name="{field}"', f'wire:model="{field}"', content)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
