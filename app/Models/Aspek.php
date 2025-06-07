<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspek extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_aspek', 
        'deskripsi', 
        // 'is_active'
    ];

    // protected $casts = [
    //     'is_active' => 'boolean'
    // ];

    // Relasi: 1 Aspek memiliki banyak Indikator
    public function indikators()
    {
        return $this->hasMany(Indikator::class)->orderBy('id');
    }

    // Relasi: Aspek memiliki banyak Nilai melalui Indikator (tidak langsung)
    // Karena nilai disimpan dalam JSON, relasi ini lebih ke helper method
    public function getNilaiForAnak($anakId, $tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        $nilai = Nilai::where('anak_id', $anakId)
            ->where('tahun', $tahun)
            ->first();
            
        if (!$nilai) return null;
        
        // Extract nilai untuk aspek ini dari JSON
        $nilaiData = $nilai->nilai_data;
        $aspekKey = "aspek_{$this->id}";
        
        return $nilaiData['semester_ganjil'][$aspekKey] ?? null;
    }
}
