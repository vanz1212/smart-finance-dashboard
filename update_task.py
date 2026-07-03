path = 'C:/Users/KINDY/.gemini/antigravity-ide/brain/ff38c1b9-d48c-4e2a-bfc7-770641edb9d9/task.md'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace('`[/]` 2. **Modul Smart Finance**', '`[x]` 2. **Modul Smart Finance**')
content = content.replace('`[ ]` Sesuaikan *Javascript* Chart.js', '`[x]` Sesuaikan *Javascript* Chart.js')
content = content.replace('`[ ]` 3. **Modul Perpajakan**', '`[x]` 3. **Modul Perpajakan**')
content = content.replace('`[ ]` Buat file `app/Livewire/Perpajakan.php`', '`[x]` Buat file `app/Livewire/Perpajakan.php`')
content = content.replace('`[ ]` Refaktor *view* `perpajakan.blade.php`', '`[x]` Refaktor *view* `perpajakan.blade.php`')
content = content.replace('`[ ]` 4. **Penyesuaian Routes**', '`[x]` 4. **Penyesuaian Routes**')
content = content.replace('`[ ]` Update `routes/web.php`', '`[x]` Update `routes/web.php`')
content = content.replace('`[ ]` 5. **Verifikasi Akhir**', '`[x]` 5. **Verifikasi Akhir**')
content = content.replace('`[ ]` Uji alur simulasi', '`[x]` Uji alur simulasi')

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
