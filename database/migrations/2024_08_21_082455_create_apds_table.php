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
        Schema::create('apds', function (Blueprint $table) {
            $table->id();
            $table->integer('elemen_id');
            $table->string('sub_elemen', 191);
            $table->text('teks');
            $table->string('fase', 1);
            $table->string('tingkat', 1)->nullable();
            $table->string('semester', 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apds');
    }
};
