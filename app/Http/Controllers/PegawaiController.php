<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\DosirPegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplatePegawaiExport;
use App\Imports\PegawaiImport;
use PhpOffice\PhpSpreadsheet\IOFactory;


class PegawaiController extends Controller
{
   public function index(Request $request)
{
    $pegawai = Pegawai::query();

    // SEARCH NAMA
    if ($request->filled('search')) {
        $pegawai->where(function($query) use ($request) {
            $query->where('pegawai.nama', 'like', '%' . $request->search . '%')
                  ->orWhere('pegawai.nip', 'like', '%' . $request->search . '%');
        });
    }

    // FILTER JABATAN
    if ($request->filled('jabatan')) {
        $pegawai->where('pegawai.jabatan_saat_ini', $request->jabatan);
    }

    // FILTER UNIT KERJA
    if ($request->filled('unit_kerja')) {
        $pegawai->where('pegawai.unit_kerja', $request->unit_kerja);
    }

    // FILTER JENIS ASN
    if ($request->filled('jenis_asn')) {
        $pegawai->where('pegawai.jenis_asn', $request->jenis_asn);
    }

    // Join dengan riwayat_jabatan untuk mendapatkan jenis_jabatan terbaru
    $pegawai->leftJoin('riwayat_jabatan', function($join) {
        $join->on('pegawai.id', '=', 'riwayat_jabatan.pegawai_id')
             ->whereRaw('riwayat_jabatan.id = (
                 SELECT id FROM riwayat_jabatan 
                 WHERE pegawai_id = pegawai.id 
                 ORDER BY tmt_mulai DESC 
                 LIMIT 1
             )');
    })
    ->select('pegawai.*', 'riwayat_jabatan.jenis_jabatan');

    // FILTER JENIS JABATAN
    if ($request->filled('jenis_jabatan')) {
        $pegawai->where('riwayat_jabatan.jenis_jabatan', $request->jenis_jabatan);
    }

    // Custom order untuk unit kerja
    $unitOrder = [
        'Mahkamah Konstitusi',
        'Kepaniteraan',
        'Biro Perencanaan dan Keuangan',
        'Biro Sumber Daya Manusia dan Organisasi',
        'Biro Hukum dan Administrasi Kepaniteraan',
        'Biro Hubungan Masyarakat dan Protokol',
        'Biro Umum',
        'Pusat Penelitian dan Pengkajian Perkara, dan Pengelolaan Perpustakaan',
        'Pusat Teknologi Informasi dan Komunikasi',
        'Pusat Pendidikan Pancasila dan Konstitusi',
    ];

    $orderByUnitClause = 'CASE pegawai.unit_kerja ';
    foreach ($unitOrder as $index => $unit) {
        $orderByUnitClause .= "WHEN '" . addslashes($unit) . "' THEN " . $index . " ";
    }
    $orderByUnitClause .= 'ELSE 999 END';

    // Custom order untuk jabatan di Kepaniteraan
    $jabatanKepaniteraanOrder = [
        'Panitera Konstitusi Ahli Utama',
        'Panitera Konstitusi Ahli Madya',
        'Panitera Konstitusi Ahli Muda',
        'Panitera Konstitusi Ahli Pertama',
        'Asisten Ahli Hakim Konstitusi Ahli Utama',
        'Asisten Ahli Hakim Konstitusi Ahli Madya',
        'Asisten Ahli Hakim Konstitusi Ahli Muda',
        'Asisten Ahli Hakim Konstitusi Ahli Pertama',
    ];

    $orderByJabatanClause = 'CASE WHEN pegawai.unit_kerja = \'Kepaniteraan\' THEN CASE pegawai.jabatan_saat_ini ';
    foreach ($jabatanKepaniteraanOrder as $index => $jabatan) {
        $orderByJabatanClause .= "WHEN '" . addslashes($jabatan) . "' THEN " . $index . " ";
    }
    $orderByJabatanClause .= 'ELSE 999 END ELSE 0 END';

    // Custom order untuk jenis jabatan di Biro, Pusat, Inspektorat
    $jenisJabatanOrder = [
        'JPT Pratama',
        'Pengawas',
        'Administrator',
        'JF Ahli Utama',
        'JF Ahli Madya',
        'JF Ahli Muda',
        'JF Ahli Pertama',
        'JF Keterampilan',
        'Pelaksana',
    ];

    $orderByJenisJabatanClause = 'CASE WHEN (pegawai.unit_kerja LIKE \'Biro%\' OR pegawai.unit_kerja LIKE \'Pusat%\' OR pegawai.unit_kerja LIKE \'Inspektorat%\') THEN CASE riwayat_jabatan.jenis_jabatan ';
    foreach ($jenisJabatanOrder as $index => $jenisJabatan) {
        $orderByJenisJabatanClause .= "WHEN '" . addslashes($jenisJabatan) . "' THEN " . $index . " ";
    }
    $orderByJenisJabatanClause .= 'ELSE 999 END ELSE 0 END';

    // Hitung jumlah pegawai berdasarkan jenis jabatan dengan urutan custom
    $jenisJabatanOrder = [
        'JPT Madya', 'JPT Pratama', 'Pengawas', 'Administrator', 'Pelaksana',
        'JF Ahli Utama', 'JF Ahli Madya', 'JF Ahli Muda', 'JF Ahli Pertama', 'JF Keterampilan'
    ];

    $orderClause = 'CASE riwayat_jabatan.jenis_jabatan ';
    foreach ($jenisJabatanOrder as $index => $jenis) {
        $orderClause .= "WHEN '" . addslashes($jenis) . "' THEN " . $index . " ";
    }
    $orderClause .= 'ELSE 999 END';

    $jenisJabatanStats = \DB::table('pegawai')
        ->leftJoin('riwayat_jabatan', function($join) {
            $join->on('pegawai.id', '=', 'riwayat_jabatan.pegawai_id')
                 ->whereRaw('riwayat_jabatan.id = (
                     SELECT id FROM riwayat_jabatan 
                     WHERE pegawai_id = pegawai.id 
                     ORDER BY tmt_mulai DESC 
                     LIMIT 1
                 )');
        })
        ->select('riwayat_jabatan.jenis_jabatan', \DB::raw('COUNT(*) as total'))
        ->whereNotNull('riwayat_jabatan.jenis_jabatan')
        ->groupBy('riwayat_jabatan.jenis_jabatan')
        ->orderByRaw($orderClause)
        ->get();

    return view('pegawai.index', [
        'pegawai'     => $pegawai
            ->orderByRaw($orderByUnitClause)
            ->orderByRaw($orderByJabatanClause)
            ->orderByRaw($orderByJenisJabatanClause)
            ->orderBy('pegawai.nama')
            ->paginate(30)
            ->withQueryString(),
        'listJabatan' => Pegawai::select('jabatan_saat_ini')->distinct()->pluck('jabatan_saat_ini'),
        'listUnit'    => Pegawai::select('unit_kerja')->distinct()->pluck('unit_kerja'),
        'listJenis'   => Pegawai::select('jenis_asn')->distinct()->pluck('jenis_asn'),
        'listJenisJabatan' => \DB::table('riwayat_jabatan')->select('jenis_jabatan')->distinct()->whereNotNull('jenis_jabatan')->orderBy('jenis_jabatan')->pluck('jenis_jabatan'),
        'jenisJabatanStats' => $jenisJabatanStats,
    ]);
}


public function dosir($id)
{
    $pegawai = Pegawai::with('dosir')->findOrFail($id);

    return view('pegawai.dosir', compact('pegawai'));
}

