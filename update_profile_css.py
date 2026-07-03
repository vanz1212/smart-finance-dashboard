import re

file_path = "c:\\Users\\KINDY\\Documents\\Magang Project\\SmartFinanceDashboard\\resources\\views\\profile.blade.php"

with open(file_path, "r", encoding="utf-8") as f:
    content = f.read()

# Update page background
content = re.sub(
    r'\.profile-page\s*\{.*?\n\s*\}',
    '''.profile-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 28px;
            color: #f8fafc;
            background:
                radial-gradient(ellipse at 80% 0%, rgba(16, 185, 129, 0.12), transparent 50%),
                radial-gradient(ellipse at 20% 100%, rgba(99, 102, 241, 0.08), transparent 50%),
                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));
        }''',
    content,
    flags=re.DOTALL
)

# Update dashboard container
content = re.sub(
    r'\.profile-dashboard\s*\{.*?\n\s*\}',
    '''.profile-dashboard {
            display: flex;
            width: min(1200px, 100%);
            min-height: 650px;
            border-radius: 20px;
            overflow: hidden;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }''',
    content,
    flags=re.DOTALL
)

# Update sidebar
content = re.sub(
    r'\.profile-sidebar\s*\{.*?\n\s*\}',
    '''.profile-sidebar {
            width: 280px;
            background: linear-gradient(180deg, rgba(13, 17, 30, 0.8) 0%, rgba(13, 17, 30, 0.6) 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
            position: relative;
        }''',
    content,
    flags=re.DOTALL
)

# Update active menu item
content = re.sub(
    r'\.sidebar-menu a\.active\s*\{.*?\n\s*\}',
    '''.sidebar-menu a.active {
            background: var(--accent-primary);
            color: #ffffff;
            border-color: var(--accent-primary);
            box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
        }''',
    content,
    flags=re.DOTALL
)

# Update glass cards
content = re.sub(
    r'\.glass-card\s*\{.*?\n\s*\}',
    '''.glass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.card-input:focus\s*\{.*?\n\s*\}',
    '''.card-input:focus {
            border-color: var(--accent-primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
        }''',
    content,
    flags=re.DOTALL
)

content = re.sub(
    r'\.card-btn\s*\{.*?\n\s*\}',
    '''.card-btn {
            background: linear-gradient(135deg, var(--accent-primary), #0d9488);
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.25s ease;
            text-align: center;
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
        }''',
    content,
    flags=re.DOTALL
)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated profile css successfully")
