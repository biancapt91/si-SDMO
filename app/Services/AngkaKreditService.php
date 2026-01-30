<?php
namespace App\Services;

use Carbon\Carbon;

class AngkaKreditService
{
    // DEFAULT mapping (sesuaikan dengan lampiran resmi / DB Anda)
    public const JENJANG_REQUIREMENTS = [
        // contoh: 'Ahli Pertama' => 100, 'Ahli Muda' => 200, 'Ahli Madya' => 300, 'Ahli Utama' => 400
        'Ahli Pertama' => 100,
        'Ahli Muda'    => 200,
        'Ahli Madya'   => 450,
        'Ahli Utama'   => null,
        'Terampil'     => 60,   // contoh kategori keterampilan
        'Mahir'	       => 100,
	'Penyelia'     => null,
    ];

    // Default kebutuhan pangkat (kenaikan pangkat 1 tingkat -> AK yang dibutuhkan)
    public const PANGKAT_REQUIREMENTS = [
    	'Ahli Utama' => 200,   // IV/d → IV/e → dst
    	'Ahli Madya' => 150,   // IV/a → IV/b → IV/c → dst
    	'Ahli Muda'  => 100,   // III/c → III/d
    	'Ahli Pertama' => 50,  // III/a → III/b → III/c
    	'Penyelia'   => 100,   // III/c → III/d
    	'Mahir'      => 50,    // III/a → III/b
    	'Terampil'   => 20,    // II/b → II/c → II/d
    	'Pemula'     => 15,    // II/a → II/b
     	];


    // Base AK yang 'melekat' pada golongan (contoh, di beberapa contoh dokumen)
    public const BASE_AK_BY_GOLONGAN = [
        'III/a' => 0,
        'III/b' => 0,
        'III/c' => 0,
        'III/d' => 100, // contoh: III/d memberi 100 AK dasar pada contoh tertentu
        'IV/a'  => 100,
        // Isi sesuai lampiran / kebijakan instansi
    ];

    // 1) extract jenjang dari teks jabatan (simple heuristics)
    public static function extractJenjangFromJabatan($jabatan)
    {
        if (!$jabatan) return '';

        $jabatan = strtolower($jabatan);

        // mapping contoh
        $patterns = [
            'ahli utama'     => 'Ahli Utama',
            'ahli madya'     => 'Ahli Madya',
            'ahli muda'      => 'Ahli Muda',
            'ahli pertama'   => 'Ahli Pertama',
            'penyelia'       => 'Penyelia',
            'mahir'          => 'Mahir',
            'terampil'       => 'Terampil',
            'pemula'         => 'Pemula',
        ];

        foreach ($patterns as $key => $value) {
            if (str_contains($jabatan, $key)) {
                return $value;
            }
        }

        // default jika tidak ketemu
        return '';
    }

    // 2) hitung AK kebutuhan untuk kenaikan jenjang
    public static function akNeededForJenjang($pegawai)
    {
        $jenjang = self::extractJenjangFromPegawai($pegawai);
        $akNow = floatval(optional($pegawai->akKumulatif)->total_ak ?? 0);

        // ambil requirement jenjang
        $requirement = $jenjang ? (self::JENJANG_REQUIREMENTS[$jenjang] ?? null) : null;

        if (!$requirement) {
            return [
                'target' => null,
                'needed' => null,
                'sisa'   => null,
            ];
        }

        // ambil base AK terkait pangkat/golongan pegawai jika ada
        $pangkatGol = $pegawai->pangkat ?? ($pegawai->golongan ?? null); // sesuaikan nama kolom
        $baseForGol = $pangkatGol ? (self::BASE_AK_BY_GOLONGAN[$pangkatGol] ?? 0) : 0;

        // peraturan menunjukkan ada langkah pengurangan base (contoh: 200 - 150 => sisa 50)
        $kebutuhan = max(0, $requirement - $baseForGol);

        // sisa yang harus dicapai dari akNow
        $sisa = max(0, $kebutuhan - $akNow);

        return [
            'target' => $requirement,
            'base'   => $baseForGol,
            'kebutuhan' => $kebutuhan,
            'needed' => $sisa,
            'current' => $akNow,
            'jenjang' => $jenjang,
        ];
    }

    // 3) hitung AK kebutuhan untuk kenaikan pangkat (sederhana)
    public static function akNeededForPangkat($pegawai)
{
    $jenjang = self::extractJenjangFromPegawai($pegawai);
    $akNow   = floatval(optional($pegawai->akKumulatif)->total_ak ?? 0);

    $requirement = self::PANGKAT_REQUIREMENTS[$jenjang] ?? null;

    if (!$requirement) {
        return null; // berarti memang tidak ada syarat pangkat (seperti Ahli Utama)
    }

    $needed = max($requirement - $akNow, 0);
    return $needed == 0 ? 0 : $needed;
}

         public static function guessNextPangkat($pangkatNow)
    {
        // contoh singkat: naik satu tingkat urutannya III/a -> III/b -> III/c -> III/d -> IV/a ...
        $order = ['II/a','II/b','II/c','II/d','III/a','III/b','III/c','III/d','IV/a','IV/b','IV/c','IV/d','IV/e'];

        if (!$pangkatNow) return null;
        $idx = array_search($pangkatNow, $order);
        if ($idx === false) return null;
        return $order[$idx+1] ?? null;
    }

    // helper: contoh prediksi tanggal (sangat sederhana: bukan peraturan baku, tinggal kembangkan)
    public static function predictAchievementDate($pegawai)
    {
        // implementasi real butuh kecepatan ak per tahun (dari konversi predikat kinerja)
        return '-';
    }

public static function extractJenjangFromPegawai($pegawai)
{
    if (!$pegawai) {
        return null;
    }

    // Ambil teks jabatan
    $jabatan = $pegawai->jabatan_saat_ini
                ?? optional($pegawai->jabatan)->nama_jabatan
                ?? '';

    return self::extractJenjangFromJabatan($jabatan);
}

}
