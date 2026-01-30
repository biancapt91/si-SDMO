<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\RiwayatJabatan;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PegawaiImport
{
    /**
     * Process a single row from the Excel import and create/update the Pegawai
     * Returns the Pegawai model
     */
    public function importRow(array $row)
    {
        // Normalize tanggal_lahir
        $tanggalLahir = null;
        if (!empty($row['tanggal_lahir'])) {
            try {
                $tanggalLahir = Carbon::parse($row['tanggal_lahir'])->toDateString();
            } catch (\Throwable $e) {
                $tanggalLahir = null;
            }
        }

        // Normalize tmt_pangkat
        $tmtPangkat = null;
        if (!empty($row['tmt_pangkat'])) {
            try {
                $tmtPangkat = Carbon::parse($row['tmt_pangkat'])->toDateString();
            } catch (\Throwable $e) {
                $tmtPangkat = null;
            }
        }

        $pegawai = Pegawai::updateOrCreate(
            [
                'nip' => trim($row['nip']),
            ],
            [
                'nama'               => $row['nama'] ?? null,
                'pangkat_golongan'   => $row['pangkat_golongan'] ?? null,
                'tmt_pangkat'        => $tmtPangkat,
                'jabatan_saat_ini'   => $row['jabatan_saat_ini'] ?? $row['jabatan'] ?? null,
                'kelas_jabatan'      => $row['kelas_jabatan'] ?? null,
                // map template 'unit_kerja_sk' to db column 'unit_kerja'
                'unit_kerja'         => $row['unit_kerja_sk'] ?? $row['unit_kerja'] ?? null,
                'unit_kerja_st'      => $row['unit_kerja_st'] ?? null,
                'penempatan'         => $row['penempatan'] ?? null,
                'status_penempatan'  => $row['status_penempatan'] ?? null,
                'jenis_asn'          => $row['jenis_asn'] ?? null,
                'status_asn'         => $row['status_asn'] ?? null,
                'jenis_kelamin'      => $this->normalizeGender($row['jenis_kelamin'] ?? null),
                'tempat_lahir'       => $row['tempat_lahir'] ?? null,
                'tanggal_lahir'      => $tanggalLahir,
            ]
        );

        // Automatically create Riwayat Jabatan if jabatan_saat_ini exists
        $this->createRiwayatJabatan($pegawai, $row);

        // Calculate retirement date based on job history
        $pegawai->updateRetirementDate();

        return $pegawai;
    }

    /**
     * VALIDASI PER BARIS EXCEL
     */
    public function rules(): array
    {
        return [
            'nip' => ['required'],
            'nama' => ['required'],
            'jabatan_saat_ini' => ['required'],
            'unit_kerja_sk' => ['required'],

            'jenis_asn' => [
                'required',
                Rule::in(['PNS', 'PPPK']),
            ],

            'jenis_kelamin' => [
                'required',
                Rule::in(['L', 'P', 'Laki-laki', 'Perempuan', 'male', 'female', 'M', 'F', 'm', 'f', 'laki-laki', 'perempuan']),
            ],

            'status_penempatan' => [
                'required',
                Rule::in(['SK', 'ST']),
            ],
        ];
    }

    /**
     * PESAN ERROR RAMAH USER
     */
    public function customValidationMessages()
    {
        return [
            'nip.required' => 'NIP wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'jabatan_saat_ini.required' => 'Jabatan saat ini wajib diisi',
            'unit_kerja_sk.required' => 'Unit kerja SK wajib diisi',
            'jenis_asn.in' => 'Jenis ASN hanya boleh PNS atau PPPK',
            'jenis_kelamin.in' => 'Jenis kelamin hanya boleh L atau P (atau Laki-laki/Perempuan)',
            'status_penempatan.in' => 'Status penempatan hanya boleh SK atau ST',
        ];
    }

    /**
     * Create Riwayat Jabatan entry for imported pegawai
     */
    protected function createRiwayatJabatan(Pegawai $pegawai, array $row)
    {
        $jabatan = $row['jabatan_saat_ini'] ?? $row['jabatan'] ?? null;
        
        if (empty($jabatan)) {
            return; // Skip if no jabatan data
        }

        // Parse TMT Mulai from Excel or use today
        $tmtMulai = null;
        if (!empty($row['tmt_jabatan']) || !empty($row['tmt_mulai'])) {
            $tmtValue = $row['tmt_jabatan'] ?? $row['tmt_mulai'];
            try {
                $tmtMulai = Carbon::parse($tmtValue)->toDateString();
            } catch (\Throwable $e) {
                $tmtMulai = Carbon::now()->toDateString();
            }
        } else {
            $tmtMulai = Carbon::now()->toDateString();
        }

        // Determine jenis_jabatan based on jabatan name or explicit column
        $jenisJabatan = $this->determineJenisJabatan($row);

        // Check if riwayat already exists for this pegawai with same jabatan and tmt
        $existing = RiwayatJabatan::where('pegawai_id', $pegawai->id)
            ->where('jabatan', $jabatan)
            ->where('tmt_mulai', $tmtMulai)
            ->first();

        if (!$existing) {
            RiwayatJabatan::create([
                'pegawai_id' => $pegawai->id,
                'jabatan' => $jabatan,
                'jenis_jabatan' => $jenisJabatan,
                'unit_kerja' => $row['unit_kerja_sk'] ?? $row['unit_kerja'] ?? null,
                'tmt_mulai' => $tmtMulai,
                'tmt_selesai' => null, // Active position
            ]);
        }
    }

    /**
     * Determine jenis_jabatan from row data
     */
    protected function determineJenisJabatan(array $row): ?string
    {
        // Check if jenis_jabatan is explicitly provided
        if (!empty($row['jenis_jabatan'])) {
            return $row['jenis_jabatan'];
        }

        // Auto-detect based on jabatan name
        $jabatan = strtolower($row['jabatan_saat_ini'] ?? $row['jabatan'] ?? '');

        if (stripos($jabatan, 'kepala') !== false || stripos($jabatan, 'ketua') !== false) {
            return 'Struktural';
        }
        
        if (stripos($jabatan, 'analis') !== false || 
            stripos($jabatan, 'perancang') !== false ||
            stripos($jabatan, 'pranata') !== false ||
            stripos($jabatan, 'pengelola') !== false ||
            stripos($jabatan, 'pengawas') !== false) {
            return 'Fungsional';
        }

        return 'Pelaksana'; // Default
    }

    /**
     * Normalize various gender representations to 'Laki-laki' or 'Perempuan' or null.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function normalizeGender(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        $v = mb_strtolower(trim($value));

        if (in_array($v, ['l', 'laki', 'laki-laki', 'laki laki', 'male', 'm'], true)) {
            return 'Laki-laki';
        }

        if (in_array($v, ['p', 'perempuan', 'female', 'f'], true)) {
            return 'Perempuan';
        }

        // If value is already in full format, accept it
        if (in_array($value, ['Laki-laki', 'Perempuan'], true)) {
            return $value;
        }

        return null;
    }
}
