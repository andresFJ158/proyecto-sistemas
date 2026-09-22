@extends('layouts.app')
@section('title', 'Ingresar - Campus Connect')
@section('guest')
<div class="login-page">
    <section class="login-art">
        <div class="eyebrow" style="color:#88d1c2">Plataforma universitaria</div>
        <h1>Campus<br>Connect</h1>
        <p>Un solo lugar para registrar, asignar y acompañar cada solicitud hasta su cierre.</p>
    </section>
    <section class="login-form">
        <form class="card stack" method="POST" action="{{ route('login.store') }}">
            @csrf
            <div><div class="eyebrow">Panel administrativo</div><h2 style="font-size:28px;margin:5px 0">Bienvenido</h2><p class="muted">Ingresa con tu cuenta institucional.</p></div>
            @if($errors->any())<div class="alert error-box">{{ $errors->first() }}</div>@endif
            <div class="field"><label>Correo electrónico</label><input class="input" type="email" name="email" value="{{ old('email') }}" required autofocus></div>
            <div class="field"><label>Contraseña</label><input class="input" type="password" name="password" required></div>
            <label style="display:flex;gap:8px;align-items:center"><input type="checkbox" name="remember" value="1"> Mantener sesión</label>
            <button class="btn" type="submit">Ingresar al panel</button>
            <p class="muted" style="font-size:12px;margin:0">Demo: admin@campus.test / password</p>
        </form>
    </section>
</div>
@endsection
