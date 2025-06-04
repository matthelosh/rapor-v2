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
        Schema::create('kunci_jawabans', function (Blueprint $table) {
            $table->id();
            $table->string('asesmen_id', 30);
            $table->text('pg')->nullable();
            $table->text('pgk')->nullable();
            $table->text('ps')->nullable();
            $table->text('bs')->nullable();
            $table->text('is')->nullable();
            $table->text('ur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunci_jawabans');
    }
};
