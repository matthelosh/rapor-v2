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
        Schema::create('proyeks', function (Blueprint $table) {
            $table->id();
            $table->string('tapel', 10);
            $table->string('semester', 1);
            $table->string('sekolah_id', 10);
            $table->string('rombel_id', 60);
            $table->string('nama', 191);
            $table->text('deskripsi');
            $table->enum('status', ['rencana', 'progres', 'selesai'])->default('rencana');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};
