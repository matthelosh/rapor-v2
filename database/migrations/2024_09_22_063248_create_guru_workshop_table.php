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
        Schema::create('guru_workshop', function (Blueprint $table) {
            $table->id();
            $table->integer('guru_id');
            $table->integer('workshop_id');
            $table->enum('peran', ['Peserta', 'Nara Sumber', 'Panitia', 'Moderator'])->default("Peserta");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_workshop');
    }
};
