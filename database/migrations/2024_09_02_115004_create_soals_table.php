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
            $table->text('a');
            $table->text('b');
            $table->text('c');
            $table->text('d');
            $table->string('kunci', 1);
            $table->enum('tipe', ['pilihan', 'isian', 'uraian'])->default('pilihan');
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
