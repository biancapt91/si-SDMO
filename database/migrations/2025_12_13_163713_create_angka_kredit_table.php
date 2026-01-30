<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('angka_kredit', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pegawai_id')->constrained('pegawai');

    $table->boolean('pak_awal')->default(false);
    $table->string('periode');
    $table->string('jenis_penilaian');

    $table->string('bulan_awal')->nullable();
    $table->string('bulan_akhir')->nullable();

    $table->string('predikat_kinerja');
    $table->decimal('koefisien', 5, 2)->default(0);

    $table->decimal('ak_hasil', 8, 2)->default(0);
    $table->decimal('ak_tambahan', 8, 2)->default(0);
    $table->decimal('ak_dasar', 8, 2)->nullable(0);
    $table->decimal('ak_jf_lama', 8, 2)->default(0);
    $table->decimal('ak_total', 8, 2)->default(0);

    $table->string('golongan')->nullable();
    $table->integer('masa_kerja_kepangkatan')->nullable();

            $table->enum('status', [
                'DRAFT',
                'MENUNGGU_SDMO',
                'DITOLAK_SDMO',
                'DIVERIFIKASI_SDMO',
                'MENUNGGU_PPK',
                'DITOLAK_PPK',
                'DISAHKAN_PPK'
            ])->default('DRAFT');

            $table->text('catatan_sdmo')->nullable();
            $table->text('catatan_ppk')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angka_kredit');
    }
};
