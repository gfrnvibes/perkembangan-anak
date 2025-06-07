<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    protected $fillable = [
        'aspek_id',
        'nama_indikator',
        'deskripsi',
        'urutan',
        // 'is_active'
    ];

    // protected $casts = [
    //     'is_active' => 'boolean'
    // ];

    // Relasi: Indikator belongs to Aspek
    public function aspek()
    {
        return $this->belongsTo(Aspek::class);
    }

    // Helper method untuk mendapatkan nilai indikator ini untuk anak tertentu
    public function getNilaiForAnak($anakId, $semester = null, $bulan = null, $minggu = null, $tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        $nilai = Nilai::where('anak_id', $anakId)
            ->where('tahun', $tahun)
            ->first();
            
        if (!$nilai) return null;
        
        $nilaiData = $nilai->nilai_data;
        $aspekKey = "aspek_{$this->aspek_id}";
        $indikatorKey = "indikator_{$this->id}";
        
        if ($semester && $bulan && $minggu) {
            // Ambil nilai spesifik minggu
            return $nilaiData["semester_{$semester}"][$aspekKey][$indikatorKey]["bulan_{$bulan}"]["minggu_{$minggu}"] ?? null;
        } elseif ($semester && $bulan) {
            // Ambil nilai seluruh minggu dalam bulan
            return $nilaiData["semester_{$semester}"][$aspekKey][$indikatorKey]["bulan_{$bulan}"] ?? null;
        } elseif ($semester) {
            // Ambil nilai seluruh bulan dalam semester
            return $nilaiData["semester_{$semester}"][$aspekKey][$indikatorKey] ?? null;
        }
        
        return $nilaiData[$aspekKey][$indikatorKey] ?? null;
    }

    // Helper method untuk mendapatkan catatan
    public function getCatatanForAnak($anakId, $semester = null, $bulan = null, $minggu = null, $tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        $nilai = Nilai::where('anak_id', $anakId)
            ->where('tahun', $tahun)
            ->first();
            
        if (!$nilai || !$nilai->catatan_data) return null;
        
        $catatanData = $nilai->catatan_data;
        $aspekKey = "aspek_{$this->aspek_id}";
        $indikatorKey = "indikator_{$this->id}";
        
        if ($semester && $bulan && $minggu) {
            return $catatanData["semester_{$semester}"][$aspekKey][$indikatorKey]["bulan_{$bulan}"]["minggu_{$minggu}"] ?? null;
        }
        
        return null;
    }
}
