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
        Schema::create('kokurikulers', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id');
            $table->string('rombel_id');
            $table->string("tapel", 5);
            $table->enum('semester', ['1','2']);
            $table->text('deskripsi');
            $table->string('guru_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kokurikulers');
    }
};
