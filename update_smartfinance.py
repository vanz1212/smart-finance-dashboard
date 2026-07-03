import os

path = 'app/Livewire/SmartFinance.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

template_method = """
    public function applyTemplate($templateId)
    {
        $this->pemasukan = $this->normalize($this->pemasukan);
        if ($this->pemasukan <= 0) {
            $this->addError('pemasukan', __('finance.select_income_first'));
            return;
        }

        $template = ExpenseCategoryTemplate::find($templateId);
        if (!$template) return;

        $this->expenses = [];
        $this->usingDynamic = true;
        foreach ($template->categories as $category) {
            $amount = ($this->pemasukan * $category['ratio_percent']) / 100;
            $this->expenses[] = [
                'name' => $category['name'],
                'amount' => round($amount, 2),
                'is_debt' => $category['is_debt'] ?? false,
            ];
        }
    }
"""

if 'function applyTemplate' not in content:
    content = content.replace('public function addExpenseRow()', template_method + '\n    public function addExpenseRow()')
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
