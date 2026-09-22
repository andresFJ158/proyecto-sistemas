@extends('layouts.app')
@section('title','Nuevo recurso - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">Inventario</div><h1>Nuevo recurso</h1></div><a class="btn ghost" href="{{ route('resources.index') }}">Volver</a></header>
<form class="card stack" method="POST" action="{{ route('resources.store') }}">@csrf @include('resources._form')<div class="actions"><button class="btn">Guardar recurso</button><a class="btn ghost" href="{{ route('resources.index') }}">Cancelar</a></div></form>
@endsection
