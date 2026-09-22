@extends('layouts.app')
@section('title', 'Dashboard - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">Visión operativa</div><h1>Panel administrativo</h1><div class="muted">Estado general de las solicitudes universitarias.</div></div><a class="btn" href="{{ route('requests.create') }}">+ Nueva solicitud</a></header>
<section class="grid grid-5">
    @foreach(['TOTAL'=>$total,'PENDIENTE'=>$statusCounts['PENDIENTE']??0,'EN_PROCESO'=>$statusCounts['EN_PROCESO']??0,'RESUELTA'=>$statusCounts['RESUELTA']??0,'CERRADA'=>$statusCounts['CERRADA']??0] as $label=>$value)
        <article class="card stat"><span>{{ str_replace('_',' ',$label) }}</span><strong>{{ $value }}</strong></article>
    @endforeach
</section>
<section class="grid grid-2">
    <div><div class="section-head"><h2>Solicitudes por tipo</h2></div><div class="card bars">
        @forelse($typeCounts as $label=>$value)<div class="bar-row"><span>{{ $label }}</span><div class="bar-track"><div class="bar-fill" style="width:{{ $typeCounts->max() ? ($value/$typeCounts->max())*100 : 0 }}%"></div></div><strong>{{ $value }}</strong></div>@empty<p class="empty">Sin datos todavía.</p>@endforelse
    </div></div>
    <div><div class="section-head"><h2>Por prioridad</h2></div><div class="card bars">
        @forelse($priorityCounts as $label=>$value)<div class="bar-row"><span class="badge {{ $label }}">{{ $label }}</span><div class="bar-track"><div class="bar-fill" style="width:{{ $priorityCounts->max() ? ($value/$priorityCounts->max())*100 : 0 }}%"></div></div><strong>{{ $value }}</strong></div>@empty<p class="empty">Sin datos todavía.</p>@endforelse
    </div></div>
</section>
<div class="section-head"><h2>Actividad reciente</h2><a class="btn small ghost" href="{{ route('requests.index') }}">Ver todas</a></div>
<div class="card table-wrap"><table class="table"><thead><tr><th>Código</th><th>Solicitud</th><th>Estado</th><th>Responsable</th><th>Fecha</th></tr></thead><tbody>
@forelse($recent as $item)<tr><td><a class="code" href="{{ route('requests.show',$item) }}">{{ $item->tracking_code }}</a></td><td><strong>{{ $item->title }}</strong><br><span class="muted">{{ $item->type->name }} · {{ $item->student->name }}</span></td><td><span class="badge {{ $item->status }}">{{ str_replace('_',' ',$item->status) }}</span></td><td>{{ $item->assignee?->name ?? 'Sin asignar' }}</td><td>{{ $item->created_at->format('d/m/Y') }}</td></tr>@empty<tr><td colspan="5" class="empty">No hay solicitudes registradas.</td></tr>@endforelse
</tbody></table></div>
@endsection
