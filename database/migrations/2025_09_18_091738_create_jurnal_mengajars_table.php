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
        Schema::create('jurnal_mengajars', function (Blueprint $table) {
            $table->id();
            $table->string('guru_id', 30);
            $table->string('tapel', 4);
            $table->string('semester', 1);
            $table->string('rombel_id', 30);
            $table->string('mapel_id', 30)->nullable();
            $table->text('keterangan')->nullable();
            $table->text('materi')->nullable();
            $table->text('tp')->nullable();
            $table->string('elemen')->nullable();
            $table->string('foto_kegiatan')->nullable();
            $table->string('dokumen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_mengajars');
    }
};
