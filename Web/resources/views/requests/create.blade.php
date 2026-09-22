@extends('layouts.app')
@section('title','Registrar solicitud - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">Nuevo registro</div><h1>Registrar solicitud</h1><div class="muted">Crea una solicitud en nombre de un estudiante.</div></div><a class="btn ghost" href="{{ route('requests.index') }}">Volver</a></header>
<form class="card stack" method="POST" action="{{ route('requests.store') }}">@csrf @include('requests._form')<div class="actions"><button class="btn" type="submit">Guardar solicitud</button><a class="btn ghost" href="{{ route('requests.index') }}">Cancelar</a></div></form>
@endsection
