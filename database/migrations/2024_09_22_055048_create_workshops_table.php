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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->integer('agenda_id')->nullable();
            $table->string('nama', 100);
            $table->string('lokasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('pelaksana');
            $table->string('nara_sumber');
            $table->date('mulai');
            $table->date('selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
