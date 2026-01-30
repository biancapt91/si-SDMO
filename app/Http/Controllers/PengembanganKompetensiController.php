<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengembanganKompetensiController extends Controller
{
    /**
     * INDEX
     * Halaman utama Pengembangan Kompetensi
     */
    public function index()
    {
        return view('pengembangan-kompetensi.index');
    }

    /**
     * Standar Kompetensi
     */
    public function standar()
    {
        return view('pengembangan-kompetensi.standar');
    }

    /**
     * Capaian Kompetensi
     */
    public function capaian()
    {
        return view('pengembangan-kompetensi.capaian');
    }

    /**
     * Gap Kompetensi
     */
    public function gap()
    {
        return view('pengembangan-kompetensi.gap');
    }

    /**
     * Rencana Pengembangan Kompetensi
     */
    public function rencana()
    {
        return view('pengembangan-kompetensi.rencana');
    }
}
