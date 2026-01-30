<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\KebutuhanAk;

class KebutuhanAkSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // contoh ringkas dari Pasal 21 (lengkapi sesuai dokumen resmi)
            ['kategori'=>'keahlian','dari'=>'Ahli Pertama','ke'=>'Ahli Muda','angka_kredit'=>100,'is_kumulatif'=>true],
            ['kategori'=>'keahlian','dari'=>'Ahli Muda','ke'=>'Ahli Madya','angka_kredit'=>200,'is_kumulatif'=>true],
            ['kategori'=>'keahlian','dari'=>'Ahli Madya','ke'=>'Ahli Utama','angka_kredit'=>450,'is_kumulatif'=>true],
            ['kategori'=>'keterampilan','dari'=>'II/a','ke'=>'II/b','angka_kredit'=>15,'is_kumulatif'=>false],
            ['kategori'=>'keterampilan','dari'=>'II/b','ke'=>'II/c','angka_kredit'=>20,'is_kumulatif'=>false],
            ['kategori'=>'keterampilan','dari'=>'III/a','ke'=>'III/b','angka_kredit'=>50,'is_kumulatif'=>false],
        ];
        foreach($items as $i) KebutuhanAk::updateOrCreate(['kategori'=>$i['kategori'],'dari'=>$i['dari'],'ke'=>$i['ke']], $i);
    }
}
