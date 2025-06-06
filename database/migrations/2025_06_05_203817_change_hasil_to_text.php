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
        Schema::table('analises', function (Blueprint $table) {
            $table->text("kunci")->nullable()->change();
            $table->text("hasil")->change();
            $table->text("keterangan")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analises', function (Blueprint $table) {
            //
        });
    }
};
