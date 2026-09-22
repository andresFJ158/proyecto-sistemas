@extends('layouts.app')
@section('title', 'Solicitudes - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">Operación</div><h1>Solicitudes</h1><div class="muted">Consulta, prioriza y da seguimiento al ciclo completo.</div></div><a class="btn" href="{{ route('requests.create') }}">+ Registrar</a></header>
<form class="card form-grid" method="GET">
    <div class="field"><label>Buscar</label><input class="input" name="search" value="{{ request('search') }}" placeholder="Código o título"></div>
    <div class="field"><label>Estado</label><select class="input" name="status"><option value="">Todos</option>@foreach(\App\Models\ServiceRequest::STATUSES as $status)<option @selected(request('status')===$status)>{{ $status }}</option>@endforeach</select></div>
    <div class="field"><label>Prioridad</label><select class="input" name="priority"><option value="">Todas</option>@foreach(\App\Models\ServiceRequest::PRIORITIES as $priority)<option @selected(request('priority')===$priority)>{{ $priority }}</option>@endforeach</select></div>
    <div class="actions" style="align-self:end"><button class="btn" type="submit">Filtrar</button><a class="btn ghost" href="{{ route('requests.index') }}">Limpiar</a></div>
</form>
<div class="section-head"><h2>{{ $requests->total() }} resultados</h2></div>
<div class="card table-wrap"><table class="table"><thead><tr><th>Código</th><th>Solicitud</th><th>Prioridad</th><th>Estado</th><th>Responsable</th><th></th></tr></thead><tbody>
@forelse($requests as $item)<tr><td class="code">{{ $item->tracking_code }}</td><td><strong>{{ $item->title }}</strong><br><span class="muted">{{ $item->type->name }} · {{ $item->student->name }}</span></td><td><span class="badge {{ $item->priority }}">{{ $item->priority }}</span></td><td><span class="badge {{ $item->status }}">{{ str_replace('_',' ',$item->status) }}</span></td><td>{{ $item->assignee?->name ?? 'Sin asignar' }}</td><td><a class="btn small ghost" href="{{ route('requests.show',$item) }}">Abrir</a></td></tr>@empty<tr><td colspan="6" class="empty">No se encontraron solicitudes.</td></tr>@endforelse
</tbody></table></div>
<div style="margin-top:18px">{{ $requests->links() }}</div>
@endsection
