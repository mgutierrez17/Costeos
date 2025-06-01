<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

use Livewire\WithPagination;

class Usuarios extends Component
{
    public $mostrarFormulario = false;
    public $name, $email, $password;

    public $modo = 'crear'; // 'crear', 'ver', 'editar'
    public $usuarioSeleccionado;

    use WithPagination;
    public $search = '';
    protected $paginationTheme = 'tailwind'; // Para estilos de Tailwind

    public function render()
    {
        $usuarios = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->with('roles')
            ->orderBy('name')
            ->paginate(5); // puedes ajustar el número

        return view('livewire.usuarios', compact('usuarios'))
            ->layout('layouts.app');
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
        $usuario = User::findOrFail($id);
        $this->usuarioSeleccionado = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->password = '';
        $this->modo = 'ver';
        $this->mostrarFormulario = true;
    }

    public function editarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $this->usuarioSeleccionado = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->password = ''; // no se muestra por seguridad
        $this->modo = 'editar';
        $this->mostrarFormulario = true;
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
        //$this->usuarios = User::with('roles')->get();
        $this->resetPage(); // reinicia paginación cuando cambia filtro
    }

    public function guardarUsuario()
    {
        if ($this->modo === 'editar' && $this->usuarioSeleccionado) {
            $usuario = User::findOrFail($this->usuarioSeleccionado);
            $usuario->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            if ($this->password) {
                $usuario->update([
                    'password' => Hash::make($this->password),
                ]);
            }
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_active' => true,
            ]);
        }

        $this->resetFormulario();
        $this->cargarUsuarios();
    }

    public function resetFormulario()
    {
        $this->reset(['name', 'email', 'password', 'mostrarFormulario', 'modo', 'usuarioSeleccionado']);
    }
}
