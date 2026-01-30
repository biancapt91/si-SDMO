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
        Schema::create('ak_penyesuaian', function (Blueprint $table) {
    $table->id();
    $table->string('golongan');
    $table->string('pendidikan');
    $table->integer('kurang_1_tahun');
    $table->integer('tahun_1');
    $table->integer('tahun_2');
    $table->integer('tahun_3');
    $table->integer('tahun_4_lebih');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ak_penyesuaian');
    }
};
