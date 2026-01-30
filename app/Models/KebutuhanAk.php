<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanAk extends Model
{
    use HasFactory;

    protected $table = 'kebutuhan_ak';  // ← WAJIB TAMBAHKAN INI!!

    protected $fillable = ['kategori', 'dari', 'ke', 'ak'];
}
