<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IkuSdmoTable;

class IkuSdmoController extends Controller
{
    public function index()
    {
        $table = IkuSdmoTable::first();
        $rows = $table?->struktur ?? [];

        return view('menu.iku-sdmo', compact('rows'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'table' => 'required|array'
        ]);

        IkuSdmoTable::updateOrCreate(
            ['nama' => 'IKU SDMO'],
            ['struktur' => $request->table]
        );

        return response()->json(['status' => 'ok']);
    }
}
