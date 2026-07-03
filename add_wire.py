import re

path = 'resources/views/page_selector.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace('<a class="module-card"', '<a wire:navigate class="module-card"')
content = content.replace('<a href="', '<a wire:navigate href="')
# Fix any double wire:navigate if I ran it before
content = content.replace('<a wire:navigate wire:navigate', '<a wire:navigate')

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
