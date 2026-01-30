<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AngkaKredit;

class PakDocument extends Model
{
    protected $table = 'pak_documents';

    // ✅ INI YANG KURANG
    protected $fillable = [
        'angka_kredit_id',
        'nomor_pak',
        'tanggal_penetapan',
        'file_pdf',
        'status',
        'ttd_sdmo_at',
        'ttd_ppk_at'
    ];

    public function angkaKredit()
    {
        return $this->belongsTo(AngkaKredit::class, 'angka_kredit_id');
    }
}
