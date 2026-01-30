<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\AngkaKredit;
use App\Services\AngkaKreditService;

class CareerMapController extends Controller
{
    public function index()
    {
        // Cek apakah user adalah admin
        if (auth()->user()->isAdmin()) {
            // Jika admin, redirect ke halaman rekap
            return redirect()->route('career-map.rekap');
        }

        // Ambil data pegawai berdasarkan NIP user yang login
        $pegawai = Pegawai::with([
            'riwayatJabatan',
            'akKumulatif',
            'predikatKinerja',
            'ujiKompetensi',
            'angkaKredits'
        ])->where('nip', auth()->user()->nip)->first();

        // Jika pegawai tidak ditemukan
        if (!$pegawai) {
            return redirect()->route('dashboard')->with('error', 'Data pegawai tidak ditemukan');
        }

        // Ambil riwayat jabatan yang sedang diemban (TMT Selesai NULL)
        $jabatanSekarang = $pegawai->riwayatJabatan()->whereNull('tmt_selesai')->first();
        $pegawai->jenis_jabatan_aktif = $jabatanSekarang->jenis_jabatan ?? '-';
        $pegawai->tmt_mulai_aktif = $jabatanSekarang->tmt_mulai ?? null;

        // Cek apakah jabatan struktural
        $jenisJabatan = $pegawai->jenis_jabatan_aktif;
        $isStruktural = in_array($jenisJabatan, ['JPT Madya', 'JPT Pratama', 'Administrator', 'Pengawas', 'Pelaksana']);

        if ($isStruktural) {
            // Untuk jabatan struktural, kolom AK tidak digunakan
            $pegawai->ak_saat_ini = '-';
            $pegawai->ak_kebutuhan_pangkat = '-';
            $pegawai->ak_kebutuhan_jenjang = '-';
            
            // Estimasi kenaikan pangkat = TMT Pangkat + 4 tahun
            if ($pegawai->tmt_pangkat) {
                try {
                    $tmtPangkat = \Carbon\Carbon::parse($pegawai->tmt_pangkat);
                    $pegawai->estimasi_pangkat = $tmtPangkat->addYears(4)->format('Y-m-d');
                } catch (\Exception $e) {
                    $pegawai->estimasi_pangkat = '-';
                }
            } else {
                $pegawai->estimasi_pangkat = '-';
            }
            
            $pegawai->estimasi_jenjang = '-';
            
            // Keterangan untuk struktural berdasarkan estimasi pangkat
            if ($pegawai->estimasi_pangkat === '-') {
                $pegawai->keterangan = '-';
                $pegawai->ket_color = 'secondary';
            } else {
                try {
                    $estimasiDate = \Carbon\Carbon::parse($pegawai->estimasi_pangkat);
                    $today = \Carbon\Carbon::today();
                    
                    if ($today->gte($estimasiDate)) {
                        $pegawai->keterangan = 'Memenuhi Syarat Naik Pangkat';
                        $pegawai->ket_color = 'green';
                    } else {
                        $pegawai->keterangan = 'Belum memenuhi syarat naik pangkat';
                        $pegawai->ket_color = 'red';
                    }
                } catch (\Exception $e) {
                    $pegawai->keterangan = '-';
                    $pegawai->ket_color = 'secondary';
                }
            }
        } else {
            // Untuk jabatan fungsional (JF), pakai logika AK
            // AK saat ini - hitung dari SUM ak_total di tabel angka_kredit
            $pegawai->ak_saat_ini = $pegawai->angkaKredits()->sum('ak_total') ?? 0;

            // Kebutuhan
            $pang = AngkaKreditService::akNeededForPangkat($pegawai);
            $jenj = AngkaKreditService::akNeededForJenjang($pegawai);

            $pegawai->ak_kebutuhan_pangkat = is_array($pang) ? ($pang['needed'] ?? null) : $pang;
            $pegawai->ak_kebutuhan_jenjang = is_array($jenj) ? ($jenj['needed'] ?? null) : $jenj;

            // Estimasi - hitung berdasarkan kebutuhan AK
            $akSaatIni = $pegawai->ak_saat_ini;
            
            // Estimasi Pangkat
            if ($pang == 0 || $pegawai->ak_kebutuhan_pangkat === null) {
                $pegawai->estimasi_pangkat = 0;
            } else {
                $akKurang = $pegawai->ak_kebutuhan_pangkat - $akSaatIni;
                if ($akKurang <= 0) {
                    $pegawai->estimasi_pangkat = 0;
                } else {
                    // Hitung rata-rata AK per bulan dari predikat kinerja
                    $akPerBulan = $this->hitungAkPerBulan($pegawai);
                    if ($akPerBulan > 0) {
                        $bulanDibutuhkan = ceil($akKurang / $akPerBulan);
                        $estimasiDate = \Carbon\Carbon::now()->addMonths($bulanDibutuhkan)->startOfMonth()->addMonth();
                        $pegawai->estimasi_pangkat = $estimasiDate->format('Y-m-d');
                    } else {
                        $pegawai->estimasi_pangkat = '-';
                    }
                }
            }
            
            // Estimasi Jenjang
            if ($jenj == 0 || $pegawai->ak_kebutuhan_jenjang === null) {
                $pegawai->estimasi_jenjang = 0;
            } else {
                $akKurang = $pegawai->ak_kebutuhan_jenjang - $akSaatIni;
                if ($akKurang <= 0) {
                    $pegawai->estimasi_jenjang = 0;
                } else {
                    // Hitung rata-rata AK per bulan dari predikat kinerja
                    $akPerBulan = $this->hitungAkPerBulan($pegawai);
                    if ($akPerBulan > 0) {
                        $bulanDibutuhkan = ceil($akKurang / $akPerBulan);
                        $estimasiDate = \Carbon\Carbon::now()->addMonths($bulanDibutuhkan)->startOfMonth()->addMonth();
                        $pegawai->estimasi_jenjang = $estimasiDate->format('Y-m-d');
                    } else {
                        $pegawai->estimasi_jenjang = '-';
                    }
                }
            }

            // Keterangan
            $memenuhiPangkat = $pegawai->ak_kebutuhan_pangkat !== null && $akSaatIni >= $pegawai->ak_kebutuhan_pangkat;
            $memenuhiJenjang = $pegawai->ak_kebutuhan_jenjang !== null && $akSaatIni >= $pegawai->ak_kebutuhan_jenjang;

            if ($memenuhiPangkat && $memenuhiJenjang) {
                $pegawai->keterangan = 'Memenuhi Syarat Naik Pangkat dan Jenjang';
                $pegawai->ket_color = 'green';
            } elseif ($memenuhiPangkat) {
                $pegawai->keterangan = 'Memenuhi Syarat Naik Pangkat';
                $pegawai->ket_color = 'green';
            } elseif ($memenuhiJenjang) {
                $pegawai->keterangan = 'Memenuhi Syarat Naik Jenjang';
                $pegawai->ket_color = 'green';
            } else {
                $pegawai->keterangan = 'Belum memenuhi syarat';
                $pegawai->ket_color = 'red';
            }
        }

        // Jenjang (extract from jabatan string)
        $pegawai->jenjang_extracted = AngkaKreditService::extractJenjangFromJabatan($pegawai->jabatan_saat_ini ?? '');

        // Ambil riwayat perhitungan AK pegawai
        $riwayatAK = $pegawai->angkaKredits()
            ->orderByDesc('created_at')
            ->get();

        return view('career-map.index', compact('pegawai', 'riwayatAK'));
    }

