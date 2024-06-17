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
        Schema::create('kktps', function (Blueprint $table) {
            $table->id();
            $table->string('tapel', 20);
            $table->string('semester', 1);
            $table->string('sekolah_id', 30);
            $table->string('mapel_id', 10);
            $table->enum('tingkat', ['1','2','3','4','5','6']);
            $table->integer('minimal');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kktps');
    }
};
