<div class="form-grid">
    @isset($students)<div class="field full"><label>Estudiante</label><select class="input" name="student_id" required><option value="">Seleccionar estudiante</option>@foreach($students as $student)<option value="{{ $student->id }}" @selected(old('student_id')==$student->id)>{{ $student->name }} · {{ $student->email }}</option>@endforeach</select></div>@endisset
    <div class="field"><label>Tipo</label><select class="input" name="request_type_id" required>@foreach($types as $type)<option value="{{ $type->id }}" @selected(old('request_type_id',$serviceRequest->request_type_id??null)==$type->id)>{{ $type->name }}</option>@endforeach</select></div>
    <div class="field"><label>Prioridad</label><select class="input" name="priority" required>@foreach(\App\Models\ServiceRequest::PRIORITIES as $priority)<option @selected(old('priority',$serviceRequest->priority??'MEDIA')===$priority)>{{ $priority }}</option>@endforeach</select></div>
    <div class="field full"><label>Título</label><input class="input" name="title" value="{{ old('title',$serviceRequest->title??'') }}" maxlength="160" required></div>
    <div class="field full"><label>Descripción</label><textarea class="input" name="description" required>{{ old('description',$serviceRequest->description??'') }}</textarea></div>
    <div class="field full"><label>Ubicación</label><input class="input" name="location" value="{{ old('location',$serviceRequest->location??'') }}" placeholder="Ej. Bloque B, laboratorio 3"></div>
</div>
