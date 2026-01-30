<?php

namespace App\Services;

use App\Models\AkPenyesuaian;

class PerhitunganPakService
{
    /* ===============================
     * KONSTANTA
     * =============================== */

    public static function predikatMap()
    {
        return [
            'SB' => 150,
            'B'  => 100,
            'BP' => 75,
            'K'  => 50,
            'SK' => 25,
        ];
    }

    public static function koefisienMap()
    {
        return [
            'pemula'        => 3.75,
            'terampil'      => 5,
            'mahir'         => 12.5,
            'penyelia'      => 25,
            'ahli_pertama'  => 12.5,
            'ahli_muda'     => 25,
            'ahli_madya'    => 37.5,
            'ahli_utama'    => 50,
        ];
    }

    /* ===============================
     * UTIL
     * =============================== */

    public static function masaKerja($tahun, $bulan)
    {
        return $tahun + ($bulan / 12);
    }

    /* ===============================
     * 1. PENGANGKATAN PERTAMA
     * =============================== */
    public static function pengangkatanPertama(
        $masaKerja,
        $predikat,
        $koefisien
    ) {
        return $masaKerja * $predikat * $koefisien / 100;
    }

    /* ===============================
     * 2. PERPINDAHAN JABATAN
     * =============================== */
    public static function perpindahan(
        $masaKerja,
        $predikat,
        $koefisien,
        $akDasar
    ) {
        return ($masaKerja * $predikat * $koefisien / 100) + $akDasar;
    }

    /* ===============================
     * 3. PENYESUAIAN
     * =============================== */
    public static function penyesuaian($akPenyesuaian, $akDasar)
    {
        return $akPenyesuaian + $akDasar;
    }

    /* ===============================
     * 4. PROMOSI
     * =============================== */
    public static function promosi(
        $predikatTahun1,
        $predikatTahun2,
        $koefisien,
        $akDasar
    ) {
        $tahun1 = $predikatTahun1 * $koefisien / 100;
        $tahun2 = $predikatTahun2 * $koefisien / 100;

        return $tahun1 + $tahun2 + $akDasar;
    }

    public static function hitungPenyesuaian(
        string $golongan,
        string $pendidikan,
        string $masaGolongan,
        float $akDasar
    ): float {
        $ak = AkPenyesuaian::where('golongan', $golongan)
            ->where('pendidikan', $pendidikan)
            ->firstOrFail();

        $akPenyesuaian = match ($masaGolongan) {
            '<1' => $ak->kurang_1_tahun,
            '1'  => $ak->tahun_1,
            '2'  => $ak->tahun_2,
            '3'  => $ak->tahun_3,
            default => $ak->tahun_4_lebih,
        };

        return $akPenyesuaian + $akDasar;
    }

    public static function masaKerja($tahun, $bulan)
    {
        return $tahun + ($bulan / 12);
    }

    public static function pengangkatanPertama($masaKerja, $predikat, $koef)
    {
        return $masaKerja * $predikat * $koef / 100;
    }

    public static function perpindahan($masaKerja, $predikat, $koef, $akDasar)
    {
        return ($masaKerja * $predikat * $koef / 100) + $akDasar;
    }

    public static function penyesuaian($akPenyesuaian, $akDasar)
    {
        return $akPenyesuaian + $akDasar;
    }

    public static function promosi($tahun1, $tahun2, $koef, $akDasar)
    {
        return (($tahun1 + $tahun2) * $koef / 100) + $akDasar;
    }
}
