<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AngkaKredit;
use App\Models\PakDocument;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;

class AngkaKreditController extends Controller
{
    /* =====================================================
     * INDEX
     * Halaman Form + Riwayat AK
     * URL: /career-map/hitung-ak
     * ===================================================== */
  
public function index(Request $request)
{
	// Ambil pegawai berdasarkan user yang login
	$user = auth()->user();
	$pegawaiSaya = Pegawai::where('nip', $user->nip)->first();

        if (!$pegawaiSaya) {
            return response('Data pegawai tidak ditemukan', 404);
        }

	$daftarPegawai = Pegawai::orderBy('nama')->get();

	// riwayat saya
	$riwayatSaya = AngkaKredit::where('pegawai_id', $pegawaiSaya->id)
    	->orderByDesc('created_at')
    	->get();

// ambil pegawai dari dropdown
    $pegawaiAktif = $request->filled('pegawai_id')
        ? Pegawai::find($request->pegawai_id)
        : null;

    // riwayat pegawai (hanya jika dipilih)
    $riwayatPegawai = $pegawaiAktif
        ? AngkaKredit::where('pegawai_id', $pegawaiAktif->id)
            ->orderByDesc('created_at')
            ->get()
        : collect();


	/* ===============================
   	AKUMULASI AK PER JABATAN
	=============================== */
	$akumulasi = AngkaKredit::where('pegawai_id', $pegawaiSaya->id)
    	->select('jabatan', \DB::raw('SUM(ak_total) as total_ak'))
    	->groupBy('jabatan')
    	->get();

	AngkaKredit::whereNull('jabatan')->get()->each(function ($ak) {
    	$ak->update([
        'jabatan' => optional($ak->pegawai)->jabatan_saat_ini
        ]);
        });
        
         return view('angka_kredit.index', compact(
        'akumulasi',
    	'daftarPegawai',
    	'pegawaiSaya',
    	'riwayatSaya',
    	'riwayatPegawai',
	'pegawaiAktif',
	));
   
    }

