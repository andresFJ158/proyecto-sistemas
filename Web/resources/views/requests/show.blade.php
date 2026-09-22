@extends('layouts.app')
@section('title', $serviceRequest->tracking_code.' - Campus Connect')
@section('content')
<header class="topbar"><div><div class="eyebrow">{{ $serviceRequest->tracking_code }}</div><h1>{{ $serviceRequest->title }}</h1><div class="actions"><span class="badge {{ $serviceRequest->priority }}">{{ $serviceRequest->priority }}</span><span class="badge {{ $serviceRequest->status }}">{{ str_replace('_',' ',$serviceRequest->status) }}</span></div></div><div class="actions"><a class="btn ghost" href="{{ route('requests.edit',$serviceRequest) }}">Editar</a><a class="btn secondary" href="{{ route('requests.index') }}">Volver</a></div></header>
<div class="grid grid-main">
    <div class="stack">
        <section class="card"><div class="section-head" style="margin-top:0"><h2>Detalle</h2></div><dl class="detail-list">
            <dt>Estudiante</dt><dd>{{ $serviceRequest->student->name }} · {{ $serviceRequest->student->email }}</dd><dt>Tipo</dt><dd>{{ $serviceRequest->type->name }}</dd><dt>Ubicación</dt><dd>{{ $serviceRequest->location ?: 'No especificada' }}</dd><dt>Creada</dt><dd>{{ $serviceRequest->created_at->format('d/m/Y H:i') }}</dd><dt>Descripción</dt><dd style="white-space:pre-line">{{ $serviceRequest->description }}</dd>
        </dl></section>
        <section class="card"><div class="section-head" style="margin-top:0"><h2>Comentarios</h2><span class="muted">{{ $serviceRequest->comments->count() }}</span></div>
            @forelse($serviceRequest->comments->sortBy('created_at') as $comment)<div class="comment"><strong>{{ $comment->user->name }}</strong> <span class="muted">· {{ $comment->created_at->format('d/m/Y H:i') }}</span><div>{{ $comment->body }}</div></div>@empty<p class="empty">Todavía no hay comentarios.</p>@endforelse
            <form class="stack" method="POST" action="{{ route('requests.comments.store',$serviceRequest) }}" style="margin-top:18px">@csrf<div class="field"><label>Nuevo comentario</label><textarea class="input" name="body" required placeholder="Actualización visible para el estudiante"></textarea></div><button class="btn" type="submit">Publicar comentario</button></form>
        </section>
        <section class="card"><div class="section-head" style="margin-top:0"><h2>Evidencias</h2></div>
            @forelse($serviceRequest->evidences as $evidence)<div class="comment"><a href="{{ asset('storage/'.$evidence->path) }}" target="_blank"><strong>{{ $evidence->original_name }}</strong></a><br><span class="muted">{{ number_format($evidence->size/1024,1) }} KB · {{ $evidence->user->name }}</span></div>@empty<p class="empty">Sin archivos adjuntos.</p>@endforelse
            <form class="actions" method="POST" enctype="multipart/form-data" action="{{ route('requests.evidences.store',$serviceRequest) }}" style="margin-top:18px">@csrf<input class="input" type="file" name="file" required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"><button class="btn" type="submit">Adjuntar</button></form>
        </section>
    </div>
    <aside class="stack">
        <section class="card"><h2 style="margin-top:0">Responsable</h2><p class="muted">{{ $serviceRequest->assignee?->name ?? 'Aún no asignada' }}</p><form class="stack" method="POST" action="{{ route('requests.assign',$serviceRequest) }}">@csrf @method('PATCH')<select class="input" name="assigned_to" required><option value="">Seleccionar</option>@foreach($admins as $admin)<option value="{{ $admin->id }}" @selected($serviceRequest->assigned_to===$admin->id)>{{ $admin->name }}</option>@endforeach</select><button class="btn" type="submit">Asignar responsable</button></form></section>
        @php($allowedStatuses = ['PENDIENTE'=>['ASIGNADA'],'ASIGNADA'=>['EN_PROCESO'],'EN_PROCESO'=>['RESUELTA'],'RESUELTA'=>['CERRADA','EN_PROCESO'],'CERRADA'=>[]][$serviceRequest->status])
        <section class="card"><h2 style="margin-top:0">Cambiar estado</h2>@if($allowedStatuses)<form class="stack" method="POST" action="{{ route('requests.status',$serviceRequest) }}">@csrf @method('PATCH')<div class="field"><label>Nuevo estado</label><select class="input" name="status" required>@foreach($allowedStatuses as $status)<option value="{{ $status }}">{{ str_replace('_',' ',$status) }}</option>@endforeach</select></div><div class="field"><label>Nota de seguimiento</label><textarea class="input" name="comment" placeholder="Qué cambió o qué se hizo"></textarea></div><button class="btn" type="submit">Actualizar estado</button></form>@else<p class="muted">Esta solicitud ya completó su ciclo de atención.</p>@endif</section>
        <section class="card"><h2 style="margin-top:0">Trazabilidad</h2><div class="timeline">@foreach($serviceRequest->statusHistory as $event)<div class="timeline-item"><strong>{{ str_replace('_',' ',$event->new_status) }}</strong><div class="muted">{{ $event->created_at->format('d/m/Y H:i') }} · {{ $event->user->name }}</div>@if($event->comment)<div>{{ $event->comment }}</div>@endif</div>@endforeach</div></section>
        <form method="POST" action="{{ route('requests.destroy',$serviceRequest) }}" onsubmit="return confirm('¿Eliminar esta solicitud?')">@csrf @method('DELETE')<button class="btn danger" style="width:100%" type="submit">Eliminar solicitud</button></form>
    </aside>
</div>
@endsection
