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
        Schema::create('buku_induks', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id', 30)->unique();
            $table->date('tanggal_masuk')->nullable();
            $table->string('asal_sekolah', 60)->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->string('alasan_keluar')->nullable();
            $table->enum('status', ['aktif', 'lulus','pindah','keluar'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_induks');
    }
};
