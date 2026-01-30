<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkuSdmoTable extends Model
{
protected $fillable = ['nama','struktur'];
protected $casts = [
    'struktur' => 'array'
];

}
