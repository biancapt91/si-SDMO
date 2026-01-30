<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ak_kumulatif', function (Blueprint $table) {
            $table->unsignedBigInteger('pegawai_id')->primary();
            $table->decimal('total_ak',12,2)->default(0);
            $table->decimal('ak_utama',12,2)->default(0);
            $table->decimal('ak_penunjang',12,2)->default(0);
            $table->timestamp('last_updated')->nullable();

            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ak_kumulatif');
    }
};