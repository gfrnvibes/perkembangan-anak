<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Detail Data Anak</h4>
                    <div>
                        <a href="{{ route('edit', $anak->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('daftar-anak') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Foto Profil -->
                        <div class="col-md-3 text-center mb-4">
                            @if($anak->pas_foto)
                                <img src="{{ Storage::url($anak->pas_foto) }}" alt="Foto {{ $anak->nama_lengkap }}" 
                                     class="img-fluid rounded-circle border" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 200px; height: 200px;">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                            @endif
                            <h5 class="mt-3 mb-1">{{ $anak->nama_lengkap }}</h5>
                            <p class="text-muted">{{ $anak->nama_panggilan }}</p>
                        </div>

                        <!-- Data Pribadi -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Data Pribadi
                                    </h5>
                                    
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Nomor Induk</td>
                                            <td>: {{ $anak->nomor_induk }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">NISN</td>
                                            <td>: {{ $anak->nisn ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Jenis Kelamin</td>
                                            <td>: 
                                                @if($anak->jenis_kelamin == 'Laki-laki')
                                                    <span class="badge bg-primary">Laki-laki</span>
                                                @else
                                                    <span class="badge bg-danger">Perempuan</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tempat Lahir</td>
                                            <td>: {{ $anak->tempat_lahir }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tanggal Lahir</td>
                                            <td>: {{ \Carbon\Carbon::parse($anak->tanggal_lahir)->format('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Umur</td>
                                            <td>: {{ \Carbon\Carbon::parse($anak->tanggal_lahir)->age }} tahun</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-users me-2"></i>Data Keluarga
                                    </h5>
                                    
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Orang Tua</td>
                                            <td>: {{ $anak->orangTua->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Email Orang Tua</td>
                                            <td>: {{ $anak->orangTua->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Ayah</td>
                                            <td>: {{ $anak->ayah }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Ibu</td>
                                            <td>: {{ $anak->ibu }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Wali</td>
                                            <td>: {{ $anak->wali ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Alamat</td>
                                            <td>: {{ $anak->alamat_lengkap }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Informasi Tambahan -->
                    {{-- <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                            </h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Terdaftar Sejak</td>
                                    <td>: {{ $anak->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Terakhir Diupdate</td>
                                    <td>: {{ $anak->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-chart-line me-2"></i>Statistik Penilaian
                            </h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 40%;">Total Penilaian</td>
                                    <td>: {{ $anak->nilais->count() }} penilaian</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status</td>
                                    <td>: 
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div> --}}

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('edit', $anak->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Edit Data
                                </a>
                                <button class="btn btn-info" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i> Cetak
                                </button>
                                <a href="{{ route('daftar-anak') }}" class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i> Daftar Anak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    @media print {
        .btn, .card-header .btn {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
    }
    </style>
</div>

