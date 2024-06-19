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
        Schema::create('ekskuls', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->string('nama', 150);
            $table->string('sekolah_id', 30);
            $table->string('pembina', 100);
            $table->string('nip_pembina', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('sifat', ['Wajib', 'Pilihan'])->default('Wajib');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekskuls');
    }
};