    /* =====================================================
     * STORE
     * Simpan Perhitungan AK
     * ===================================================== */
   public function store(Request $request)
{
    $klaimPendidikan = $request->input('klaim_pendidikan') === 'ya';

    // VALIDASI WAJIB
    $request->validate([
        'pegawai_id' => 'required|exists:pegawai,id',
    ]);

    // AMBIL PEGAWAI DARI DROPDOWN
    $pegawai = Pegawai::findOrFail($request->pegawai_id);

    $masaKerja = 0;
    $predikat  = 0;
    $akDasar   = 0;
    $akHasil   = 0;
    $akTotal   = 0;
    $akPendidikan = 0;

    /* =====================================================
     * OPSI A → TENTUKAN PAK AWAL DI BACKEND
     * ===================================================== */
	$isPakBerkala = $request->filled('jenis_penilaian');
	$isPakAwal    = $request->filled('jenis_pak_awal');


   	 /* =====================================================
	 * PAK BERKALA (TAHUNAN & PROPORSIONAL)
 	 * ===================================================== */
	if ($request->filled('jenis_penilaian')) {


    	$request->validate([
        'jenis_penilaian'            => 'required|in:tahunan,proporsional',
        'periode'                    => 'required|numeric',
        'koef_jenjang'               => 'required|numeric',
        'ak_dasar'                   => 'nullable|numeric',
        'predikat_kinerja_tahunan'   => 'required|numeric',

	]);

    	$predikat = (float) $request->predikat_kinerja_tahunan;
    	$koef     = (float) $request->koef_jenjang;
    	$akDasar  = (float) ($request->ak_dasar ?? 0);

        // Hitung Masa Kepangkatan dengan logika pembulatan
        $masaKepangkatanTahun = (int) ($request->masa_kepangkatan_tahun ?? 0);
        $masaKepangkatanBulan = (int) ($request->masa_kepangkatan_bulan ?? 0);
        
        // Konversi ke desimal
        $masaKepangkatanTotal = $masaKepangkatanTahun + ($masaKepangkatanBulan / 12);
        
        // Logika pembulatan: ≥6 bulan naik, <6 bulan turun
        if ($masaKepangkatanBulan >= 6) {
            $masaKepangkatan = ceil($masaKepangkatanTotal);
        } else {
            $masaKepangkatan = floor($masaKepangkatanTotal);
        }
        
        // Maksimal 3 tahun
        if ($masaKepangkatan > 3) {
            $masaKepangkatan = 3;
        }
        
        // Jika 0, set ke 1 (default)
        if ($masaKepangkatan == 0) {
            $masaKepangkatan = 1;
        }

    	/* ===== TAHUNAN ===== */
    	if ($request->jenis_penilaian === 'tahunan') {
        $akHasil = ($predikat * $koef * $masaKepangkatan) + $akDasar;
    	}

    	/* ===== PROPORSIONAL ===== */
    	if ($request->jenis_penilaian === 'proporsional') {

        $request->validate([
            'bulan_awal'  => 'required',
            'bulan_akhir' => 'required',
        ]);

        $mapBulan = [
            'Januari'=>1,'Februari'=>2,'Maret'=>3,'April'=>4,'Mei'=>5,'Juni'=>6,
            'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12
        ];

        $awal  = $mapBulan[$request->bulan_awal];
        $akhir = $mapBulan[$request->bulan_akhir];

        if ($akhir < $awal) {
            return back()->withErrors('Rentang bulan tidak valid')->withInput();
        }
	

	// mapping AK kumulatif minimal
	$akMinimal = [
    'Ahli Utama'    => 200,
    'Ahli Madya'    => 150,
    'Ahli Muda'     => 100,
    'Ahli Pertama'  => 50,
    'Penyelia'      => 100,
    'Mahir'         => 50,
    'Terampil'      => 20,
	];


	if ($klaimPendidikan) {
        $jenjang = $request->jenjang_jabatan; // pastikan name ini ada di form

        if (isset($akMinimal[$jenjang])) {
        $akPendidikan = 0.25 * $akMinimal[$jenjang];
    	}
	}

        $jumlahBulan = ($akhir - $awal) + 1;
        $faktor      = $jumlahBulan / 12;

        $akHasil = ($faktor * $predikat * $koef * $masaKepangkatan) + $akDasar + $akPendidikan;
    	}

    $ak = AngkaKredit::create([
        'pegawai_id'       => $pegawai->id,
        'jabatan'          => $pegawai->jabatan_saat_ini,
        'periode'          => $request->periode,
        'pak_awal'         => false,
        'jenis_penilaian'  => strtoupper($request->jenis_penilaian),
        'predikat_kinerja' => $predikat,
        'koef_jenjang'     => $koef,
        'masa_kerja_total' => $masaKepangkatan, // simpan masa kepangkatan yang sudah dibulatkan
        'ak_dasar'         => round($akDasar, 2),
        'ak_hasil'         => round($akHasil, 2),
        'ak_total'         => round($akHasil, 2),
        'status'           => 'DRAFT',
	'klaim_pendidikan' => $klaimPendidikan ? 1 : 0,
    	'ak_pendidikan'    => round($akPendidikan, 2),

    ]);

    PakDocument::create([
        'angka_kredit_id' => $ak->id,
    ]);

    return redirect('/career-map/hitung-ak')
    ->with('success', 'PAK Berkala berhasil disimpan');

}

    /* =====================================================
     * PAK AWAL
     * ===================================================== */
    if ($request->filled('jenis_pak_awal')) {
	$request->validate([
        'jenis_pak_awal' => 'required|in:pak_awal_pengangkatan,pak_awal_perpindahan,pak_awal_penyesuaian,pak_awal_promosi',
    	]);

	switch ($request->jenis_pak_awal) {

    	/* ===============================
     	* PENGANGKATAN PERTAMA
     	* =============================== */
    	case 'pak_awal_pengangkatan':

        $request->validate([
            'masa_kerja_tahun' => 'required|numeric|min:0',
            'masa_kerja_bulan' => 'required|numeric|min:0|max:11',
            'predikat_kinerja' => 'required|numeric',
            'jenjang'          => 'required|numeric|min:0',
        ]);

        $masaKerja = $request->masa_kerja_tahun
                   + ($request->masa_kerja_bulan / 12);

        $predikat = (float) $request->predikat_kinerja;
        $jenjang  = (float) $request->jenjang;

        $akHasil = $masaKerja * ($predikat / 100) * $jenjang;
        $akTotal = $akHasil;
	
	break;

    	/* =========================================
     	* PENYESUAIAN / PENYETARAAN
     	* ========================================= */
    
	case 'pak_awal_penyesuaian':

    	$request->validate([
        'ak_penyesuaian' => 'required|numeric|min:0',
        'ak_dasar'       => 'required|numeric|min:0',
    	]);

    	$akPenyesuaian = (float) $request->ak_penyesuaian;
    	$akDasar       = (float) $request->ak_dasar;

    	$akHasil = $akPenyesuaian + $akDasar;
    	$akTotal = $akHasil;

    	$masaKerja = 0;
    	$predikat  = 0;

    	break;


    	/* =========================================
     	* PROMOSI
     	* ========================================= */
    	case 'pak_awal_promosi':

        $request->validate([
            'ak_dasar' => 'required|numeric|min:0',
        ]);

        $akDasar = (float) $request->ak_dasar;
        $akHasil = $akDasar;
        $akTotal = $akHasil;
        $masaKerja = 0;
        $predikat  = null;

	break;
    
    /* =========================================
     * PERPINDAHAN DARI JABATAN LAIN
     * ========================================= */
     case 'pak_awal_perpindahan':

    	$request->validate([
    	'sub_aksi_perpindahan' => 'required_if:jenis_pak_awal,pak_awal_perpindahan
                               |in:jf_ke_jf,ja_jf_sesuai,ja_jf_tidak_sesuai',
	]);

	if (
    	$request->jenis_pak_awal === 'pak_awal_perpindahan' &&
    	!$request->sub_aksi_perpindahan
	) {
    	return back()
        ->withErrors('Sub aksi perpindahan belum dipilih')
        ->withInput();
	}

    	switch ($request->sub_aksi_perpindahan) {

        /* =========================================
         * JF → JF
         * ========================================= */
	case 'jf_ke_jf':

    		$request->validate([
        	'ak_jf_sebelumnya' => 'required|numeric|min:0',
    		]);

    	$masaKerja = 0;        
    	$predikat  = 0;
    	$akDasar   = 0;
    	$akHasil   = (float) $request->ak_jf_sebelumnya;
    	$akTotal   = $akHasil;

    	break;

        /* =========================================
         * JA → JF (SESUAI)
         * ========================================= */
        case 'ja_jf_sesuai':

            $request->validate([
                'masa_kepangkatan_tahun' => 'required|numeric|min:0',
                'masa_kepangkatan_bulan' => 'required|numeric|min:0|max:11',
                'predikat_kinerja'       => 'required|numeric',
                'jenjang'                => 'required|numeric|min:0',
                'ak_dasar'               => 'nullable|numeric|min:0',
            ]);

            $masaKerja = $request->masa_kepangkatan_tahun
                       + ($request->masa_kepangkatan_bulan / 12);

            $predikat = (float) $request->predikat_kinerja;
            $jenjang  = (float) $request->jenjang;
            $akDasar = (float) $request->ak_dasar;

            $akHasil = ($masaKerja * $predikat * $jenjang) + $akDasar;
            $akTotal = $akHasil;

            break;

        /* =========================================
         * JA → JF (TIDAK SESUAI)
         * ========================================= */
        case 'ja_jf_tidak_sesuai':

            $request->validate([
                'ak_otomatis' => 'required|numeric|min:0',
            ]);

            $akDasar   = (float) $request->ak_otomatis;
            $akHasil   = $akDasar;
            $akTotal   = $akHasil;
            $masaKerja = 0;
            $predikat  = 0;

   	break;
	
    /* =========================================
     * DEFAULT (AMAN)
     * ========================================= */
    default:
        abort(400, 'Jenis PAK Awal tidak dikenali');
}
}
if ($request->jenis_pak_awal === 'pak_awal_perpindahan' && $akTotal <= 0) {
    return back()->withErrors('AK Total bernilai 0, periksa input perpindahan');
}
	if ($akTotal <= 0) {
    return back()->withErrors(
        'AK Total bernilai 0, periksa input ' .
        str_replace('pak_awal_', '', $request->jenis_pak_awal)
    )->withInput();
	}


    /* =====================================================
     * SIMPAN DATA
     * ===================================================== */
    $ak = AngkaKredit::create([
        'pegawai_id'       => $pegawai->id,
        'jabatan'          => $pegawai->jabatan_saat_ini,
        'periode'          => now()->year,
        'pak_awal'         => true,
        'jenis_penilaian'  => strtoupper($request->jenis_pak_awal),
        'masa_kerja_total' => round($masaKerja, 2),
        'predikat_kinerja' => $predikat,
	'ak_penyesuaian'   => $request->jenis_pak_awal === 'pak_awal_penyesuaian'
                            ? round($request->ak_penyesuaian, 2): null,
        'ak_dasar'         => round($akDasar, 2),
        'ak_hasil'         => round($akHasil, 2),
        'ak_total'         => round($akTotal, 2),
        'status'           => 'DRAFT',
    ]);

    PakDocument::create([
        'angka_kredit_id' => $ak->id,
    ]);

    return redirect('/career-map/hitung-ak')
    ->with('success', 'Perhitungan Angka Kredit berhasil disimpan');
}
    return redirect('/career-map/hitung-ak')
    ->withErrors('Tidak ada data perhitungan yang diproses');

}

