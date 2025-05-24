<?php

namespace App\Imports;

use App\Models\Anak;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AnakImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Anak([
            'nama_lengkap' => $row['nama_lengkap'],
            'nomor_induk' => $row['nomor_induk'],
            'nisn' => $row['nisn'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'ayah' => $row['ayah'],
            'ibu' => $row['ibu'],
            'alamat_lengkap' => $row['alamat_lengkap'],
            'nama_panggilan' => $row['nama_panggilan'] ?? null,
            // 'wali' => $row['wali'] ?? null,
            'user_id' => $row['user_id'] ?? null,
        ]);
    }
    
    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nomor_induk' => 'required|string|max:50',
            // 'nisn' => 'required|string|max:10',
            // 'nama_panggilan' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|string',
            'ayah' => 'required|string|max:255',
            'ibu' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
        ];
    }
}
