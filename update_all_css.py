import os

base_path = "c:\\Users\\KINDY\\Documents\\Magang Project\\SmartFinanceDashboard\\resources\\views\\"

def update_file(filename, replacements):
    path = os.path.join(base_path, filename)
    if not os.path.exists(path):
        return
    with open(path, "r", encoding="utf-8") as f:
        content = f.read()
    
    for old, new in replacements:
        content = content.replace(old, new, 1) # Only replace the first occurrence!
        
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)

# 1. SMART FINANCE
smart_finance_replacements = [
    (
        ".finance-workspace {\n            margin: -24px;\n            min-height: calc(100vh - 1px);\n            padding: 34px 24px 56px;\n            color: var(--text-main);\n            background:\n                linear-gradient(180deg, var(--bg-primary), var(--bg-primary)),\n                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;\n        }",
        ".finance-workspace {\n            margin: -24px;\n            min-height: calc(100vh - 1px);\n            padding: 34px 24px 56px;\n            color: var(--text-main);\n            background:\n                radial-gradient(ellipse at 80% 0%, rgba(16, 185, 129, 0.12), transparent 50%),\n                radial-gradient(ellipse at 20% 100%, rgba(99, 102, 241, 0.08), transparent 50%),\n                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));\n        }"
    ),
    (
        ".workspace-panel {\n            border: 1px solid var(--border-color);\n            border-radius: 12px;\n            background: var(--nav-bg);\n        }",
        ".workspace-panel {\n            border: 1px solid rgba(255,255,255,.1); \n            border-radius: 20px; \n            background: rgba(15, 23, 42, 0.6); \n            box-shadow: 0 20px 60px rgba(0,0,0,.25); \n            backdrop-filter: blur(20px); \n            -webkit-backdrop-filter: blur(20px);\n            transition: border-color 0.3s ease, box-shadow 0.3s ease;\n        }\n        .workspace-panel:hover {\n            border-color: rgba(255,255,255,.15);\n            box-shadow: 0 25px 70px rgba(0,0,0,.3);\n        }"
    ),
    (
        ".workspace-button {\n            width: 100%;\n            min-height: 48px;\n            margin-top: 18px;\n            border: 0;\n            border-radius: 12px;\n            background: var(--accent-primary);\n            color: var(--accent-hover);\n            cursor: pointer;\n            font: inherit;\n            font-weight: 800;\n            font-size: .95rem;\n        }",
        ".workspace-button {\n            width: 100%;\n            min-height: 52px;\n            margin-top: 18px;\n            border: 0;\n            border-radius: 14px;\n            background: linear-gradient(135deg, var(--accent-primary), #0d9488); \n            color: #fff;\n            cursor: pointer;\n            font: inherit;\n            font-weight: 800;\n            font-size: 1rem;\n            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);\n            transition: all 0.25s ease;\n        }\n        .workspace-button:hover {\n            transform: translateY(-2px);\n            box-shadow: 0 12px 28px rgba(20, 184, 166, 0.4);\n        }\n        .workspace-button:active { transform: translateY(0); }"
    )
]

update_file("smart_finance.blade.php", smart_finance_replacements)

# 2. STATA
stata_replacements = [
    (
        ".stata-workspace {\n            margin: -24px;\n            min-height: calc(100vh - 1px);\n            padding: 34px 24px 56px;\n            color: var(--text-main);\n            background:\n                linear-gradient(180deg, var(--bg-primary), var(--bg-primary)),\n                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;\n        }",
        ".stata-workspace {\n            margin: -24px;\n            min-height: calc(100vh - 1px);\n            padding: 34px 24px 56px;\n            color: var(--text-main);\n            background:\n                radial-gradient(ellipse at 80% 0%, rgba(16, 185, 129, 0.12), transparent 50%),\n                radial-gradient(ellipse at 20% 100%, rgba(99, 102, 241, 0.08), transparent 50%),\n                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));\n        }"
    ),
    (
        ".stata-panel {\n            border: 1px solid rgba(255,255,255,.14);\n            border-radius: 14px;\n            background: linear-gradient(180deg, rgba(13,47,51,.78), rgba(6,24,32,.84));\n            box-shadow: 0 28px 80px rgba(0,0,0,.34);\n            backdrop-filter: blur(16px);\n        }",
        ".stata-panel {\n            border: 1px solid rgba(255,255,255,.1); \n            border-radius: 20px; \n            background: rgba(15, 23, 42, 0.6); \n            box-shadow: 0 20px 60px rgba(0,0,0,.25); \n            backdrop-filter: blur(20px); \n            -webkit-backdrop-filter: blur(20px);\n            transition: border-color 0.3s ease, box-shadow 0.3s ease;\n        }\n        .stata-panel:hover {\n            border-color: rgba(255,255,255,.15);\n            box-shadow: 0 25px 70px rgba(0,0,0,.3);\n        }"
    ),
    (
        ".stata-action { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; margin-top: 26px; padding: 0 20px; border-radius: 999px; background: var(--accent-primary); color: var(--accent-hover); text-decoration: none; font-weight: 900; }",
        ".stata-action {\n            display: inline-flex;\n            align-items: center;\n            justify-content: center;\n            min-height: 52px;\n            margin-top: 26px;\n            padding: 0 24px;\n            border-radius: 14px;\n            background: linear-gradient(135deg, var(--accent-primary), #0d9488); \n            color: #fff;\n            text-decoration: none;\n            font-weight: 800;\n            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);\n            transition: all 0.25s ease;\n        }\n        .stata-action:hover {\n            transform: translateY(-2px);\n            box-shadow: 0 12px 28px rgba(20, 184, 166, 0.4);\n        }"
    ),
    (
        ".feature-card {\n            min-height: 230px;\n            padding: 24px;\n            border: 1px solid rgba(255,255,255,.14);\n            border-radius: 14px;\n            background: linear-gradient(180deg, rgba(255,255,255,.07), rgba(243,201,105,.07));\n            box-shadow: 0 22px 60px rgba(0,0,0,.24);\n        }",
        ".feature-card {\n            min-height: 230px;\n            padding: 24px;\n            border: 1px solid rgba(255,255,255,.08);\n            border-radius: 16px;\n            background: rgba(255,255,255,.04);\n            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;\n        }\n        .feature-card:hover {\n            transform: translateY(-4px);\n            box-shadow: 0 12px 30px rgba(0,0,0,.2);\n            border-color: rgba(255,255,255,.15);\n        }"
    )
]

