<div class="container mx-auto px-4 py-6">
    <!-- Botón para mostrar/ocultar el formulario -->
    <button wire:click="$toggle('mostrarFormulario')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">
        {{ $mostrarFormulario ? 'Ocultar' : 'Crear Usuario' }}
    </button>

    <!-- MODAL -->
    @if ($mostrarFormulario)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-auto">
                <h2 class="text-xl font-bold mb-4">
                    @if ($modo === 'crear')
                        Crear Usuario
                    @elseif ($modo === 'editar')
                        Editar Usuario
                    @else
                        Ver Usuario
                    @endif
                </h2>

                <div class="space-y-4">
                    <!-- Nombre -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre</label>
                        <input type="text" wire:model="name"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300"
                            @if ($modo === 'ver') disabled @endif>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Correo</label>
                        <input type="email" wire:model="email"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300"
                            @if ($modo === 'ver') disabled @endif>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    @if ($modo !== 'ver')
                        <div>
                            <label class="block text-sm font-medium mb-1">Contraseña</label>
                            <input type="password" wire:model="password"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300">
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Botones -->
                <div class="mt-6 flex justify-end space-x-2">
                    <button wire:click="resetFormulario"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                        Cerrar
                    </button>

                    @if ($modo !== 'ver')
                        <button wire:click="guardarUsuario"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Guardar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Campo de busqueda -->
    <div class="flex space-x-2 mb-4">
        <input type="text"  id="input-busqueda" wire:model="search" placeholder="Buscar por nombre o correo"
            class="w-full max-w-md border-gray-300 rounded px-4 py-2 shadow-sm focus:ring focus:ring-blue-300">
        <button wire:click="aplicarFiltro" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Buscar
        </button>
        <button wire:click="limpiarFiltro" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">
            Limpiar
        </button>
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
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td class="px-6 py-3">{{ $usuario->name }}</td>
                        <td class="px-6 py-3">{{ $usuario->email }}</td>
                        <td class="px-6 py-3">{{ implode(', ', $usuario->getRoleNames()->toArray()) }}</td>
                        <td class="px-6 py-3">
                            @if ($usuario->is_active)
                                <span
                                    class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Activo</span>
                            @else
                                <span
                                    class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Bloqueado</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 space-x-1">
                            <button wire:click="verUsuario({{ $usuario->id }})"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">Ver</button>
                            <button wire:click="editarUsuario({{ $usuario->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">Editar</button>
                            <button wire:click="eliminarUsuario({{ $usuario->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Eliminar</button>
                            <button wire:click="toggleEstado({{ $usuario->id }})"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
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
        window.addEventListener('limpiar-input-busqueda', () => {
            alert("aaaaaaaa");
            const input = document.getElementById('input-busqueda');
            if (input) {
                input.value = 'qwe';
            }
        });

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
