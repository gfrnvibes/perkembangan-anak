<div>
    {{-- Additional Information Card - Moved above table --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-info-circle me-2"></i>Informasi Laporan
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-sm">
                        @if ($selectedAnak)
                            <tr>
                                <td><strong>Nama Anak:</strong></td>
                                <td>{{ $anakList->find($selectedAnak)->nama_lengkap ?? '-' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td><strong>Mode:</strong></td>
                                <td><span class="badge bg-info">Download Semua Anak</span></td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>Periode:</strong></td>
                            <td>{{ ucfirst($selectedPeriode) }}</td>
                        </tr>
                        @if ($selectedPeriode == 'mingguan')
                            <tr>
                                <td><strong>Minggu:</strong></td>
                                <td>Minggu ke-{{ $selectedWeek ?? '-' }}, {{ $monthOptions[$selectedMonth] ?? '' }}
                                    {{ $selectedYear }}</td>
                            </tr>
                            <tr>
                                <td><strong>Semester:</strong></td>
                                <td>{{ $selectedMonth >= 7 && $selectedMonth <= 12 ? 'Ganjil' : 'Genap' }}</td>
                            </tr>
                        @elseif ($selectedPeriode == 'bulanan')
                            <tr>
                                <td><strong>Bulan:</strong></td>
                                <td>{{ $monthOptions[$selectedMonth] ?? '' }} {{ $selectedYear }}</td>
                            </tr>
                            <tr>
                                <td><strong>Semester:</strong></td>
                                <td>{{ $selectedMonth >= 7 && $selectedMonth <= 12 ? 'Ganjil' : 'Genap' }}</td>
                            </tr>
                        @elseif ($selectedPeriode == 'semesteran')
                            <tr>
                                <td><strong>Semester:</strong></td>
                                <td>{{ ucfirst($selectedSemester) }} {{ $selectedYear }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-sm">
                        @if ($selectedAnak && !empty($nilaiData))
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
                        @else
                            <tr>
                                <td><strong>Total Anak:</strong></td>
                                <td>{{ $anakList->count() }} Anak</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if ($selectedAnak)
                                        <span class="badge bg-danger">Data tidak tersedia</span>
                                    @else
                                        <span class="badge bg-success">Siap download semua</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>Tanggal Dibuat:</strong></td>
                            <td>{{ now()->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-3"><i class="fas fa-download me-2"></i>Download Laporan</h6>
                            <div class="d-flex gap-2">
                                {{-- Download Semua - Only show when no anak selected --}}
                                @if (
                                    !$selectedAnak &&
                                        (($selectedPeriode == 'mingguan' && $selectedWeek && $selectedMonth && $selectedYear) ||
                                            ($selectedPeriode == 'bulanan' && $selectedMonth && $selectedYear) ||
                                            ($selectedPeriode == 'semesteran' && $selectedSemester && $selectedYear)))
                                    <button wire:click="downloadAllPDF" class="btn btn-info">
                                        <i class="fas fa-download me-2"></i>Download Semua Anak
                                    </button>
                                @endif

                                {{-- Download Individual - Only when anak selected and data available --}}
                                @if ($selectedAnak && !empty($nilaiData))
                                    <button wire:click="downloadPDF" class="btn btn-success">
                                        <i class="fas fa-file-pdf me-2"></i>Download
                                        {{ $anakList->find($selectedAnak)->nama_lengkap ?? 'Anak Ini' }}
                                    </button>
                                @endif

                                {{-- Info when no complete filter --}}
                                @if (
                                    ($selectedPeriode == 'mingguan' && (!$selectedWeek || !$selectedMonth || !$selectedYear)) ||
                                        ($selectedPeriode == 'bulanan' && (!$selectedMonth || !$selectedYear)) ||
                                        ($selectedPeriode == 'semesteran' && (!$selectedSemester || !$selectedYear)))
                                    <div class="alert alert-warning mb-0 py-2">
                                        <small><i class="fas fa-info-circle me-1"></i>Lengkapi filter untuk mengaktifkan
                                            download</small>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
                    <div class="col-md-2">
                        <label class="form-label">Pilih Minggu</label>
                        <select wire:model.live="selectedWeek" class="form-select">
                            <option value="">-- Pilih Minggu --</option>
                            @foreach ($weekOptions as $weekNumber => $weekLabel)
                                <option value="{{ $weekNumber }}">{{ $weekLabel }}</option>
                            @endforeach
                        </select>
                    </div>
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
                                    <th rowspan="2">CATATAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilaiData as $aspekNama => $nilaiAspek)
                                    <tr>
                                        <td colspan="4" class="align-middle fw-bold bg-light">
                                            {{ $aspekNama }}
                                        </td>
                                    </tr>
                                    @foreach ($nilaiAspek as $nilai)
                                        <tr>
                                            <td class="text-center">IND-{{ $nilai->indikator->id }}</td>
                                            <td>{{ $nilai->indikator->nama_indikator }}</td>
                                            <td class="text-center">
                                                @if ($nilai->nilai_numerik)
                                                    <span
                                                        class="badge bg-success">{{ $nilaiMapping[$nilai->nilai_numerik] }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $nilai->catatan ?? '-' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    {{-- Tabel Bulanan --}}
                    @if ($selectedPeriode == 'bulanan')
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
                                            <td class="text-center">IND-{{ $nilai->indikator->id }}</td>
                                            <td>{{ $nilai->indikator->nama_indikator }}</td>

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
                                            <td class="text-center">IND-{{ $nilai->indikator->id }}</td>
                                            <td>{{ $nilai->indikator->nama_indikator }}</td>

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
                    <small class="text-muted">
                        Pastikan data sudah diinput untuk:
                        @if ($selectedPeriode == 'mingguan')
                            Minggu {{ $selectedWeek }}, {{ $monthOptions[$selectedMonth] ?? '' }} {{ $selectedYear }}
                        @elseif ($selectedPeriode == 'bulanan')
                            {{ $monthOptions[$selectedMonth] ?? '' }} {{ $selectedYear }}
                        @else
                            Semester {{ ucfirst($selectedSemester) }} {{ $selectedYear }}
                        @endif
                    </small>
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
                <p class="mt-2 text-muted">Memuat data dari JSON structure...</p>
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

    {{-- Debug Info (Development Only) --}}
    @if (config('app.debug') && $selectedAnak)
        <div class="card mt-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-bug"></i> Debug Info (Development Only)
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <small>
                            <strong>Selected Values:</strong><br>
                            Anak ID: {{ $selectedAnak }}<br>
                            Periode: {{ $selectedPeriode }}<br>
                            @if ($selectedPeriode == 'mingguan')
                                Minggu: {{ $selectedWeek }}<br>
                                Bulan: {{ $selectedMonth }}<br>
                            @elseif ($selectedPeriode == 'bulanan')
                                Bulan: {{ $selectedMonth }}<br>
                            @else
                                Semester: {{ $selectedSemester }}<br>
                            @endif
                            Tahun: {{ $selectedYear }}
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small>
                            <strong>Data Structure:</strong><br>
                            Total Aspek: {{ count($nilaiData) }}<br>
                            @if (!empty($nilaiData))
                                Aspek Names: {{ implode(', ', array_keys($nilaiData)) }}<br>
                            @endif
                            JSON Path:
                            @if ($selectedPeriode == 'mingguan')
                                semester_{{ $selectedMonth >= 7 && $selectedMonth <= 12 ? 'ganjil' : 'genap' }}.aspek_X.indikator_Y.bulan_{{ $selectedMonth }}.minggu_{{ $selectedWeek }}
                            @elseif ($selectedPeriode == 'bulanan')
                                semester_{{ $selectedMonth >= 7 && $selectedMonth <= 12 ? 'ganjil' : 'genap' }}.aspek_X.indikator_Y.bulan_{{ $selectedMonth }}.minggu_1-4
                            @else
                                semester_{{ $selectedSemester }}.aspek_X.indikator_Y.bulan_1-12.minggu_1-4
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
