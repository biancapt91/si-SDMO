<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('koefisien_ak', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang')->unique();
            $table->decimal('koef_per_tahun',8,2);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('koefisien_ak');
    }
};