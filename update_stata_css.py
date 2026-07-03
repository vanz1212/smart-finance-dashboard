import re

file_path = "c:\\Users\\KINDY\\Documents\\Magang Project\\SmartFinanceDashboard\\resources\\views\\stata.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Update workspace background
content = re.sub(
    r'\.stata-workspace\s*\{.*?\n\s*\}',
    '''.stata-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: var(--text-main);
            background:
                radial-gradient(ellipse at 80% 0%, rgba(16, 185, 129, 0.12), transparent 50%),
                radial-gradient(ellipse at 20% 100%, rgba(99, 102, 241, 0.08), transparent 50%),
                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));
        }''',
    content,
    flags=re.DOTALL
)

# Update stata panel
content = re.sub(
    r'\.stata-panel\s*\{.*?\n\s*\}',
    '''.stata-panel {
            border: 1px solid rgba(255,255,255,.1); 
            border-radius: 20px; 
            background: rgba(15, 23, 42, 0.6); 
            box-shadow: 0 20px 60px rgba(0,0,0,.25); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .stata-panel:hover {
            border-color: rgba(255,255,255,.15);
            box-shadow: 0 25px 70px rgba(0,0,0,.3);
        }''',
    content,
    flags=re.DOTALL
)

# Update stata action button
content = re.sub(
    r'\.stata-action\s*\{.*?\n\s*\}',
    '''.stata-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            margin-top: 26px;
            padding: 0 24px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent-primary), #0d9488); 
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
            transition: all 0.25s ease;
        }
        .stata-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(20, 184, 166, 0.4);
        }''',
    content,
    flags=re.DOTALL
)

# Update feature card
content = re.sub(
    r'\.feature-card\s*\{.*?\n\s*\}',
    '''.feature-card {
            min-height: 230px;
            padding: 24px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px;
            background: rgba(255,255,255,.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,.2);
            border-color: rgba(255,255,255,.15);
        }''',
    content,
    flags=re.DOTALL
)

# Update other stata cards
content = re.sub(
    r'\.stata-upload-card,\s*\.stata-dataset-card,\s*\.stata-variable-panel,\s*\.stata-editor-panel,\s*\.stata-result-panel\s*\{.*?\n\s*\}',
    '''.stata-upload-card,
        .stata-dataset-card,
        .stata-variable-panel,
        .stata-editor-panel,
        .stata-result-panel {
            padding: 24px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
        }''',
    content,
    flags=re.DOTALL
)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated stata css successfully")
