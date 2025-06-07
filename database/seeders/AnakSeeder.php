<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AnakImport;
use Illuminate\Support\Facades\DB;

class AnakSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel terlebih dahulu (opsional)
        // DB::table('anak')->truncate();

        // Path ke file Excel (letakkan di folder storage/app)
        $filePath = storage_path('app/data_anak.xlsx');
        
        // Atau jika file ada di folder public
        // $filePath = public_path('data/data_anak.xlsx');

        // Cek apakah file exists
        if (!file_exists($filePath)) {
            $this->command->error("File Excel tidak ditemukan di: {$filePath}");
            return;
        }

        try {
            Excel::import(new AnakImport, $filePath);
            $this->command->info('Data anak berhasil diimport dari Excel!');
        } catch (\Exception $e) {
            $this->command->error('Error importing data: ' . $e->getMessage());
        }
    }
}
