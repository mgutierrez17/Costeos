<?php

// app/Livewire/Admin/Paginas.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pagina;
use Spatie\Permission\Models\Role;

class Paginas extends Component
{
    use WithPagination;

    public $nombre, $ruta, $activo = true, $paginaId;
    public $rolesSeleccionados = [];
    public $modo = 'crear';
    public $mostrarModal = false; // Agregado para controlar la visibilidad del modal
    public $mostrarCrearRol = false;
    public $mostrarCortinaRol = false;
    public $nuevoNombreRol = '';


    protected $rules = [
        'nombre' => 'required|string|max:255',
        'ruta' => 'required|string|max:255',
        'rolesSeleccionados' => 'array'
    ];

    public function render()
    {
        return view('livewire.admin.paginas', [
            'paginas' => Pagina::with('roles')->paginate(10),
            'roles' => Role::all()
        ])->layout('layouts.app');
    }

    public function crearPagina()
    {
        $this->resetFormulario();
        $this->modo = 'crear';
        $this->mostrarModal = true;
    }


    public function guardar()
    {
        $this->validate();

        $pagina = $this->modo === 'editar' ? Pagina::find($this->paginaId) : new Pagina();
        $pagina->fill([
            'nombre' => $this->nombre,
            'ruta' => $this->ruta,
            'activo' => $this->activo
        ])->save();

        $pagina->roles()->sync($this->rolesSeleccionados);

        $this->resetFormulario();
    }

    public function editar($id)
    {
        $pagina = Pagina::find($id);

        $this->paginaId = $pagina->id;
        $this->nombre = $pagina->nombre;
        $this->ruta = $pagina->ruta;
        $this->activo = $pagina->activo;
        $this->rolesSeleccionados = $pagina->roles()->pluck('roles.id')->toArray();
        $this->modo = 'editar';
        $this->mostrarModal = true;
    }

    public function ver($id)
    {
        $pagina = Pagina::find($id);
        $this->paginaId = $pagina->id;
        $this->nombre = $pagina->nombre;
        $this->ruta = $pagina->ruta;
        $this->activo = $pagina->activo;
        $this->rolesSeleccionados = $pagina->roles()->pluck('roles.id')->toArray();
        $this->modo = 'ver';
        $this->mostrarModal = true;
    }

    public function eliminar($id)
    {
        Pagina::find($id)?->delete();
        $this->resetFormulario();
    }

    public function resetFormulario()
    {
        $this->reset(['nombre', 'ruta', 'activo', 'rolesSeleccionados', 'paginaId', 'modo', 'mostrarModal']);
    }

    public function crearRol()
    {
        $this->mostrarCrearRol = true;
    }

    public function guardarRol()
    {
        $this->validate([
            'nuevoNombreRol' => 'required|unique:roles,name'
        ]);

        Role::create(['name' => $this->nuevoNombreRol]);

        $this->nuevoNombreRol = '';
        $this->mostrarCortinaRol = false;
    }
}
