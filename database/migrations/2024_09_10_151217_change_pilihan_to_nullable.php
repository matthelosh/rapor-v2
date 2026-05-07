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
        Schema::table('soals', function (Blueprint $table) {
            $table->text('a')->nullable(true)->change();
            $table->text('b')->nullable(true)->change();
            $table->text('c')->nullable(true)->change();
            $table->text('d')->nullable(true)->change();
            $table->text('kunci')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            //
        });
    }
};
