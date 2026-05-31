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
        if (!Schema::hasTable('rapor_details')) {
            Schema::create('rapor_details', function (Blueprint $table) {
                $table->id();
                $table->string('rapor_id', 191);
                $table->string('mapel_id', 191);
                $table->decimal('uh', 5, 2)->default(0);
                $table->decimal('ts', 5, 2)->default(0);
                $table->decimal('as', 5, 2)->default(0);
                $table->decimal('rerata', 5, 2)->default(0);
                $table->timestamps();

                $table->foreign('rapor_id')->references('kode')->on('rapors')->onDelete('cascade');
            });
        } else {
            // Tambah foreign key jika belum ada
            Schema::table('rapor_details', function (Blueprint $table) {
                $table->foreign('rapor_id')->references('kode')->on('rapors')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapor_details');
    }
};
