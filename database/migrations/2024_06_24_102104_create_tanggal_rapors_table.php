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
        Schema::create('tanggal_rapors', function (Blueprint $table) {
            $table->id();
            $table->string('sekolah_id', 30);
            $table->string('tapel', 6);
            $table->string('semester', 1);
            $table->enum('tipe', ['pts', 'pas']);
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggal_rapors');
    }
};
