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
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->string('subdomain', 40)->nullable();
        });
        DB::table('sekolahs')->update(['subdomain' => DB::raw('LOWER(REPLACE(nama, " ", ""))')]);
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->unique('subdomain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            //
        });
    }
};
