<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BatasPensiunSeeder extends Seeder
{
    public function run()
    {
        DB::table('ref_batas_pensiun')->truncate();

        DB::table('ref_batas_pensiun')->insert([
            [
		'jenis_jabatan' => 'Pelaksana', 'batas_usia' => 58
	    ],
            [
		'jenis_jabatan' => 'JF Keterampilan', 'batas_usia' => 58
	    ],
	    [
		'jenis_jabatan' => 'JF Ahli Pertama', 'batas_usia' => 58
	    ],
	    [
		'jenis_jabatan' => 'JF Ahli Muda', 'batas_usia' => 58
	    ],
            [
		'jenis_jabatan' => 'JF Ahli Madya', 'batas_usia' => 60
	    ],
	    [
		'jenis_jabatan' => 'JF Ahli Utama', 'batas_usia' => 65
	    ],
	    [
		'jenis_jabatan' => 'Administrator', 'batas_usia' => 58
	    ],
	    [
		'jenis_jabatan' => 'Pengawas', 'batas_usia' => 58
	    ],
	    [
		'jenis_jabatan' => 'JPT Pratama', 'batas_usia' => 60
	    ],
	    ['jenis_jabatan' => 'JPT Madya', 'batas_usia' => 60
 	    ],
        ]);
    }
}
