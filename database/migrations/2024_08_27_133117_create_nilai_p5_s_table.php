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
        Schema::create('nilai_p5_s', function (Blueprint $table) {
            $table->id();
            $table->integer('proyek_id');
            $table->string('siswa_id', 16);
            $table->string('rombel_id', 100);
            $table->string('tapel', 10);
            $table->string('semester', 1);
            $table->integer('apd_id');
            $table->enum('nilai', ['bb', 'mb', 'bsh', 'sb']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_p5_s');
    }
};
