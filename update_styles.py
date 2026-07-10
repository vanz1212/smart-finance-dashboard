import re
import os

livewire_path = r"d:\SmartFinanceDashboard\resources\views\livewire\smart-finance.blade.php"
standard_path = r"d:\SmartFinanceDashboard\resources\views\smart_finance.blade.php"

tailwind_injection = """    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary": "#ffffff",
                        "inverse-primary": "#bec6e0",
                        "on-primary-fixed-variant": "#3f465c",
                        "on-primary": "#ffffff",
                        "secondary-container": "#316bf3",
                        "on-surface": "#191c1e",
                        "on-primary-container": "#7c839b",
                        "outline": "#76777d",
                        "surface-container": "#eceef0",
                        "surface-tint": "#565e74",
                        "tertiary-fixed": "#6ffbbe",
                        "on-error-container": "#93000a",
                        "inverse-on-surface": "#eff1f3",
                        "on-primary-fixed": "#131b2e",
                        "on-tertiary-container": "#009668",
                        "inverse-surface": "#2d3133",
                        "surface-dim": "#d8dadc",
                        "on-secondary": "#ffffff",
                        "secondary-fixed": "#dbe1ff",
                        "surface-variant": "#e0e3e5",
                        "on-secondary-fixed": "#00174b",
                        "tertiary-container": "#002113",
                        "surface-container-highest": "#e0e3e5",
                        "secondary": "#0051d5",
                        "surface": "#f7f9fb",
                        "on-surface-variant": "#45464d",
                        "primary-container": "#131b2e",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-low": "#f2f4f6",
                        "error-container": "#ffdad6",
                        "primary": "#000000",
                        "error": "#ba1a1a",
                        "secondary-fixed-dim": "#b4c5ff",
                        "primary-fixed-dim": "#bec6e0",
                        "outline-variant": "#c6c6cd",
                        "on-tertiary-fixed": "#002113",
                        "tertiary": "#000000",
                        "on-error": "#ffffff",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-secondary-fixed-variant": "#003ea8",
                        "surface-container-lowest": "#ffffff",
                        "background": "#f7f9fb",
                        "on-secondary-container": "#fefcff",
                        "on-background": "#191c1e",
                        "primary-fixed": "#dae2fd",
                        "tertiary-fixed-dim": "#4edea3",
                        "surface-bright": "#f7f9fb"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "body-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "label-md": ["Inter"],
                        "stats-num": ["Inter"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"]
                    }
                }
            }
        }
    </script>
    <style>
        .finance-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: var(--text-main, #191c1e);
            background: theme('colors.background');
            font-family: theme('fontFamily.body-md');
        }

        .workspace-inner {
            width: min(1180px, 100%);
            margin: 0 auto;
        }

        .workspace-topbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: center;
            margin-bottom: 34px;
        }

        .workspace-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .workspace-nav a {
            padding: 10px 14px;
            border: 1px solid theme('colors.outline-variant');
            border-radius: 999px;
            color: theme('colors.on-surface-variant');
            text-decoration: none;
            font-weight: 600;
            background: theme('colors.surface-container-low');
        }

        .workspace-nav a.is-active,
        .workspace-nav a:hover {
            color: theme('colors.on-secondary');
            background: theme('colors.secondary');
            border-color: theme('colors.secondary');
        }

        .workspace-hero {
            display: flex;
            justify-content: space-between;
            gap: 22px;
            align-items: flex-end;
            margin-bottom: 28px;
        }

        .workspace-kicker {
            color: theme('colors.secondary');
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .workspace-hero h1 {
            margin: 12px 0 0;
            font-family: theme('fontFamily.display-lg');
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            line-height: 0.98;
            letter-spacing: -0.02em;
            font-weight: 700;
            color: theme('colors.primary');
        }

        .workspace-hero p {
            max-width: 680px;
            margin: 16px 0 0;
            color: theme('colors.on-surface-variant');
            line-height: 1.7;
        }

        .status-badge {
            min-width: 126px;
            padding: 12px 16px;
            border-radius: 999px;
            text-align: center;
            font-weight: 700;
        }

        .status-success { background: theme('colors.tertiary-fixed-dim'); color: theme('colors.on-tertiary-fixed'); }
        .status-warning { background: theme('colors.secondary-fixed'); color: theme('colors.on-secondary-fixed'); }
        .status-danger { background: theme('colors.error-container'); color: theme('colors.on-error-container'); }

        .workspace-grid {
            display: grid;
            grid-template-columns: minmax(320px, 0.92fr) minmax(380px, 1.08fr);
            gap: 24px;
            align-items: start;
        }

        /* Forms / Cards */
        .workspace-panel {
            border: 1px solid theme('colors.outline-variant');
            border-radius: theme('borderRadius.xl');
            background: theme('colors.surface-container-lowest');
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .workspace-panel-inner {
            padding: 24px;
        }

        .panel-heading { margin-bottom: 20px; }
        .panel-heading h2 { margin: 0; font-family: theme('fontFamily.headline-sm'); font-size: 1.25rem; font-weight: 600; color: theme('colors.primary'); }
        .panel-heading p { margin: 8px 0 0; color: theme('colors.on-surface-variant'); line-height: 1.6; }

        .finance-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .finance-form-grid label { display: grid; gap: 8px; }
        .finance-form-grid span { color: theme('colors.on-surface-variant'); font-size: 0.875rem; font-weight: 600; }
        
        .finance-form-grid input, .expense-row input[type="text"] {
            min-height: 46px; width: 100%;
            border: 1px solid theme('colors.outline-variant');
            border-radius: theme('borderRadius.DEFAULT');
            padding: 10px 12px;
            background: theme('colors.surface-container-lowest');
            color: theme('colors.on-surface');
            font-family: theme('fontFamily.body-md');
        }
        .finance-form-grid input:focus, .expense-row input[type="text"]:focus {
            outline: none;
            border-color: theme('colors.secondary');
            box-shadow: 0 0 0 2px rgba(0, 81, 213, 0.2);
        }
        .money-field { position: relative; }
        .money-prefix { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: theme('colors.on-surface-variant'); font-size: 0.875rem; font-weight: 600; pointer-events: none; }
        .money-field input { padding-left: 42px; }

        .workspace-button {
            width: 100%; min-height: 50px; margin-top: 24px;
            border: 0; border-radius: theme('borderRadius.DEFAULT');
            background: theme('colors.secondary'); color: theme('colors.on-secondary');
            cursor: pointer; font-weight: 600; font-size: 1rem;
            transition: opacity 0.2s;
        }
        .workspace-button:hover { opacity: 0.9; }

        .metric-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .metric-tile { padding: 16px; border: 1px solid theme('colors.outline-variant'); border-radius: theme('borderRadius.lg'); background: theme('colors.surface-container-lowest'); box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .metric-tile span, .goal-card span { display: block; margin-bottom: 8px; color: theme('colors.on-surface-variant'); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; }
        .metric-tile strong, .goal-card strong { color: theme('colors.primary'); font-size: 1.5rem; font-family: theme('fontFamily.stats-num'); font-weight: 700; letter-spacing: -0.01em; }

        .ratio-stack { display: grid; gap: 16px; margin-top: 24px; }
        .ratio-line { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 8px; color: theme('colors.on-surface-variant'); font-size: 0.875rem; font-weight: 600;}
        .ratio-line strong { color: theme('colors.primary'); }
        .track { height: 8px; overflow: hidden; border-radius: 999px; background: theme('colors.surface-container-high'); }
        .track span { display: block; height: 100%; border-radius: inherit; background: theme('colors.secondary'); }
        .track.good span { background: theme('colors.tertiary-fixed-dim'); }
        .track.debt span { background: theme('colors.error'); }

        .insight-box, .empty-state, .goal-card, .recommendations-panel {
            margin-top: 24px; padding: 20px;
            border: 1px solid theme('colors.outline-variant');
            border-radius: theme('borderRadius.lg');
            background: theme('colors.surface-container-lowest');
        }
        .insight-box h3, .empty-state h3, .recommendations-title { margin: 0 0 12px; font-family: theme('fontFamily.headline-sm'); font-size: 1.125rem; font-weight: 600; color: theme('colors.primary'); }
        .insight-box ul { margin: 0; padding-left: 20px; color: theme('colors.on-surface-variant'); line-height: 1.7; }
        .empty-state { text-align: center; color: theme('colors.on-surface-variant'); }

        .breakdown-panel { margin-top: 24px; }
        .breakdown-layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(240px, 0.42fr); gap: 24px; }
        .breakdown-list { display: grid; gap: 12px; }
        .breakdown-item { display: grid; grid-template-columns: minmax(140px, 1fr) minmax(120px, auto) minmax(74px, auto); gap: 12px; padding: 16px; border: 1px solid theme('colors.outline-variant'); border-radius: theme('borderRadius.DEFAULT'); background: theme('colors.surface-container-lowest'); align-items: center;}
        .breakdown-item span { color: theme('colors.primary'); font-weight: 600; font-size: 0.875rem; display: flex; align-items: center; gap: 8px; }
        .breakdown-item strong { color: theme('colors.on-surface-variant'); font-size: 0.875rem; font-weight: 400; }
        .breakdown-item em { color: theme('colors.secondary'); font-style: normal; font-weight: 600; text-align: right; }
        .breakdown-item.highlight { background: theme('colors.surface-container-low'); border-color: theme('colors.outline-variant'); }
        .breakdown-item.is-debt { background: theme('colors.error-container'); border-color: theme('colors.error-container'); }
        
        .debt-tag { font-size: 0.65rem; padding: 2px 6px; background: theme('colors.error'); color: theme('colors.on-error'); border-radius: 4px; text-transform: uppercase; font-weight: 700; }

        /* Dynamic Expense Categories */
        .expense-section { margin: 24px 0 0; }
        .expense-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid theme('colors.outline-variant'); }
        .expense-section-label { color: theme('colors.on-surface-variant'); font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .expense-section-hint { color: theme('colors.outline'); font-size: 0.75rem; }
        .expense-row { display: grid; grid-template-columns: 1fr 1.1fr auto auto; gap: 12px; align-items: center; margin-bottom: 12px; }
        
        .debt-toggle { display: flex; align-items: center; gap: 6px; color: theme('colors.on-surface-variant'); font-size: 0.75rem; font-weight: 600; cursor: pointer; padding: 10px 12px; border: 1px solid theme('colors.outline-variant'); border-radius: theme('borderRadius.DEFAULT'); background: theme('colors.surface-container-lowest'); transition: all 0.2s; }
        .debt-toggle:has(input:checked) { border-color: theme('colors.error'); background: theme('colors.error-container'); color: theme('colors.on-error-container'); }
        .debt-toggle input[type="checkbox"] { accent-color: theme('colors.error'); width: 16px; height: 16px; cursor: pointer; }
        
        .btn-remove-expense { display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border: 1px solid theme('colors.error-container'); border-radius: theme('borderRadius.DEFAULT'); background: theme('colors.error-container'); color: theme('colors.error'); cursor: pointer; font-size: 1.2rem; transition: all 0.2s; }
        .btn-remove-expense:hover { background: theme('colors.error'); color: theme('colors.on-error'); }
        
        .btn-add-expense { display: flex; align-items: center; gap: 8px; padding: 10px 16px; margin-top: 12px; border: 1px dashed theme('colors.outline-variant'); border-radius: theme('borderRadius.DEFAULT'); background: transparent; color: theme('colors.on-surface-variant'); font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; }
        .btn-add-expense:hover { border-color: theme('colors.secondary'); color: theme('colors.secondary'); background: theme('colors.surface-container-low'); }

        .template-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; margin-bottom: 12px; }
        .template-btn { padding: 12px; border: 1px solid theme('colors.outline-variant'); border-radius: theme('borderRadius.DEFAULT'); background: theme('colors.surface-container-lowest'); color: theme('colors.on-surface-variant'); cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .template-btn:hover { border-color: theme('colors.secondary'); background: theme('colors.surface-container-low'); color: theme('colors.secondary'); }
        .template-name { font-weight: 600; font-size: 0.875rem; color: theme('colors.primary'); }
        .template-desc { font-size: 0.75rem; color: theme('colors.outline'); }
        .template-label { color: theme('colors.on-surface-variant'); font-size: 0.875rem; font-weight: 600; text-transform: uppercase; margin-bottom: 12px; display: block;}

        .donut-chart-wrapper { position: relative; background: theme('colors.surface-container-lowest'); border: 1px solid theme('colors.outline-variant'); border-radius: theme('borderRadius.lg'); padding: 20px; }
        .donut-chart-wrapper canvas { max-height: 220px; }
        .donut-legend { margin-top: 16px; display: flex; flex-direction: column; gap: 8px; }
        .donut-legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: theme('colors.on-surface-variant'); font-weight: 600;}
        .donut-legend-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }

        .comparison-panel { margin-top: 40px; }
        .comparison-chart-wrapper { background: theme('colors.surface-container-lowest'); border: 1px solid theme('colors.outline-variant'); border-radius: theme('borderRadius.lg'); padding: 24px; margin-bottom: 24px; height: 320px; }
        
        .chart-filter-controls { display: flex; gap: 8px; background: theme('colors.surface-container-low'); padding: 4px; border-radius: theme('borderRadius.DEFAULT'); border: 1px solid theme('colors.outline-variant'); }
        .btn-filter { padding: 6px 16px; border-radius: theme('borderRadius.sm'); background: transparent; color: theme('colors.on-surface-variant'); border: none; cursor: pointer; font-size: 0.875rem; font-weight: 600; transition: all 0.2s; }
        .btn-filter:hover { color: theme('colors.on-surface'); }
        .btn-filter.is-active { background: theme('colors.secondary'); color: theme('colors.on-secondary'); }

        .comparison-table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 0.875rem; }
        .comparison-table th, .comparison-table td { padding: 16px; text-align: left; border-bottom: 1px solid theme('colors.outline-variant'); }
        .comparison-table th { color: theme('colors.on-surface-variant'); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; background: theme('colors.surface-container-low'); }
        .comparison-table td { color: theme('colors.on-surface'); vertical-align: middle; }
        
        .action-buttons { display: flex; gap: 8px; }
        .btn-use { padding: 8px 16px; border-radius: theme('borderRadius.DEFAULT'); background: theme('colors.secondary'); color: theme('colors.on-secondary'); text-decoration: none; font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer; }
        .btn-use:hover { opacity: 0.9; }
        .btn-delete { padding: 8px 16px; border-radius: theme('borderRadius.DEFAULT'); background: theme('colors.error-container'); color: theme('colors.error'); text-decoration: none; font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer; }
        .btn-delete:hover { background: theme('colors.error'); color: theme('colors.on-error'); }
        
        .recommendation-item { display: grid; grid-template-columns: minmax(140px, 1fr) auto; gap: 12px; padding: 16px; margin-bottom: 12px; border-left: 4px solid; border-radius: theme('borderRadius.DEFAULT'); background: theme('colors.surface-container-lowest'); border: 1px solid theme('colors.outline-variant'); }
        .recommendation-item.ok { border-left-color: theme('colors.tertiary-fixed-dim'); }
        .recommendation-item.warning { border-left-color: theme('colors.secondary'); }
        .recommendation-item.critical { border-left-color: theme('colors.error'); }
        .recommendation-label { color: theme('colors.primary'); font-weight: 700; font-size: 0.875rem;}
        .recommendation-text { color: theme('colors.on-surface-variant'); font-size: 0.75rem; margin-top: 4px; }
        .recommendation-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .recommendation-badge.ok { background: theme('colors.tertiary-fixed-dim'); color: theme('colors.on-tertiary-fixed'); }
        .recommendation-badge.warning { background: theme('colors.secondary-fixed'); color: theme('colors.on-secondary-fixed'); }
        .recommendation-badge.critical { background: theme('colors.error-container'); color: theme('colors.error'); }
        
        .category-trend-wrapper { background: theme('colors.surface-container-lowest'); border: 1px solid theme('colors.outline-variant'); border-radius: theme('borderRadius.lg'); padding: 24px; margin-top: 24px; position: relative; min-height: 300px; }
        .category-trend-wrapper canvas { max-height: 280px; }

        .alert-success-banner { padding: 16px 20px; border: 1px solid theme('colors.tertiary-fixed-dim'); background: rgba(78, 222, 163, 0.1); border-radius: theme('borderRadius.lg'); margin-bottom: 24px; color: theme('colors.on-tertiary-fixed-variant'); font-weight: 600; font-size: 0.875rem; }

        @media (max-width: 900px) {
            .workspace-grid, .breakdown-layout { grid-template-columns: 1fr; }
            .finance-form-grid, .metric-grid { grid-template-columns: 1fr; }
            .expense-row { grid-template-columns: 1fr; }
        }
    </style>"""

def update_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Replace the <style> block completely
    new_content = re.sub(r'<style>.*?</style>', tailwind_injection, content, flags=re.DOTALL)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print(f"Updated {filepath}")

update_file(livewire_path)
update_file(standard_path)
