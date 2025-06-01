<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios;
use App\Livewire\Admin\Empleados;


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
