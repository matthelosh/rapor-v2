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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 191);
            $table->string('pelaksana', 191)->default('Lembaga');
            $table->date('mulai');
            $table->date('selesai');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_libur')->default(true);
            $table->string('warna')->default('red');
            $table->string('tapel', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
