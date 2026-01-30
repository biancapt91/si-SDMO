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
Schema::create('ref_batas_pensiun', function (Blueprint $table) {
    $table->id('tanggal_lahir');
    $table->string('jenis_jabatan');
    $table->integer('batas_usia');   
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_batas_pensiun');
    }
};
