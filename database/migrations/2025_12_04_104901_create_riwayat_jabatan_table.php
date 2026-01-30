<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_jabatan', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pegawai_id')->constrained('pegawai');

    $table->string('jabatan');
    $table->string('jenis_jabatan');
    $table->date('tmt_mulai');
    $table->date('tmt_selesai')->nullable();
    $table->string('unit_kerja')->nullable(); // 🔴 INI WAJIB

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_jabatan');
    }
};
