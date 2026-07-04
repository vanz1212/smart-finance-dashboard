import re

path = 'resources/views/livewire/smart-finance.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Remove the first broken script block (the one that had data-rupiah-input handling)
# It looks like:
# <script>
#     document.addEventListener('DOMContentLoaded', function () {
#         var fields = document.querySelectorAll(...);
#         ...
#     });
# </script>
content = re.sub(r'<script>\s*document\.addEventListener\(\'DOMContentLoaded\', function \(\) \{\s*var fields = document\.querySelectorAll\(.*?\);\s*.*?\s*\}\);\s*</script>', '', content, flags=re.DOTALL)

# 2. Extract the Category trend chart logic and replace the template logic
start_marker = '// Template selector functionality'
end_marker = '// Category trend chart'

if start_marker in content and end_marker in content:
    start_idx = content.find(start_marker)
    end_idx = content.find(end_marker)
    # The start_idx is preceded by document.addEventListener('DOMContentLoaded' ...
    # We want to remove everything from start_marker to end_marker
    content = content[:start_idx] + content[end_idx:]

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
