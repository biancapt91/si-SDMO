<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CascadingKinerja;

class CascadingKinerjaController extends Controller
{
    public function index()
    {
        // Define the 8 sections (key => label)
        $sections = [
            'biro-sdm-organisasi' => 'Biro SDM dan Organisasi',
            'bagian-sdm' => 'Bagian Sumber Daya Manusia',
            'sub-bagian-adm-hakim-pegawai' => 'Sub Bagian Administrasi Hakim dan Pegawai',
            'sub-bagian-pembinaan-pengembangan-non-pns' => 'Sub Bagian Pembinaan & Pengembangan Pegawai Non PNS',
            'sub-bagian-pengembangan-sdm' => 'Sub Bagian Pengembangan SDM',
            'bagian-ortala' => 'Bagian Ortala dan Fasilitasi RB',
            'sub-bagian-organisasi-tata-laksana' => 'Sub Bagian Organisasi dan Tata Laksana',
            'sub-bagian-fasilitasi-rb' => 'Sub Bagian Fasilitasi Reformasi Birokrasi',
        ];

        $items = [];
        foreach ($sections as $key => $label) {
            $item = CascadingKinerja::firstWhere('key', $key);
            if (! $item) {
                $item = CascadingKinerja::create([
                    'key' => $key,
                    'data' => [
                        'headers' => ['Sasaran', 'Indikator', 'Target'],
                        'rows' => [],
                    ],
                ]);
            }
            $items[$key] = $item;
        }

        // prepare rekapTim for the specific sub-section (used in view)
        $rekapTim = [];
        $record = $items['sub-bagian-adm-hakim-pegawai'] ?? null;
        if ($record) {
            $data = $record->data ?? [];
            $counter = [];
            foreach ($data['rows'] ?? [] as $row) {
                if (!isset($row[6])) continue; // kolom Tim Kerja
                $names = array_filter(array_map('trim', preg_split("/\r\n|\r|\n/", $row[6])));
                foreach ($names as $name) {
                    $counter[$name] = ($counter[$name] ?? 0) + 1;
                }
            }
            foreach ($counter as $nama => $jumlah) {
                $rekapTim[] = [
                    'nama' => $nama,
                    'jumlah' => $jumlah
                ];
            }
        }

        return view('menu.cascading-kinerja', ['items' => $items, 'sections' => $sections, 'rekapTim' => $rekapTim]);
    }

    public function update(Request $request)
    {
        // Only admin allowed
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $payload = $request->validate([
            'key' => 'required|string',
            'headers' => 'required|array|min:1',
            'rows' => 'required|array',
        ]);

        $key = $payload['key'];

        // Only allow known section keys
        $allowed = [
            'biro-sdm-organisasi', 'bagian-sdm', 'sub-bagian-adm-hakim-pegawai', 'sub-bagian-pembinaan-pengembangan-non-pns',
            'sub-bagian-pengembangan-sdm', 'bagian-ortala', 'sub-bagian-organisasi-tata-laksana', 'sub-bagian-fasilitasi-rb'
        ];

        if (! in_array($key, $allowed, true)) {
            abort(400, 'Invalid section key');
        }

        $item = CascadingKinerja::firstWhere('key', $key);
        if (! $item) {
            $item = new CascadingKinerja(['key' => $key]);
        }

        $item->data = ['headers' => $payload['headers'], 'rows' => $payload['rows']];
        $item->save();

        return response()->json(['status' => 'ok']);
    }
}
