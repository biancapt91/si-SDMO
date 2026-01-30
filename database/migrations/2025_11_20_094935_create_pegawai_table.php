<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       Schema::create('pegawai', function (Blueprint $table) {
    $table->id();
    $table->string('nip')->unique();
    $table->string('nama');

    $table->string('jabatan_saat_ini')->nullable();
    $table->string('kelas_jabatan')->nullable();
    $table->string('unit_kerja')->nullable();
    $table->string('unit_kerja_st')->nullable();
    $table->string('status_penempatan')->nullable();
    $table->string('jenis_asn')->nullable();

    $table->string('tempat_lahir')->nullable();
    $table->date('tanggal_lahir')->nullable();

    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
};