<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AngkaKredit;
use App\Models\PakDocument;
use Illuminate\Http\Request;
use Auth;

class PakController extends Controller
{
    public function create()
    {
        return view('pak.create');
    }

    public function store(Request $request)
    {
        $akPendidikan = $request->klaim_pendidikan === 'ya' ? 25 : 0;

        Pak::create([
            'pak_awal'           => $request->pak_awal,
            'periode'            => $request->periode,
            'jenis_penilaian'    => $request->jenis_penilaian,
            'bulan_awal'         => $request->bulan_awal,
            'bulan_akhir'        => $request->bulan_akhir,
            'predikat_kinerja'   => $request->predikat_kinerja,
            'ak_dasar'           => $request->ak_dasar,
            'ak_jf_lama'         => $request->ak_jf_lama,
            'ak_pendidikan'      => $akPendidikan,
            'golongan'           => $request->golongan,
            'masa_kerja_bulan'   => $request->masa_kerja_bulan,
        ]);

        return redirect()->route('pak.create')
            ->with('success', 'Perhitungan PAK berhasil disimpan');
    }

    public function show($id)
{

    $ak = AngkaKredit::with('pegawai')->findOrFail($id);
    $pegawai = $ak->pegawai;

    /* ===============================
       HITUNG PANGKAT SETINGKAT DI ATAS
    =============================== */
    $pangkatMap = [
        'III/a' => 'III/b',
        'III/b' => 'III/c',
        'III/c' => 'III/d',
        'III/d' => 'IV/a',
        'IV/a'  => 'IV/b',
        'IV/b'  => 'IV/c',
        'IV/c'  => 'IV/d',
        'IV/d'  => 'IV/e',
    ];

    $currentPangkat = $pegawai->golongan ?? null;
    $pangkat_naik   = $pangkatMap[$currentPangkat] ?? '-';

    /* ===============================
       HITUNG JENJANG SETINGKAT DI ATAS
    =============================== */
    $jenjangMap = [
        'Ahli Pertama' => 'Ahli Muda',
        'Ahli Muda'    => 'Ahli Madya',
        'Ahli Madya'   => 'Ahli Utama',
    ];

    $currentJenjang = $pegawai->jenjang_jabatan ?? null;
    $jenjang_naik   = $jenjangMap[$currentJenjang] ?? '-';

    /* ===============================
       GENERATE PDF
    =============================== */
    $pdf = Pdf::loadView('pak.pdf', compact(
        'ak',
        'pegawai',
        'pangkat_naik',
        'jenjang_naik'
    ))->setPaper('A4', 'portrait');

    return $pdf->stream('PAK_'.$pegawai->nip.'.pdf');


    }

    public function verifikasiSdmo($id)
{
    $ak = AngkaKredit::findOrFail($id);
    $ak->update([
        'status' => 'DIVERIFIKASI SDMO'
    ]);

    $ak->pakDocument->update([
        'verified_by_sdmo' => true
    ]);

    return back()->with('success', 'PAK diverifikasi SDMO');
}


    public function ttdPpk($id)
    {
        $ak = AngkaKredit::findOrFail($id);

        $ak->update([
            'status' => 'DISAHKAN PPK'
        ]);

        $ak->pak->update([
            'signed_by_ppk' => Auth::id(),
            'signed_at' => now()
        ]);

        return back()->with('success','PAK disahkan');
    }

    public function tolakPpk(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required'
        ]);

        $ak = AngkaKredit::findOrFail($id);

        $ak->update([
            'status' => 'DITOLAK_PPK',
            'catatan_ppk' => $request->catatan
        ]);

        return back()->with('error','PAK ditolak PPK');
    }
}
