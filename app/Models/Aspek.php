<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspek extends Model
{
    use HasFactory;

    // protected $table = 'aspek';

    protected $fillable = ['nama_aspek'];

    public function indikator()
    {
        return $this->hasMany(Indikator::class);
    }
}