    public function rekap(Request $request)
    {
        // Query pegawai dengan relasi yang diperlukan
        $query = Pegawai::with([
            'riwayatJabatan',
            'akKumulatif',
            'predikatKinerja',
            'ujiKompetensi',
            'angkaKredits'
        ]);

        // Filter berdasarkan nama jika ada pencarian
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Ambil semua data tanpa pagination dulu
        $allPegawais = $query->get();

        // Hitung fields tambahan untuk semua pegawai
        foreach ($allPegawais as $p) {
            // Ambil riwayat jabatan aktif
            $jabatanSekarang = $p->riwayatJabatan->where('tmt_selesai', null)->first();
            $jenisJabatan = $jabatanSekarang->jenis_jabatan ?? null;
            
            // Cek apakah jabatan struktural
            $isStruktural = in_array($jenisJabatan, ['JPT Madya', 'JPT Pratama', 'Administrator', 'Pengawas', 'Pelaksana']);

            if ($isStruktural) {
                // Untuk jabatan struktural, kolom AK tidak digunakan
                $p->ak_saat_ini = '-';
                $p->ak_kebutuhan_pangkat = '-';
                $p->ak_kebutuhan_jenjang = '-';
                
                // Estimasi kenaikan pangkat = TMT Pangkat + 4 tahun
                if ($p->tmt_pangkat) {
                    try {
                        $tmtPangkat = \Carbon\Carbon::parse($p->tmt_pangkat);
                        $p->estimasi_pangkat = $tmtPangkat->addYears(4)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $p->estimasi_pangkat = '-';
                    }
                } else {
                    $p->estimasi_pangkat = '-';
                }
                
                $p->estimasi_jenjang = '-';
                
                // Keterangan untuk struktural berdasarkan estimasi pangkat
                if ($p->estimasi_pangkat === '-') {
                    $p->keterangan = '-';
                    $p->ket_color = 'secondary';
                } else {
                    try {
                        $estimasiDate = \Carbon\Carbon::parse($p->estimasi_pangkat);
                        $today = \Carbon\Carbon::today();
                        
                        if ($today->gte($estimasiDate)) {
                            $p->keterangan = 'Memenuhi Syarat Naik Pangkat';
                            $p->ket_color = 'green';
                        } else {
                            $p->keterangan = 'Belum memenuhi syarat naik pangkat';
                            $p->ket_color = 'red';
                        }
                    } catch (\Exception $e) {
                        $p->keterangan = '-';
                        $p->ket_color = 'secondary';
                    }
                }
            } else {
                // Untuk jabatan fungsional (JF), pakai logika AK
                // ak saat ini - hitung dari SUM ak_total di tabel angka_kredit
                $p->ak_saat_ini = $p->angkaKredits()->sum('ak_total') ?? 0;

                // kebutuhan
                $pang = AngkaKreditService::akNeededForPangkat($p);
                $jenj = AngkaKreditService::akNeededForJenjang($p);

                $p->ak_kebutuhan_pangkat = is_array($pang) ? ($pang['needed'] ?? null) : $pang;
                $p->ak_kebutuhan_jenjang = is_array($jenj) ? ($jenj['needed'] ?? null) : $jenj;

                // estimasi - hitung berdasarkan kebutuhan AK
                $akSaatIni = $p->ak_saat_ini;
                
                // Estimasi Pangkat
                if ($pang == 0 || $p->ak_kebutuhan_pangkat === null) {
                    $p->estimasi_pangkat = 0;
                } else {
                    $akKurang = $p->ak_kebutuhan_pangkat - $akSaatIni;
                    if ($akKurang <= 0) {
                        $p->estimasi_pangkat = 0;
                    } else {
                        // Hitung rata-rata AK per bulan dari predikat kinerja
                        $akPerBulan = $this->hitungAkPerBulan($p);
                        if ($akPerBulan > 0) {
                            $bulanDibutuhkan = ceil($akKurang / $akPerBulan);
                            $estimasiDate = \Carbon\Carbon::now()->addMonths($bulanDibutuhkan)->startOfMonth()->addMonth();
                            $p->estimasi_pangkat = $estimasiDate->format('Y-m-d');
                        } else {
                            $p->estimasi_pangkat = '-';
                        }
                    }
                }
                
                // Estimasi Jenjang
                if ($jenj == 0 || $p->ak_kebutuhan_jenjang === null) {
                    $p->estimasi_jenjang = 0;
                } else {
                    $akKurang = $p->ak_kebutuhan_jenjang - $akSaatIni;
                    if ($akKurang <= 0) {
                        $p->estimasi_jenjang = 0;
                    } else {
                        // Hitung rata-rata AK per bulan dari predikat kinerja
                        $akPerBulan = $this->hitungAkPerBulan($p);
                        if ($akPerBulan > 0) {
                            $bulanDibutuhkan = ceil($akKurang / $akPerBulan);
                            $estimasiDate = \Carbon\Carbon::now()->addMonths($bulanDibutuhkan)->startOfMonth()->addMonth();
                            $p->estimasi_jenjang = $estimasiDate->format('Y-m-d');
                        } else {
                            $p->estimasi_jenjang = '-';
                        }
                    }
                }

                // keterangan
                $memenuhiPangkat = $p->ak_kebutuhan_pangkat !== null && $akSaatIni >= $p->ak_kebutuhan_pangkat;
                $memenuhiJenjang = $p->ak_kebutuhan_jenjang !== null && $akSaatIni >= $p->ak_kebutuhan_jenjang;

                if ($memenuhiPangkat && $memenuhiJenjang) {
                    $p->keterangan = 'Memenuhi Syarat Naik Pangkat dan Jenjang';
                    $p->ket_color = 'green';
                } elseif ($memenuhiPangkat) {
                    $p->keterangan = 'Memenuhi Syarat Naik Pangkat';
                    $p->ket_color = 'green';
                } elseif ($memenuhiJenjang) {
                    $p->keterangan = 'Memenuhi Syarat Naik Jenjang';
                    $p->ket_color = 'green';
                } else {
                    $p->keterangan = 'Belum memenuhi syarat';
                    $p->ket_color = 'red';
                }
            }

            // jenjang (extract from jabatan string)
            $p->jenjang_extracted = AngkaKreditService::extractJenjangFromJabatan($p->jabatan_saat_ini ?? '');
        }

        // Urutkan: yang memenuhi syarat (green) di atas, lalu yang belum (red), lalu yang lain
        $sortedPegawais = $allPegawais->sortBy(function($p) {
            if ($p->ket_color == 'green') {
                return 0; // Memenuhi syarat - paling atas
            } elseif ($p->ket_color == 'red') {
                return 1; // Belum memenuhi syarat - tengah
            } else {
                return 2; // Lainnya (-) - paling bawah
            }
        })->values();

        // Hitung statistik
        $totalPegawai = $sortedPegawais->count();
        $memenuhiSyarat = $sortedPegawais->filter(function($p) {
            return $p->ket_color == 'green';
        })->count();
        $belumMemenuhi = $sortedPegawais->filter(function($p) {
            return $p->ket_color == 'red';
        })->count();

        // Manual pagination
        $page = $request->get('page', 1);
        $perPage = 30;
        $offset = ($page - 1) * $perPage;
        
        $paginatedItems = $sortedPegawais->slice($offset, $perPage)->values();
        
        $pegawais = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $totalPegawai,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('career-map.rekap', compact('pegawais', 'totalPegawai', 'memenuhiSyarat', 'belumMemenuhi'));
    }

