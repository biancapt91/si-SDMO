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
        Schema::table('pegawai', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawai', 'tmt_pangkat')) {
                $table->date('tmt_pangkat')->nullable()->after('pangkat_golongan');
            }
            
            if (!Schema::hasColumn('pegawai', 'status_asn')) {
                $table->string('status_asn')->nullable()->after('jenis_asn');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            if (Schema::hasColumn('pegawai', 'tmt_pangkat')) {
                $table->dropColumn('tmt_pangkat');
            }
            
            if (Schema::hasColumn('pegawai', 'status_asn')) {
                $table->dropColumn('status_asn');
            }
        });
    }
};
