<div>


	@isset ($pid)
	@php
		$p = App\Models\Pedido::where('slug', $pid)->first()
	@endphp
	@if ($p->compra()->exists())
	<div class="bootstrap-iso" ></div>
	<div class="alert alert-info" role="alert" style="margin-top: 15px;">
		<span class="sr-only">Error:</span>
		La compra ya existe
	</div>
	@else
	<div wire:init="create({{$p}})" ></div>
	@endif
	@endisset

	<div class="row">

		<div class="col-lg-12">
			<h1 class="page-header">Compras</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">

				<div class="panel-body">
					<table class="table" id="tcompra">
						<thead >
							<tr>

								<th scope="col" class="text-center">Farmacia</th>
								<th scope="col" class="text-center">Pedido</th>
								<th scope="col" class="text-center">Vencimiento</th>
								<th scope="col" class="text-center">Cancelado</th>
							</tr>
						</thead>

						<tbody>
							@foreach($compras as $c)

							<tr>
								<td class="text-center">{{$c->farmacia->nombre}}</td>
								<td class="text-center">{{$c->id_pedido}}</td>
								<td class="text-center">{{$c->vencimiento}}</td>
								<td class="text-center">
									{{$c->cancelado == 0 ? 'No' : 'Si' }}
								</td>

								@can('compra.edit')
								<td class="text-center" class="col-1 text-center">
									<button wire:click="show({{$c}})" class="btn btn-success" >Ver</button>
									<button wire:click="pay({{$c}})" class="btn btn-warning" >Cancelar</button>
								</td>
								@endcan
							</tr>
							@endforeach
						</tbody>
					</table>

					<div class="col-2 text-center">
						<div class="bootstrap-iso">
							<div class="col-2 text-right">
								{{ $compras->links() }}
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	@include('livewire.compra.create.create')
	@include('livewire.compra.show')



</div>
