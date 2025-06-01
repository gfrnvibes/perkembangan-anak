<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Nilai Perkembangan Anak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial Narrow', sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section table {
            width: 100%;
        }

        .info-section td {
            padding: 5px 0;
        }

        .keterangan {
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .keterangan h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .keterangan ul {
            margin: 0;
            padding-left: 20px;
        }

        .keterangan li {
            margin-bottom: 3px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .table-light th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        .bg-light {
            background-color: #f8f9fa !important;
            font-weight: bold;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-muted {
            color: #6c757d;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .signature {
            margin-top: 60px;
        }

        .page-break {
            page-break-after: always;
        }

        .catatan-section {
            margin-top: 30px;
        }

        .catatan-item {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        @if ($periode == 'mingguan')
            <h1>LAPORAN PERKEMBANGAN MINGGUAN</h1>
        @elseif($periode == 'bulanan')
            <h1>LAPORAN PERKEMBANGAN BULANAN</h1>
        @else
            <h1>LAPORAN PERKEMBANGAN SEMESTERAN</h1>
        @endif
    </div>

    {{-- Info Anak --}}
    <div class="info-section">
        <table>
            <tr>
                <td width="150"><strong>Nama Anak</strong></td>
                <td width="10">:</td>
                <td>{{ $anak->nama_lengkap }}</td>
            </tr>
            <tr>
                <td><strong>Periode</strong></td>
                <td>:</td>
                <td>
                    @if ($periode == 'mingguan')
                        Minggu {{ \Carbon\Carbon::parse($selectedWeek)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($selectedWeek)->addDays(4)->format('d M Y') }}
                    @elseif($periode == 'bulanan')
                        @php
                            $monthNames = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        {{ $monthNames[$selectedMonth] }} {{ $selectedYear }}
                    @else
                        Semester {{ ucfirst($selectedSemester) }} {{ $selectedYear }}
                        @if ($selectedSemester == 'ganjil')
                            (Juli - Desember)
                        @else
                            (Januari - Juni)
                        @endif
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- Tabel Nilai --}}
    @if ($periode == 'mingguan')
        {{-- Tabel Mingguan --}}
        <table class="table table-bordered table-striped">
            <thead class="table-light text-center align-middle">
                <tr>
                    <th colspan="2" rowspan="2">KD & INDIKATOR</th>
                    <th rowspan="2">CAPAIAN<br>MINGGU INI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                    <tr>
                        <td colspan="3" class="bg-light">
                            {{ $aspekNama }}
                        </td>
                    </tr>
                    @foreach ($nilaiAspek as $nilai)
                        <tr>
                            <td class="text-center">{{ $nilai->indikator->kode_indikator }}</td>
                            <td class="text-left">{{ $nilai->indikator->deskripsi }}</td>
                            <td class="text-center">
                                @if ($nilai->nilai_numerik)
                                    {{ $nilaiMapping[$nilai->nilai_numerik] }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @elseif($periode == 'bulanan')
        {{-- Tabel Bulanan --}}
        <table class="table table-bordered table-striped">
            <thead class="table-light text-center align-middle">
                <tr>
                    <th rowspan="2" colspan="2">KD & INDIKATOR</th>
                    <th colspan="4">MINGGU KE</th>
                    <th rowspan="2">CAPAIAN<br>AKHIR BLN</th>
                </tr>
                <tr>
                    <th>Minggu 1</th>
                    <th>Minggu 2</th>
                    <th>Minggu 3</th>
                    <th>Minggu 4</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                    <tr>
                        <td colspan="7" class="bg-light">
                            {{ $aspekNama }}
                        </td>
                    </tr>
                    @foreach ($nilaiAspek as $nilai)
                        <tr>
                            <td class="text-center">{{ $nilai->indikator->kode_indikator }}</td>
                            <td class="text-left">{{ $nilai->indikator->deskripsi }}</td>

                            @for ($week = 1; $week <= 4; $week++)
                                <td class="text-center">
                                    @if (isset($nilai->minggu_data["minggu_$week"]))
                                        {{ $nilaiMapping[$nilai->minggu_data["minggu_$week"]] }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endfor

                            <td class="text-center">
                                @if ($nilai->capaian_akhir_bulan)
                                    {{ $nilaiMapping[$nilai->capaian_akhir_bulan] }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        {{-- Tabel Semesteran --}}
        <table class="table table-bordered table-striped">
            <thead class="table-light text-center align-middle">
                <tr>
                    <th rowspan="2" colspan="2">KD & INDIKATOR</th>
                    <th colspan="6">BULAN</th>
                    <th rowspan="2">CAPAIAN<br>AKHIR SMT</th>
                </tr>
                <tr>
                    @if ($selectedSemester == 'ganjil')
                        <th>Jul</th>
                        <th>Agu</th>
                        <th>Sep</th>
                        <th>Okt</th>
                        <th>Nov</th>
                        <th>Des</th>
                    @else
                        <th>Jan</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Apr</th>
                        <th>Mei</th>
                        <th>Jun</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                    <tr>
                        <td colspan="9" class="bg-light">
                            {{ $aspekNama }}
                        </td>
                    </tr>
                    @foreach ($nilaiAspek as $nilai)
                        <tr>
                            <td class="text-center">{{ $nilai->indikator->kode_indikator }}</td>
                            <td class="text-left">{{ $nilai->indikator->deskripsi }}</td>

                            @php
                                $months =
                                    $selectedSemester == 'ganjil'
                                        ? ['Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
                                        : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
                            @endphp

                            @foreach ($months as $month)
                                <td class="text-center">
                                    @if (isset($nilai->bulan_data[$month]))
                                        {{ $nilaiMapping[$nilai->bulan_data[$month]] }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="text-center">
                                @if ($nilai->capaian_akhir_semester)
                                    {{ $nilaiMapping[$nilai->capaian_akhir_semester] }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Keterangan Nilai --}}
    <div class="keterangan">
        <h4>Keterangan Penilaian:</h4>
        <ul style="list-style: none; padding-left: 0;">
            <li><strong>BB:</strong> Belum Berkembang (Nilai 1)</li>
            <li><strong>MB:</strong> Mulai Berkembang (Nilai 2)</li>
            <li><strong>BSH:</strong> Berkembang Sesuai Harapan (Nilai 3)</li>
            <li><strong>BSB:</strong> Berkembang Sangat Baik (Nilai 4)</li>
        </ul>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="signature">
            <table width="100%">
                <tr>
                    <td width="50%"></td>
                    <td width="50%" style="text-align: center;">
                        <p>Guru Kelas</p>
                        <br><br><br>
                        <p>(_________________________)</p>
                        <p>NIP. </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
