<style>
    @view-transition {
        navigation: auto;
    }

    html {
        background: var(--bg-primary);
    }

    body.module-page {
        --module-surface: rgba(8, 34, 37, .96);
        --module-surface-raised: rgba(13, 43, 46, .97);
        --module-surface-soft: rgba(255, 255, 255, .055);
        --module-border: rgba(164, 190, 190, .2);
        --module-text: #f7faf9;
        --module-muted: #b9c8c7;
        --module-accent: #e6c46d;
        --module-green: #20bd7a;
        min-width: 320px;
        min-height: 100vh;
        background: #050c0f !important;
        color: var(--module-text);
    }

    body.module-page > .container {
        width: 100% !important;
        max-width: none !important;
        min-height: 100vh;
        margin: 0 !important;
        padding: 0 !important;
    }

    body.module-page .site-header {
        position: sticky;
        top: 0;
        z-index: 100;
        width: 100% !important;
        min-height: 76px;
        display: flex;
        flex-wrap: nowrap;
        align-items: center !important;
        justify-content: space-between;
        gap: 28px !important;
        margin: 0 !important;
        padding: 14px clamp(18px, 4vw, 70px) !important;
        border: 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, .1) !important;
        background: rgba(5, 23, 24, .92);
        box-shadow: 0 12px 36px rgba(0, 0, 0, .2);
        backdrop-filter: blur(18px);
    }

    body.module-page .brand {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: #ffffff;
        font-size: .98rem;
        font-weight: 900;
        letter-spacing: .035em;
        text-decoration: none;
        white-space: nowrap;
    }

    body.module-page .brand-symbol {
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        border-radius: 11px;
        background: var(--accent-primary);
        color: #ffffff;
        font-size: 1.35rem;
        font-weight: 950;
        box-shadow: 0 10px 28px rgba(99, 102, 241, .3);
    }

    body.module-page .main-nav {
        width: auto !important;
        min-width: 0;
        display: flex;
        flex-wrap: nowrap !important;
        align-items: center;
        justify-content: flex-end;
        gap: 6px !important;
        overflow-x: auto;
        padding: 2px 0 5px;
        scrollbar-width: thin;
        scrollbar-color: rgba(99, 102, 241, .45) transparent;
    }

    body.module-page .main-nav a,
    body.module-page .nav-form button {
        position: relative;
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        padding: 0 15px !important;
        border: 1px solid transparent !important;
        border-radius: 999px;
        color: rgba(248, 250, 252, .76) !important;
        background: transparent !important;
        text-decoration: none;
        white-space: nowrap !important;
        transition: color .2s ease, background .2s ease, border-color .2s ease;
    }

    body.module-page .main-nav a::after,
    body.module-page .nav-form button::after {
        display: none;
    }

    body.module-page .main-nav a:hover,
    body.module-page .nav-form button:hover {
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, .14) !important;
        background: rgba(255, 255, 255, .07) !important;
    }

    body.module-page .main-nav a.is-active {
        color: #052e2b !important;
        border-color: transparent !important;
        background: transparent !important;
    }

    body.module-page .module-tab-label {
        position: relative;
        z-index: 2;
    }

    body.module-page .module-active-pill {
        position: absolute;
        inset: 0;
        z-index: 1;
        border: 1px solid rgba(147, 197, 253, .4);
        border-radius: 999px;
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-hover));
        box-shadow:
            0 8px 24px rgba(99, 102, 241, .25),
            inset 0 1px 0 rgba(255, 255, 255, .2);
        view-transition-name: module-active-tab;
        contain: layout paint;
    }

    body.module-page .nav-form {
        flex: 0 0 auto;
        width: auto !important;
        max-width: max-content !important;
        margin: 0;
    }

    body.module-page .nav-form button {
        width: auto !important;
        max-width: max-content !important;
    }

    body.module-page .content {
        width: 100%;
        min-height: calc(100vh - 76px);
    }

    body.module-page .finance-workspace,
    body.module-page .tax-workspace,
    body.module-page .stata-workspace,
    body.module-page .targets-workspace,
    body.module-page .form-workspace,
    body.module-page .detail-workspace {
        view-transition-name: module-workspace;
        width: 100% !important;
        min-height: calc(100vh - 76px) !important;
        margin: 0 !important;
        padding: clamp(30px, 4vw, 64px) clamp(18px, 4vw, 70px) clamp(54px, 7vw, 100px) !important;
        background-color: #071719 !important;
        background-image:
            linear-gradient(180deg, rgba(3, 15, 17, .84) 0%, rgba(4, 17, 19, .94) 48%, #050c0f 100%),
            url('{{ asset('images/backgroundfinance.jpg') }}'),
            radial-gradient(circle at 82% 0%, rgba(99, 102, 241, .2), transparent 38%) !important;
        background-position: top center, top center, top center !important;
        background-size: 100% 100%, 100% auto, 100% 100% !important;
        background-repeat: no-repeat !important;
        background-attachment: scroll !important;
    }

    body.module-page .workspace-inner,
    body.module-page .tax-inner,
    body.module-page .stata-inner {
        width: 100% !important;
        max-width: 1680px !important;
        margin: 0 auto !important;
    }

    body.module-page .workspace-topbar,
    body.module-page .tax-topbar {
        display: none !important;
    }

    body.module-page .module-hero {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) minmax(280px, .45fr) !important;
        gap: 30px !important;
        align-items: stretch !important;
        margin-bottom: 30px !important;
    }

    body.module-page .module-hero-panel {
        min-height: 360px;
        padding: clamp(28px, 3vw, 46px);
        border: 1px solid var(--module-border);
        border-radius: 18px;
        background:
            linear-gradient(145deg, rgba(19, 63, 65, .98), var(--module-surface) 68%);
        box-shadow: 0 24px 70px rgba(0, 0, 0, .34);
        backdrop-filter: blur(16px);
    }

    body.module-page .module-hero-panel::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 3px;
        border-radius: 18px 0 0 18px;
        background: linear-gradient(180deg, var(--module-green), transparent 76%);
    }

    body.module-page .module-hero-panel {
        position: relative;
        overflow: hidden;
    }

    body.module-page .module-hero-copy {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
    }

    body.module-page .module-hero-copy h1 {
        margin: 14px 0 0;
        font-size: clamp(3.2rem, 7vw, 7rem) !important;
        line-height: .92 !important;
        letter-spacing: -.055em;
    }

    body.module-page .module-hero-copy p {
        max-width: 800px;
        margin: 24px 0 0;
        color: rgba(248, 250, 252, .72);
        font-size: clamp(1rem, 1.3vw, 1.2rem);
        line-height: 1.7;
    }

    body.module-page .module-hero-action {
        min-height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 30px;
        padding: 0 24px;
        border-radius: 999px;
        background: #f3c969;
        color: #052e2b;
        font-weight: 900;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    body.module-page .module-hero-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(243, 201, 105, .2);
    }

    body.module-page .module-hero-summary {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
    }

    body.module-page .module-hero-summary strong {
        color: #ffffff;
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        line-height: 1;
    }

    body.module-page .module-hero-summary span {
        margin-top: 16px;
        color: rgba(248, 250, 252, .68);
        font-size: 1.05rem;
        line-height: 1.65;
    }

    body.module-page .workspace-kicker,
    body.module-page .tax-kicker,
    body.module-page .stata-kicker {
        color: var(--module-accent) !important;
    }

    body.module-page .workspace-panel,
    body.module-page .tax-panel,
    body.module-page .stata-panel,
    body.module-page .feature-card,
    body.module-page .stata-console {
        border: 1px solid var(--module-border) !important;
        background: var(--module-surface) !important;
        box-shadow: 0 18px 52px rgba(0, 0, 0, .24) !important;
        backdrop-filter: blur(16px);
    }

    body.module-page .workspace-panel,
    body.module-page .tax-panel,
    body.module-page .stata-panel,
    body.module-page .feature-card {
        border-radius: 16px !important;
    }

    body.module-page h1,
    body.module-page h2,
    body.module-page h3,
    body.module-page strong {
        color: var(--module-text);
    }

    body.module-page p,
    body.module-page .panel-heading p,
    body.module-page .tax-panel p,
    body.module-page .stata-panel p,
    body.module-page .feature-card p,
    body.module-page .tutorial-content p,
    body.module-page .stata-command-item p {
        color: var(--module-muted) !important;
    }

    body.module-page .metric-tile,
    body.module-page .tax-metric,
    body.module-page .tax-note,
    body.module-page .stata-data-card,
    body.module-page .insight-box,
    body.module-page .empty-state,
    body.module-page .goal-card,
    body.module-page .breakdown-item,
    body.module-page .tutorial-step,
    body.module-page .stata-command-group,
    body.module-page .stata-command-item {
        border-color: rgba(164, 190, 190, .17) !important;
        background: var(--module-surface-soft) !important;
        box-shadow: none !important;
    }

    body.module-page .finance-form-grid span,
    body.module-page .tax-form span,
    body.module-page .metric-tile span,
    body.module-page .tax-metric span,
    body.module-page .stata-data-card span,
    body.module-page .goal-card span {
        color: #c7d4d3 !important;
    }

    body.module-page .template-label,
    body.module-page .expense-section-label,
    body.module-page .template-name,
    body.module-page .template-btn,
    body.module-page .debt-toggle,
    body.module-page .expense-row input[type="text"],
    body.module-page .template-desc,
    body.module-page .expense-section-hint {
        color: #f8fafc !important;
    }

    body.module-page .template-desc,
    body.module-page .expense-section-hint {
        opacity: .82;
    }

    body.module-page .template-btn {
        background: rgba(3, 20, 22, .68) !important;
        border-color: rgba(180, 203, 202, .24) !important;
    }

    body.module-page .template-btn:hover {
        color: #052e2b !important;
        background: rgba(32, 189, 122, .12) !important;
    }

    body.module-page .finance-form-grid input,
    body.module-page .tax-form input,
    body.module-page .tax-form select,
    body.module-page .expense-row input[type="text"] {
        min-height: 50px;
        border: 1px solid rgba(180, 203, 202, .24) !important;
        border-radius: 11px !important;
        background: rgba(3, 20, 22, .68) !important;
        color: #ffffff !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .03);
    }

    body.module-page .finance-form-grid input:focus,
    body.module-page .tax-form input:focus,
    body.module-page .tax-form select:focus,
    body.module-page .expense-row input[type="text"]:focus {
        outline: 3px solid rgba(32, 189, 122, .18) !important;
        border-color: rgba(54, 211, 145, .72) !important;
        background: rgba(4, 27, 29, .94) !important;
    }

    body.module-page .workspace-button,
    body.module-page .tax-button,
    body.module-page .module-hero-action,
    body.module-page .stata-action {
        border: 1px solid rgba(255, 232, 166, .55) !important;
        background: linear-gradient(135deg, #e9c86f, #d8ae4d) !important;
        color: #092c2d !important;
        box-shadow: 0 12px 28px rgba(216, 174, 77, .14);
    }

    body.module-page .workspace-button:hover,
    body.module-page .tax-button:hover,
    body.module-page .module-hero-action:hover,
    body.module-page .stata-action:hover {
        filter: brightness(1.04);
        transform: translateY(-2px);
    }

    body.module-page .tax-table,
    body.module-page .stata-output-table {
        overflow: hidden;
        border: 1px solid rgba(164, 190, 190, .16);
        border-radius: 12px;
        background: rgba(2, 18, 20, .46);
    }

    body.module-page .tax-table th,
    body.module-page .stata-output-table th {
        background: rgba(163, 190, 189, .11) !important;
        color: #f8fafc !important;
        font-size: .82rem;
        letter-spacing: .035em;
        text-transform: uppercase;
    }

    body.module-page .tax-table td,
    body.module-page .stata-output-table td {
        color: #c8d4d3 !important;
    }

    body.module-page .tax-table tr:last-child td,
    body.module-page .stata-output-table tr:last-child td {
        border-bottom: 0;
    }

    body.module-page .feature-card {
        min-height: 210px;
        transition: transform .22s ease, border-color .22s ease, background .22s ease;
    }

    body.module-page .feature-card:hover {
        transform: translateY(-3px);
        border-color: rgba(32, 189, 122, .42) !important;
        background: var(--module-surface-raised) !important;
    }

    body.module-page .module-hero-summary .status-badge,
    body.module-page .module-hero-summary .tax-badge {
        min-width: 0;
        margin-bottom: 22px;
    }

    body.module-page .site-footer {
        display: none !important;
    }

    [data-theme="light"] body.module-page {
        --module-surface: rgba(255, 255, 255, .92);
        --module-surface-raised: rgba(255, 255, 255, .98);
        --module-surface-soft: rgba(15, 23, 42, .045);
        --module-border: rgba(15, 23, 42, .12);
        --module-text: #0f172a;
        --module-muted: #475569;
        --module-accent: #0f766e;
        --module-green: #14b8a6;
        background: var(--bg-primary) !important;
        color: var(--module-text);
    }

    [data-theme="light"] body.module-page .site-header {
        border-bottom-color: rgba(15, 23, 42, .1) !important;
        background: rgba(255, 255, 255, .9) !important;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .08);
    }

    [data-theme="light"] body.module-page .brand,
    [data-theme="light"] body.module-page .main-nav a,
    [data-theme="light"] body.module-page .nav-form button {
        color: rgba(15, 23, 42, .72) !important;
    }

    [data-theme="light"] body.module-page .main-nav a:hover,
    [data-theme="light"] body.module-page .nav-form button:hover {
        color: #0f172a !important;
        border-color: rgba(15, 23, 42, .12) !important;
        background: rgba(15, 23, 42, .06) !important;
    }

    [data-theme="light"] body.module-page .main-nav a.is-active {
        color: #ffffff !important;
    }

    [data-theme="light"] body.module-page .module-active-pill {
        border-color: rgba(20, 184, 166, .4);
        background: linear-gradient(135deg, rgba(20, 184, 166, .98), rgba(13, 148, 136, .96));
        box-shadow:
            0 10px 24px rgba(20, 184, 166, .18),
            inset 0 1px 0 rgba(255, 255, 255, .5);
    }

    [data-theme="light"] body.module-page .finance-workspace,
    [data-theme="light"] body.module-page .tax-workspace,
    [data-theme="light"] body.module-page .stata-workspace,
    [data-theme="light"] body.module-page .targets-workspace,
    [data-theme="light"] body.module-page .form-workspace,
    [data-theme="light"] body.module-page .detail-workspace {
        background-color: var(--bg-primary) !important;
        background-image:
            linear-gradient(180deg, rgba(241, 245, 249, .92) 0%, rgba(248, 250, 252, .96) 52%, rgba(241, 245, 249, 1) 100%),
            url('{{ asset('images/backgroundfinance.jpg') }}'),
            radial-gradient(circle at 82% 0%, rgba(20, 184, 166, .14), transparent 38%) !important;
    }

    [data-theme="light"] body.module-page .module-hero-panel {
        background:
            linear-gradient(145deg, rgba(255, 255, 255, .96), rgba(240, 253, 250, .9) 68%) !important;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .1) !important;
    }

    [data-theme="light"] body.module-page .module-hero-copy p,
    [data-theme="light"] body.module-page .module-hero-summary span,
    [data-theme="light"] body.module-page p,
    [data-theme="light"] body.module-page .panel-heading p,
    [data-theme="light"] body.module-page .tax-panel p,
    [data-theme="light"] body.module-page .stata-panel p,
    [data-theme="light"] body.module-page .feature-card p,
    [data-theme="light"] body.module-page .tutorial-content p,
    [data-theme="light"] body.module-page .stata-command-item p {
        color: var(--module-muted) !important;
    }

    [data-theme="light"] body.module-page .workspace-panel,
    [data-theme="light"] body.module-page .tax-panel,
    [data-theme="light"] body.module-page .stata-panel,
    [data-theme="light"] body.module-page .feature-card,
    [data-theme="light"] body.module-page .stata-console,
    [data-theme="light"] body.module-page .stat-card,
    [data-theme="light"] body.module-page .target-card,
    [data-theme="light"] body.module-page .form-panel,
    [data-theme="light"] body.module-page .header-section,
    [data-theme="light"] body.module-page .panel {
        border-color: var(--module-border) !important;
        background: var(--module-surface) !important;
        box-shadow: 0 18px 52px rgba(15, 23, 42, .08) !important;
    }

    [data-theme="light"] body.module-page .metric-tile,
    [data-theme="light"] body.module-page .tax-metric,
    [data-theme="light"] body.module-page .tax-note,
    [data-theme="light"] body.module-page .stata-data-card,
    [data-theme="light"] body.module-page .insight-box,
    [data-theme="light"] body.module-page .empty-state,
    [data-theme="light"] body.module-page .goal-card,
    [data-theme="light"] body.module-page .breakdown-item,
    [data-theme="light"] body.module-page .tutorial-step,
    [data-theme="light"] body.module-page .stata-command-group,
    [data-theme="light"] body.module-page .stata-command-item,
    [data-theme="light"] body.module-page .stat-card,
    [data-theme="light"] body.module-page .target-card,
    [data-theme="light"] body.module-page .back-link,
    [data-theme="light"] body.module-page .info-item,
    [data-theme="light"] body.module-page .status-indicator,
    [data-theme="light"] body.module-page .deposit-form,
    [data-theme="light"] body.module-page .deposit-item,
    [data-theme="light"] body.module-page .monthly-breakdown,
    [data-theme="light"] body.module-page .quick-stat,
    [data-theme="light"] body.module-page .amount-item,
    [data-theme="light"] body.module-page .target-deadline,
    [data-theme="light"] body.module-page .target-performance {
        border-color: rgba(15, 23, 42, .1) !important;
        background: var(--module-surface-soft) !important;
    }

    [data-theme="light"] body.module-page .finance-form-grid span,
    [data-theme="light"] body.module-page .tax-form span,
    [data-theme="light"] body.module-page .metric-tile span,
    [data-theme="light"] body.module-page .tax-metric span,
    [data-theme="light"] body.module-page .stata-data-card span,
    [data-theme="light"] body.module-page .goal-card span,
    [data-theme="light"] body.module-page .stat-label,
    [data-theme="light"] body.module-page .form-header p,
    [data-theme="light"] body.module-page .form-group label,
    [data-theme="light"] body.module-page .progress-info,
    [data-theme="light"] body.module-page .info-label,
    [data-theme="light"] body.module-page .deposit-date,
    [data-theme="light"] body.module-page .quick-stat-label,
    [data-theme="light"] body.module-page .amount-label,
    [data-theme="light"] body.module-page .deadline-label,
    [data-theme="light"] body.module-page .performance-label,
    [data-theme="light"] body.module-page .template-label,
    [data-theme="light"] body.module-page .expense-section-label,
    [data-theme="light"] body.module-page .template-name,
    [data-theme="light"] body.module-page .template-btn,
    [data-theme="light"] body.module-page .debt-toggle,
    [data-theme="light"] body.module-page .expense-row input[type="text"],
    [data-theme="light"] body.module-page .template-desc,
    [data-theme="light"] body.module-page .expense-section-hint {
        color: var(--module-text) !important;
    }

    [data-theme="light"] body.module-page .template-desc,
    [data-theme="light"] body.module-page .expense-section-hint {
        opacity: .72;
    }

    [data-theme="light"] body.module-page .template-btn,
    [data-theme="light"] body.module-page .finance-form-grid input,
    [data-theme="light"] body.module-page .tax-form input,
    [data-theme="light"] body.module-page .tax-form select,
    [data-theme="light"] body.module-page .expense-row input[type="text"],
    [data-theme="light"] body.module-page .form-group input,
    [data-theme="light"] body.module-page .form-group textarea,
    [data-theme="light"] body.module-page .form-group select,
    [data-theme="light"] body.module-page .form-input,
    [data-theme="light"] body.module-page .btn-action {
        border-color: rgba(15, 23, 42, .14) !important;
        background: rgba(255, 255, 255, .78) !important;
        color: var(--module-text) !important;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .7);
    }

    [data-theme="light"] body.module-page .finance-form-grid input:focus,
    [data-theme="light"] body.module-page .tax-form input:focus,
    [data-theme="light"] body.module-page .tax-form select:focus,
    [data-theme="light"] body.module-page .expense-row input[type="text"]:focus,
    [data-theme="light"] body.module-page .form-group input:focus,
    [data-theme="light"] body.module-page .form-group textarea:focus,
    [data-theme="light"] body.module-page .form-group select:focus,
    [data-theme="light"] body.module-page .form-input:focus {
        border-color: rgba(20, 184, 166, .64) !important;
        background: #ffffff !important;
    }

    [data-theme="light"] body.module-page .btn-action:hover {
        border-color: rgba(20, 184, 166, .42) !important;
        background: rgba(20, 184, 166, .1) !important;
        color: #0f766e !important;
    }

    [data-theme="light"] body.module-page .progress-bar {
        background: rgba(15, 23, 42, .1) !important;
    }

    [data-theme="light"] body.module-page .progress-info strong,
    [data-theme="light"] body.module-page .info-value,
    [data-theme="light"] body.module-page .quick-stat-value,
    [data-theme="light"] body.module-page .deadline-value,
    [data-theme="light"] body.module-page .progress-info strong {
        color: #0f766e !important;
    }

    [data-theme="light"] body.module-page .deposit-note {
        color: #334155 !important;
    }

    [data-theme="light"] body.module-page .workspace-button,
    [data-theme="light"] body.module-page .tax-button,
    [data-theme="light"] body.module-page .module-hero-action,
    [data-theme="light"] body.module-page .stata-action,
    [data-theme="light"] body.module-page .btn-primary {
        border-color: rgba(20, 184, 166, .5) !important;
        background: linear-gradient(135deg, #14b8a6, #0d9488) !important;
        color: #ffffff !important;
        box-shadow: 0 12px 28px rgba(20, 184, 166, .16);
    }

    [data-theme="light"] body.module-page .tax-table,
    [data-theme="light"] body.module-page .stata-output-table {
        border-color: rgba(15, 23, 42, .12);
        background: rgba(255, 255, 255, .76);
    }

    [data-theme="light"] body.module-page .tax-table th,
    [data-theme="light"] body.module-page .stata-output-table th {
        background: rgba(15, 23, 42, .06) !important;
        color: #0f172a !important;
    }

    [data-theme="light"] body.module-page .tax-table td,
    [data-theme="light"] body.module-page .stata-output-table td {
        color: #334155 !important;
    }

    ::view-transition-old(root),
    ::view-transition-new(root) {
        animation: none;
    }

    ::view-transition-group(module-active-tab) {
        z-index: 110;
        animation-duration: .68s;
        animation-timing-function: cubic-bezier(.16, 1, .3, 1);
    }

    ::view-transition-old(module-active-tab),
    ::view-transition-new(module-active-tab) {
        height: 100%;
        mix-blend-mode: normal;
        animation-duration: .68s;
        animation-timing-function: cubic-bezier(.16, 1, .3, 1);
    }

    ::view-transition-old(module-workspace) {
        animation: module-slide-out .34s cubic-bezier(.4, 0, 1, 1) both;
    }

    ::view-transition-new(module-workspace) {
        animation: module-slide-in .46s cubic-bezier(.22, 1, .36, 1) both;
    }

    @keyframes module-slide-out {
        to {
            opacity: 0;
            transform: translateX(-7vw) scale(.985);
        }
    }

    @keyframes module-slide-in {
        from {
            opacity: 0;
            transform: translateX(9vw) scale(.985);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    @media (max-width: 860px) {
        body.module-page .site-header {
            min-height: 68px;
            padding-inline: 16px !important;
        }

        body.module-page .brand span:last-child {
            display: none;
        }

        body.module-page .brand-symbol {
            width: 36px;
            height: 36px;
        }

        body.module-page .main-nav {
            justify-content: flex-start;
        }

        body.module-page .main-nav a,
        body.module-page .nav-form button {
            min-height: 38px;
            padding-inline: 12px !important;
            font-size: .88rem;
        }

        body.module-page .finance-workspace,
        body.module-page .tax-workspace,
        body.module-page .stata-workspace {
            min-height: calc(100vh - 68px) !important;
            padding-inline: 16px !important;
        }

        body.module-page .module-hero {
            grid-template-columns: 1fr !important;
        }

        body.module-page .module-hero-panel {
            min-height: auto;
        }
    }

    @media (max-width: 520px) {
        body.module-page .site-header {
            gap: 12px !important;
        }

        body.module-page .main-nav a,
        body.module-page .nav-form button {
            min-height: 36px;
            padding-inline: 10px !important;
            font-size: .8rem;
        }

        body.module-page .finance-workspace,
        body.module-page .tax-workspace,
        body.module-page .stata-workspace {
            padding-top: 26px !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        ::view-transition-group(module-active-tab),
        ::view-transition-old(module-workspace),
        ::view-transition-new(module-workspace) {
            animation-duration: .01ms !important;
        }
    }
</style>
