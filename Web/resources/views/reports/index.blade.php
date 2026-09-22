@extends('layouts.app')
@section('title','Reportes - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">Información consolidada</div><h1>Reportes</h1><div class="muted">Indicadores para priorizar y evaluar la atención.</div></div><a class="btn" href="{{ route('reports.export') }}">Exportar CSV</a></header>
<section class="grid grid-2">
    <article class="card stat"><span>Tiempo promedio de atención</span><strong>{{ $averageHours }} h</strong><p class="muted">Desde el registro hasta el cierre.</p></article>
    <article class="card"><h2 style="margin-top:0">Carga por responsable</h2><div class="bars">@forelse($byAssignee as $row)<div class="bar-row"><span>{{ $row->assignee?->name }}</span><div class="bar-track"><div class="bar-fill" style="width:{{ ($row->total/$byAssignee->max('total'))*100 }}%"></div></div><strong>{{ $row->total }}</strong></div>@empty<p class="empty">Sin asignaciones.</p>@endforelse</div></article>
</section>
<div class="section-head"><h2>Solicitudes abiertas más antiguas</h2></div>
<div class="card table-wrap"><table class="table"><thead><tr><th>Código</th><th>Solicitud</th><th>Estado</th><th>Antigüedad</th></tr></thead><tbody>@forelse($oldestPending as $item)<tr><td><a class="code" href="{{ route('requests.show',$item) }}">{{ $item->tracking_code }}</a></td><td><strong>{{ $item->title }}</strong><br><span class="muted">{{ $item->student->name }} · {{ $item->type->name }}</span></td><td><span class="badge {{ $item->status }}">{{ str_replace('_',' ',$item->status) }}</span></td><td>{{ (int) $item->created_at->diffInDays() }} días</td></tr>@empty<tr><td colspan="4" class="empty">No hay solicitudes abiertas.</td></tr>@endforelse</tbody></table></div>
@endsection
