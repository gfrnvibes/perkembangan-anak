<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Nilai extends Model
{
    protected $fillable = [
        'anak_id',
        'indikator_id', 
        'nilai_numerik',
        'minggu',
        'bulan',
        'tahun'
    ];

    public function anak(): BelongsTo
    {
        return $this->belongsTo(Anak::class);
    }

    public function indikator(): BelongsTo
    {
        return $this->belongsTo(Indikator::class);
    }

    public function catatanAnak(): HasOne
    {
        return $this->hasOne(CatatanAnak::class);
    }

    // Method untuk mendapatkan template catatan berdasarkan nilai
    public function getTemplateCatatan()
    {
        $nilaiMapping = [1 => 'BB', 2 => 'MB', 3 => 'BSH', 4 => 'BSB'];
        $nilaiKode = $nilaiMapping[$this->nilai_numerik];
        
        return TemplateCatatan::where('indikator_id', $this->indikator_id)
            ->where('nilai', $nilaiKode)
            ->first();
    }
}
