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
        Schema::create('guru_org', function (Blueprint $table) {
            $table->id();
            $table->integer('guru_id');
            $table->integer('org_id');
            $table->enum('jabatan', ['Ketua', 'Wakil', 'Sekretaris 1', 'Sekretaris 2', 'Bendahara 1', 'Bendahara 2', 'Anggota'])->default('Anggota');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_org');
    }
};
