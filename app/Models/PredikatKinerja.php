<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PredikatKinerja extends Model
{
    protected $table = 'predikat_kinerja'; // ← WAJIB ditambahkan

    protected $fillable = [
        'pegawai_id',
        'predikat',
        'periode_awal',
        'periode_akhir',
    ];
}
