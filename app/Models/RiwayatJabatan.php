<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatJabatan extends Model
{
    protected $table = 'riwayat_jabatan';

    protected $fillable = [
        'pegawai_id',
        'jabatan',
        'jenis_jabatan',
        'unit_kerja',
        'tmt_mulai',
        'tmt_selesai',
    ];
}
