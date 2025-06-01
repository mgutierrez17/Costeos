<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'cargo',
        'area',
        'fecha_ingreso',
        'doc_identidad',
        'fecha_nacimiento',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
