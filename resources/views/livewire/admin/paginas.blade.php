<div class="container mx-auto px-4 py-6">
    <!-- Botón para abrir modal crear pagina-->
    <button wire:click="crearPagina" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">
        Crear Página
    </button>

    <!-- Modal -->
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg">
                <h2 class="text-lg font-semibold mb-4">{{ $modo === 'editar' ? 'Editar Página' : 'Registrar Página' }}
                </h2>

                <form wire:submit.prevent="guardar">
                    <div class="mb-6">
                        <label class="block text-sm font-medium">Nombre</label>
                        <input type="text" wire:model="nombre" class="w-full border rounded p-2"
                            @if ($modo === 'ver') disabled @endif>
                        @error('nombre')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium">Ruta</label>
                        <input type="text" wire:model="ruta" class="w-full border rounded p-2"
                            @if ($modo === 'ver') disabled @endif>
                        @error('ruta')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-6">

                        <div class="flex items-center gap-2">
                            <label class="block text-sm font-medium">Roles asignados </label>
                            @if ($modo !== 'ver')
                                <button wire:click="$set('mostrarCortinaRol', true)"
                                    class="text-sm text-blue-600 hover:underline">
                                    Crear rol
                                </button>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <select wire:model="rolesSeleccionados" multiple class="w-full border rounded p-2"
                                @if ($modo === 'ver') disabled @endif>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" wire:click="resetFormulario"
                            class="px-4 py-2 bg-gray-400 text-white rounded">Cancelar</button>
                        @if ($modo !== 'ver')
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Tabla de páginas -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 text-sm uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Ruta</th>
                    <th class="px-6 py-3 text-left">Roles</th>
                    <th class="px-6 py-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @foreach ($paginas as $pagina)
                    <tr>
                        <td class="px-6 py-3">{{ $pagina->nombre }}</td>
                        <td class="px-6 py-3">{{ $pagina->ruta }}</td>
                        <td class="px-6 py-3">
                            {{ implode(', ', $pagina->roles->pluck('name')->toArray()) }}
                        </td>
                        <td class="px-6 py-3 space-x-1">
                            <button wire:click="editar({{ $pagina->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">Editar</button>
                            <button wire:click="ver({{ $pagina->id }})"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">Ver</button>
                            <button wire:click="eliminar({{ $pagina->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $paginas->links() }}
    </div>

    @if ($mostrarCortinaRol)
        <div class="fixed inset-0 z-50 flex justify-end bg-black bg-opacity-50">
            <div class="w-1/3 h-full bg-white p-6 shadow-lg relative">
                <button wire:click="$set('mostrarCortinaRol', false)"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black">✕</button>

                <h2 class="text-xl font-semibold mb-4">Crear nuevo rol</h2>

                <form wire:submit.prevent="guardarRol" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Nombre del rol</label>
                        <input type="text" wire:model.defer="nuevoNombreRol"
                            class="w-full border-gray-300 rounded shadow-sm" placeholder="Ej: Administrador">
                        @error('nuevoNombreRol')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('mostrarCortinaRol', false)"
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
