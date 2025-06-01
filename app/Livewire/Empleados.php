<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Empleado;
use App\Models\User;

class Empleados extends Component
{
    use WithPagination, WithFileUploads;

    public $fotoFile;
    public $empleadoId = null;

    public $user_id, $nombre, $apellido, $cargo, $area, $fecha_ingreso, $doc_identidad, $fecha_nacimiento, $foto;

    public $empleadoSeleccionado, $mostrarFormulario = false, $modo = 'crear', $search = '';
    protected $paginationTheme = 'tailwind';

    protected $messages = [
        'user_id.required' => 'Debe seleccionar un usuario.',
        'nombre.required' => 'El nombre es obligatorio.',
        'apellido.required' => 'El apellido es obligatorio.',
        'cargo.required' => 'El cargo es obligatorio.',
        'area.required' => 'El área es obligatoria.',
        'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
        'fecha_ingreso.date' => 'Formato de fecha inválido.',
        'doc_identidad.required' => 'Debe ingresar el documento de identidad.',
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'fotoFile.image' => 'La foto debe ser una imagen.',
        'fotoFile.mimes' => 'La foto debe ser JPG o PNG.',
        'fotoFile.max' => 'La imagen no debe pesar más de 2MB.',
    ];


    public function render()
    {
        $empleados = Empleado::with('user')
            ->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nombre')->paginate(5);

        // Incluir también el usuario actual si se está editando/viendo
        $usuarios = User::when($this->user_id, function ($query) {
            $query->orWhere('id', $this->user_id);
        })
            ->doesntHave('empleado')
            ->orWhereHas('empleado', function ($q) {
                $q->where('id', $this->empleadoId); // incluir si es el asignado
            })
            ->get();

        return view('livewire.empleados', compact('empleados', 'usuarios'))->layout('layouts.app');
    }

    public function guardarEmpleado()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required',
            'apellido' => 'required',
            'cargo' => 'required',
            'area' => 'required',
            'fecha_ingreso' => 'required|date',
            'doc_identidad' => 'required',
            'fecha_nacimiento' => 'required|date',
            'fotoFile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'user_id.required' => 'Debe seleccionar un usuario.',
            'user_id.exists' => 'El usuario seleccionado no es válido.',

            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'cargo.required' => 'El cargo es obligatorio.',
            'area.required' => 'El área es obligatoria.',

            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser válida.',

            'doc_identidad.required' => 'El documento de identidad es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser válida.',

            'fotoFile.image' => 'El archivo debe ser una imagen.',
            'fotoFile.mimes' => 'Solo se permiten imágenes JPG o PNG.',
            'fotoFile.max' => 'La imagen no debe superar los 2MB.',
        ]);

        $data = [
            'user_id' => $this->user_id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'cargo' => $this->cargo,
            'area' => $this->area,
            'fecha_ingreso' => $this->fecha_ingreso,
            'doc_identidad' => $this->doc_identidad,
            'fecha_nacimiento' => $this->fecha_nacimiento,
        ];

        if ($this->fotoFile) {
            $path = $this->fotoFile->store('img/empleados', 'public');
            $data['foto'] = 'storage/' . $path;
        }

        if ($this->modo === 'editar' && $this->empleadoId) {
            Empleado::findOrFail($this->empleadoId)->update($data);
        } else {
            Empleado::create($data);
        }

        $this->resetFormulario();
    }

    public function editarEmpleado($id)
    {
        $empleado = Empleado::findOrFail($id);
        $this->empleadoId = $empleado->id;

        $this->fill($empleado->only([
            'user_id',
            'nombre',
            'apellido',
            'cargo',
            'area',
            'fecha_ingreso',
            'doc_identidad',
            'fecha_nacimiento',
            'foto',
        ]));

        $this->modo = 'editar';
        $this->mostrarFormulario = true;
        $this->resetErrorBag(); // limpia errores también aquí
    }

    public function verEmpleado($id)
    {
        $empleado = Empleado::findOrFail($id);
        $this->empleadoId = $empleado->id;

        $this->fill($empleado->only([
            'user_id',
            'nombre',
            'apellido',
            'cargo',
            'area',
            'fecha_ingreso',
            'doc_identidad',
            'fecha_nacimiento',
            'foto',
        ]));

        $this->modo = 'ver';
        $this->mostrarFormulario = true;
        $this->resetErrorBag(); // limpia errores
    }

    public function eliminarEmpleado($id)
    {
        Empleado::find($id)?->delete();
        $this->resetPage();
    }

    public function resetFormulario()
    {
        $this->reset([
            'empleadoId',
            'user_id',
            'nombre',
            'apellido',
            'cargo',
            'area',
            'fecha_ingreso',
            'doc_identidad',
            'fecha_nacimiento',
            'foto',
            'fotoFile',
            'empleadoSeleccionado',
            'modo',
            'mostrarFormulario'
        ]);
    }

    public function limpiarBusqueda()
    {
        $this->reset('search');
    }
}
