<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anaks')->onDelete('cascade');
            $table->year('tahun');
            $table->json('nilai_data'); // Menyimpan semua nilai dalam JSON
            $table->json('catatan_data')->nullable(); // Menyimpan semua catatan dalam JSON
            $table->timestamps();
            
            // Unique constraint: 1 anak hanya punya 1 record per tahun
            $table->unique(['anak_id', 'tahun']);
            
            // Index untuk performa
            $table->index(['anak_id', 'tahun']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilais');
    }
};
