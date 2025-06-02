<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios;
use App\Livewire\Admin\Empleados;
use App\Livewire\Admin\Paginas;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/usuarios', \App\Livewire\Usuarios::class)->name('usuarios');
    //    Route::get('/usuarios', Usuarios::class)->name('usuarios');
    Route::get('/empleados', \App\Livewire\Empleados::class)->name('empleados');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/paginas', Paginas::class)->name('admin.paginas');
});