    /* =====================================================
     * SHOW PAK (HTML)
     * ===================================================== */
    public function show($id)
{
    /* ===============================
       1. AMBIL DATA + RELASI
    =============================== */
    $ak = AngkaKredit::with([
    'pegawai',
    'pakDocument'
	])->findOrFail($id);


    $pegawai = $ak->pegawai;

    /* ===============================
       2. HITUNG AK DARI FORM
    =============================== */
    $ak_jf_lama      = $ak->jfLama->nilai_ak ?? 0;
    $ak_penyesuaian = $ak->penyesuaian->nilai_ak ?? 0;
    $ak_konversi    = $ak->konversi->nilai_ak ?? 0;
    $ak_pendidikan  = $ak->pendidikan->nilai_ak ?? 0;

    $ak_total =
        $ak_jf_lama +
        $ak_penyesuaian +
        $ak_konversi +
        $ak_pendidikan;

    /* ===============================
       3. PANGKAT NAIK
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

    $pangkat_naik = $pangkatMap[$pegawai->golongan ?? ''] ?? '-';

    /* ===============================
       4. JENJANG NAIK
    =============================== */
    $jenjangMap = [
        'Pertama' => 'Muda',
        'Muda'    => 'Madya',
        'Madya'   => 'Utama',
    ];

    $jenjang_naik = $jenjangMap[$pegawai->jenjang_jabatan ?? ''] ?? '-';

    /* ===============================
       5. AK MINIMAL (sementara)
    =============================== */
    $ak_minimal = $ak->ak_minimal ?? 100;

    /* ===============================
       6. DEBUG (OPSIONAL)
    =============================== */
    // dd($ak_jf_lama, $ak_penyesuaian, $ak_konversi, $ak_pendidikan, $ak_total);

    /* ===============================
       7. KIRIM KE VIEW (SATU KALI)
    =============================== */
    return view('pak.show', compact(
        'ak',
        'pegawai',
        'ak_jf_lama',
        'ak_penyesuaian',
        'ak_konversi',
        'ak_pendidikan',
        'ak_total',
        'pangkat_naik',
        'jenjang_naik',
        'ak_minimal'
    ));
}


