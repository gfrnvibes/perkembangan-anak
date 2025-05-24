<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penilaian_mingguan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anaks')->onDelete('cascade');
            $table->foreignId('indikator_id')->constrained()->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users'); // Kalau guru juga pakai tabel users
            $table->unsignedTinyInteger('minggu_ke'); // Minggu ke-1, 2, dst
            $table->year('tahun');
            $table->enum('semester', [1, 2]);
            $table->unsignedTinyInteger('nilai'); // Skala 1–4
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['anak_id', 'indikator_id', 'minggu_ke', 'tahun', 'semester'], 'unique_penilaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_mingguans');
    }
};
