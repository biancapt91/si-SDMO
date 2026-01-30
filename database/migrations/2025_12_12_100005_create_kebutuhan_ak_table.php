<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kebutuhan_ak', function (Blueprint $table) {
            $table->id();
            $table->string('kategori')->nullable(); // 'keterampilan'|'keahlian'
            $table->string('dari')->nullable();     // 'III/a' or 'Ahli Pertama'
            $table->string('ke')->nullable();
            $table->integer('angka_kredit')->default(0);
            $table->boolean('is_kumulatif')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kebutuhan_ak');
    }
};