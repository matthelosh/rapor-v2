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
        Schema::table('asesmens', function (Blueprint $table) {
            $table->string('rombel_id', 40)->nullable()->change();
            $table->enum('jenis', ['uh', 'pts', 'pas', 'lainnya'])->change();
            $table->enum('kelas', ['1', '2', '3', '4', '5', '6'])->change();
            $table->enum('tingkat', ['lembaga', 'gugus', 'kecamatan'])->default('lembaga')->change();
            $table->string('agama', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asesmens', function (Blueprint $table) {
            //
        });
    }
};
