{{-- Universidad --}}
<div class="mb-3">
	<label class="form-label">Universidad</label>
	<input type="text" class="form-control" wire:model.defer="universidad" >
</div>

{{-- Fecha --}}
<div class="mb-3">
	<label class="form-label">Fecha</label>
	<input type="date" class="form-control" wire:model.defer="fecha">
</div>

{{-- Numero de Registro  --}}
<div class="mb-3">
	<label class="form-label">Numero de Registro</label>
	<input type="number" class="form-control" wire:model.defer="n_registro" >
</div>

{{-- Permiso Sanitario --}}
<div class="mb-3">
	<label class="form-label">Permiso Sanitario</label>
	<input type="number" class="form-control" wire:model.defer="p_sanitario" >
</div>

{{-- Numero Colegiatura --}}
<div class="mb-3">
	<label class="form-label">Numero de Colegiatura</label>
	<input type="number" class="form-control" wire:model.defer="n_colegiatura" >
</div>
