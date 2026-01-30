<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TemplatePegawaiExport implements FromArray, WithHeadings, WithTitle
{
    /**
     * ISI CONTOH (1 baris)
     */
    public function array(): array
    {
        return [
            [
                '1987xxxx',
                'Budi Santoso',
                'III/b',
                '2020-01-15',
                'Analis SDM',
                'Fungsional',
                '9',
                'Biro SDMO',
                'Subbag Kepegawaian',
                'Ruang 301, Gedung A',
                'SK',
                'PNS',
                'Aktif',
                'L',
                'Jakarta',
                '1987-06-12',
                '2020-01-01',
            ]
        ];
    }

    /**
     * HEADER EXCEL (HARUS SAMA DENGAN IMPORT)
     */
    public function headings(): array
    {
        return [
            'nip',
            'nama',
            'pangkat_golongan',
            'tmt_pangkat',
            'jabatan_saat_ini',
            'jenis_jabatan',
            'kelas_jabatan',
            'unit_kerja_sk',
            'unit_kerja_st',
            'penempatan',
            'status_penempatan',
            'jenis_asn',
            'status_asn',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'tmt_jabatan',
        ];
    }

    /**
     * NAMA SHEET
     */
    public function title(): string
    {
        return 'pegawai';
    }
}
