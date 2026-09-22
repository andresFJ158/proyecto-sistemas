@extends('layouts.app')
@section('title','Editar recurso - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">{{ $resource->code }}</div><h1>Editar recurso</h1></div><a class="btn ghost" href="{{ route('resources.index') }}">Volver</a></header>
<form class="card stack" method="POST" action="{{ route('resources.update',$resource) }}">@csrf @method('PUT') @include('resources._form')<div class="actions"><button class="btn">Guardar cambios</button><a class="btn ghost" href="{{ route('resources.index') }}">Cancelar</a></div></form>
@endsection
