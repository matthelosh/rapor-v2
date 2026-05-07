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
        Schema::create('proses_asesmens', function (Blueprint $table) {
            $table->id();
            $table->string('asesmen_id', 100);
            $table->string('siswa_id', 30);
            $table->timestamp('mulai')->default(now());
            $table->timestamp('selesai')->nullable();
            $table->enum('status', ['progres', 'selesai'])->default('progres');
            $table->decimal('skor', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proses_asesmens');
    }
};
