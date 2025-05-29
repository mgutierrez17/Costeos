<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class Usuarios extends Component
{
    public $usuarios, $mostrarFormulario = false;
    public $name, $email, $password;

    public function render()
    {
        return view('livewire.admin.usuarios')
            ->layout('layouts.app'); // Asegúrate de que esto coincida con tu layout real
    }

    public function crearUsuario()
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'is_active' => true, // asegúrate de tener este campo en tu tabla users
        ]);

        // Aquí puedes asignar roles si deseas
        // $user->assignRole('Usuario');

        $this->reset(['name', 'email', 'password']);
        $this->cargarUsuarios();
        $this->mostrarFormulario = false;
    }

    public function verUsuario($id)
    {
        // Abre modal o muestra datos (pendiente implementación según diseño)
    }

    public function editarUsuario($id)
    {
        // Carga datos del usuario y permite edición (puedes usar un modal)
    }

    public function eliminarUsuario($id)
    {
        User::find($id)?->delete();
        $this->cargarUsuarios();
    }

    public function toggleEstado($id)
    {
        $usuario = User::find($id);
        $usuario->is_active = !$usuario->is_active;
        $usuario->save();
        $this->cargarUsuarios();
    }

    public function mount()
    {
        $this->cargarUsuarios();
    }

    public function cargarUsuarios()
    {
        $this->usuarios = User::with('roles')->get();
    }
}
