<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $titlePage ?? 'Candidature — ESP' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --green:#00853F; --green2:#005f2d; --yellow:#FDEF42; --red:#E31E24;
    --dark:#0D1B2A; --bg:#f0f4f8; --card:#fff; --text:#1a1a2e;
    --muted:#6b7280; --border:#e5e7eb;
  }
  body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
  a { color:inherit; text-decoration:none; }
  button { font-family:inherit; }

  .topbar {
    background:linear-gradient(135deg,var(--green),var(--green2));
    color:#fff; padding:18px 32px;
    display:flex; align-items:center; justify-content:space-between;
    position:sticky; top:0; z-index:50;
  }
  .topbar::after {
    content:''; position:absolute; left:0; right:0; bottom:0; height:3px;
    background:linear-gradient(90deg,var(--green),var(--yellow),var(--red));
  }
  .topbar .logo { display:flex; align-items:center; gap:12px; font-weight:700; font-size:16px; }
  .topbar .logo i { font-size:22px; color:var(--yellow); }
  .topbar nav { display:flex; gap:18px; font-size:13.5px; font-weight:500; }
  .topbar nav a { padding:8px 14px; border-radius:8px; transition:background .2s; }
  .topbar nav a:hover { background:rgba(255,255,255,.12); }
  .topbar nav a.active { background:rgba(255,255,255,.2); }
  .topbar nav .btn-login {
    background:#fff; color:var(--green);
  }
  .topbar nav .btn-login:hover { background:#f0fdf4; }

  .wrap { max-width:1080px; margin:0 auto; padding:40px 24px; }

  .hero {
    text-align:center; padding:48px 20px;
    background:#fff; border-radius:18px; border:1px solid var(--border);
    margin-bottom:28px;
  }
  .hero h1 { font-size:28px; font-weight:800; margin-bottom:10px; }
  .hero p { color:var(--muted); font-size:14px; max-width:520px; margin:0 auto; line-height:1.6; }

  .card { background:#fff; border:1px solid var(--border); border-radius:14px; overflow:hidden; }
  .card-head { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
  .card-head h2 { font-size:16px; font-weight:700; display:flex; align-items:center; gap:8px; }
  .card-head h2 i { color:var(--green); }
  .card-body { padding:24px; }

  .form-group { margin-bottom:18px; }
  .form-group label { display:block; font-size:13px; font-weight:600; margin-bottom:6px; }
  .iw { position:relative; display:flex; align-items:center; }
  .iw i { position:absolute; left:14px; color:var(--muted); font-size:14px; pointer-events:none; }
  .iw input, .iw select, .iw textarea {
    width:100%; padding:11px 14px 11px 40px;
    border:1.5px solid var(--border); border-radius:10px;
    font-size:14px; font-family:inherit; background:#f9fafb;
  }
  .iw textarea { padding-left:14px; min-height:80px; resize:vertical; }
  .iw input:focus, .iw select:focus, .iw textarea:focus {
    outline:none; border-color:var(--green); background:#fff;
  }
  .row-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  @media (max-width:640px) { .row-2 { grid-template-columns:1fr; } }

  .btn {
    display:inline-flex; align-items:center; gap:8px;
    padding:12px 22px; border-radius:10px;
    font-size:14px; font-weight:600;
    border:none; cursor:pointer; transition:all .15s;
  }
  .btn-green { background:linear-gradient(135deg,var(--green),var(--green2)); color:#fff; }
  .btn-green:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(0,133,63,.3); }
  .btn-outline { background:#fff; border:1.5px solid var(--border); color:var(--text); }
  .btn-outline:hover { border-color:var(--green); color:var(--green); }

  .alert { padding:14px 18px; border-radius:10px; font-size:13.5px; margin-bottom:20px; display:flex; align-items:center; gap:10px; }
  .alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
  .alert-error   { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
  .alert-info    { background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; }

  .footer {
    margin-top:40px; padding:24px;
    text-align:center; color:var(--muted); font-size:12px;
  }
  .flag { display:flex; gap:4px; justify-content:center; margin-top:10px; }
  .flag span { height:5px; border-radius:2px; width:60px; }
</style>
@stack('styles')
</head>
<body>

<header class="topbar">
  <a href="{{ url('/candidater') }}" class="logo">
    <i class="fas fa-graduation-cap"></i> ESP — Plateforme Numérique
  </a>
  <nav>
    <a href="{{ route('candidat.formulaire') }}" class="{{ request()->routeIs('candidat.formulaire') ? 'active' : '' }}">
      <i class="fas fa-edit"></i> Candidater
    </a>
    <a href="{{ route('candidat.suivi') }}" class="{{ request()->routeIs('candidat.suivi') || request()->routeIs('candidat.details') ? 'active' : '' }}">
      <i class="fas fa-search"></i> Suivre ma candidature
    </a>
    <a href="{{ route('login') }}" class="btn-login">
      <i class="fas fa-sign-in-alt"></i> Connexion
    </a>
  </nav>
</header>

<div class="wrap">
  @if (session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <ul style="list-style:none">
        @foreach ($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  @yield('content')
</div>

<footer class="footer">
  <p>École Supérieure Polytechnique · Dakar, Sénégal</p>
  <div class="flag">
    <span style="background:#00853F"></span>
    <span style="background:#FDEF42"></span>
    <span style="background:#E31E24"></span>
  </div>
</footer>

@stack('scripts')
</body>
</html>
