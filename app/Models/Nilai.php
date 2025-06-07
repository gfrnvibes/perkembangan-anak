<?php

namespace App\Models;

use App\Models\Anak;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'anak_id',
        'tahun',
        'nilai_data',
        'catatan_data'
    ];

    protected $casts = [
        'nilai_data' => 'array',
        'catatan_data' => 'array'
    ];

    // Relasi: Nilai belongs to Anak
    public function anak()
    {
        return $this->belongsTo(Anak::class);
    }

    // Helper method untuk set nilai
    public function setNilai($aspekId, $indikatorId, $semester, $bulan, $minggu, $nilai)
    {
        $nilaiData = $this->nilai_data ?? [];
        $nilaiData["semester_{$semester}"]["aspek_{$aspekId}"]["indikator_{$indikatorId}"]["bulan_{$bulan}"]["minggu_{$minggu}"] = $nilai;
        $this->nilai_data = $nilaiData;
        return $this;
    }

    // Helper method untuk get nilai
    public function getNilai($aspekId, $indikatorId, $semester, $bulan, $minggu)
    {
        return $this->nilai_data["semester_{$semester}"]["aspek_{$aspekId}"]["indikator_{$indikatorId}"]["bulan_{$bulan}"]["minggu_{$minggu}"] ?? null;
    }

    // Helper method untuk set catatan
    public function setCatatan($aspekId, $indikatorId, $semester, $bulan, $minggu, $catatan)
    {
        $catatanData = $this->catatan_data ?? [];
        $catatanData["semester_{$semester}"]["aspek_{$aspekId}"]["indikator_{$indikatorId}"]["bulan_{$bulan}"]["minggu_{$minggu}"] = $catatan;
        $this->catatan_data = $catatanData;
        return $this;
    }

    // Helper method untuk get catatan
    public function getCatatan($aspekId, $indikatorId, $semester, $bulan, $minggu)
    {
        return $this->catatan_data["semester_{$semester}"]["aspek_{$aspekId}"]["indikator_{$indikatorId}"]["bulan_{$bulan}"]["minggu_{$minggu}"] ?? null;
    }

    // Helper method untuk mendapatkan semua aspek yang ada
    public function getAspekIds($semester = null)
    {
        if (!$this->nilai_data) return [];
        
        if ($semester) {
            $semesterData = $this->nilai_data["semester_{$semester}"] ?? [];
            return array_map(function($key) {
                return str_replace('aspek_', '', $key);
            }, array_keys($semesterData));
        }
        
        $aspekIds = [];
        foreach (['ganjil', 'genap'] as $sem) {
            $semesterData = $this->nilai_data["semester_{$sem}"] ?? [];
            foreach (array_keys($semesterData) as $key) {
                $aspekIds[] = str_replace('aspek_', '', $key);
            }
        }
        
        return array_unique($aspekIds);
    }
}
