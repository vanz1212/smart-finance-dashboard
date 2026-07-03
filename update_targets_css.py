import re

file_path = "c:\\Users\\KINDY\\Documents\\Magang Project\\SmartFinanceDashboard\\resources\\views\\financial_targets\\index.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Update workspace background
content = re.sub(
    r'\.targets-workspace\s*\{.*?\n\s*\}',
    '''.targets-workspace {
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

# Update stat card
content = re.sub(
    r'\.stat-card\s*\{.*?\n\s*\}',
    '''.stat-card {
            padding: 18px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            background: rgba(255,255,255,.04);
            transition: all 0.25s ease;
        }
        .stat-card:hover { 
            background: rgba(255,255,255,.07); 
            border-color: rgba(255,255,255,.15);
            transform: translateY(-2px); 
        }''',
    content,
    flags=re.DOTALL
)

# Update primary button
content = re.sub(
    r'\.btn-primary\s*\{.*?\n\s*\}',
    '''.btn-primary {
            padding: 12px 24px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent-primary), #0d9488); 
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
            transition: all 0.25s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(20, 184, 166, 0.4);
        }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.btn-primary:hover\s*\{.*?\n\s*\}',
    '',
    content,
    flags=re.DOTALL
)

# Update target card
content = re.sub(
    r'\.target-card\s*\{.*?\n\s*\}',
    '''.target-card {
            padding: 24px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .target-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,.2);
            border-color: rgba(255,255,255,.15);
        }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.target-card:hover\s*\{.*?\n\s*\}',
    '',
    content,
    flags=re.DOTALL
)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated targets index css successfully")
