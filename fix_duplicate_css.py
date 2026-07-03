import re
import os

base_path = r"c:\Users\KINDY\Documents\Magang Project\SmartFinanceDashboard\resources\views"

files = [
    "stata.blade.php",
    "smart_finance.blade.php",
    "perpajakan.blade.php",
    "financial_targets/index.blade.php"
]

for filename in files:
    path = os.path.join(base_path, filename)
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    # We want to find where the injected block starts.
    # It might start with '/* Full-page refinement' or 'html,\n        body {'
    
    # Use regex to find everything from 'html,\n        body {' down to the last '}' before '</style>'
    # Actually, it's safer to just search for 'html,\n        body {' and slice from there to '</style>'
    
    idx = content.find("html,\n        body {")
    if idx == -1:
        idx = content.find("html,\n    body {")
        
    if idx != -1:
        # Find the comment if it exists right before it
        comment_idx1 = content.rfind("/* Full-page refinement", 0, idx)
        comment_idx2 = content.rfind("/* Standalone Module", 0, idx)
        
        start_idx = idx
        if comment_idx1 != -1 and (idx - comment_idx1) < 150:
            start_idx = comment_idx1
        elif comment_idx2 != -1 and (idx - comment_idx2) < 150:
            start_idx = comment_idx2
            
        end_idx = content.find("</style>", start_idx)
        if end_idx != -1:
            print(f"Fixing {filename}, removing {end_idx - start_idx} characters")
            new_content = content[:start_idx] + "    " + content[end_idx:]
            with open(path, "w", encoding="utf-8") as f:
                f.write(new_content)
        else:
            print(f"Could not find </style> in {filename}")
    else:
        print(f"Could not find html, body in {filename}")
