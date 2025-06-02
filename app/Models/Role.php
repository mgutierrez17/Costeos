<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends SpatieRole
{
    public function paginas()
    {
        return $this->belongsToMany(\App\Models\Pagina::class, 'pagina_role');
    }
}
