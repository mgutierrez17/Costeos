<div class="container p-4">

    <!-- Botón para mostrar/ocultar el formulario -->
    <button wire:click="$toggle('mostrarFormulario')" class="btn btn-primary mb-3">
        {{ $mostrarFormulario ? 'Ocultar' : 'Crear Usuario' }}
    </button>

    <!-- Formulario de creación -->
    @if($mostrarFormulario)
    <form wire:submit.prevent="crearUsuario" class="mb-4">
        <div class="form-group">
            <input type="text" wire:model="name" class="form-control" placeholder="Nombre">
        </div>
        <div class="form-group">
            <input type="email" wire:model="email" class="form-control" placeholder="Correo">
        </div>
        <div class="form-group">
            <input type="password" wire:model="password" class="form-control" placeholder="Contraseña">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
    @endif

    <!-- Tabla de usuarios -->
    <table id="usuariosTable" class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol(es)</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ implode(', ', $usuario->getRoleNames()->toArray()) }}</td>
                <td>
                    @if ($usuario->is_active)
                    <span class="badge bg-success">Activo</span>
                    @else
                    <span class="badge bg-danger">Bloqueado</span>
                    @endif
                </td>
                <td>
                    <button wire:click="verUsuario({{ $usuario->id }})" class="btn btn-info btn-sm">Ver</button>
                    <button wire:click="editarUsuario({{ $usuario->id }})" class="btn btn-warning btn-sm">Editar</button>
                    <button wire:click="eliminarUsuario({{ $usuario->id }})" class="btn btn-danger btn-sm">Eliminar</button>
                    <button wire:click="toggleEstado({{ $usuario->id }})" class="btn btn-secondary btn-sm">
                        {{ $usuario->is_active ? 'Bloquear' : 'Activar' }}
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        initDataTable();

        Livewire.hook('message.processed', (message, component) => {
            // Reinicia DataTable después de actualizar el DOM
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