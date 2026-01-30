<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penetapan_ak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->string('dokumen_no')->nullable();
            $table->date('tanggal')->nullable();
            $table->decimal('total_ak',12,2)->default(0);
            $table->enum('jenis_penetapan', ['PENETAPAN','PENGANGKATAN','PENYESUAIAN','PROMOSI'])->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->index('pegawai_id');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('penetapan_ak');
    }
};
