@extends('layouts.app')
@section('title','Editar solicitud - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">{{ $serviceRequest->tracking_code }}</div><h1>Editar solicitud</h1></div><a class="btn ghost" href="{{ route('requests.show',$serviceRequest) }}">Volver</a></header>
<form class="card stack" method="POST" action="{{ route('requests.update',$serviceRequest) }}">@csrf @method('PUT') @include('requests._form')<div class="actions"><button class="btn" type="submit">Guardar cambios</button><a class="btn ghost" href="{{ route('requests.show',$serviceRequest) }}">Cancelar</a></div></form>
@endsection
