<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\KoefisienAk;

class KoefisienAkSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['jenjang'=>'Ahli Pertama','koef_per_tahun'=>12.5],
            ['jenjang'=>'Ahli Muda','koef_per_tahun'=>25],
            ['jenjang'=>'Ahli Madya','koef_per_tahun'=>50],
            ['jenjang'=>'Ahli Utama','koef_per_tahun'=>75],
            ['jenjang'=>'Terampil','koef_per_tahun'=>10],
            ['jenjang'=>'Mahir','koef_per_tahun'=>20],
        ];
        foreach($data as $d) KoefisienAk::updateOrCreate(['jenjang'=>$d['jenjang']], $d);
    }
}
