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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('subdomain')->nullable()->after('slug');
            $table->index('subdomain');
            $table->integer('sekolah_id')->nullable()->after('subdomain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('subdomain');
            $table->dropIndex(['subdomain']);
            $table->dropColumn('sekolah_id');
        });
    }
};
