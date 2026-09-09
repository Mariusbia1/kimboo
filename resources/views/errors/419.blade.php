<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session expirée • Kimboo</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0B0F17;
            color: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        .bg-glow-1 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(252, 179, 21, 0.08) 0%, rgba(252, 179, 21, 0) 70%);
            top: -150px;
            left: -150px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
        }

        .bg-glow-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.06) 0%, rgba(245, 158, 11, 0) 70%);
            bottom: -150px;
            right: -150px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
        }

        .card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 3.5rem 3rem;
            max-width: 580px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.03);
            position: relative;
            z-index: 10;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 1.9rem;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.03em;
            margin-bottom: 2rem;
            text-decoration: none;
        }

        .logo-dot {
            color: #FCB315;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            background: rgba(252, 179, 21, 0.1);
            border: 1px solid rgba(252, 179, 21, 0.25);
            color: #FCB315;
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.8125rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-bottom: 1.75rem;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: #FCB315;
            border-radius: 50%;
            box-shadow: 0 0 8px #FCB315;
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.35; transform: scale(0.8); }
        }

        h1 {
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1.25;
            color: #FFFFFF;
            margin-bottom: 1rem;
            letter-spacing: -0.025em;
        }

        .description {
            font-family: 'Inter', -apple-system, sans-serif;
            font-size: 1rem;
            line-height: 1.65;
            color: #94A3B8;
            margin-bottom: 2.25rem;
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2.25rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #FCB315;
            color: #0B0F17;
            font-weight: 700;
            font-size: 0.9375rem;
            padding: 0.85rem 1.6rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(252, 179, 21, 0.35);
            cursor: pointer;
            border: none;
        }

        .btn-primary:hover {
            background: #e5a110;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(252, 179, 21, 0.45);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.06);
            color: #F8FAFC;
            font-weight: 600;
            font-size: 0.9375rem;
            padding: 0.85rem 1.6rem;
            border-radius: 12px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            margin-bottom: 1.75rem;
        }

        .footer-note {
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            color: #64748B;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .footer-note a {
            color: #FCB315;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .footer-note a:hover {
            color: #FDE68A;
            text-decoration: underline;
        }

        @media (max-width: 540px) {
            .card {
                padding: 2.25rem 1.5rem;
            }
            h1 {
                font-size: 1.7rem;
            }
            .actions {
                flex-direction: column;
                width: 100%;
            }
            .btn-primary, .btn-secondary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <main class="card">
        <a href="{{ route('home') }}" class="logo">
            kimboo<span class="logo-dot">.</span>
        </a>

        <div>
            <div class="status-pill">
                <span class="status-dot"></span>
                Erreur 419 • Session expirée
            </div>
        </div>

        <h1>Votre session a expiré</h1>

        <p class="description">
            Par mesure de sécurité suite à une période d'inactivité, votre jeton de session a expiré. Veuillez actualiser la page ou vous reconnecter pour continuer en toute sécurité.
        </p>

        <div class="actions">
            <button onclick="window.location.reload();" class="btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                Actualiser la page
            </button>
            <a href="{{ route('login') }}" class="btn-secondary">
                Se reconnecter
            </a>
        </div>

        <div class="divider"></div>

        <div class="footer-note">
            <span>Une question ? Contactez le support :</span>
            <a href="mailto:support@kimboo.net">support@kimboo.net</a>
        </div>
    </main>
</body>
</html>
