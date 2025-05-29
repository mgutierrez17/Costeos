<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\User;
use Livewire\WithFileUploads;

class Empleados extends Component
{
    public $user_id, $nombre, $apellido, $cargo, $area, $fecha_ingreso, $doc_identidad, $fecha_nacimiento, $foto;
    public $usuarios;
    use WithFileUploads;

    public function render()
    {
        return view('livewire.admin.empleados');
    }

    public function mount()
    {
        $this->usuarios = User::doesntHave('empleado')->get();
    }

    public function crearEmpleado()
    {
        $fotoPath = $this->foto ? $this->foto->store('empleados', 'public') : null;

        Empleado::create([
            'user_id' => $this->user_id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'cargo' => $this->cargo,
            'area' => $this->area,
            'fecha_ingreso' => $this->fecha_ingreso,
            'doc_identidad' => $this->doc_identidad,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'foto' => $fotoPath,
        ]);

        $this->reset();
    }
}
