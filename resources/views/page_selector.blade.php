@extends('layouts.app')

@section('title', 'User Dashboard - Smart Finance')

@section('content')
    @php
        $isLoggedIn = auth()->check();
        $hour = now('Asia/Jakarta')->hour;
        if ($hour < 11) {
            $greeting = __('app.good_morning');
        } elseif ($hour < 15) {
            $greeting = __('app.good_afternoon');
        } elseif ($hour < 18) {
            $greeting = __('app.good_evening');
        } else {
            $greeting = __('app.good_night');
        }
        $userName = auth()->user()->name ?? 'Guest';
        $firstName = explode(' ', $userName)[0];
        $formatRupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $ptkpTable = [
            'TK/0' => 54000000,
            'TK/1' => 58500000,
            'TK/2' => 63000000,
            'TK/3' => 67500000,
            'K/0' => 58500000,
            'K/1' => 63000000,
            'K/2' => 67500000,
            'K/3' => 72000000,
            'K/I/0' => 112500000,
            'K/I/1' => 117000000,
            'K/I/2' => 121500000,
            'K/I/3' => 126000000,
        ];
        $taxBrackets = [
            ['label' => 's.d. Rp60.000.000', 'rate' => '5%'],
            ['label' => '> Rp60.000.000 - Rp250.000.000', 'rate' => '15%'],
            ['label' => '> Rp250.000.000 - Rp500.000.000', 'rate' => '25%'],
            ['label' => '> Rp500.000.000 - Rp5.000.000.000', 'rate' => '30%'],
            ['label' => '> Rp5.000.000.000', 'rate' => '35%'],
        ];
    @endphp

    <style>
        .site-header,
        .site-footer {
            display: none !important;
        }

        body {
            background: var(--bg-primary);
        }

        body > .container {
            max-width: none;
            width: 100%;
            min-height: 100vh;
            padding: 0;
        }

        .content {
            min-height: 100vh;
        }

        .selector-shell {
            min-height: 100vh;
            padding: 0;
            color: var(--text-main);
            background: var(--bg-primary);
        }

        .selector-head {
            min-height: 72px;
            margin: 0;
            padding: 0 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-secondary);
        }

        .selector-logo {
            display: inline-flex;
            align-items: center;
        }

        .selector-logo span {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--text-main);
            color: var(--bg-panel);
            font-size: 0.78rem;
            font-weight: 900;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.2);
        }

        .selector-logo span + span {
            margin-left: -8px;
            background: var(--nav-bg);
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(8px);
        }

        .selector-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: clamp(1.6rem, 4vw, 2.8rem);
            font-weight: 900;
            letter-spacing: 0.08em;
        }

        .selector-title .brand-mark {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--accent-primary);
        }

        .case-link {
            padding: 10px 18px;
            border: 2px solid rgba(255, 255, 255, 0.78);
            border-radius: 999px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 900;
        }

        .head-actions {
            display: flex;
            justify-self: end;
            gap: 10px;
            align-items: center;
        }

        .head-actions form,
        .side-footer form {
            margin: 0;
        }

        .head-actions button,
        .side-footer button {
            padding: 10px 18px;
            border: 2px solid rgba(255, 255, 255, 0.78);
            border-radius: 999px;
            background: transparent;
            color: var(--text-main);
            cursor: pointer;
            font: inherit;
            font-weight: 900;
        }

        .head-actions button:hover,
        .side-footer button:hover {
            background: var(--accent-primary);
            border-color: var(--accent-primary);
            color: var(--accent-hover);
        }

        .dashboard-frame {
            width: 100%;
            min-height: calc(100vh - 72px);
            margin: 0;
            background: var(--bg-primary);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .dashboard-frame::before {
            content: '';
            position: absolute;
            top: -5%; left: -5%; right: -5%; bottom: -5%;
            background-image: url('{{ asset('images/abstract_finance_bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 0;
            opacity: 0.25;
            animation: slowPan 35s ease-in-out infinite alternate;
            pointer-events: none;
        }

        [data-theme="light"] .dashboard-frame::before {
            opacity: 0.15;
            filter: invert(1) hue-rotate(180deg);
        }

        @keyframes slowPan {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.1) translate(-1.5%, 1.5%); }
        }

        .selector-app, .selector-content {
            position: relative;
            z-index: 1;
        }

        .browser-bar {
            display: none;
        }

        .window-dots {
            display: flex;
            gap: 8px;
        }

        .window-dots span {
            width: 11px;
            height: 11px;
            border-radius: 50%;
        }

        .window-dots span:nth-child(1) { background: #ff6b5f; }
        .window-dots span:nth-child(2) { background: var(--accent-primary); }
        .window-dots span:nth-child(3) { background: #4ade80; }

        .address-bar {
            min-height: 28px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.09);
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .browser-actions {
            justify-self: end;
            color: var(--text-muted);
            font-weight: 900;
        }

        .selector-app { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }

        .selector-sidebar {
            display: flex;
            flex-direction: column;
            padding: 28px 20px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
        }

        .app-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            color: var(--text-main);
            font-size: 0.95rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .brand-mark {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--accent-primary);
        }

        .side-menu {
            display: grid;
            gap: 10px;
        }

        .side-menu button,
        .side-menu a {
            min-height: 44px;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0 14px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            font: inherit;
            font-weight: 900;
            text-align: left;
            text-decoration: none;
        }

        .side-menu button.is-active,
        .side-menu button:hover,
        .side-menu a:hover {
            background: rgba(20, 184, 111, 0.18);
            color: var(--text-main);
            box-shadow: inset 3px 0 0 var(--accent-primary);
        }

        .side-footer {
            margin-top: auto;
            display: grid;
            gap: 10px;
        }

        .selector-content {
            padding: 40px 20px;
            background: transparent;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .content-panel {
            display: none;
            animation: panelIn 0.24s ease-out both;
        }

        .content-panel.is-active {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .panel-toolbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: center;
            margin-bottom: 24px;
        }

        .panel-toolbar h1 {
            margin: 0;
            font-size: clamp(1.45rem, 3vw, 2.1rem);
        }

        .mobile-hint {
            display: none;
            margin: -6px 0 18px;
            padding: 12px 14px;
            border: 1px solid rgba(20, 184, 111, 0.28);
            border-radius: 12px;
            background: rgba(20, 184, 111, 0.08);
            color: rgba(248, 250, 252, 0.84);
            line-height: 1.6;
            font-size: 0.9rem;
        }

        .module-table {
            overflow: hidden;
            border: 1px solid rgba(91, 130, 142, 0.64);
            border-radius: 12px;
        }

        .table-head,
        .table-row {
            display: grid;
            grid-template-columns: 90px minmax(160px, 1.2fr) minmax(140px, 0.9fr) 100px minmax(150px, 0.9fr) 120px;
            gap: 16px;
            align-items: center;
            padding: 15px 18px;
        }

        .table-head {
            background: rgba(81, 124, 137, 0.72);
            color: var(--text-muted);
            font-size: 0.84rem;
            font-weight: 900;
        }

        .table-row {
            min-height: 78px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            text-decoration: none;
        }

        .table-row:hover {
            background: var(--nav-bg);
        }

        .table-row strong {
            display: block;
            color: var(--accent-primary);
            margin-bottom: 4px;
        }

        .status-pill {
            width: fit-content;
            padding: 7px 10px;
            border-radius: 999px;
            color: var(--accent-hover);
            background: var(--accent-primary);
            font-size: 0.78rem;
            font-weight: 900;
        }

        .quick-stats,
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .full-grid {
            display: grid;
            grid-template-columns: minmax(300px, 0.9fr) minmax(360px, 1.1fr);
            gap: 16px;
            margin-top: 18px;
        }

        .tool-panel {
            padding: 18px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--nav-bg);
        }

        .tool-panel h2 {
            margin: 0 0 12px;
            color: var(--text-main);
            font-size: 1.08rem;
        }

        .tool-panel p {
            color: var(--text-muted);
            line-height: 1.65;
        }

        .selector-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .selector-form label {
            display: grid;
            gap: 7px;
        }

        .selector-form label.full {
            grid-column: 1 / -1;
        }

        .selector-form span {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .selector-form input,
        .selector-form select {
            width: 100%;
            min-height: 44px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 9px 11px;
            background: var(--nav-bg);
            color: var(--text-main);
            font: inherit;
        }

        .selector-form select option {
            background: var(--text-main);
            color: var(--bg-panel);
        }

        .selector-form select option:checked,
        .selector-form select option:hover {
            background: #d1fae5;
            color: var(--accent-hover);
        }

        .selector-submit {
            grid-column: 1 / -1;
            min-height: 48px;
            border: 0;
            border-radius: 10px;
            background: var(--accent-primary);
            color: var(--accent-hover);
            cursor: pointer;
            font: inherit;
            font-weight: 900;
        }

        .tax-overview {
            display: grid;
            grid-template-columns: minmax(280px, 0.9fr) minmax(360px, 1.1fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start;
        }

        .tax-form-panel {
            position: sticky;
            top: 18px;
        }

        .tax-reference-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .tax-summary-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .tax-summary-cards .data-card {
            min-height: 98px;
        }

        .mini-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }

        .mini-table th,
        .mini-table td {
            padding: 11px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted);
            text-align: left;
        }

        .mini-table th {
            color: var(--text-main);
            background: var(--nav-bg);
        }

        .mini-table td:last-child,
        .mini-table th:last-child {
            text-align: right;
        }

        .quick-card,
        .feature-card,
        .data-card {
            padding: 18px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--nav-bg);
        }

        .quick-card span,
        .feature-card span,
        .data-card span {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .quick-card strong,
        .data-card strong {
            display: block;
            margin-top: 8px;
            color: var(--text-main);
            font-size: 1.4rem;
        }

        .feature-card h2 {
            margin: 14px 0 10px;
            color: var(--accent-primary);
        }

        .feature-card p {
            color: var(--text-muted);
            line-height: 1.65;
        }

        .panel-copy {
            max-width: 760px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        @keyframes panelIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 980px) {
            .selector-head {
                flex-direction: column;
                padding-block: 18px;
            }

            .case-link {
                justify-self: center;
            }

            .selector-app { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }

            .selector-sidebar {
                position: sticky;
                top: 0;
                z-index: 2;
                padding: 14px;
            }

            .side-menu {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .side-footer {
                display: none;
            }

            .table-head {
                display: none;
            }

            .table-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .quick-stats,
            .feature-grid,
            .data-grid,
            .full-grid,
            .tax-overview,
            .tax-summary-cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .selector-head {
                padding: 16px 14px;
                min-height: auto;
                gap: 10px;
            }

            .selector-title {
                font-size: 1.35rem;
                text-align: center;
            }

            .selector-sidebar {
                padding: 12px;
            }

            .app-brand {
                margin-bottom: 10px;
                font-size: 0.86rem;
            }

            .panel-toolbar {
                align-items: flex-start;
                flex-direction: column;
                margin-bottom: 14px;
            }

            .side-menu {
                grid-template-columns: 1fr;
            }

            .selector-form {
                grid-template-columns: 1fr;
            }

            .selector-content {
            padding: 40px 20px;
            background: transparent;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

            .content-panel {
                padding: 16px;
            }

            .mobile-hint {
                display: block;
            }

            .module-table {
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.03);
            }

            .table-row {
                gap: 10px;
                padding: 14px;
                border-top: 1px solid var(--border-color);
                border-radius: 0;
                background: transparent;
            }

            .table-row > span {
                display: grid;
                gap: 4px;
            }

            .table-row > span::before {
                content: attr(data-label);
                color: rgba(248, 250, 252, 0.52);
                font-size: 0.76rem;
                font-weight: 800;
                letter-spacing: 0.04em;
                text-transform: uppercase;
            }

            .table-row .status-pill {
                margin-top: 2px;
            }

            .quick-card {
                padding: 16px;
            }
        }
        /* Responsive sidebar hardening: keep every menu label readable at any browser size. */
        .selector-app { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }

        .selector-sidebar {
            min-width: 0;
            overflow-x: hidden;
            position: sticky;
            top: 0;
            height: 100vh;
            max-height: 100vh;
            padding-left: clamp(14px, 1.6vw, 22px);
            padding-right: clamp(14px, 1.6vw, 22px);
        }

        .side-menu {
            flex: 1 1 auto;
            align-content: start;
        }

        .side-footer {
            flex: 0 0 auto;
            margin-top: auto;
        }

        .selector-content {
            padding: 40px 20px;
            background: transparent;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .app-brand {
            min-width: 0;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .side-menu button,
        .side-menu a,
        .side-footer a {
            min-width: 0;
            height: auto;
            min-height: 44px;
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 1.25;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        @media (max-width: 1180px) {
            .selector-app { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }
        }

        @media (max-width: 980px) {
            .selector-app { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }

            .selector-sidebar {
                position: sticky;
                top: 0;
                z-index: 5;
                height: auto;
                max-height: none;
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, .12);
            }

            .app-brand {
                margin-bottom: 14px;
            }

            .side-menu {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }

            .side-footer {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                margin-top: 12px;
            }
        }

        @media (max-width: 620px) {
            .side-menu,
            .side-footer {
                grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            }
        }
        /* Fixed desktop sidebar: it stays visible while long panels scroll or browser zoom changes. */
        @media (min-width: 981px) {
            .selector-app { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }

            .selector-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 20;
                width: clamp(220px, 20vw, 280px);
                height: 100vh;
                height: 100dvh;
                max-height: 100vh;
                max-height: 100dvh;
                overflow-y: auto;
                overscroll-behavior: contain;
            }

            .selector-content {
            padding: 40px 20px;
            background: transparent;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

            .side-menu {
                flex: 1 1 auto;
                align-content: start;
            }

            .side-footer {
                flex: 0 0 auto;
                margin-top: auto;
            }
        }

        @media (min-width: 981px) and (max-height: 680px) {
            .selector-sidebar {
                padding-top: 18px;
                padding-bottom: 18px;
            }

            .app-brand {
                margin-bottom: 18px;
            }

            .side-menu {
                gap: 6px;
            }

            .side-footer {
                gap: 6px;
                margin-top: 18px;
            }
        }
        /* Lock the shell: only the main content scrolls, the left sidebar stays still. */
        @media (min-width: 981px) {
            html,
            body {
                height: 100%;
                overflow: hidden;
            }

            .selector-app { 
            width: 100%; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }

            .selector-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                z-index: 50;
                display: flex;
                flex-direction: column;
                width: clamp(220px, 20vw, 280px);
                height: 100vh;
                height: 100dvh;
                max-height: 100vh;
                max-height: 100dvh;
                overflow-y: auto;
                transform: none !important;
            }

            .selector-content {
            padding: 40px 20px;
            background: transparent;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

            .side-menu {
                flex: 1 1 auto;
            }

            .side-footer {
                flex: 0 0 auto !important;
                margin-top: auto;
                padding-bottom: max(0px, env(safe-area-inset-bottom));
            }
        }

        .side-footer {
            flex: 0 0 auto !important;
            margin-top: auto !important;
            align-content: end;
        }

        .side-footer form {
            width: 100%;
        }

        .side-footer a,
        .side-footer button {
            width: 100%;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 14px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--text-muted);
            font: inherit;
            font-weight: 900;
            line-height: 1;
            text-align: left;
            text-decoration: none;
        }

        .side-footer a:hover,
        .side-footer button:hover {
            background: rgba(20, 184, 111, 0.18);
            color: var(--text-main);
            box-shadow: inset 3px 0 0 var(--accent-primary);
        }

        .selector-head {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
        }

        .selector-logo {
            justify-self: start;
        }

        .selector-title {
            justify-self: center;
        }

        @media (max-width: 980px) {
            .selector-head {
                grid-template-columns: 1fr;
                justify-items: center;
            }

            .selector-logo,
            .selector-title {
                justify-self: center;
            }
        }
    </style>

    <main class="selector-shell">
        
        <style>
            .header-btn {
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
                position: relative;
                overflow: hidden;
            }
            .header-btn:hover {
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 8px 20px rgba(243, 201, 105, 0.25) !important;
                border-color: var(--accent-primary) !important;
                background: rgba(243, 201, 105, 0.1) !important;
                color: var(--accent-primary) !important;
            }
            .header-btn.admin-btn:hover {
                background: var(--accent-hover) !important;
                color: var(--accent-primary) !important;
            }
            .theme-toggle:hover {
                transform: rotate(15deg) scale(1.15) !important;
                box-shadow: 0 0 15px rgba(243, 201, 105, 0.4) !important;
            }
            .module-card {
                border-radius: 12px; /* fallback */
            }
        </style>
<header class="selector-head">
            <div class="selector-logo"><span>SF</span><span>UI</span></div>
            <div class="selector-title"><span class="brand-mark" aria-hidden="true"></span> SMART FINANCE</div>
            <div class="head-actions" style="justify-self: end; display: flex; align-items: center; gap: 12px; margin-right: 14px;">
                <div class="profile-actions" style="display: flex; gap: 8px;">
                    <a href="{{ route('profile') }}" class="header-btn" style="font-size: 0.85rem; padding: 6px 14px; background: transparent; color: var(--text-main); border: 1px solid var(--border-color); border-radius: 99px; text-decoration: none; font-weight: 600;">{{ __('app.profile') }}</a>
                    @if ($isLoggedIn)
                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="header-btn" style="font-size: 0.85rem; padding: 6px 14px; background: transparent; color: var(--text-main); border: 1px solid var(--border-color); border-radius: 99px; cursor:pointer; font-weight: 600;">{{ __('app.logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="header-btn" style="font-size: 0.85rem; padding: 6px 14px; background: transparent; color: var(--text-main); border: 1px solid var(--border-color); border-radius: 99px; text-decoration: none; font-weight: 600;">{{ __('app.login') }}</a>
                    @endif
                </div>
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard.admin') }}" class="header-btn admin-btn" style="font-size: 0.85rem; padding: 6px 14px; background: var(--accent-primary); color: var(--accent-hover); border-radius: 99px; text-decoration: none; font-weight: bold;">{{ __('app.admin_panel') }}</a>
                @endif
                <div style="display: flex; gap: 8px; align-items: center; font-weight: bold; font-size: 0.9rem;">
                    <a href="{{ url('/lang/id') }}" style="text-decoration: none; color: {{ App::getLocale() === 'id' ? 'var(--accent-primary)' : 'var(--text-main)' }};">ID</a>
                    <span style="color: var(--text-muted);">|</span>
                    <a href="{{ url('/lang/en') }}" style="text-decoration: none; color: {{ App::getLocale() === 'en' ? 'var(--accent-primary)' : 'var(--text-main)' }};">EN</a>
                </div>
                <button class="theme-toggle" aria-label="Toggle Theme" style="border: 1px solid var(--border-color); background: transparent; color: var(--text-main); cursor: pointer; padding: 6px 10px; border-radius: 99px; font-size: 1.1rem; transition: all 0.2s;">
                    <span class="theme-icon">🌙</span>
                </button>
            </div>
        </header>

        <section class="dashboard-frame">
            <div class="selector-app">
                <section class="selector-content">
                    <div id="panel-dashboard" class="content-panel is-active">
                        <div class="panel-toolbar" style="margin-bottom: clamp(20px, 5vh, 60px); text-align: center; display: flex; flex-direction: column; align-items: center; width: 100%; padding: 0 20px;">
                            <div>
                                <h1 style="color: var(--accent-primary); font-size: clamp(1.8rem, 4vw, 3rem); margin-bottom: 12px;">{{ $greeting }}, {{ $firstName }}! 👋</h1>
                                <p style="color: var(--text-muted); font-size: clamp(0.9rem, 1.5vw, 1.2rem); max-width: 600px; margin: 0 auto;">{{ __('app.welcome_text') }}</p>
                            </div>
                        </div>

                        <style>
                            .module-container {
                                display: flex;
                                justify-content: center;
                                align-items: stretch;
                                gap: clamp(16px, 2.5vw, 32px);
                                width: 100%;
                                max-width: 1200px;
                                padding: 0 3vw;
                            }
                            .module-card {
                                flex: 1 1 0;
                                min-width: 200px;
                                max-width: 260px;
                                background: rgba(255, 255, 255, 0.04);
                                backdrop-filter: blur(16px);
                                -webkit-backdrop-filter: blur(16px);
                                border: 1px solid rgba(255, 255, 255, 0.08);
                                border-radius: 24px;
                                position: relative;
                                text-decoration: none;
                                color: var(--text-main);
                                transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
                                display: flex;
                                flex-direction: column;
                                overflow: hidden;
                                padding: clamp(20px, 3vh, 32px);
                                padding-bottom: clamp(24px, 3.5vh, 36px);
                            }
                            .module-card::before {
                                content: '';
                                position: absolute;
                                top: 0; left: 0; right: 0; bottom: 0;
                                background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, transparent 50%, rgba(243,201,105,0.04) 100%);
                                opacity: 0;
                                transition: opacity 0.4s;
                                border-radius: 24px;
                                z-index: 0;
                            }
                            .module-card:hover {
                                transform: translateY(-10px);
                                border-color: rgba(243, 201, 105, 0.35);
                                box-shadow:
                                    0 20px 50px -12px rgba(243, 201, 105, 0.2),
                                    0 0 0 1px rgba(243, 201, 105, 0.1),
                                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
                                background: rgba(255, 255, 255, 0.07);
                            }
                            .module-card:hover::before {
                                opacity: 1;
                            }
                            .module-card:hover .module-icon-wrap {
                                transform: scale(1.08);
                                box-shadow: 0 12px 30px -8px var(--card-accent);
                            }
                            .module-card:hover .module-title {
                                color: var(--accent-primary);
                            }
                            [data-theme="light"] .module-card {
                                background: rgba(255, 255, 255, 0.65);
                                border-color: rgba(0, 0, 0, 0.08);
                            }
                            [data-theme="light"] .module-card:hover {
                                background: rgba(255, 255, 255, 0.85);
                                border-color: rgba(20, 184, 166, 0.3);
                                box-shadow:
                                    0 20px 50px -12px rgba(20, 184, 166, 0.15),
                                    0 0 0 1px rgba(20, 184, 166, 0.1);
                            }
                            .module-icon-wrap {
                                width: clamp(64px, 10vh, 80px);
                                height: clamp(64px, 10vh, 80px);
                                border-radius: 20px;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                margin: 0 auto clamp(16px, 2.5vh, 24px);
                                font-size: clamp(2rem, 5vh, 2.8rem);
                                transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
                                position: relative;
                                z-index: 1;
                            }
                            .module-info {
                                text-align: center;
                                flex-grow: 1;
                                display: flex;
                                flex-direction: column;
                                position: relative;
                                z-index: 1;
                            }
                            .module-title {
                                font-size: clamp(1rem, 1.8vh, 1.2rem);
                                font-weight: 800;
                                letter-spacing: 0.5px;
                                margin: 0 0 clamp(6px, 1vh, 10px) 0;
                                transition: color 0.3s;
                            }
                            .module-desc {
                                font-size: clamp(0.78rem, 1.3vh, 0.88rem);
                                color: var(--text-muted);
                                line-height: 1.6;
                                flex-grow: 1;
                            }
                            .module-badge {
                                display: inline-block;
                                margin-top: clamp(12px, 2vh, 18px);
                                padding: 6px 18px;
                                border-radius: 99px;
                                font-size: 0.7rem;
                                font-weight: 800;
                                letter-spacing: 1.5px;
                                text-transform: uppercase;
                                background: rgba(243, 201, 105, 0.15);
                                color: var(--accent-primary);
                                border: 1px solid rgba(243, 201, 105, 0.2);
                            }
                            [data-theme="light"] .module-badge {
                                background: rgba(20, 184, 166, 0.1);
                                color: #0d9488;
                                border-color: rgba(20, 184, 166, 0.2);
                            }
                        </style>
                        <div class="module-container">
                            <a class="module-card" href="{{ route('finance.index') }}">
                                <div class="module-icon-wrap" style="background: linear-gradient(135deg, rgba(20,184,166,0.2), rgba(20,184,166,0.05)); --card-accent: rgba(20,184,166,0.3);">📊</div>
                                <div class="module-info">
                                    <h3 class="module-title">{{ __('app.module_smart_finance') }}</h3>
                                    <p class="module-desc">{{ __('app.desc_smart_finance') }}</p>
                                    <span class="module-badge">{{ __('app.ready') }}</span>
                                </div>
                            </a>
                            
                            <a class="module-card" href="{{ route('perpajakan.index') }}">
                                <div class="module-icon-wrap" style="background: linear-gradient(135deg, rgba(243,201,105,0.2), rgba(243,201,105,0.05)); --card-accent: rgba(243,201,105,0.3);">🧾</div>
                                <div class="module-info">
                                    <h3 class="module-title">{{ __('app.module_perpajakan') }}</h3>
                                    <p class="module-desc">{{ __('app.desc_perpajakan') }}</p>
                                    <span class="module-badge">{{ __('app.ready') }}</span>
                                </div>
                            </a>

                            <a class="module-card" href="{{ route('stata') }}">
                                <div class="module-icon-wrap" style="background: linear-gradient(135deg, rgba(168,85,247,0.2), rgba(168,85,247,0.05)); --card-accent: rgba(168,85,247,0.3);">📈</div>
                                <div class="module-info">
                                    <h3 class="module-title">{{ __('app.module_stata') }}</h3>
                                    <p class="module-desc">{{ __('app.desc_stata') }}</p>
                                    <span class="module-badge">{{ __('app.ready') }}</span>
                                </div>
                            </a>

                            <a class="module-card" href="{{ route('targets.index') }}">
                                <div class="module-icon-wrap" style="background: linear-gradient(135deg, rgba(239,68,68,0.2), rgba(239,68,68,0.05)); --card-accent: rgba(239,68,68,0.3);">🎯</div>
                                <div class="module-info">
                                    <h3 class="module-title">{{ __('app.module_targets') }}</h3>
                                    <p class="module-desc">{{ __('app.desc_targets') }}</p>
                                    <span class="module-badge">{{ __('app.ready') }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
            </div>
        </section>
    </main>


    <script>
        document.querySelectorAll('[data-panel-target]').forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.dataset.panelTarget;

                document.querySelectorAll('[data-panel-target]').forEach((item) => {
                    item.classList.toggle('is-active', item === button);
                });

                document.querySelectorAll('.content-panel').forEach((panel) => {
                    panel.classList.toggle('is-active', panel.id === `panel-${target}`);
                });
            });
        });
    </script>
@endsection
