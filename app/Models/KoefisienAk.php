<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoefisienAk extends Model
{
    use HasFactory;

    protected $table = 'koefisien_ak';   // ← TAMBAHKAN INI!

    protected $fillable = ['jenjang', 'koef_per_tahun'];
}
