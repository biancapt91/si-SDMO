<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('cascading_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('data')->nullable();
            $table->timestamps();
        });

        // seed a default entry for 'biro-sdm-organisasi'
        DB::table('cascading_kinerjas')->insert([
            'key' => 'biro-sdm-organisasi',
            'data' => json_encode([
                'headers' => ['Sasaran', 'Indikator', 'Target'],
                'rows' => [
                    ['Meningkatkan kapasitas SDM', 'Jumlah pelatihan per tahun', '6 kali'],
                    ['Peningkatan kepuasan pegawai', 'Skor survei kepuasan', '>=80%'],
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cascading_kinerjas');
    }
};
