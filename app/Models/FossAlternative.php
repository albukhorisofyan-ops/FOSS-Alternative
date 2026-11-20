<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['proprietary_software_id', 'name', 'license', 'website', 'description', 'migration_guide'])]
class FossAlternative extends Model
{
    /**
     * Perangkat lunak berbayar yang digantikan oleh alternatif ini.
     */
    public function proprietarySoftware(): BelongsTo
    {
        return $this->belongsTo(ProprietarySoftware::class);
    }
}
