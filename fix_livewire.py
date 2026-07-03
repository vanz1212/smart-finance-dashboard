import os

controller_path = 'app/Http/Controllers/FinanceController.php'
with open(controller_path, 'r', encoding='utf-8') as f:
    controller_code = f.read()

livewire_code = open('generate_livewire.py', encoding='utf-8').read().split('"""')[1]

methods_to_extract = ['calculateResults', 'expenseCategories', 'generateCategoryRecommendations', 'getCategoryHistory', 'getCategoryTemplates', 'normalizeRupiah']

for method in methods_to_extract:
    start_idx = controller_code.find(f'function {method}(')
    if start_idx != -1:
        # find opening brace
        brace_idx = controller_code.find('{', start_idx)
        count = 1
        idx = brace_idx + 1
        while count > 0 and idx < len(controller_code):
            if controller_code[idx] == '{':
                count += 1
            elif controller_code[idx] == '}':
                count -= 1
            idx += 1
        # Extract the method from 'private function' or 'protected function' etc.
        method_def_start = controller_code.rfind('\n', 0, start_idx)
        method_code = controller_code[method_def_start:idx]
        livewire_code += "\n    " + method_code.replace("\n", "\n    ") + "\n"

livewire_code += """
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.smart-finance');
    }
}
"""

with open('app/Livewire/SmartFinance.php', 'w', encoding='utf-8') as f:
    f.write(livewire_code)

print("SmartFinance.php generated successfully.")
