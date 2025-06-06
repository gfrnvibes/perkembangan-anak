<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    use HasFactory;

    // protected $table = 'anak';

    protected $fillable = ['user_id', 'nama_lengkap', 'nama_panggilan', 'nomor_induk', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'ayah', 'ibu', 'wali', 'alamat_lengkap'];

    public function orangTua()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}
