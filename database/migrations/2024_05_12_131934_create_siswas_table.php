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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 16)->unique();
            $table->string('nis', 16)->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('nama', 175);
            $table->enum('jk', ['Laki-laki','Perempuan']);
            $table->string('alamat', 191);
            $table->string('hp', 16)->default('-');
            $table->string('email', 120)->nullable();
            $table->text('foto')->nullable();
            $table->enum('agama', ['Islam', 'Kristen','Katolik','Hindu','Budha', 'Konghuchu']);
            $table->string('angkatan', 10)->nullable();
            $table->string('sekolah_id', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
