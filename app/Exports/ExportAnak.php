<?php

namespace App\Exports;

use App\Models\Anak;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportAnak implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $filterJenisKelamin;
    protected $sortField;
    protected $sortDirection;
    
    public function __construct($search = '', $filterJenisKelamin = '', $sortField = 'nama_lengkap', $sortDirection = 'asc')
    {
        $this->search = $search;
        $this->filterJenisKelamin = $filterJenisKelamin;
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
    }
    
    public function query()
    {
        return Anak::query()
            ->when($this->search, function($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nomor_induk', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterJenisKelamin, function($query) {
                $query->where('jenis_kelamin', $this->filterJenisKelamin);
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }
    
    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Nomor Induk',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Nama Ayah',
            'Nama Ibu',
            'Alamat',
        ];
    }
    
    public function map($anak): array
    {
        return [
            $anak->nama_lengkap,
            $anak->nomor_induk,
            $anak->nisn,
            $anak->jenis_kelamin,
            $anak->tempat_lahir,
            $anak->tanggal_lahir,
            $anak->ayah,
            $anak->ibu,
            $anak->alamat_lengkap,
            $anak->nama_panggilan,
            $anak->wali,
        ];
    }
}
