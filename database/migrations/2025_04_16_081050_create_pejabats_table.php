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
        Schema::create('pejabats', function (Blueprint $table) {
            $table->id();
            $table->string('korwil')->nullable();
            $table->string('pangkat_korwil')->nullable();
            $table->string('nip_korwil')->nullable();
            $table->string('pengawas')->nullable();
            $table->string('pangkat_pengawas')->nullable();
            $table->string('nip_pengawas')->nullable();
            $table->string('ppai')->nullable();
            $table->string('pangkat_ppai')->nullable();
            $table->string('nip_ppai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabats');
    }
};