    public function profil()
    {
        // Get logged-in user's pegawai record by NIP
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Find pegawai by NIP
        $pegawai = Pegawai::where('nip', $user->nip)->with(['riwayatJabatan'])->first();

        if (!$pegawai) {
            return view('pegawai.profil-not-found');
        }

        // Show the detail page for logged-in user's pegawai record
        return view('pegawai.show', compact('pegawai'));
    }

    /**
     * Suggest pegawai by name or nip for login/autocomplete
     */
    public function suggest(\Illuminate\Http\Request $request)
    {
        $q = (string) $request->get('q', '');
        if ($q === '') {
            return response()->json([]);
        }

        $matches = Pegawai::query()
            ->where('nama', 'like', "%{$q}%")
            ->orWhere('nip', 'like', "%{$q}%")
            ->orderBy('nama')
            ->limit(15)
            ->get(['id', 'nip', 'nama', 'jabatan_saat_ini']);

        return response()->json($matches);
    }
    
	public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'  => 'required|unique:pegawai,nip',
            'nama' => 'required',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        ]);

        $pegawai = Pegawai::create([
            'nip'             => $request->nip,
            'nama'            => $request->nama,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'unit_kerja'     => $request->unit_kerja,
	    'unit_kerja_st'   => $request->unit_kerja_st,
	    'penempatan'      => $request->penempatan,
	    'status_penempatan'=> $request->status_penempatan,
	    'jenis_asn'       => $request->jenis_asn,
	    'tempat_lahir'    => $request->tempat_lahir,
            'tanggal_lahir'   => $request->tanggal_lahir,
        ]);

        // Calculate retirement date if job history exists
        $pegawai->updateRetirementDate();

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

   public function show($id)
{
    $pegawai = Pegawai::with(['riwayatJabatan'])->findOrFail($id);

    return view('pegawai.show', compact('pegawai'));
}

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
{
    $pegawai = Pegawai::findOrFail($id);

    $request->validate([
        'nip'  => 'required|unique:pegawai,nip,' . $pegawai->id,
        'nama' => 'required',
        'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
    ]);

    // ---- 1. Ambil Riwayat Jabatan Terbaru ----
    $riwayatTerbaru = \App\Models\RiwayatJabatan::where('pegawai_id', $pegawai->id)
        ->orderBy('tmt_mulai', 'desc')
        ->first();

    $jenisJabatanAktif = $riwayatTerbaru ? $riwayatTerbaru->jenis_jabatan : null;

    // ---- 2. Ambil batas usia pensiun dari tabel referensi ----
    $batasUsia = $jenisJabatanAktif
        ? \DB::table('ref_batas_pensiun')
            ->where('jenis_jabatan', $jenisJabatanAktif)
            ->value('batas_usia')
        : null;

    // ---- 3. Hitung ulang tanggal pensiun ----
    if ($request->tanggal_lahir && $batasUsia) {
        $tanggalPensiun = Carbon::parse($request->tanggal_lahir)
                                ->addYears($batasUsia);
    } else {
        $tanggalPensiun = $pegawai->tanggal_pensiun;
    }

    // ---- 4. Jika user memilih "Tidak Ada ST" ----
    $unitKerjaSt = ($request->unit_kerja_st == 'NONE') ? null : $request->unit_kerja_st;

    // ---- 5. Update Pegawai ----
    $pegawai->update([
        'nip'               => $request->nip,
        'nama'              => $request->nama,        'jenis_kelamin'     => $request->jenis_kelamin,	'pangkat_golongan'  	    => $request->pangkat_golongan,
        'jabatan_saat_ini'  => $request->jabatan_saat_ini,
        'kelas_jabatan'     => $request->kelas_jabatan,
        'unit_kerja'        => $request->unit_kerja,
        'unit_kerja_st'     => $unitKerjaSt,
        'penempatan'        => $request->penempatan,
        'status_penempatan' => $request->status_penempatan,
        'jenis_asn'         => $request->jenis_asn,
        'tanggal_lahir'     => $request->tanggal_lahir,
        'tanggal_pensiun'   => $tanggalPensiun,
    ]);

    return redirect()->route('pegawai.index')
        ->with('success', 'Data pegawai berhasil diperbarui.');
}

