<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('angka_kredit_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->enum('sumber', ['KONVERSI_PREDIKAT','DIKLAT','TUGAS_JABATAN','PENGALAMAN','PENUNJANG','DASAR','PENDIDIKAN_TAMBAHAN']);
            $table->text('deskripsi')->nullable();
            $table->decimal('nilai',10,2)->default(0);
            $table->date('tanggal')->nullable();
            $table->enum('status', ['DIAJUKAN','DIVERIF','DISAHKAN'])->default('DISAHKAN');
            $table->timestamps();

            $table->index('pegawai_id');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('angka_kredit_entries');
    }
};