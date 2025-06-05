<div>
    @php
    $usuario = Auth::user();
    $rol = $usuario->roles->first();
    $paginas = $rol?->paginas ?? collect();
    @endphp

    <nav class="space-y-2">
        @foreach ($paginas as $pagina)
        @if ($pagina->activo)
        <a href="{{ url($pagina->ruta) }}" class="block px-4 py-2 rounded hover:bg-blue-100">
            {{ ucfirst($pagina->nombre) }}
        </a>
        @endif
        @endforeach
    </nav>

</div>