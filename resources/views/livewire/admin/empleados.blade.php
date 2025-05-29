<div>
    <form wire:submit.prevent="crearEmpleado" enctype="multipart/form-data">
        <select wire:model="user_id">
            <option value="">Seleccione un usuario</option>
            @foreach($usuarios as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <input type="text" wire:model="nombre" placeholder="Nombre">
        <input type="text" wire:model="apellido" placeholder="Apellido">
        <input type="text" wire:model="cargo" placeholder="Cargo">
        <input type="text" wire:model="area" placeholder="Área">
        <input type="date" wire:model="fecha_ingreso">
        <input type="text" wire:model="doc_identidad" placeholder="Doc. Identidad">
        <input type="date" wire:model="fecha_nacimiento">
        <input type="file" wire:model="foto">
        <button type="submit">Guardar</button>
    </form>
</div>