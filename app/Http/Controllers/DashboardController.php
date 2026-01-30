<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\RiwayatJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pegawai
        $totalPegawai = Pegawai::count();

        // Total pegawai ST (Surat Tugas)
        $totalST = Pegawai::whereNotNull('unit_kerja_st')->count();

        // Jenis ASN
        $jumlahASN = Pegawai::select('jenis_asn')
            ->groupBy('jenis_asn')
            ->selectRaw('jenis_asn, COUNT(*) as total')
            ->pluck('total', 'jenis_asn')
            ->toArray();

        // Grafik unit kerja SK
        $chartUnitKerja = Pegawai::select('unit_kerja')
            ->whereNotNull('unit_kerja')
            ->groupBy('unit_kerja')
            ->selectRaw('unit_kerja, COUNT(*) as total')
            ->get();

        // Grafik unit kerja ST
        $chartST = Pegawai::select('unit_kerja_st')
            ->whereNotNull('unit_kerja_st')
            ->groupBy('unit_kerja_st')
            ->selectRaw('unit_kerja_st, COUNT(*) as total')
            ->get();

	// Berdasarkan Jabatan
	$chartJabatan = Pegawai::select('jabatan_saat_ini', DB::raw('COUNT(*) as total'))
            ->groupBy('jabatan_saat_ini')
            ->orderBy('jabatan_saat_ini')
            ->get();

	// Berdasarkan Jenis Jabatan
	$chartJenisJabatan = RiwayatJabatan::select('jenis_jabatan', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_jabatan')
            ->orderBy('jenis_jabatan')
            ->get();

        // --- Pegawai Akan Pensiun ≤ 1 Tahun ---
        $today     = Carbon::today();
        $nextYear  = Carbon::today()->addYear();

        $pensiunSoon = Pegawai::whereBetween('tanggal_pensiun', [$today, $nextYear])
    ->orderBy('tanggal_pensiun')
    ->get()
    ->map(function ($p) {

        $today   = Carbon::today();
        $pensiun = Carbon::parse($p->tanggal_pensiun);

        // Untuk warning color
        $p->diff = $today->diffInMonths($pensiun, false);

        // Jika sudah lewat
        if ($pensiun->isPast()) {
            $p->sisa_waktu = "Sudah pensiun";
            return $p;
        }

        // Hitung selisih bulan total
        $diffInMonths = $today->diffInMonths($pensiun);

        // Hitung tahun & bulan
        $p->sisa_tahun = floor($diffInMonths / 12);
        $p->sisa_bulan = $diffInMonths % 12;

	$afterMonths = $today->copy()
            	   ->addYears($p->sisa_tahun)
           	        ->addMonths($p->sisa_bulan);

	$p->sisa_hari = $afterMonths->diffInDays($pensiun);

        // Gabungkan teks
        $p->sisa_waktu = "{$p->sisa_tahun} tahun {$p->sisa_bulan} bulan {$p->sisa_hari} hari lagi";

        return $p;
    });      

        return view('dashboard.index_modern', compact(
            'totalPegawai',
            'totalST',
            'jumlahASN',
            'chartUnitKerja',
            'chartST',
            'pensiunSoon',
	    'chartJabatan',
            	          'chartJenisJabatan'
        ));
    }
}