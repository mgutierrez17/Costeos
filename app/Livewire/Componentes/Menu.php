<?php

namespace App\Livewire\Componentes;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Pagina;

class Menu extends Component
{
    public $paginas = [];
 
    public function mount()
    {
        $usuario = Auth::user();

        if ($usuario && $usuario->roles->isNotEmpty()) {
            $rol = $usuario->roles->first();

            $this->paginas = $rol->paginas()->where('activo', true)->get();
        }
    }

    public function render()
    {
        return view('livewire.componentes.menu');
    }
}
