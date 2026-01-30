<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkPenyesuaian extends Model
{
    protected $table = 'ak_penyesuaian';

    protected $fillable = [
        'golongan',
        'pendidikan',
        'kurang_1_tahun',
        'tahun_1',
        'tahun_2',
        'tahun_3',
        'tahun_4_lebih'
    ];
}

}
