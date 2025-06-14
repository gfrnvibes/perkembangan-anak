<div class="container p-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0">Profil Ananda</h4>
                                <small class="opacity-75">Informasi Lengkap Anak Didik</small>
                            </div>
                        </div>
                        
                        <!-- Dropdown Pilih Anak -->
                        @if($anakList->count() > 1)
                            <div class="dropdown">
                                <select wire:model.live="selectedAnakId" class="form-select form-select-sm bg-white">
                                    <option value="">-- Pilih Anak --</option>
                                    @foreach($anakList as $anakItem)
                                        <option value="{{ $anakItem->id }}">{{ $anakItem->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($anak)
                <!-- Main Profile Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- Profile Picture Section -->
                            <div class="col-md-3 text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 120px; height: 120px; margin: 0 auto;">
                                        <i class="fas fa-user text-white" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                                <h5 class="mt-3 mb-1 text-primary">{{ $anak->nama_lengkap }}</h5>
                                <p class="text-muted mb-0">{{ $anak->nama_panggilan }}</p>
                                    <span class="badge bg-danger mt-2">{{ $anak->orangTua->email }}</span>
                            </div>

                            <!-- Basic Information -->
                            <div class="col-md-9">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                        <p class="form-control-plaintext border-bottom">{{ $anak->nama_lengkap }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Nama Panggilan</label>
                                        <p class="form-control-plaintext border-bottom">{{ $anak->nama_panggilan }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Nomor Induk</label>
                                        <p class="form-control-plaintext border-bottom">{{ $anak->nomor_induk }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">NISN</label>
                                        <p class="form-control-plaintext border-bottom">
                                            {{ $anak->nisn ?: '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Birth Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-birthday-cake me-2"></i>Informasi Kelahiran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-muted">Tempat Lahir</label>
                                <p class="form-control-plaintext border-bottom">{{ $anak->tempat_lahir }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-muted">Tanggal Lahir</label>
                                <p class="form-control-plaintext border-bottom">
                                    {{ \Carbon\Carbon::parse($anak->tanggal_lahir)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold text-muted">Usia</label>
                                <p class="form-control-plaintext border-bottom">
                                    {{ \Carbon\Carbon::parse($anak->tanggal_lahir)->age }} tahun
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-users me-2"></i>Informasi Keluarga
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Nama Ayah</label>
                                <p class="form-control-plaintext border-bottom">
                                    {{ $anak->ayah ?: '-' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Nama Ibu</label>
                                <p class="form-control-plaintext border-bottom">
                                    {{ $anak->ibu ?: '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold text-muted">Alamat Lengkap</label>
                                <p class="form-control-plaintext border-bottom">{{ $anak->alamat_lengkap }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-chart-bar me-2"></i>Statistik Perkembangan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <i class="fas fa-clipboard-list text-primary fs-2 mb-2"></i>
                                    <h4 class="text-primary mb-1">{{ $anak->nilais->count() }}</h4>
                                    <small class="text-muted">Total Penilaian</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <i class="fas fa-calendar-alt text-success fs-2 mb-2"></i>
                                    <h4 class="text-success mb-1">
                                        {{ $anak->nilais->where('created_at', '>=', now()->startOfMonth())->count() }}
                                    </h4>
                                    <small class="text-muted">Penilaian Bulan Ini</small>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <i class="fas fa-clock text-info fs-2 mb-2"></i>
                                    <div class="text-info mb-1">
                                        @if($anak->nilais->sortByDesc('created_at')->first())

                                            <small style="font-size: 0.9rem;">
                                                {{ $anak->nilais->sortByDesc('created_at')->first()->created_at->locale('id')->diffForHumans() }}
                                            </small>
                                        @else
                                            <small>Belum ada</small>
                                        @endif
                                    </div>
                                    <small class="text-muted">Penilaian Terakhir</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Information Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-info me-2"></i>Informasi Pendaftaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Tanggal Pendaftaran</label>
                                <p class="form-control-plaintext border-bottom">
                                    {{ $anak->created_at->locale('id')->isoFormat('DD MMMM YYYY') }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted">Terakhir Diperbarui</label>
                                <p class="form-control-plaintext border-bottom">
                                    {{ $anak->updated_at->locale('id')->isoFormat('DD MMMM YYYY HH:mm') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($anakList->count() == 0)
                <!-- No Children Registered -->
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-user-plus text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-3">Belum Ada Anak Terdaftar</h4>
                        <p class="text-muted mb-4">Anda belum mendaftarkan anak. Silakan hubungi administrator untuk mendaftarkan anak Anda.</p>
                        <button class="btn btn-primary" onclick="history.back()">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                    </div>
                </div>
            @else
                <!-- No Data State -->
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-user-slash text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-3">Data Anak Tidak Ditemukan</h4>
                        <p class="text-muted mb-4">Silakan pilih anak terlebih dahulu dari dropdown di atas.</p>
                        <button class="btn btn-primary" onclick="history.back()">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>