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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->string('siswa_id', 30);
            $table->string('tapel', 4);
            $table->string('semester', 1);
            $table->string('rombel_id', 30);
            $table->string('guru_id');
            $table->enum('status', ['h', 's', 'i', 'a'])->default('h');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
