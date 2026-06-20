<style>
    html {
        background: #050c0f;
    }

    body.module-page {
        min-width: 320px;
        min-height: 100vh;
        background: #050c0f !important;
        color: #f8fafc;
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
        background: #14b86f;
        color: #042f2e;
        font-size: 1.35rem;
        font-weight: 950;
        box-shadow: 0 10px 28px rgba(20, 184, 111, .22);
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
        scrollbar-color: rgba(243, 201, 105, .45) transparent;
    }

    body.module-page .main-nav a,
    body.module-page .nav-form button {
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
        border-color: #f3c969 !important;
        background: #f3c969 !important;
    }

    body.module-page .nav-form {
        flex: 0 0 auto;
        margin: 0;
    }

    body.module-page .content {
        width: 100%;
        min-height: calc(100vh - 76px);
    }

    body.module-page .finance-workspace,
    body.module-page .tax-workspace,
    body.module-page .stata-workspace {
        width: 100% !important;
        min-height: calc(100vh - 76px) !important;
        margin: 0 !important;
        padding: clamp(30px, 4vw, 64px) clamp(18px, 4vw, 70px) clamp(54px, 7vw, 100px) !important;
        background-color: #071719 !important;
        background-image:
            linear-gradient(180deg, rgba(5, 12, 15, .66) 0%, rgba(5, 12, 15, .9) 52%, #050c0f 100%),
            url('{{ asset('images/backgroundfinance.jpg') }}'),
            radial-gradient(circle at 82% 0%, rgba(20, 184, 111, .2), transparent 38%) !important;
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

    body.module-page .site-footer {
        display: none !important;
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
</style>
