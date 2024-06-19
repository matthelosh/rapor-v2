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
        Schema::create('ortus', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id', 30);
            $table->string('nik', 30)->unique();
            $table->string('nama', 160);
            $table->enum('relasi', ['Ayah', 'Ibu', 'Wali']);
            $table->string('alamat', 191);
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('desa', 100);
            $table->string('kode_pos', 5)->default('65158');
            $table->string('kecamatan', 100)->default('Wagir');
            $table->string('kabupaten', 100)->default('Malang');
            $table->string('hp', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ortus');
    }
};
