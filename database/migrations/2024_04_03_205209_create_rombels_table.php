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
        Schema::create('rombels', function (Blueprint $table) {
            $table->id();
            $table->string('tapel', 4);
            $table->string('pararel', 2)->default('0');
            $table->string('kode', 100)->unique();
            $table->string('label', 150);
            $table->string('fase', 1);
            $table->string('tingkat', 2);
            $table->integer('sekolah_id');
            $table->integer('guru_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombels');
    }
};
