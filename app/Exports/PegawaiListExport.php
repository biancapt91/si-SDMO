<?php

namespace App\Exports;

use App\Models\Pegawai;
use Illuminate\Support\Collection;

class PegawaiListExport
{
    public function collection(): Collection
    {
        $pegawai = Pegawai::orderBy('nama')->get();
        
        $data = new Collection();
        
        // Header
        $data->push([
            'NIP',
            'Nama',
            'Pangkat/Golongan',
            'Jabatan',
            'Unit Kerja',
            'Jenis ASN',
            'Status Penempatan',
        ]);
        
        // Data rows
        foreach ($pegawai as $p) {
            $data->push([
                $p->nip,
                $p->nama,
                $p->pangkat_golongan ?? '-',
                $p->jabatan_saat_ini ?? '-',
                $p->unit_kerja ?? '-',
                $p->jenis_asn ?? '-',
                $p->status_penempatan ?? '-',
            ]);
        }
        
        return $data;
    }
}