update_file("stata.blade.php", stata_replacements)

# 3. PROFILE
profile_replacements = [
    (
        ".profile-page {\n            min-height: 100vh;\n            display: grid;\n            place-items: center;\n            padding: 28px;\n            color: #f8fafc;\n            background:\n                linear-gradient(135deg, rgba(7, 11, 20, 0.85) 0%, rgba(7, 11, 20, 0.98) 100%),\n                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;\n        }",
        ".profile-page {\n            min-height: 100vh;\n            display: grid;\n            place-items: center;\n            padding: 28px;\n            color: #f8fafc;\n            background:\n                radial-gradient(ellipse at 80% 0%, rgba(16, 185, 129, 0.12), transparent 50%),\n                radial-gradient(ellipse at 20% 100%, rgba(99, 102, 241, 0.08), transparent 50%),\n                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));\n        }"
    ),
    (
        ".profile-dashboard {\n            display: flex;\n            width: min(1200px, 100%);\n            min-height: 650px;\n            border-radius: 20px;\n            overflow: hidden;\n            background: rgba(7, 10, 19, 0.7);\n            border: 1px solid rgba(255, 255, 255, 0.08);\n            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.6);\n            backdrop-filter: blur(20px);\n            -webkit-backdrop-filter: blur(20px);\n        }",
        ".profile-dashboard {\n            display: flex;\n            width: min(1200px, 100%);\n            min-height: 650px;\n            border-radius: 20px;\n            overflow: hidden;\n            background: rgba(15, 23, 42, 0.6);\n            border: 1px solid rgba(255, 255, 255, 0.1);\n            box-shadow: 0 20px 60px rgba(0,0,0,.25);\n            backdrop-filter: blur(20px);\n            -webkit-backdrop-filter: blur(20px);\n        }"
    )
]

update_file("profile.blade.php", profile_replacements)

# 4. TARGETS
targets_replacements = [
    (
        ".targets-workspace {\n            margin: -24px;\n            min-height: calc(100vh - 1px);\n            padding: 34px 24px 56px;\n            color: #f8fafc;\n            background:\n                linear-gradient(180deg, rgba(5, 12, 15, 0.76), rgba(5, 12, 15, 0.97)),\n                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;\n        }",
        ".targets-workspace {\n            margin: -24px;\n            min-height: calc(100vh - 1px);\n            padding: 34px 24px 56px;\n            color: var(--text-main);\n            background:\n                radial-gradient(ellipse at 80% 0%, rgba(16, 185, 129, 0.12), transparent 50%),\n                radial-gradient(ellipse at 20% 100%, rgba(99, 102, 241, 0.08), transparent 50%),\n                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));\n        }"
    ),
    (
        ".stat-card {\n            padding: 18px;\n            border: 1px solid rgba(255, 255, 255, 0.12);\n            border-radius: 12px;\n            background: rgba(13, 47, 51, 0.6);\n        }",
        ".stat-card {\n            padding: 18px;\n            border: 1px solid rgba(255,255,255,.08);\n            border-radius: 14px;\n            background: rgba(255,255,255,.04);\n            transition: all 0.25s ease;\n        }\n        .stat-card:hover { \n            background: rgba(255,255,255,.07); \n            border-color: rgba(255,255,255,.15);\n            transform: translateY(-2px); \n        }"
    ),
    (
        ".target-card {\n            padding: 20px;\n            border: 1px solid rgba(255, 255, 255, 0.12);\n            border-radius: 14px;\n            background: linear-gradient(180deg, rgba(13, 47, 51, 0.78), rgba(6, 24, 32, 0.84));\n            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.34);\n            backdrop-filter: blur(16px);\n            transition: all 0.3s;\n        }",
        ".target-card {\n            padding: 24px;\n            border: 1px solid rgba(255,255,255,.08);\n            border-radius: 16px;\n            background: rgba(15, 23, 42, 0.6);\n            backdrop-filter: blur(16px);\n            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;\n        }"
    ),
    (
        ".target-card:hover {\n            border-color: rgba(20, 184, 166, 0.3);\n            background: linear-gradient(180deg, rgba(13, 47, 51, 0.88), rgba(6, 24, 32, 0.92));\n        }",
        ".target-card:hover {\n            transform: translateY(-4px);\n            box-shadow: 0 12px 30px rgba(0,0,0,.2);\n            border-color: rgba(255,255,255,.15);\n        }"
    )
]

update_file("financial_targets/index.blade.php", targets_replacements)

print("CSS files updated successfully and safely.")
