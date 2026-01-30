<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngkaKredit extends Model
{
    protected $table = 'angka_kredit'; // ✅ FIX

    protected $fillable = [
        'pegawai_id',

        // LOGIKA PAK
        'jenis_pak_awal',
        'periode',
        'jenis_penilaian',
	'predikat_kinerja',
        'bulan_penilaian',
        'bulan_awal',
        'bulan_akhir',
 	'ak_dasar',
        'ak_total',
        'status',
        'periode',
	'jenjang',
	'jabatan',
	'ak_penyesuaian',

        // NILAI
        'koefisien',
        'ak_hasil',
        'ak_tambahan',   // ← AK pendidikan (25)
        'ak_total',
        'ak_dasar',
        'ak_jf_lama',

        // PAK AWAL
        'golongan',
        'masa_kerja_kepangkatan',

        // KINERJA & STATUS
        'predikat_kinerja',
        'status',
        'catatan_sdmo',
        'catatan_ppk',
    ];

    /* ================= RELASI ================= */

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pakDocument()
    {
        return $this->hasOne(PakDocument::class, 'angka_kredit_id');
    }

    /* ================= ACCESSOR ================= */

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'DRAFT'              => 'Draft',
            'MENUNGGU_SDMO'      => 'Menunggu Verifikasi SDMO',
            'DIVERIFIKASI_SDMO'  => 'Diverifikasi SDMO',
            'MENUNGGU_PPK'       => 'Menunggu Pengesahan PPK',
            'DISAHKAN_PPK'       => 'Disahkan Pejabat Penilai Kinerja',
            'DITOLAK_SDMO'       => 'Ditolak SDMO',
            'DITOLAK_PPK'        => 'Ditolak PPK',
            default              => $this->status,
        };
    }

public function getJenisPenilaianLabelAttribute()
{
    return match ($this->jenis_penilaian) {
        'PAK_AWAL_PENYESUAIAN' => 'PAK Awal: Penyesuaian/Penyetaraan',
        'PAK_AWAL_PERPINDAHAN' => 'PAK Awal: Perpindahan dari Jabatan Lain',
        'PAK_AWAL_PROMOSI'     => 'PAK Awal: Promosi',
        'PAK_AWAL_PENGANGKATAN'=> 'PAK Awal: Pengangkatan Pertama',
        'TAHUNAN'              => 'Tahunan',
        'PROPORSIONAL'         => 'Proporsional',
        default                => $this->jenis_penilaian,
    };
}


}
