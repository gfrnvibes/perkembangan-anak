<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Nilai Perkembangan Anak</h3>
        </div>
        <div class="card-body">

            {{-- Alert Messages --}}
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Filter Section --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Pilih Anak</label>
                    <select wire:model.live="selectedAnak" class="form-select">
                        <option value="">-- Pilih Anak --</option>
                        @foreach ($anakList as $anak)
                            <option value="{{ $anak->id }}">{{ $anak->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Periode Laporan</label>
                    <select wire:model.live="selectedPeriode" class="form-select">
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                        <option value="semesteran">Semesteran</option>
                    </select>
                </div>

                {{-- Filter Mingguan --}}
                @if ($selectedPeriode == 'mingguan')
                    <div class="col-md-3">
                        <label class="form-label">Pilih Minggu</label>
                        <select wire:model.live="selectedWeek" class="form-select">
                            <option value="">-- Pilih Minggu --</option>
                            @foreach ($weekOptions as $weekNumber => $weekLabel)
                                <option value="{{ $weekNumber }}">{{ $weekLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <select wire:model.live="selectedYear" class="form-select">
                            @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                @endif

                {{-- Filter Bulanan --}}
                @if ($selectedPeriode == 'bulanan')
                    <div class="col-md-2">
                        <label class="form-label">Bulan</label>
                        <select wire:model.live="selectedMonth" class="form-select">
                            @foreach ($monthOptions as $monthNumber => $monthName)
                                <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <select wire:model.live="selectedYear" class="form-select">
                            @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                @endif

                {{-- Filter Semesteran --}}
                @if ($selectedPeriode == 'semesteran')
                    <div class="col-md-2">
                        <label class="form-label">Semester</label>
                        <select wire:model.live="selectedSemester" class="form-select">
                            <option value="ganjil">Ganjil (Jul-Des)</option>
                            <option value="genap">Genap (Jan-Jun)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>
                        <select wire:model.live="selectedYear" class="form-select">
                            @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                @endif

                {{-- Download Buttons --}}
                @if (
                    ($selectedPeriode == 'mingguan' && $selectedWeek && $selectedYear) ||
                        ($selectedPeriode == 'bulanan' && $selectedMonth && $selectedYear) ||
                        ($selectedPeriode == 'semesteran' && $selectedSemester && $selectedYear))
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="downloadAllPDF" class="btn btn-info w-100 mt-3">
                            <i class="fas fa-download me-2"></i>Download Semua
                        </button>
                    </div>

                    @if ($selectedAnak && !empty($nilaiData))
                        <div class="col-md-2 d-flex align-items-end">
                            <button wire:click="downloadPDF" class="btn btn-success w-100">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </button>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Data Table --}}
            @if ($selectedAnak && !empty($nilaiData))
                <div class="table-responsive">

                    {{-- Tabel Mingguan --}}
                    @if ($selectedPeriode == 'mingguan')
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark text-center align-middle">
                                <tr>
                                    <th colspan="2" rowspan="2">KD & INDIKATOR</th>
                                    <th rowspan="2">CAPAIAN<br>MINGGU INI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                                    @foreach ($nilaiAspek as $index => $nilai)
                                        <tr>
                                            @if ($index == 0)
                                                <td colspan="3" class="align-middle fw-bold bg-light">
                                                    {{ $aspekNama }}
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="text-center">{{ $nilai->indikator->kode_indikator }}</td>
                                            <td>{{ $nilai->indikator->deskripsi }}</td>

                                            {{-- Tampilkan nilai per hari dalam minggu --}}
                                            {{-- @for ($day = 1; $day <= 5; $day++)
                                                <td class="text-center">
                                                    @php
                                                        // Cari nilai untuk hari tertentu berdasarkan indikator yang sama
                                                        $nilaiHari = $nilaiAspek
                                                            ->where('indikator_id', $nilai->indikator_id)
                                                            ->first();
                                                    @endphp
                                                    @if ($nilaiHari)
                                                        <span
                                                            class="badge bg-primary">{{ $nilaiMapping[$nilaiHari->nilai_numerik] }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endfor --}}

                                            {{-- Capaian minggu ini --}}
                                            <td class="text-center">
                                                @if ($nilai->nilai_numerik)
                                                    <span
                                                        class="badge bg-success">{{ $nilaiMapping[$nilai->nilai_numerik] }}</span>
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

                    {{-- Tabel Bulanan --}}
                    @if ($selectedPeriode == 'bulanan')

                    {{-- Debug Info --}}
                        {{-- <div class="alert alert-info">
                            <strong>Debug Info:</strong><br>
                            Selected Anak: {{ $selectedAnak }}<br>
                            Selected Month: {{ $selectedMonth }}<br>
                            Selected Year: {{ $selectedYear }}<br>
                            Nilai Data Count: {{ count($nilaiData) }}<br>
                            @if (!empty($nilaiData))
                                Aspek: {{ implode(', ', array_keys($nilaiData)) }}
                            @endif
                        </div> --}}
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark text-center align-middle">
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
                                        <td colspan="7" class="align-middle fw-bold bg-light">
                                            {{ $aspekNama }}
                                        </td>
                                    </tr>
                                    @foreach ($nilaiAspek as $nilai)
                                        <tr>
                                            <td class="text-center">{{ $nilai->indikator->kode_indikator }}</td>
                                            <td>{{ $nilai->indikator->deskripsi }}</td>

                                            @for ($week = 1; $week <= 4; $week++)
                                                <td class="text-center">
                                                    @if (isset($nilai->minggu_data["minggu_$week"]))
                                                        <span
                                                            class="badge bg-primary">{{ $nilaiMapping[$nilai->minggu_data["minggu_$week"]] }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endfor

                                            {{-- Capaian akhir bulan --}}
                                            <td class="text-center">
                                                @if ($nilai->capaian_akhir_bulan)
                                                    <span
                                                        class="badge bg-success">{{ $nilaiMapping[$nilai->capaian_akhir_bulan] }}</span>
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

                    {{-- Tabel Semesteran --}}
                    @if ($selectedPeriode == 'semesteran')
                        <table class="table table-bordered table-striped">
                            <thead class="table-danger text-white align-middle text-center">
                                <tr>
                                    <th rowspan="2" colspan="2">KD & INDIKATOR</th>
                                    <th colspan="6">BULAN</th>
                                    <th rowspan="2">CAPAIAN<br>AKHIR SMT</th>
                                </tr>
                                <tr>
                                    @if ($selectedSemester == 'ganjil')
                                        <th>Jul</th>
                                        <th>Aug</th>
                                        <th>Sep</th>
                                        <th>Oct</th>
                                        <th>Nov</th>
                                        <th>Dec</th>
                                    @else
                                        <th>Jan</th>
                                        <th>Feb</th>
                                        <th>Mar</th>
                                        <th>Apr</th>
                                        <th>May</th>
                                        <th>Jun</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                                    <tr>
                                        <td colspan="9" class="align-middle fw-bold bg-light">
                                            {{ $aspekNama }}
                                        </td>
                                    </tr>
                                    @foreach ($nilaiAspek as $nilai)
                                        <tr>
                                            <td class="text-center">{{ $nilai->indikator->kode_indikator }}</td>
                                            <td>{{ $nilai->indikator->deskripsi }}</td>

                                            @php
                                                $months =
                                                    $selectedSemester == 'ganjil'
                                                        ? ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                                                        : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                                            @endphp

                                            @foreach ($months as $month)
                                                <td class="text-center">
                                                    @if (isset($nilai->bulan_data[$month]))
                                                        <span
                                                            class="badge bg-primary">{{ $nilaiMapping[$nilai->bulan_data[$month]] }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            {{-- Capaian akhir semester --}}
                                            <td class="text-center">
                                                @if ($nilai->capaian_akhir_semester)
                                                    <span
                                                        class="badge bg-success">{{ $nilaiMapping[$nilai->capaian_akhir_semester] }}</span>
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
                </div>
            @elseif($selectedAnak && empty($nilaiData))
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data nilai untuk periode yang dipilih</p>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-child fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Pilih anak untuk melihat data nilai</p>
                </div>
            @endif

            {{-- Keterangan Nilai --}}
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading">Keterangan Penilaian:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li><strong>BB:</strong> Belum Berkembang</li>
                            <li><strong>MB:</strong> Mulai Berkembang</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li><strong>BSH:</strong> Berkembang Sesuai Harapan</li>
                            <li><strong>BSB:</strong> Berkembang Sangat Baik</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Loading Indicator --}}
            <div wire:loading class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data...</p>
            </div>

            {{-- Download Loading Indicator --}}
            <div wire:loading.delay wire:target="downloadPDF,downloadAllPDF"
                class="position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
                <div class="bg-white p-4 rounded shadow-lg text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Sedang memproses download...</p>
                </div>
            </div>

        </div>
    </div>

    {{-- Additional Information Card --}}
    @if ($selectedAnak && !empty($nilaiData))
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Laporan
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nama Anak:</strong></td>
                                <td>{{ $anakList->find($selectedAnak)->nama_lengkap ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Periode:</strong></td>
                                <td>{{ ucfirst($selectedPeriode) }}</td>
                            </tr>
                            @if ($selectedPeriode == 'mingguan')
                                <tr>
                                    <td><strong>Minggu:</strong></td>
                                    <td>Minggu ke-{{ $selectedWeek }} Tahun {{ $selectedYear }}</td>
                                </tr>
                            @elseif ($selectedPeriode == 'bulanan')
                                <tr>
                                    <td><strong>Bulan:</strong></td>
                                    <td>{{ $monthOptions[$selectedMonth] ?? '' }} {{ $selectedYear }}</td>
                                </tr>
                            @elseif ($selectedPeriode == 'semesteran')
                                <tr>
                                    <td><strong>Semester:</strong></td>
                                    <td>{{ ucfirst($selectedSemester) }} {{ $selectedYear }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Total Aspek:</strong></td>
                                <td>{{ count($nilaiData) }} Aspek</td>
                            </tr>
                            <tr>
                                <td><strong>Total Indikator:</strong></td>
                                <td>
                                    @php
                                        $totalIndikator = 0;
                                        foreach ($nilaiData as $aspek) {
                                            $totalIndikator += $aspek->count();
                                        }
                                    @endphp
                                    {{ $totalIndikator }} Indikator
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Generate:</strong></td>
                                <td>{{ now()->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Quick Actions Card --}}
    @if ($selectedAnak)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <button wire:click="$set('selectedPeriode', 'mingguan')"
                            class="btn btn-outline-primary w-100 mb-2 {{ $selectedPeriode == 'mingguan' ? 'active' : '' }}">
                            <i class="fas fa-calendar-week me-2"></i>Laporan Mingguan
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button wire:click="$set('selectedPeriode', 'bulanan')"
                            class="btn btn-outline-info w-100 mb-2 {{ $selectedPeriode == 'bulanan' ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt me-2"></i>Laporan Bulanan
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button wire:click="$set('selectedPeriode', 'semesteran')"
                            class="btn btn-outline-success w-100 mb-2 {{ $selectedPeriode == 'semesteran' ? 'active' : '' }}">
                            <i class="fas fa-calendar me-2"></i>Laporan Semesteran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
