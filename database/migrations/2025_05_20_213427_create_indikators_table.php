<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspek_id')->constrained('aspeks')->onDelete('cascade');
            $table->string('nama_indikator');
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0); // Untuk sorting
            // $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['aspek_id', 'urutan']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('indikators');
    }
};
