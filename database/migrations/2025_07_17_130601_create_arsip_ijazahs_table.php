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
        Schema::create('arsip_ijazahs', function (Blueprint $table) {
            $table->id();
            $table->string('no_seri', 60)->unique();
            $table->string('tapel', 4);
            $table->string('siswa_id', 10);
            $table->string('sekolah_id', 30);
            $table->text('url');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_ijazahs');
    }
};
