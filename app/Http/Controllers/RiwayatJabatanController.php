<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\RiwayatJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatJabatanController extends Controller
{
    public function create($pegawai_id)
    {
        $pegawai = Pegawai::findOrFail($pegawai_id);
        return view('riwayat_jabatan.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'jabatan' => 'required',
            'jenis_jabatan' => 'required',
            'tmt_mulai' => 'required',
        ]);

        // 1. Simpan riwayat jabatan
        $riwayat = RiwayatJabatan::create([
            'pegawai_id' => $request->pegawai_id,
            'jabatan' => $request->jabatan,
            'jenis_jabatan' => $request->jenis_jabatan,
            'tmt_mulai' => $request->tmt_mulai,
            'tmt_selesai' => $request->tmt_selesai,
            'unit_kerja' => $request->unit_kerja,
        ]);

        // 2. Update retirement date based on new job history
        $pegawai = Pegawai::find($request->pegawai_id);
        $pegawai->updateRetirementDate();

        return redirect()->route('pegawai.show', $request->pegawai_id)
                         ->with('success', 'Riwayat jabatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = RiwayatJabatan::findOrFail($id);
        return view('riwayat_jabatan.edit', compact('riwayat'));
    }

    public function update(Request $request, $id)
{
    $riwayat = RiwayatJabatan::findOrFail($id);

    // 1. Update riwayat jabatan
    $riwayat->update([
        'jabatan'       => $request->jabatan,
        'jenis_jabatan' => $request->jenis_jabatan,
        'tmt_mulai'     => $request->tmt_mulai,
        'tmt_selesai'   => $request->tmt_selesai,
        'unit_kerja'    => $request->unit_kerja,
    ]);

    // 2. Update retirement date based on updated job history
    $pegawai = Pegawai::find($riwayat->pegawai_id);
    $pegawai->updateRetirementDate();

    return redirect()->route('pegawai.show', $riwayat->pegawai_id)
                     ->with('success', 'Riwayat jabatan berhasil diupdate dan tanggal pensiun diperbarui.');
}


    public function destroy($id)
    {
        $riwayat = RiwayatJabatan::findOrFail($id);
        $pegawai_id = $riwayat->pegawai_id;
        $riwayat->delete();

        return redirect()->route('pegawai.show', $pegawai_id)
                         ->with('success', 'Riwayat jabatan berhasil dihapus.');
    }
}
