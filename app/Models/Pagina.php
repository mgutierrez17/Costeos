<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pagina extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'ruta', 'icono', 'visible'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(\Spatie\Permission\Models\Role::class);
    }

    public function paginas()
    {
        return $this->belongsToMany(\App\Models\Pagina::class, 'pagina_role');
    }
}
