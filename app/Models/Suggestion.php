<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'proprietary_name', 'website', 'note', 'status'])]
class Suggestion extends Model {}
