<div class="container mx-auto px-4 py-6">
    <!-- Botón para mostrar/ocultar el formulario -->
    <button wire:click="$toggle('mostrarFormulario')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">
        {{ $mostrarFormulario ? 'Ocultar' : 'Crear Usuario' }}
    </button>

    <!-- Formulario de creación -->
    @if($mostrarFormulario)
    <form wire:submit.prevent="guardarUsuario" class="bg-white p-6 rounded shadow mb-6 space-y-4">
        <h2 class="text-xl font-semibold mb-2">
            {{ $modo === 'ver' ? 'Ver Usuario' : ($modo === 'editar' ? 'Editar Usuario' : 'Crear Usuario') }}
        </h2>

        <div>
            <label class="block text-sm font-medium mb-1">Nombre</label>
            <input type="text" wire:model="name"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300"
                @if($modo==='ver' ) readonly @endif>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Correo</label>
            <input type="email" wire:model="email"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300"
                @if($modo==='ver' ) readonly @endif>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        @if ($modo !== 'ver')
        <div>
            <label class="block text-sm font-medium mb-1">Contraseña</label>
            <input type="password" wire:model="password"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @endif

        <div class="mt-4 flex justify-end gap-2">
            <button type="button" wire:click="resetFormulario"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancelar</button>

            @if ($modo !== 'ver')
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                {{ $modo === 'editar' ? 'Actualizar' : 'Guardar' }}
            </button>
            @endif
        </div>
    </form>
    @endif


    <div class="mb-4">
        <input type="text" wire:model.debounce.300ms="search"
            placeholder="Buscar por nombre o correo..."
            class="w-1/3 border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-300">
    </div>

    <!-- Tabla de usuarios -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table id="usuariosTable" class="min-w-full table-auto divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Rol(es)</th>
                    <th class="px-6 py-3 text-left">Estado</th>
                    <th class="px-6 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @foreach($usuarios as $usuario)
                <tr>
                    <td class="px-6 py-3">{{ $usuario->name }}</td>
                    <td class="px-6 py-3">{{ $usuario->email }}</td>
                    <td class="px-6 py-3">{{ implode(', ', $usuario->getRoleNames()->toArray()) }}</td>
                    <td class="px-6 py-3">
                        @if ($usuario->is_active)
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Activo</span>
                        @else
                        <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Bloqueado</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 space-x-1">
                        <button wire:click="verUsuario({{ $usuario->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">Ver</button>
                        <button wire:click="editarUsuario({{ $usuario->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">Editar</button>
                        <button wire:click="eliminarUsuario({{ $usuario->id }})" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Eliminar</button>
                        <button wire:click="toggleEstado({{ $usuario->id }})" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                            {{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $usuarios->links() }}
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        initDataTable();

        Livewire.hook('message.processed', (message, component) => {
            $('#usuariosTable').DataTable().destroy();
            initDataTable();
        });
    });

    function initDataTable() {
        $('#usuariosTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    }
</script>
@endpush