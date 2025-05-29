<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('/admin/usuarios', \App\Livewire\Admin\Usuarios::class)->name('admin.usuarios');
    Route::get('/admin/empleados', \App\Livewire\Admin\Empleados::class)->name('admin.empleados');
});

