<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('dosir_pegawai', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('pegawai_id');
        $table->string('nama_dokumen');
        $table->string('file_path');
        $table->timestamps();

        $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosir_pegawai');
    }
};
