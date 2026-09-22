<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Campus Connect')</title>
    <style>
        :root{--ink:#17252a;--muted:#64748b;--line:#dbe4e8;--paper:#f4f7f6;--card:#fff;--brand:#0d6658;--brand2:#124e66;--accent:#ec9a29;--danger:#b42318;--shadow:0 12px 34px rgba(18,78,102,.09)}
        *{box-sizing:border-box} body{margin:0;background:var(--paper);color:var(--ink);font:15px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif} a{color:inherit;text-decoration:none} button,input,select,textarea{font:inherit}
        .shell{min-height:100vh;display:grid;grid-template-columns:240px 1fr}.sidebar{position:sticky;top:0;height:100vh;background:#0c2c35;color:#d9ece8;padding:28px 20px;display:flex;flex-direction:column}.brand{font-weight:900;font-size:19px;letter-spacing:.02em}.brand small{display:block;color:#84c5b7;font-size:10px;letter-spacing:.18em;text-transform:uppercase;margin-top:4px}.nav{display:grid;gap:7px;margin-top:35px}.nav a{padding:11px 13px;border-radius:10px;color:#bbd3d0;font-weight:650}.nav a:hover,.nav a.active{background:#154752;color:#fff}.sidebar-foot{margin-top:auto;font-size:12px;color:#92b3b0}.content{padding:34px 4vw 60px;min-width:0}.topbar{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-bottom:28px}.eyebrow{font-size:11px;text-transform:uppercase;letter-spacing:.16em;color:var(--brand);font-weight:800}.topbar h1{margin:4px 0 3px;font-size:clamp(25px,3vw,38px);line-height:1.1}.muted{color:var(--muted)}
        .card{background:var(--card);border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow);padding:22px}.grid{display:grid;gap:18px}.grid-5{grid-template-columns:repeat(5,minmax(0,1fr))}.grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}.grid-main{grid-template-columns:minmax(0,1.55fr) minmax(280px,.7fr)}.stat{position:relative;overflow:hidden}.stat:after{content:"";position:absolute;right:-20px;bottom:-30px;width:85px;height:85px;border-radius:50%;background:rgba(13,102,88,.08)}.stat strong{display:block;font-size:30px;margin-top:7px}.stat span{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.08em}
        .section-head{display:flex;align-items:center;justify-content:space-between;gap:14px;margin:30px 0 14px}.section-head h2{font-size:18px;margin:0}.btn{border:0;border-radius:9px;padding:10px 14px;background:var(--brand);color:white;font-weight:750;display:inline-flex;align-items:center;justify-content:center;cursor:pointer}.btn:hover{filter:brightness(.94)}.btn.secondary{background:#e6efed;color:#17443d}.btn.ghost{background:white;color:var(--brand);border:1px solid var(--line)}.btn.danger{background:#fff0ef;color:var(--danger)}.btn.small{padding:7px 10px;font-size:13px}
        .table-wrap{overflow:auto}.table{width:100%;border-collapse:collapse}.table th{text-align:left;color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:.08em;padding:10px 12px;border-bottom:1px solid var(--line)}.table td{padding:14px 12px;border-bottom:1px solid #edf1f2;vertical-align:top}.table tr:last-child td{border:0}.code{font:700 12px ui-monospace,SFMono-Regular,Menlo,monospace;color:var(--brand2)}
        .badge{display:inline-flex;border-radius:999px;padding:4px 9px;font-size:11px;font-weight:850;letter-spacing:.04em;background:#edf2f2;color:#435b61}.badge.PENDIENTE{background:#fff5d9;color:#855900}.badge.ASIGNADA{background:#e8f0ff;color:#2856a3}.badge.EN_PROCESO{background:#e4f5f1;color:#086454}.badge.RESUELTA{background:#e6f6e6;color:#247226}.badge.CERRADA{background:#e9edf0;color:#45525a}.badge.ALTA{background:#fff0ef;color:#a52a21}.badge.MEDIA{background:#fff6df;color:#8a5c00}.badge.BAJA{background:#edf5f3;color:#37645c}
        .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.field{display:grid;gap:7px}.field.full{grid-column:1/-1}.field label{font-size:12px;font-weight:800;color:#40545a}.input{width:100%;border:1px solid #cad6d9;background:#fff;border-radius:9px;padding:11px 12px;color:var(--ink);outline:none}.input:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(13,102,88,.09)}textarea.input{min-height:120px;resize:vertical}.error{color:var(--danger);font-size:12px}.alert{border-radius:10px;padding:12px 15px;margin-bottom:20px;background:#e7f6ef;color:#17643d}.alert.error-box{background:#fff0ef;color:var(--danger)}
        .bars{display:grid;gap:13px}.bar-row{display:grid;grid-template-columns:120px 1fr 35px;align-items:center;gap:10px;font-size:13px}.bar-track{height:9px;background:#eef2f2;border-radius:99px;overflow:hidden}.bar-fill{height:100%;background:linear-gradient(90deg,var(--brand),#41a38f);border-radius:99px}.detail-list{display:grid;grid-template-columns:130px 1fr;gap:10px 16px;margin:0}.detail-list dt{color:var(--muted);font-size:12px}.detail-list dd{margin:0;font-weight:650}.timeline{display:grid;gap:0}.timeline-item{border-left:2px solid #c6d8d5;padding:0 0 22px 18px;position:relative}.timeline-item:before{content:"";position:absolute;left:-6px;top:4px;width:10px;height:10px;border-radius:50%;background:var(--brand)}.timeline-item:last-child{padding-bottom:0}.comment{padding:13px 0;border-bottom:1px solid #edf1f2}.comment:last-child{border:0}.empty{text-align:center;padding:36px;color:var(--muted)}.actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}.stack{display:grid;gap:15px}.login-page{min-height:100vh;display:grid;grid-template-columns:1.1fr .9fr}.login-art{padding:8vw;background:linear-gradient(135deg,#092b35,#0d6658);color:white;display:flex;flex-direction:column;justify-content:center}.login-art h1{font-size:clamp(40px,7vw,78px);line-height:.95;margin:12px 0 20px;max-width:650px}.login-art p{font-size:18px;color:#c8e2de;max-width:520px}.login-form{display:flex;align-items:center;justify-content:center;padding:30px}.login-form .card{width:min(430px,100%)}
        @media(max-width:1050px){.grid-5{grid-template-columns:repeat(3,1fr)}.grid-main{grid-template-columns:1fr}}@media(max-width:760px){.shell{display:block}.sidebar{position:relative;height:auto;padding:18px}.nav{display:flex;overflow:auto;margin-top:18px}.sidebar-foot{display:none}.content{padding:24px 16px}.grid-5,.grid-2,.form-grid{grid-template-columns:1fr 1fr}.login-page{grid-template-columns:1fr}.login-art{padding:50px 28px}.login-art h1{font-size:44px}.topbar{align-items:center}}@media(max-width:500px){.grid-5,.grid-2,.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.bar-row{grid-template-columns:90px 1fr 28px}}
    </style>
</head>
<body>
@auth
<div class="shell">
    <aside class="sidebar">
        <div class="brand">CAMPUS CONNECT<small>Gestión universitaria</small></div>
        <nav class="nav">
            <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Resumen</a>
            <a class="{{ request()->routeIs('requests.*') ? 'active' : '' }}" href="{{ route('requests.index') }}">Solicitudes</a>
            <a class="{{ request()->routeIs('resources.*') ? 'active' : '' }}" href="{{ route('resources.index') }}">Recursos</a>
            <a class="{{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Reportes</a>
        </nav>
        <div class="sidebar-foot">
            <strong>{{ auth()->user()->name }}</strong><br>{{ auth()->user()->email }}
            <form method="POST" action="{{ route('logout') }}" style="margin-top:12px">@csrf<button class="btn small secondary" type="submit">Cerrar sesión</button></form>
        </div>
    </aside>
    <main class="content">
        @if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert error-box"><strong>Revisa los datos:</strong> {{ $errors->first() }}</div>@endif
        @yield('content')
    </main>
</div>
@else
    @yield('guest')
@endauth
</body>
</html>
