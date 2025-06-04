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
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->string('guru_id', 20);
            $table->string('tingkat', 1);
            $table->string('semester', 1);
            $table->string('mapel_id', 20);
            $table->string('agama', 30)->nullable();
            $table->integer('tp_id');
            $table->text('pertanyaan');
            $table->text('jawabans');
            /* $table->text('b'); */
            /* $table->text('c'); */
            /* $table->text('d'); */
            $table->text('kunci');
            $table->enum('tipe', ['pilihan_ganda', 'pilihan_ganda_kompleks', 'menjodohkan', 'benar_salah', 'isian', 'uraian'])->default('pilihan_ganda');
            $table->enum('level', ['lot', 'mot', 'hot'])->default('mot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
