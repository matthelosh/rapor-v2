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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20);
            $table->string('nama', 150);
            $table->enum('jk', ['Lak-laki','Perempuan']);
            $table->string('alamat', 191)->default('-');
            $table->string('hp', 16);
            $table->string('email', 150)->nullable();
            $table->text('foto')->nullable();
            $table->enum('agama', ['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu']);
            $table->enum('role', ['gkel','gpabp','gpjok','gben']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
