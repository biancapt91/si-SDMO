<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjiKompetensi extends Model
{
    protected $table = 'uji_kompetensi';  // ← WAJIB

    protected $fillable = [
        'pegawai_id',
        'hasil',          // Lulus / Tidak Lulus / Belum
        'tanggal_uji',
        'keterangan',
    ];
}
