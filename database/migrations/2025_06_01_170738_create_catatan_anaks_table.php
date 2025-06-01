<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_anaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nilai_id')->constrained('nilais')->onDelete('cascade');
            $table->foreignId('template_catatan_id')->nullable()->constrained('template_catatans')->onDelete('set null');
            $table->text('isi_catatan'); // Bisa dari template atau custom
            $table->boolean('is_custom')->default(false); // Apakah catatan custom atau dari template
            $table->timestamps();
            
            $table->index(['nilai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_anaks');
    }
};
