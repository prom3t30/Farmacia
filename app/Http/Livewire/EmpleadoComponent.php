<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\Farmacia;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmpleadoComponent extends Component
{
	use WithPagination;

	protected $paginationTheme = 'bootstrap';
	public $empleado;
	public $formType;
	public $ci, $farmacia, $nombre, $apellido, $edad, $cargo, $telefono;
	public $institucion, $especialidad, $f_inicio, $n_permiso, $activo, $minoria, $f_final;
	public $ci_r, $nombre_r, $apellido_r, $telefono_r;
	public $universidad, $fecha, $n_registro, $p_sanitario, $n_colegiatura;
	public $cargos = ["pasante", "administrativo", "farmaceutico", "vigilante", "analista",];

	public function render()
	{
		return view('livewire.empleado.empleado-component')
		->with('empleados', Empleado::paginate(8))
		->with('farmacias', Farmacia::all())
		;
	}

	public function create() {
		$this->reset();
		$this->formType = 0;
		$this->cargos = ["pasante", "administrativo", "farmaceutico", "vigilante", "analista",];
		$this->dispatchBrowserEvent('openCreateForm');
	}

	public function closeCreate() {
		$this->dispatchBrowserEvent('closeCreateForm');
		$this->reset();
	}

	public function edit(Empleado $empleado) {
		$this->reset();
		$this->formType = 1;
		$this->loadData($empleado);
		$this->dispatchBrowserEvent('openEditForm');
	}

	public function closeEdit() {
		$this->dispatchBrowserEvent('closeEditForm');
		$this->reset();
	}

	public function loadData(Empleado $empleado){

		$this->empleado = $empleado;
		$this->ci = $empleado->ci;
		$this->farmacia = $empleado->farmacia->id;
		$this->nombre = $empleado->nombre;
		$this->apellido = $empleado->apellido;
		$this->edad = $empleado->edad;
		$this->cargo = $empleado->cargo;
		$this->telefono = $empleado->telefono;

		if($empleado->cargo == 'pasante') {
			if(isset($empleado->pasantia)) {
				$this->institucion = $empleado->pasantia->institucion;
				$this->especialidad = $empleado->pasantia->especialidad;
				$this->f_inicio = $empleado->pasantia->f_inicio;
				$this->f_final = $empleado->pasantia->f_final;
				$this->n_permiso = $empleado->pasantia->n_permiso;
				$this->activo = $empleado->pasantia->activo;
			}

			if(isset($empleado->responsable)) {
				if($empleado->edad < 18) {
					$this->ci_r = $empleado->responsable->ci_representante;
					$this->nombre_r = $empleado->responsable->nombre;
					$this->apellido_r = $empleado->responsable->apellido;
					$this->telefono_r = $empleado->responsable->telefono;
				}
			}

		} else if($empleado->cargo == 'farmaceutico') {
			if(isset($empleado->titulo)) {
				$this->universidad = $empleado->titulo->universidad;
				$this->fecha = $empleado->titulo->fecha;
				$this->n_registro = $empleado->titulo->n_registro;
				$this->p_sanitario = $empleado->titulo->p_sanitario;
				$this->n_colegiatura = $empleado->titulo->n_colegiatura;
			}
		}

		$this->cargos = ["pasante", "administrativo", "farmaceutico", "vigilante", "analista"];
	}

	public function store() {

		if($this->formType == 0) {
			$empleado = Empleado::create([
				'ci' => $this->ci,
				'id_farmacia' => $this->farmacia,
				'nombre' => $this->nombre,
				'apellido' => $this->apellido,
				'edad' => $this->edad,
				'cargo' => $this->cargo,
				'telefono' => $this->telefono,
			]);

			if($this->cargo == "farmaceutico") {
				Empleado::find($this->ci)->titulo()->create([
					'ci' => $this->ci, 
					'universidad' => $this->universidad,
					'fecha' => $this->fecha,
					'n_registro' => $this->n_registro,
					'p_sanitario' => $this->p_sanitario,
					'n_colegiatura' => $this->n_colegiatura,
				]);
			}

			if($this->cargo == "pasante") {
				$this->edad < 18 ? $minoria = true : $minoria = false;
				$this->activo == "1" ? $activo = true : $activo = false;
				Empleado::find($this->ci)->pasantia()->create([
					'ci' => $this->ci,
					'institucion' => $this->institucion,
					'especialidad' => $this->especialidad,
					'f_inicio' => $this->f_inicio,
					'n_permiso' => $this->n_permiso, 
					'minoria_edad' => $minoria,
					'activo' => $activo
				]);

				if($this->edad < 18) {
					Empleado::find($this->ci)->responsable()->create([
						'ci' => $this->ci,
						'ci_representante' => $this->ci_r,
						'nombre' => $this->nombre_r,
						'apellido' => $this->apellido_r,
						'telefono' => $this->telefono_r
					]);
				}
			}

			for($i=1;; $i++) { 
				$username = strtolower(substr($this->nombre, 0, $i).$this->apellido);
				if (!(User::where('username',$username)->first())) {
					break;
				}
			}

			$user = Empleado::find($this->ci)->user()->create([
				'ci' => $this->ci,
				'username' => $username,
				'password' => Hash::make($username),
			]);

			$user->assignRole($empleado->cargo);
			$user->givePermissionTo(Role::findByName($empleado->cargo)->permissions()->pluck('name'));

			$this->closeCreate();

		} else if($this->formType == 1) {

			$this->empleado->update([
				'id_farmacia' => $this->farmacia,
				'nombre' => $this->nombre,
				'apellido' => $this->apellido,
				'edad' => $this->edad,
				'cargo' => $this->cargo,
				'telefono' => $this->telefono,
			]);

			if($this->cargo == "farmaceutico") {
				$this->empleado->titulo()->updateOrCreate([
					'ci' => $this->empleado->ci,
					'universidad' => $this->universidad,
					'fecha' => $this->fecha,
					'n_registro' => $this->n_registro,
					'p_sanitario' => $this->p_sanitario,
					'n_colegiatura' => $this->n_colegiatura,
				]);
			}

			if($this->cargo == "pasante") {
				$this->edad < 18 ? $minoria = 1 : $minoria = 0;
				$this->activo == "1" ? $activo = 1 : $activo = 0;
				$this->empleado->pasantia()->updateOrCreate([
					'ci' => $this->empleado->ci,
					'institucion' => $this->institucion,
					'especialidad' => $this->especialidad,
					'f_inicio' => $this->f_inicio,
					'f_final' => $this->f_final,
					'n_permiso' => $this->n_permiso, 
					'minoria_edad' => $minoria,
					'activo' => $activo
				]);

				if ($this->edad < 18) {
					$this->empleado->responsable()->updateOrCreate([
						'ci' => $this->empleado->ci,
						'ci_representante' => $this->ci_r,
						'nombre' => $this->nombre_r,
						'apellido' => $this->apellido_r,
						'telefono' => $this->telefono_r
					]);
				}
			}

			$this->closeEdit();
		}
	}

	public function delete($id) {
		Empleado::destroy($id);		
	}

	public function show(Empleado $empleado) {
		if($empleado != null) $this->empleado = $empleado;
		$this->dispatchBrowserEvent('openShow');
	}

	public function closeShow() {
		$this->dispatchBrowserEvent('closeShow');
		$this->reset();
	}
}
