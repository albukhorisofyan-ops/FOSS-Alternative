<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('proprietary_software')]
#[Fillable(['category_id', 'name', 'vendor', 'price_per_year', 'description'])]
class ProprietarySoftware extends Model
{
    /**
     * Kategori dari perangkat lunak berbayar ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Alternatif open source untuk perangkat lunak berbayar ini.
     */
    public function fossAlternatives(): HasMany
    {
        return $this->hasMany(FossAlternative::class);
    }
}
