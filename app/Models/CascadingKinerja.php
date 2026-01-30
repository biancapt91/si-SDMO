<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CascadingKinerja extends Model
{
    protected $table = 'cascading_kinerjas';

    protected $fillable = ['key', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
