<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    use HasFactory;

    // protected $table = 'anak';

    protected $fillable = ['user_id', 'email', 'nama_lengkap', 'nama_panggilan', 'nomor_induk', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'ayah', 'ibu', 'wali', 'alamat_lengkap', 'pas_foto'];

    // Perbaiki nama relationship - gunakan 'user' bukan 'users'
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Atau bisa juga menggunakan nama 'orangTua' untuk lebih jelas
    public function orangTua()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}
