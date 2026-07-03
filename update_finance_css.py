import re

file_path = "c:\\Users\\KINDY\\Documents\\Magang Project\\SmartFinanceDashboard\\resources\\views\\smart_finance.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace background and workspace inner
content = re.sub(
    r'\.finance-workspace\s*\{.*?\n\s*\}',
    '''.finance-workspace {
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

# Replace workspace nav
content = re.sub(
    r'\.workspace-nav a\s*\{.*?\n\s*\}',
    '''.workspace-nav a {
            padding: 10px 14px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 999px;
            color: rgba(248,250,252,.78);
            text-decoration: none;
            font-weight: 800;
            background: rgba(255,255,255,.05);
            transition: all 0.25s ease;
        }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.workspace-nav a\.is-active,\s*\.workspace-nav a:hover\s*\{.*?\n\s*\}',
    '''.workspace-nav a.is-active,
        .workspace-nav a:hover {
            color: #fff;
            background: var(--accent-primary);
            border-color: var(--accent-primary);
            box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
        }''',
    content,
    flags=re.DOTALL
)

# Replace status badge
content = re.sub(
    r'\.status-badge\s*\{.*?\n\s*\}',
    '''.status-badge {
            min-width: 126px;
            padding: 12px 16px;
            border-radius: 999px;
            text-align: center;
            font-weight: 900;
            transition: transform 0.2s ease;
        }
        .status-badge:hover { transform: scale(1.05); }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.status-success\s*\{.*?\n\s*\}',
    '''.status-success { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); }''',
    content,
    flags=re.DOTALL
)
content = re.sub(
    r'\.status-warning\s*\{.*?\n\s*\}',
    '''.status-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); }''',
    content,
    flags=re.DOTALL
)
content = re.sub(
    r'\.status-danger\s*\{.*?\n\s*\}',
    '''.status-danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); }''',
    content,
    flags=re.DOTALL
)

# Replace workspace panel
content = re.sub(
    r'\.workspace-panel\s*\{.*?\n\s*\}',
    '''.workspace-panel {
            border: 1px solid rgba(255,255,255,.1); 
            border-radius: 20px; 
            background: rgba(15, 23, 42, 0.6); 
            box-shadow: 0 20px 60px rgba(0,0,0,.25); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .workspace-panel:hover {
            border-color: rgba(255,255,255,.15);
            box-shadow: 0 25px 70px rgba(0,0,0,.3);
        }''',
    content,
    flags=re.DOTALL
)

# Replace inputs
content = re.sub(
    r'\.finance-form-grid input\s*\{.*?\n\s*\}',
    '''.finance-form-grid input {
            min-height: 48px;
            width: 100%;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            padding: 10px 14px;
            background: rgba(255,255,255,.05);
            color: #fff;
            font: inherit;
            transition: all 0.25s ease;
        }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.finance-form-grid input:focus\s*\{.*?\n\s*\}',
    '''.finance-form-grid input:focus {
            outline: none; 
            border-color: var(--accent-primary); 
            background: rgba(255,255,255,.08);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
        }''',
    content,
    flags=re.DOTALL
)

# Replace button
content = re.sub(
    r'\.workspace-button\s*\{.*?\n\s*\}',
    '''.workspace-button {
            width: 100%;
            min-height: 52px;
            margin-top: 18px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent-primary), #0d9488); 
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
            transition: all 0.25s ease;
        }
        .workspace-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(20, 184, 166, 0.4);
        }
        .workspace-button:active { transform: translateY(0); }''',
    content,
    flags=re.DOTALL
)

# Replace metric tile
content = re.sub(
    r'\.metric-tile\s*\{.*?\n\s*\}',
    '''.metric-tile {
            padding: 18px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            background: rgba(255,255,255,.04);
            transition: all 0.25s ease;
        }
        .metric-tile:hover { 
            background: rgba(255,255,255,.07); 
            border-color: rgba(255,255,255,.15);
            transform: translateY(-2px); 
        }''',
    content,
    flags=re.DOTALL
)

# Replace metric tile spans/strong
content = re.sub(
    r'\.metric-tile span,\s*\.goal-card span\s*\{.*?\n\s*\}',
    '''.metric-tile span,
        .goal-card span {
            display: block;
            margin-bottom: 8px;
            color: rgba(248, 250, 252, 0.55);
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.metric-tile strong,\s*\.goal-card strong\s*\{.*?\n\s*\}',
    '''.metric-tile strong,
        .goal-card strong {
            color: #fff;
            font-size: 1.1rem;
        }''',
    content,
    flags=re.DOTALL
)

# Replace track spans
content = re.sub(
    r'\.track span\s*\{.*?\n\s*\}',
    '''.track span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--accent-primary);
            transition: width 1s ease-in-out;
        }''',
    content,
    flags=re.DOTALL
)

# Replace breakdown item
content = re.sub(
    r'\.breakdown-item\s*\{.*?\n\s*\}',
    '''.breakdown-item {
            display: grid;
            grid-template-columns: minmax(140px, 1fr) minmax(120px, auto) minmax(74px, auto);
            gap: 12px;
            padding: 13px 14px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;
            background: rgba(255,255,255,.03);
            transition: all 0.2s ease;
        }
        .breakdown-item:hover {
            background: rgba(255,255,255,.05);
            border-color: rgba(255,255,255,.15);
        }''',
    content,
    flags=re.DOTALL
)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated smart finance css successfully")