public function uploadDosir(Request $request, $id)
{
    $request->validate([
        'nama_dokumen' => 'required|string',
        'file' => 'required|mimes:pdf,jpg,png,doc,docx|max:20480'
    ]);

    $path = $request->file('file')->store('dosir');

    DosirPegawai::create([
        'pegawai_id' => $id,
        'nama_dokumen' => $request->nama_dokumen,
        'file_path' => $path
    ]);

    return back()->with('success', 'Dokumen berhasil diupload');
}

public function downloadDosir($id)
{
    $file = DosirPegawai::findOrFail($id);

    return response()->download(storage_path('app/' . $file->file_path));
}

public function deleteDosir($id)
{
    $file = DosirPegawai::findOrFail($id);

    \Storage::delete($file->file_path);
    $file->delete();

    return back()->with('success', 'Dokumen berhasil dihapus');
}

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function downloadTemplateExcel()
    {
        return Excel::download(
            new TemplatePegawaiExport(), 
            'template_import_pegawai_sdmo.xlsx'
        );
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $originalName = $file->getClientOriginalName();
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $rows = [];
        $loadError = null;

        // 1) CSV fallback
        if ($ext === 'csv') {
            if (($handle = fopen($filePath, 'r')) !== false) {
                $headers = fgetcsv($handle);
                if ($headers === false) {
                    return redirect()->back()->withErrors(['File CSV kosong atau tidak valid']);
                }
                $headers = array_map(function ($h) {
                    return str_replace(' ', '_', strtolower(trim($h)));
                }, $headers);

                while (($data = fgetcsv($handle)) !== false) {
                    if (count($data) === 0) continue;
                    if (count($data) < count($headers)) {
                        $data = array_pad($data, count($headers), null);
                    }
                    $rows[] = array_combine($headers, $data);
                }
                fclose($handle);
            }
        }

        // 2) Use PhpSpreadsheet directly (Maatwebsite Excel 3.x uses this internally)
        if (empty($rows) && in_array($ext, ['xlsx', 'xls'])) {
            try {
                $spreadsheet = IOFactory::load($filePath);
                $sheet = $spreadsheet->getActiveSheet();
                $all = $sheet->toArray(null, true, true, false);
                
                if (count($all) >= 1) {
                    $headers = array_map(function ($h) {
                        return str_replace(' ', '_', strtolower(trim((string) $h)));
                    }, $all[0]);

                    for ($i = 1; $i < count($all); $i++) {
                        $row = $all[$i];
                        if (count($row) < count($headers)) {
                            $row = array_pad($row, count($headers), null);
                        }
                        $rows[] = array_combine($headers, $row);
                    }
                }
            } catch (\Throwable $e) {
                $loadError = $e->getMessage();
                $rows = [];
            }
        }

        if (empty($rows)) {
            $msg = 'File tidak dapat dibaca atau format tidak didukung. Silakan gunakan CSV atau pastikan ekstensi PHPExcel/maatwebsite terpasang.';
            if ($loadError) {
                $msg .= ' Detail: ' . $loadError;
                \Log::warning('Pegawai import load error: ' . $loadError);
            }
            return redirect()->back()->withErrors([$msg]);
        }

        $importer = new \App\Imports\PegawaiImport();
        $errors = [];
        $line = 1; // header

        foreach ($rows as $row) {
            $line++;
            // normalize keys
            $data = [];
            foreach ($row as $k => $v) {
                $kk = str_replace(' ', '_', strtolower(trim((string) $k)));
                $data[$kk] = $v;
            }

            $validator = \Validator::make($data, $importer->rules(), $importer->customValidationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $msg) {
                    $errors[] = "Baris {$line}: {$msg}";
                }
                continue;
            }

            try {
                $importer->importRow($data);
            } catch (\Throwable $e) {
                $errors[] = "Baris {$line}: {$e->getMessage()}";
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors);
        }

        return redirect()->back()
            ->with('success', 'Data pegawai berhasil di-import');
    }

    public function exportExcel()
    {
        $pegawai = Pegawai::orderBy('nama')->get();
        
        // Create XML for Excel 2003 format (.xls)
        $filename = 'Daftar_Pegawai_' . date('Y-m-d_His') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($pegawai) {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
            echo 'xmlns:x="urn:schemas-microsoft-com:office:excel" ';
            echo 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" ';
            echo 'xmlns:html="http://www.w3.org/TR/REC-html40">';
            echo '<Worksheet ss:Name="Daftar Pegawai">';
            echo '<Table>';
            
            // Header row
            echo '<Row>';
            $headers = ['NIP', 'Nama', 'Pangkat/Golongan', 'Jabatan', 'Unit Kerja', 'Jenis ASN', 'Status Penempatan'];
            foreach ($headers as $header) {
                echo '<Cell><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>';
            }
            echo '</Row>';
            
            // Data rows
            foreach ($pegawai as $p) {
                echo '<Row>';
                $data = [
                    $p->nip,
                    $p->nama,
                    $p->pangkat_golongan ?? '-',
                    $p->jabatan_saat_ini ?? '-',
                    $p->unit_kerja ?? '-',
                    $p->jenis_asn ?? '-',
                    $p->status_penempatan ?? '-',
                ];
                
                foreach ($data as $cell) {
                    echo '<Cell><Data ss:Type="String">' . htmlspecialchars($cell) . '</Data></Cell>';
                }
                echo '</Row>';
            }
            
            echo '</Table>';
            echo '</Worksheet>';
            echo '</Workbook>';
        };
        
        return response()->stream($callback, 200, $headers);
    }

}
