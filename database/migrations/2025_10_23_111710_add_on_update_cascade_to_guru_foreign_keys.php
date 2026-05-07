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
        Schema::table('guru_rombel', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->foreign('guru_id')
                ->references('nip')
                ->on('gurus')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::table('transkrips', function (Blueprint $table) {
            $table->dropForeign(['diterbitkan_oleh']);
            $table->foreign('diterbitkan_oleh')
                ->references('nip')
                ->on('gurus')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru_rombel', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->foreign('guru_id')
                ->references('nip')
                ->on('gurus')
                ->onDelete('cascade');
        });

        Schema::table('transkrips', function (Blueprint $table) {
            $table->dropForeign(['diterbitkan_oleh']);
            $table->foreign('diterbitkan_oleh')
                ->references('nip')
                ->on('gurus')
                ->onDelete('cascade');
        });
    }
};