    public function editAk($pegawaiId)
    {
        $pegawai = Pegawai::with(['angkaKredits' => function($query) {
            $query->orderByDesc('created_at');
        }])->findOrFail($pegawaiId);

        return view('career-map.edit-ak', compact('pegawai'));
    }

    public function storeAk(Request $request, $pegawaiId)
    {
        $validated = $request->validate([
            'tanggal_penetapan' => 'required|date',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
            'jabatan' => 'required|string',
            'jenis_penilaian' => 'required|string',
            'predikat_kinerja' => 'nullable|numeric',
            'koefisien' => 'nullable|numeric',
            'ak_dasar' => 'nullable|numeric',
            'ak_total' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $validated['pegawai_id'] = $pegawaiId;
        $validated['status'] = 'draft'; // Default status

        AngkaKredit::create($validated);

        return redirect()
            ->route('career-map.edit-ak', $pegawaiId)
            ->with('success', 'Riwayat AK berhasil ditambahkan');
    }

    public function updateAk(Request $request, $angkaKreditId)
    {
        $validated = $request->validate([
            'tanggal_penetapan' => 'required|date',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
            'jabatan' => 'required|string',
            'jenis_penilaian' => 'required|string',
            'predikat_kinerja' => 'nullable|numeric',
            'koefisien' => 'nullable|numeric',
            'ak_dasar' => 'nullable|numeric',
            'ak_total' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $angkaKredit = AngkaKredit::findOrFail($angkaKreditId);
        $angkaKredit->update($validated);

        return redirect()
            ->route('career-map.edit-ak', $angkaKredit->pegawai_id)
            ->with('success', 'Riwayat AK berhasil diupdate');
    }

    public function deleteAk($angkaKreditId)
    {
        $angkaKredit = AngkaKredit::findOrFail($angkaKreditId);
        $pegawaiId = $angkaKredit->pegawai_id;
        $angkaKredit->delete();

        return redirect()
            ->route('career-map.edit-ak', $pegawaiId)
            ->with('success', 'Riwayat AK berhasil dihapus');
    }

    /**
     * Hitung rata-rata AK per bulan berdasarkan predikat kinerja pegawai
     */
    private function hitungAkPerBulan($pegawai)
    {
        // Ambil predikat kinerja terbaru
        $predikatKinerja = $pegawai->predikatKinerja()->latest()->first();
        
        if (!$predikatKinerja || !$predikatKinerja->nilai) {
            // Jika tidak ada predikat kinerja, coba hitung dari history AK
            $totalAk = $pegawai->angkaKredits()->sum('ak_total');
            $firstAk = $pegawai->angkaKredits()->oldest()->first();
            
            if ($firstAk && $totalAk > 0) {
                $bulanBekerja = \Carbon\Carbon::parse($firstAk->periode_awal)->diffInMonths(\Carbon\Carbon::now());
                if ($bulanBekerja > 0) {
                    return $totalAk / $bulanBekerja;
                }
            }
            
            // Default: asumsi predikat Baik = 25 AK per tahun / 12 bulan
            return 25 / 12; // ~2.08 AK per bulan
        }
        
        // Konversi nilai predikat kinerja menjadi AK per tahun, lalu per bulan
        // Asumsi: Sangat Baik=30, Baik=25, Cukup=20, Kurang=15, Sangat Kurang=10
        $nilaiPredikat = $predikatKinerja->nilai;
        $akPerTahun = 25; // default Baik
        
        if ($nilaiPredikat >= 90) {
            $akPerTahun = 30; // Sangat Baik
        } elseif ($nilaiPredikat >= 76) {
            $akPerTahun = 25; // Baik
        } elseif ($nilaiPredikat >= 61) {
            $akPerTahun = 20; // Cukup
        } elseif ($nilaiPredikat >= 51) {
            $akPerTahun = 15; // Kurang
        } else {
            $akPerTahun = 10; // Sangat Kurang
        }
        
        return $akPerTahun / 12; // Konversi ke per bulan
    }
}
