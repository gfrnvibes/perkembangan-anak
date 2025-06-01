<?php

namespace App\Models;

use App\Models\CatatanAnak;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateCatatan extends Model
{
    use HasFactory;

    // protected $table = 'template_catatan';

    protected $fillable = [
        'indikator_id',
        'nilai',
        'isi_template',
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_perkembangan_id');
    }

    public function catatanAnaks(): HasMany
    {
        return $this->hasMany(CatatanAnak::class);
    }
}
