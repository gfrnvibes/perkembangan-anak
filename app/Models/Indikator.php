<?php

namespace App\Models;

use App\Models\Aspek;
use App\Models\TemplateCatatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Indikator extends Model
{
    use HasFactory;

    protected $fillable = ['aspek_id', 'deskripsi'];

    public function aspek()
    {
        return $this->belongsTo(Aspek::class, 'aspek_id');
    }

    public function penilaianMingguan()
    {
        return $this->hasMany(PenilaianMingguan::class);
    }

    public function templateCatatan()
    {
        return $this->hasMany(TemplateCatatan::class, 'indikator_perkembangan_id');
    }
}
