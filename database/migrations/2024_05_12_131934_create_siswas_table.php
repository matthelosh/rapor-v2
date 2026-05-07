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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 16)->unique();
            $table->string('nis', 16)->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('nama', 175);
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir', 100)->default('Malng');
            $table->date('tanggal_lahir')->default('2010-01-01');
            $table->string('alamat', 191);
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('desa', 60)->nullable();
            $table->string('kecamatan', 160)->default('Wagir');
            $table->string('kode_pos', 160)->default('65158');
            $table->string('kabupaten', 160)->default('Malang');
            $table->string('Provinsi', 160)->default('Jawa Timur');
            $table->string('hp', 16)->default('-');
            $table->string('email', 120)->nullable();
            $table->text('foto')->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu']);
            $table->string('angkatan', 10)->nullable();
            $table->string('sekolah_id', 10);
            $table->enum('status', ['aktif', 'lulus', 'do', 'mutasi'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
