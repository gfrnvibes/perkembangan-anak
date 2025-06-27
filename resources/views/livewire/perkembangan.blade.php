<div class="container py-4">
    <div class="row">
        <div class="col-12">
            {{-- Tabel Nilai Section --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2"></i>
                        Tabel Nilai Perkembangan
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Filter Section untuk Tabel --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Periode Laporan</label>
                            <select wire:model.live="selectedPeriode" class="form-select">
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="semesteran">Semesteran</option>
                            </select>
                        </div>

                        {{-- Filter Mingguan --}}
                        @if ($selectedPeriode == 'mingguan')
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Pilih Minggu</label>
                                <select wire:model.live="selectedWeek" class="form-select">
                                    <option value="">-- Pilih Minggu --</option>
                                    @foreach ($weekOptions as $weekNumber => $weekLabel)
                                        <option value="{{ $weekNumber }}">{{ $weekLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Bulan</label>
                                <select wire:model.live="selectedMonth" class="form-select">
                                    @foreach ($monthOptions as $monthNumber => $monthName)
                                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Tahun</label>
                                <select wire:model.live="selectedTahun" class="form-select">
                                    @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        @endif

                        {{-- Filter Bulanan --}}
                        @if ($selectedPeriode == 'bulanan')
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Bulan</label>
                                <select wire:model.live="selectedMonth" class="form-select">
                                    @foreach ($monthOptions as $monthNumber => $monthName)
                                        <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Tahun</label>
                                <select wire:model.live="selectedTahun" class="form-select">
                                    @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        @endif

                        {{-- Filter Semesteran --}}
                        @if ($selectedPeriode == 'semesteran')
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Semester</label>
                                <select wire:model.live="selectedSemester" class="form-select">
                                    <option value="ganjil">Ganjil (Jul-Des)</option>
                                    <option value="genap">Genap (Jan-Jun)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Tahun</label>
                                <select wire:model.live="selectedTahun" class="form-select">
                                    @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        @endif
                    </div>

                    @if ($selectedAnak && !empty($nilaiData))
                        <div class="table-responsive">
                            {{-- Tabel Mingguan --}}
                            @if ($selectedPeriode == 'mingguan')
                                <table class="table table-bordered table-striped">
                                    <thead class="table-success text-center align-middle">
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
                                    <thead class="table-success text-center align-middle">
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
                                    <thead class="table-success text-white align-middle text-center">
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
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-child fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Pilih anak untuk melihat data nilai</p>
                        </div>
                    @endif

                    {{-- Keterangan Nilai --}}
                    @if ($selectedAnak && !empty($nilaiData))
                        <div class="alert alert-info mb-0 mt-4">
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
