<div class="form-grid">
    <div class="field"><label>Código</label><input class="input" name="code" value="{{ old('code',$resource->code??'') }}" required></div>
    <div class="field"><label>Nombre</label><input class="input" name="name" value="{{ old('name',$resource->name??'') }}" required></div>
    <div class="field"><label>Categoría</label><input class="input" name="category" value="{{ old('category',$resource->category??'') }}" placeholder="Tecnología, mobiliario…" required></div>
    <div class="field"><label>Estado</label><select class="input" name="status">@foreach(\App\Models\Resource::STATUSES as $status)<option @selected(old('status',$resource->status??'DISPONIBLE')===$status)>{{ $status }}</option>@endforeach</select></div>
    <div class="field full"><label>Ubicación</label><input class="input" name="location" value="{{ old('location',$resource->location??'') }}"></div>
    <div class="field full"><label>Descripción</label><textarea class="input" name="description">{{ old('description',$resource->description??'') }}</textarea></div>
</div>