    /* =====================================================
     * PDF
     * ===================================================== */
    public function downloadPdf($id)
    {
        $ak = AngkaKredit::with('pegawai')->findOrFail($id);

        $pdf = Pdf::loadView('angka_kredit.pdf', compact('ak'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('PAK_'.$ak->pegawai->nama.'.pdf');
    }

    /* =====================================================
     * EDIT
     * ===================================================== */
public function edit($id)
{
    $ak = AngkaKredit::findOrFail($id);

    return view('angka_kredit.edit', compact('ak'));
}
    /* =====================================================
     * UPDATE
     * ===================================================== */
	public function update(Request $request, $id)
	{
    	$ak = AngkaKredit::findOrFail($id);

    	if ($ak->status !== 'Draft') {
        return back()->with('error', 'Data tidak dapat diedit.');
    	}

    	$ak->update([
        'ak_total' => $request->ak_total
    	]);

    	return redirect()
        ->route('ak.index')
        ->with('success', 'Perhitungan AK berhasil diperbarui.');
	}


    /* =====================================================
     * DELETE
     * ===================================================== */
    public function destroy($id)
    {
        $ak = AngkaKredit::findOrFail($id);

        if ($ak->status !== 'DRAFT') {
            return back()->with('error', 'Data tidak dapat dihapus');
        }

        optional($ak->pakDocument)->delete();
        $ak->delete();

        return back()->with('success', 'Perhitungan AK dihapus');
    }

public function kirimUsulan($id)
{
    $ak = AngkaKredit::findOrFail($id);

    // hanya Draft yang bisa dikirim
    if ($ak->status !== 'Draft') {
        return back()->with('error', 'Usulan ini sudah dikirim.');
    }

    // cari data DRAFT yang LEBIH LAMA (created_at < ini)
    $adaDraftLebihLama = AngkaKredit::where('pegawai_id', $ak->pegawai_id)
        ->where('status', 'Draft')
        ->where('created_at', '<', $ak->created_at)
        ->exists();

    if ($adaDraftLebihLama) {
        return back()->with('error',
            'Masih ada perhitungan AK sebelumnya yang belum dikirim. Kirim secara berurutan.'
        );
    }

    // OK → boleh kirim
    $ak->update([
        'status' => 'Menunggu Verifikasi SDMO'
    ]);

    return back()->with('success', 'Usulan AK berhasil dikirim.');
}

}

