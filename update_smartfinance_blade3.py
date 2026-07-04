import re

path = 'resources/views/livewire/smart-finance.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace template buttons to use wire:click
content = re.sub(
    r'<button type="button" class="template-btn" data-template-id="(\{\{ \$template\[\'id\'\] \?\? \'\' \}\})"',
    r'<button type="button" class="template-btn" wire:click.prevent="applyTemplate({{ $template[\'id\'] }})"',
    content
)

# Replace data-rupiah-input with Alpine.js formatting
alpine_handler = 'x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g, \'\').replace(/\\B(?=(\\d{3})+(?!\\d))/g, \'.\')"'
content = content.replace('data-rupiah-input', alpine_handler)

# Replace the expense-list div with a blade foreach loop for Livewire
expense_list_replacement = """
                        <div id="expense-list">
                            @if(count($expenses) > 0)
                                @foreach($expenses as $index => $expense)
                                    <div class="expense-row" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                                        <input type="text" wire:model="expenses.{{ $index }}.name" placeholder="{{ __('finance.category_name') }}" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white;" required>
                                        <div class="money-field" style="flex: 1; display: flex; align-items: center; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding-left: 10px;">
                                            <span style="color: rgba(255,255,255,0.5);">Rp</span>
                                            <input type="text" wire:model="expenses.{{ $index }}.amount" x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\\B(?=(\\d{3})+(?!\\d))/g, '.')" style="flex: 1; border: none; background: transparent; padding: 10px; color: white;" required>
                                        </div>
                                        <label style="display: flex; align-items: center; gap: 5px; color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                                            <input type="checkbox" wire:model="expenses.{{ $index }}.is_debt"> {{ __('finance.is_debt') }}
                                        </label>
                                        <button type="button" wire:click.prevent="removeExpenseRow({{ $index }})" style="background: rgba(239,68,68,0.2); color: #ef4444; border: none; border-radius: 8px; padding: 10px; cursor: pointer;">✕</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
"""
# Find and replace the div block manually
start_div = '<div id="expense-list">'
end_div = '</div>'
start_idx = content.find(start_div)
if start_idx != -1:
    end_idx = content.find(end_div, start_idx)
    if end_idx != -1:
        content = content[:start_idx] + expense_list_replacement + content[end_idx + len(end_div):]

# Change the Add Category button to use wire:click
content = content.replace('id="add-expense-btn"', 'id="add-expense-btn" wire:click.prevent="addExpenseRow"')

# Remove the initial-expenses-data script tag because Livewire handles initial state
content = re.sub(r'<script id="initial-expenses-data" type="application/json">.*?</script>', '', content)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
