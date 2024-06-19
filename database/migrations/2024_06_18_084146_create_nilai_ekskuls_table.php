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
        Schema::create('nilai_ekskuls', function (Blueprint $table) {
            $table->id();
            $table->integer('ekskul_id');
            $table->string('tapel', 10);
            $table->string('semester', 2);
            $table->string('siswa_id', 35);
            $table->string('nilai',3);
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_ekskuls');
    }
};
