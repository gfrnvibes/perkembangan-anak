<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateCatatan extends Model
{
    use HasFactory;

    protected $table = 'template_catatan';

    protected $fillable = [
        'indikator_id',
        'nilai',
        'isi_template',
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_perkembangan_id');
    }
}
