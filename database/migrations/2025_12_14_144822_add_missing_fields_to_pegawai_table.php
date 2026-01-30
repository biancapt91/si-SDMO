<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {

            if (!Schema::hasColumn('pegawai', 'unit_kerja')) {
                $table->string('unit_kerja')->nullable();
            }

            if (!Schema::hasColumn('pegawai', 'unit_kerja_st')) {
                $table->string('unit_kerja_st')->nullable();
            }

            if (!Schema::hasColumn('pegawai', 'status_penempatan')) {
                $table->string('status_penempatan')->nullable();
            }

            if (!Schema::hasColumn('pegawai', 'jenis_asn')) {
                $table->string('jenis_asn')->nullable();
            }

            if (!Schema::hasColumn('pegawai', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable();
            }

            if (!Schema::hasColumn('pegawai', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable();
            }
        });
    }

    public function down(): void
    {
        // tidak perlu rollback di fase dev
    }
};
