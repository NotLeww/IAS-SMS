<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Document & Credentials')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: dark;
            --bg: #0f1220;
            --bg-dark: #08091a;
            --surface: #161a2e;
            --surface-strong: #1e2340;
            --surface-soft: rgba(255,255,255,.04);
            --text: #e8eaf2;
            --muted: #8a90a8;
            --primary: #4fa3ff;
            --primary-strong: #00e5a0;
            --warning: #ffb94a;
            --danger: #ff5f7a;
            --border: rgba(255,255,255,.12);
            --shadow-sm: 0 1px 2px 0 rgba(0,0,0,.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top left, #161a2e 0%, #08091a 38%, #0f1220 100%);
            color: var(--text);
            font-family: 'DM Sans', system-ui, sans-serif;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            font-family: 'Syne', sans-serif;
            color: var(--text);
        }

        h1 {
            font-size: clamp(2.8rem, 4vw, 3rem);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        h2 {
            font-size: 2.25rem;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        h3 {
            font-size: 2rem;
            font-weight: 600;
        }

        h4 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        h5 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 32px;
        }

        .card {
            background: rgba(18, 24, 49, 0.92);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-md);
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
            min-height: 40px;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .button-primary {
            background: var(--primary);
            color: #09133b;
            border-color: rgba(79,163,255,.3);
        }

        .button-secondary {
            background: rgba(79,163,255,.08);
            color: var(--text);
            border-color: rgba(255,255,255,.12);
        }

        .button-danger {
            background: var(--danger);
            color: #fff;
            border-color: rgba(255,95,122,.3);
        }

        .button-warning {
            background: var(--warning);
            color: #0f1220;
            border-color: rgba(255,185,74,.3);
        }

        .button-small {
            padding: 10px 14px;
            font-size: .925rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: var(--radius-md);
            font: inherit;
            color: var(--text);
            background: rgba(255,255,255,.04);
            outline: none;
            transition: border .15s ease, box-shadow .15s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: rgba(79,163,255,.5);
            box-shadow: 0 0 0 3px rgba(79,163,255,.12);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.78rem;
            color: #a3aed0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: transparent;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        th {
            font-weight: 700;
            color: var(--text);
            background: rgba(255,255,255,.03);
        }

        .badge {
            display: inline-flex;
            font-size: .82rem;
            font-weight: 700;
            padding: 7px 12px;
            border-radius: 9999px;
            background: rgba(255,255,255,.08);
            color: var(--text);
        }

        .mt-2 { margin-top: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .text-sm { font-size: .95rem; }
        .text-muted { color: var(--muted); }

        .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-bottom: 28px;
        }

        .alert {
            padding: 16px 18px;
            border-radius: var(--radius-lg);
            border: 1px solid transparent;
        }

        .alert-success {
            background: rgba(0,229,160,.12);
            color: #d3ffe4;
            border-color: rgba(0,229,160,.25);
        }

        .alert-warning {
            background: rgba(255,185,74,.12);
            color: #fff1d6;
            border-color: rgba(255,185,74,.3);
        }

        .alert-error {
            background: rgba(255,95,122,.12);
            color: #ffd7e1;
            border-color: rgba(255,95,122,.3);
        }

        .footer {
            margin-top: 36px;
            padding-top: 22px;
            border-top: 1px solid rgba(255,255,255,.06);
            color: var(--muted);
            font-size: .95rem;
        }

        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
            .container { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="mb-4">
            <h1 style="margin:0;font-size:clamp(1.75rem,2.5vw,2.5rem);">Document & Credentials Sub-system</h1>
            <p class="text-sm text-muted">Manage student document requests, approvals, generation, release tracking, and archival in one workflow.</p>
        </header>

        <nav class="nav">
            <a href="{{ route('documents.request.form') }}" class="button button-secondary button-small">Student Request Form</a>
            <a href="{{ route('admin.documents.index') }}" class="button button-primary button-small">Admin Requests</a>
            <a href="{{ route('admin.documents.archived') }}" class="button button-secondary button-small">Archived Records</a>
        </nav>

        @if (session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning mb-4">{{ session('warning') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error mb-4">
                <strong>There were some problems with your input.</strong>
                <ul class="mt-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

        <footer class="footer">IAS-SMS Document & Credentials Sub-system</footer>
    </div>
</body>
</html>
