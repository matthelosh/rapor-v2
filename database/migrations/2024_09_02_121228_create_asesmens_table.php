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
        Schema::create('asesmens', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 60);
            $table->string('nama', 191);
            $table->string('deskripsi', 191)->nullable();
            $table->string('mapel_id', 20);
            $table->date('tanggal');
            $table->time('mulai');
            $table->time('selesai');
            $table->enum('jenjang', ['lembaga', 'gurus', 'kecamatan'])->default('lembaga');
            $table->enum('jenis', ['uh', 'pts', 'pas', 'lainnya'])->default('uh');
            $table->enum('kelas', ['1', '2', '3', '4', '5', '6']);
            $table->string('rombel_id', 30)->nullable();
            $table->string('sekolah_id', 10)->nullable();
            $table->string('semester', 1);
            $table->string('tapel', 10);
            $table->string('guru_id', 30);
            $table->string('agama', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesmens');
    }
};
