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
            $table->string('gelar_depan', 10)->nullable();
            $table->string('nama', 150);
            $table->string('gelar_belakang', 10)->nullable();
            $table->enum('jk', ['Lak-laki','Perempuan']);
            $table->string('alamat', 191)->default('-');
            $table->string('hp', 16);
            $table->enum('status', ['pns','p3k','gtt']);
            $table->string('email', 150)->nullable();
            $table->text('foto')->nullable();
            $table->enum('agama', ['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu']);
            $table->string('pangkat', 20)->nullable();
            $table->string('jabatan', 100)->nullable();
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
