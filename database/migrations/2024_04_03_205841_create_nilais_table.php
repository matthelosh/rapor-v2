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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->string('tapel', 6);
            $table->string('semester', 1);
            $table->string('siswa_id', 16);
            $table->string('guru_id', 35)->nullable();
            $table->string('rombel_id', 30);
            $table->string('mapel_id', 30);
            $table->string('agama', 20)->nullable();
            $table->string('tp_id', 20);
            $table->enum('tipe', ['uh','ts','as']);
            $table->integer('skor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
