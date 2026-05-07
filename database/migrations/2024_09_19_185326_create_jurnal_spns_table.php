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
        Schema::create('jurnal_spns', function (Blueprint $table) {
            $table->id();
            $table->string('sekolah_id', 30);
            $table->integer('jilid_id');
            $table->string('guru_id', 30);
            $table->text('materi');
            $table->text('fotos');
            $table->text('absensis')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_spns');
    }
};
