<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pak_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('angka_kredit_id')
                  ->constrained('angka_kredit')
                  ->cascadeOnDelete();

            $table->string('nomor_pak')->nullable();
            $table->date('tanggal_penetapan')->nullable();

            $table->foreignId('verified_by_sdmo')->nullable();
            $table->foreignId('signed_by_ppk')->nullable();

            $table->timestamp('signed_at')->nullable();
            $table->string('file_pdf')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pak_documents');
    }
};
