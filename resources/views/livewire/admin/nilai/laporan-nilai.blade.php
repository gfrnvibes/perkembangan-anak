<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Nilai Perkembangan Anak</title>
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

        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .nilai-table th,
        .nilai-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .nilai-table th {
            background-color: #333;
            color: white;
            font-weight: bold;
        }

        .nilai-table .aspek-cell {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: left;
        }

        .nilai-table .indikator-cell {
            text-align: left;
            max-width: 200px;
        }

        .badge {
            background-color: #007bff;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
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
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>CEKLIS AKHIR SEMESTER</h1>
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
                        {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}
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
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- Tabel Nilai --}}
    @if ($periode == 'mingguan')
        {{-- Tabel Mingguan --}}
        @if ($selectedPeriode == 'mingguan')
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Aspek Perkembangan</th>
                        <th>Indikator</th>
                        <th>Kode</th>
                        <th>Senin</th>
                        <th>Selasa</th>
                        <th>Rabu</th>
                        <th>Kamis</th>
                        <th>Jumat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                        @php
                            $indikatorGroups = $nilaiAspek->groupBy('indikator_id');
                        @endphp
                        @foreach ($indikatorGroups as $indikatorId => $nilaiIndikator)
                            @php
                                $firstNilai = $nilaiIndikator->first();
                            @endphp
                            <tr>
                                @if ($loop->first)
                                    <td rowspan="{{ $indikatorGroups->count() }}" class="align-middle fw-bold bg-light">
                                        {{ $aspekNama }}
                                    </td>
                                @endif
                                <td>{{ $firstNilai->indikator->deskripsi }}</td>
                                <td class="text-center">{{ $firstNilai->indikator->kode_indikator }}</td>

                                @php
                                    $startWeek = \Carbon\Carbon::parse($selectedWeek);
                                @endphp

                                @for ($day = 1; $day <= 5; $day++)
                                    <td class="text-center">
                                        @php
                                            $targetDate = $startWeek
                                                ->copy()
                                                ->addDays($day - 1)
                                                ->format('Y-m-d');
                                            $nilaiHari = $nilaiIndikator->where('tanggal', $targetDate)->first();
                                        @endphp
                                        @if ($nilaiHari)
                                            <span
                                                class="badge bg-primary">{{ $nilaiMapping[$nilaiHari->nilai_numerik] }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
    @elseif($periode == 'bulanan')
        {{-- Tabel Bulanan --}}
        <table class="nilai-table">
            <thead>
                <tr>
                    <th colspan="3">KD & INDIKATOR</th>
                    <th colspan="4">MINGGU KE</th>
                    <th rowspan="2">CAPAIAN AKHIR BULAN</th>
                </tr>
                <tr>
                    <th width="7.5%">Minggu 1</th>
                    <th width="7.5%">Minggu 2</th>
                    <th width="7.5%">Minggu 3</th>
                    <th width="7.5%">Minggu 4</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                    @foreach ($nilaiAspek as $index => $nilai)
                        <tr>
                            @if ($index == 0)
                                <td rowspan="{{ $nilaiAspek->count() }}" class="aspek-cell">
                                    {{ $aspekNama }}
                                </td>
                            @endif
                            <td>{{ $nilai->indikator->kode_indikator }}</td>
                            <td class="indikator-cell">{{ $nilai->indikator->deskripsi }}</td>

                            @for ($week = 1; $week <= 4; $week++)
                                <td>
                                    @if (isset($nilai->minggu_data["minggu_$week"]))
                                        <span
                                            class="badge">{{ $nilaiMapping[$nilai->minggu_data["minggu_$week"]] }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        {{-- Tabel Semesteran --}}
        <table class="nilai-table">
            <thead class="table-danger text-white align-middle text-center">
                <tr>
                    <th colspan="3">KD & INDIKATOR</th>
                    <th colspan="6">BULAN</th>
                    <th rowspan="2">CAPAIAN AKHIR SMT</th>
                </tr>
                <tr>
                    @if ($selectedSemester == 'ganjil')
                        <th width="7%">Jul</th>
                        <th width="7%">Agu</th>
                        <th width="7%">Sep</th>
                        <th width="7%">Okt</th>
                        <th width="7%">Nov</th>
                        <th width="7%">Des</th>
                    @else
                        <th width="7%">Jan</th>
                        <th width="7%">Feb</th>
                        <th width="7%">Mar</th>
                        <th width="7%">Apr</th>
                        <th width="7%">Mei</th>
                        <th width="7%">Jun</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                    @foreach ($nilaiAspek as $index => $nilai)
                        <tr>
                            @if ($index == 0)
                                <td rowspan="9" class="aspek-cell">
                                    {{ $aspekNama }}
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td>{{ $nilai->indikator->kode_indikator }}</td>
                            <td class="indikator-cell">{{ $nilai->indikator->deskripsi }}</td>

                            @php
                                $months =
                                    $selectedSemester == 'ganjil'
                                        ? ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                                        : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                            @endphp

                            @foreach ($months as $month)
                                <td>
                                    @if (isset($nilai->bulan_data[$month]))
                                        <span class="badge">{{ $nilaiMapping[$nilai->bulan_data[$month]] }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
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
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>

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
