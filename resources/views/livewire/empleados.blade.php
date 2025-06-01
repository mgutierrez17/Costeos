<div class="container mx-auto px-4 py-6">
    <!-- Botón mostrar/ocultar formulario -->
    <button wire:click="$toggle('mostrarFormulario')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">
        {{ $mostrarFormulario ? 'Ocultar' : 'Crear Empleado' }}
    </button>

    <!-- Formulario en Modal -->
    @if ($mostrarFormulario)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-4xl">
                <form wire:submit.prevent="guardarEmpleado" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Usuario</label>
                            <select wire:model="user_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                                <option value="">-- Seleccionar usuario --</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Nombre</label>
                            <input type="text" wire:model="nombre"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('nombre')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Apellido</label>
                            <input type="text" wire:model="apellido"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('apellido')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Cargo</label>
                            <input type="text" wire:model="cargo"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('cargo')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror

                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Área</label>
                            <input type="text" wire:model="area" class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('area')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Fecha Ingreso</label>
                            <input type="date" wire:model="fecha_ingreso"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('fecha_ingreso')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">CI / Documento</label>
                            <input type="text" wire:model="doc_identidad"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('doc_identidad')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Fecha Nacimiento</label>
                            <input type="date" wire:model="fecha_nacimiento"
                                class="w-full border-gray-300 rounded-lg shadow-sm">
                            @error('fecha_nacimiento')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Foto (opcional)</label>
                        <input type="file" wire:model="fotoFile" accept="image/jpeg,image/png"
                            class="w-full border-gray-300 rounded-lg shadow-sm">

                        @if ($fotoFile)
                            <div class="mt-2">
                                <img src="{{ $fotoFile->temporaryUrl() }}" class="h-24 rounded shadow">
                            </div>
                        @elseif($foto)
                            <div class="mt-2">
                                <img src="{{ asset($foto) }}" class="h-24 rounded shadow">
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" wire:click="resetFormulario"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancelar</button>
                        @if ($modo !== 'ver')
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Guardar</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Tabla de empleados -->
    <div class="mb-4 flex items-center space-x-2">
        <input type="text" wire:model="search" placeholder="Buscar por nombre o apellido..."
            class="w-1/3 border rounded p-2 shadow-sm focus:ring focus:ring-blue-300">
        <button wire:click="$refresh" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Buscar</button>
        <button wire:click="limpiarBusqueda"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Limpiar</button>
    </div>


    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full table-auto divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nombre Completo</th>
                    <th class="px-6 py-3 text-left">Cargo</th>
                    <th class="px-6 py-3 text-left">Área</th>
                    <th class="px-6 py-3 text-left">Ingreso</th>
                    <th class="px-6 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse($empleados as $empleado)
                    <tr>
                        <td class="px-6 py-3">{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                        <td class="px-6 py-3">{{ $empleado->cargo }}</td>
                        <td class="px-6 py-3">{{ $empleado->area }}</td>
                        <td class="px-6 py-3">{{ $empleado->fecha_ingreso }}</td>
                        <td class="px-6 py-3 space-x-1">
                            <button wire:click="verEmpleado({{ $empleado->id }})"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">Ver</button>
                            <button wire:click="editarEmpleado({{ $empleado->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">Editar</button>
                            <button wire:click="eliminarEmpleado({{ $empleado->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-3 text-center text-gray-500">No se encontraron empleados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $empleados->links() }}
    </div>
</div>
