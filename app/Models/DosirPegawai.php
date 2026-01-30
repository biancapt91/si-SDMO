<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosirPegawai extends Model
{
    protected $table = 'dosir_pegawai'; // Nama tabel di database

    protected $fillable = [
        'pegawai_id',
        'nama_dokumen',
        'file_path',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
