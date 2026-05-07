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
        Schema::table('arsip_ijazahs', function (Blueprint $table) {
            $table->text('url_transkrip')->nullable();
            $table->text('url_skl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arisp_ijazahs', function (Blueprint $table) {
            //
        });
    }
};
