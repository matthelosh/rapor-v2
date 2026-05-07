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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30);
            $table->integer('workshop_id');
            $table->string('guru_id', 30);
            $table->string('jp', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('template', 191)->nullable();
            $table->text('arsip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
