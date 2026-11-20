<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /**
     * Perangkat lunak berbayar yang termasuk dalam kategori ini.
     */
    public function proprietarySoftware(): HasMany
    {
        return $this->hasMany(ProprietarySoftware::class);
    }
}
