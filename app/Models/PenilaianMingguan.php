<?php

namespace App\Models;

use App\Models\Indikator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianMingguan extends Model
{
    use HasFactory;

    protected $fillable = ['anak_id', 'indikator_perkembangan_id', 'guru_id', 'minggu_ke', 'tahun', 'semester', 'nilai', 'catatan'];

    public function anak()
    {
        return $this->belongsTo(Anak::class);
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
