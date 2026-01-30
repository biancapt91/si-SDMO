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
    Schema::create('iku_sdmo_tables', function (Blueprint $table) {
        $table->id();
        $table->string('nama')->default('IKU SDMO');
        $table->json('struktur');   // kolom, baris, rowspan, colspan
        $table->timestamps();
    });
}

};
