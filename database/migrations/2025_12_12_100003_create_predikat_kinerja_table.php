<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('predikat_kinerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->string('predikat'); // Baik, Sangat Baik, dll
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->timestamps();

            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('predikat_kinerja');
    }
};
