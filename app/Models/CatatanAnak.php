<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanAnak extends Model
{
    protected $fillable = [
        'nilai_id',
        'template_catatan_id',
        'isi_catatan',
        'is_custom'
    ];

    protected $casts = [
        'is_custom' => 'boolean'
    ];

    public function nilai(): BelongsTo
    {
        return $this->belongsTo(Nilai::class);
    }

    public function templateCatatan(): BelongsTo
    {
        return $this->belongsTo(TemplateCatatan::class);
    }
}
