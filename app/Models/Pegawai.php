<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DosirPegawai; // tambahkan di atas file
use App\Models\RiwayatJabatan;


class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'jabatan_saat_ini',
        'unit_kerja',
	'unit_kerja_st',
	'jenis_asn',
	'status_asn',
	'tanggal_lahir',
        'tanggal_pensiun',
        'status_penempatan',
        'penempatan',
	'tempat_lahir',
        'kelas_jabatan',
	'pangkat_golongan',
	'tmt_pangkat',
	
    ];

    public function toUserArray(): array
    {
        // Prepare a minimal user array if you want to auto-create user records later
        return [
            'name' => $this->nama,
            'email' => null,
            'nip' => $this->nip,
            'password' => null,
            'role' => 'user',
        ];
    }

    /**
     * RELASI KE RIWAYAT JABATAN
     * Pegawai memiliki banyak riwayat jabatan (one to many)
     */
    public function riwayatJabatan()
{
    return $this->hasMany(RiwayatJabatan::class);
}

    /**
     * RELASI KE UNIT KERJA (OPTIONAL)
     * Jika tabel pegawai memiliki kolom unit_kerja_id
     */
    public function unitKerjaModel()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja');
    }

    /**
     * RELASI KE KELAS JABATAN (OPTIONAL)
     */
    public function kelasJabatanModel()
    {
        return $this->belongsTo(KelasJabatan::class, 'kelas_jabatan_id');
    }

    /**
     * RELASI KE STATUS PENEMPATAN (OPTIONAL)
     */
    public function statusPenempatanModel()
    {
        return $this->belongsTo(StatusPenempatan::class, 'status_penempatan_id');
    }

public function dosir()
{
    return $this->hasMany(DosirPegawai::class, 'pegawai_id');
}

public function akKumulatif()
{
    return $this->hasOne(\App\Models\AkKumulatif::class, 'pegawai_id');
}

public function angkaKreditEntries()
{
    return $this->hasMany(\App\Models\AngkaKreditEntry::class, 'pegawai_id');
}

public function angkaKredits()
{
    return $this->hasMany(\App\Models\AngkaKredit::class, 'pegawai_id');
}

public function predikatKinerja()
{
    return $this->hasMany(\App\Models\PredikatKinerja::class, 'pegawai_id');
}

public function ujiKompetensi()
{
    return $this->hasMany(\App\Models\UjiKompetensi::class, 'pegawai_id');
}

public function penetapanAk()
{
    return $this->hasMany(\App\Models\PenetapanAk::class, 'pegawai_id');
}

public function kebutuhanAk()
{
    return $this->hasOne(\App\Models\KebutuhanAk::class, 'pegawai_id');
}

/**
 * Calculate retirement date based on birth date and latest job type
 */
public function calculateRetirementDate()
{
    if (!$this->tanggal_lahir) {
        return null;
    }

    // Get latest job history
    $latestJob = $this->riwayatJabatan()
        ->orderBy('tmt_mulai', 'desc')
        ->first();

    if (!$latestJob) {
        return null;
    }

    // Get retirement age from reference table
    $batasUsia = \DB::table('ref_batas_pensiun')
        ->where('jenis_jabatan', $latestJob->jenis_jabatan)
        ->value('batas_usia');

    if (!$batasUsia) {
        return null;
    }

    // Calculate: birth date + retirement age
    return \Carbon\Carbon::parse($this->tanggal_lahir)->addYears($batasUsia);
}

/**
 * Update tanggal_pensiun field based on current job
 */
public function updateRetirementDate()
{
    $retirementDate = $this->calculateRetirementDate();
    if ($retirementDate) {
        $this->tanggal_pensiun = $retirementDate;
        $this->saveQuietly(); // Save without triggering events
    }
    return $this;
}

}


