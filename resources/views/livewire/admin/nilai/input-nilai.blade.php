<div>
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                Input Nilai Perkembangan Anak (Mingguan)
            </h3>

                    <div class="row align-items-end">
                        <div class="col-md-6">
                            {{-- <label for="importFile" class="form-label">Import Nilai</label> --}}
                            <input type="file" class="form-control" wire:model="importFile" accept=".xlsx,.xls,.csv">
                            @error('importFile')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success" wire:click="importFromExcel"
                                    wire:loading.attr="disabled" wire:target="importFromExcel">
                                    <i class="mdi mdi-file-excel me-1"></i>
                                    <span wire:loading.remove wire:target="importFromExcel">Import Excel</span>
                                    <span wire:loading wire:target="importFromExcel">Importing...</span>
                                </button>

                                <button type="button" class="btn btn-info" wire:click="downloadTemplate"
                                    wire:loading.attr="disabled" wire:target="downloadTemplate">
                                    <i class="mdi mdi-download me-1"></i>
                                    <span wire:loading.remove wire:target="downloadTemplate">Download Template</span>
                                    <span wire:loading wire:target="downloadTemplate">Downloading...</span>
                                </button>
                            </div>
                        </div>
                    </div>

        </div>
        <div class="card-body p-4">

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

            {{-- Form Input --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="anak" class="form-label">Nama Anak <span class="text-danger">*</span></label>
                    <select wire:model.live="selectedAnak"
                        class="form-select @error('selectedAnak') is-invalid @enderror">
                        <option value="">-- Pilih Anak --</option>
                        @foreach ($anakList as $anak)
                            <option value="{{ $anak->id }}">{{ $anak->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    @error('selectedAnak')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="minggu" class="form-label">Minggu <span class="text-danger">*</span></label>
                    <select wire:model.live="selectedMinggu"
                        class="form-select @error('selectedMinggu') is-invalid @enderror">
                        <option value="1">Minggu 1</option>
                        <option value="2">Minggu 2</option>
                        <option value="3">Minggu 3</option>
                        <option value="4">Minggu 4</option>
                    </select>
                    @error('selectedMinggu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                    <select wire:model.live="selectedBulan"
                        class="form-select @error('selectedBulan') is-invalid @enderror">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">
                                {{ \Carbon\Carbon::create()->month($i)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                    @error('selectedBulan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                    <select wire:model.live="selectedTahun"
                        class="form-select @error('selectedTahun') is-invalid @enderror">
                        @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                    @error('selectedTahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select bg-light" disabled>
                        <option value="{{ $selectedSemester }}">
                            Semester {{ ucfirst($selectedSemester) }}
                        </option>
                    </select>
                </div>

            </div>

            {{-- Tabel Input Nilai --}}
            @if ($selectedAnak)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr class="text-center align-middle">
                                <th width="20%">Aspek Perkembangan</th>
                                {{-- <th width="10%">Kode</th> --}}
                                <th width="30%">Indikator</th>
                                <th width="15%">Nilai</th>
                                <th width="25%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aspekList as $aspek)
                                @foreach ($aspek->indikators as $index => $indikator)
                                    @php
                                        $key = "aspek_{$aspek->id}_indikator_{$indikator->id}";
                                    @endphp
                                    <tr class="align-middle">
                                        @if ($index == 0)
                                            <td rowspan="{{ $aspek->indikators->count() }}"
                                                class="align-middle fw-bold">
                                                {{ $aspek->nama_aspek }}
                                            </td>
                                        @endif
                                        {{-- <td class="text-center">{{ $indikator->kode_indikator ?? 'IND-' . $indikator->id }}</td> --}}
                                        <td>{{ $indikator->nama_indikator }}</td>
                                        <td class="text-center">
                                            <select wire:model.live="nilai.{{ $key }}"
                                                class="form-select form-select-sm @error('nilai.' . $key) is-invalid @enderror">
                                                <option value="">-- Pilih --</option>
                                                @foreach ($nilaiMapping as $nilaiKey => $label)
                                                    <option value="{{ $nilaiKey }}">{{ $nilaiKey }} -
                                                        {{ explode(' (', $label)[1] ?? $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('nilai.' . $key)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <textarea wire:model="catatan.{{ $key }}"
                                                class="form-control form-control-sm @error('catatan.' . $key) is-invalid @enderror" rows="3"
                                                placeholder="Catatan akan otomatis muncul saat memilih nilai, atau tulis catatan custom..."></textarea>
                                            @error('catatan.' . $key)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            {{-- Info jika catatan dari template --}}
                                            @if (!empty($catatan[$key]) && !empty($nilai[$key]))
                                                @php
                                                    $template = null;
                                                    $nilaiMappingCheck = [1 => 'BB', 2 => 'MB', 3 => 'BSH', 4 => 'BSB'];
                                                    $nilaiKode = $nilaiMappingCheck[$nilai[$key]] ?? null;
                                                    if ($nilaiKode) {
                                                        $template = \App\Models\TemplateCatatan::where(
                                                            'indikator_id',
                                                            $indikator->id,
                                                        )
                                                            ->where('nilai', $nilaiKode)
                                                            ->first();
                                                    }
                                                    $isFromTemplate =
                                                        $template && $catatan[$key] === $template->isi_template;
                                                @endphp

                                                @if ($isFromTemplate)
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle"></i> Dari template (dapat diedit)
                                                    </small>
                                                @else
                                                    <small class="text-success">
                                                        <i class="fas fa-edit"></i> Catatan custom
                                                    </small>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Info Periode --}}
                {{-- <div class="alert alert-warning mb-4">
                    <h6 class="alert-heading">
                        <i class="fas fa-calendar-week"></i> Periode Penilaian:
                    </h6>
                    <p class="mb-0">
                        <strong>Minggu {{ $selectedMinggu }}</strong> -
                        <strong>{{ DateTime::createFromFormat('!m', $selectedBulan)->format('F') }}
                            {{ $selectedTahun }}</strong> -
                        <strong>Semester {{ ucfirst($selectedSemester) }}</strong>
                    </p>
                    <small class="text-muted">
                        Penilaian dilakukan 1x per minggu. Data disimpan dalam format JSON ultra optimized - 1 record
                        per anak per tahun.
                    </small>
                </div> --}}

                {{-- Perbaikan bagian info storage --}}
                {{-- <div class="alert alert-info mb-4">
                    <h6 class="alert-heading">
                        <i class="fas fa-database"></i> Storage Ultra Optimized:
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0 small">
                                @php
                                    $totalIndikator = 0;
                                    foreach ($aspekList as $aspek) {
                                        $totalIndikator += $aspek->indikators->count();
                                    }
                                    $strukturLama = $totalIndikator * 12 * 4;
                                @endphp
                                <li><strong>Struktur Lama:</strong> {{ $strukturLama }} records per anak</li>
                                <li><strong>Struktur Baru:</strong> 1 record per anak per tahun</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0 small">
                                <li><strong>Pengurangan:</strong> ~99.9% lebih sedikit records</li>
                                <li><strong>Format:</strong> JSON dengan nested structure</li>
                            </ul>
                        </div>
                    </div>
                </div> --}}

                {{-- Keterangan Nilai --}}
                <div class="alert alert-info mb-4 mt-4">
                    <h6 class="alert-heading">Keterangan Penilaian:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>1 - BB:</strong> Belum Berkembang</li>
                                <li><strong>2 - MB:</strong> Mulai Berkembang</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="mb-0">
                                <li><strong>3 - BSH:</strong> Berkembang Sesuai Harapan</li>
                                <li><strong>4 - BSB:</strong> Berkembang Sangat Baik</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-secondary me-2" wire:click="resetForm">
                        <i class="fas fa-undo me-2"></i>Reset Form
                    </button>
                    <button type="button" class="btn btn-primary fw-bold px-4" wire:click="simpanNilai">
                        <i class="fas fa-save me-2"></i>Simpan Nilai & Catatan
                    </button>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-child fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Pilih anak terlebih dahulu untuk mulai input nilai</p>
                </div>
            @endif

            {{-- Informasi Tambahan --}}
            {{-- <div class="mt-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-lightbulb text-warning"></i> Tips Penggunaan Ultra Optimized:
                        </h6>
                        <ul class="mb-0 small">
                            <li>Pilih nilai terlebih dahulu, catatan akan otomatis muncul dari template</li>
                            <li>Anda dapat mengedit catatan sesuai kebutuhan untuk setiap anak</li>
                            <li>Catatan yang diedit akan ditandai sebagai "catatan custom"</li>
                            <li>Semua data disimpan dalam 1 record JSON per anak per tahun</li>
                            <li>Data yang sudah disimpan dapat diedit dengan memilih periode yang sama</li>
                            <li><strong>Keuntungan:</strong> Database lebih ringan, query lebih cepat, storage minimal
                            </li>
                        </ul>
                    </div>
                </div>
            </div> --}}

            {{-- Debug Info (hanya untuk development) --}}
            {{-- @if (config('app.debug') && $selectedAnak)
                <div class="mt-4">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0">
                                <i class="fas fa-bug"></i> Debug Info (Development Only)
                            </h6>
                        </div>
                        <div class="card-body">
                            <small>
                                <strong>Selected:</strong> Anak: {{ $selectedAnak }},
                                Minggu: {{ $selectedMinggu }},
                                Bulan: {{ $selectedBulan }},
                                Tahun: {{ $selectedTahun }},
                                Semester: {{ $selectedSemester }}
                            </small>
                            <br>
                            <small>
                                <strong>JSON Path:</strong>
                                semester_{{ $selectedSemester }} → aspek_X → indikator_Y → bulan_{{ $selectedBulan }}
                                → minggu_{{ $selectedMinggu }}
                            </small>
                        </div>
                    </div>
                </div>
            @endif --}}
        </div>
    </div>
</div>
