@extends('layouts.app')
@section('title','Recursos - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">Inventario institucional</div><h1>Recursos</h1><div class="muted">Disponibilidad, ubicación y estado de los activos.</div></div><a class="btn" href="{{ route('resources.create') }}">+ Nuevo recurso</a></header>
<div class="card table-wrap"><table class="table"><thead><tr><th>Código</th><th>Recurso</th><th>Categoría</th><th>Ubicación</th><th>Estado</th><th></th></tr></thead><tbody>
@forelse($resources as $resource)<tr><td class="code">{{ $resource->code }}</td><td><strong>{{ $resource->name }}</strong><br><span class="muted">{{ $resource->description }}</span></td><td>{{ $resource->category }}</td><td>{{ $resource->location ?: '—' }}</td><td><span class="badge">{{ $resource->status }}</span></td><td><div class="actions"><a class="btn small ghost" href="{{ route('resources.edit',$resource) }}">Editar</a><form method="POST" action="{{ route('resources.destroy',$resource) }}" onsubmit="return confirm('¿Eliminar recurso?')">@csrf @method('DELETE')<button class="btn small danger">Eliminar</button></form></div></td></tr>@empty<tr><td colspan="6" class="empty">No hay recursos registrados.</td></tr>@endforelse
</tbody></table></div><div style="margin-top:18px">{{ $resources->links() }}</div>
@endsection
