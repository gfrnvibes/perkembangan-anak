<div>
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Edit Data Anak</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('daftar-anak') }}">Daftar Anak</a></li>
                            <li class="breadcrumb-item active">Edit Data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (session()->has('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form wire:submit.prevent="update">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Orang Tua</label>
                                        <input type="email" wire:model="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text" wire:model="nama_lengkap" id="nama_lengkap"
                                            class="form-control @error('nama_lengkap') is-invalid @enderror">
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_panggilan" class="form-label">Nama Panggilan</label>
                                        <input type="text" wire:model="nama_panggilan" id="nama_panggilan"
                                            class="form-control @error('nama_panggilan') is-invalid @enderror">
                                        @error('nama_panggilan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nomor_induk" class="form-label">Nomor Induk</label>
                                        <input type="number" wire:model="nomor_induk" id="nomor_induk"
                                            class="form-control @error('nomor_induk') is-invalid @enderror">
                                        @error('nomor_induk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nisn" class="form-label">NISN (Opsional)</label>
                                        <input type="number" wire:model="nisn" id="nisn"
                                            class="form-control @error('nisn') is-invalid @enderror">
                                        @error('nisn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select wire:model="jenis_kelamin" id="jenis_kelamin"
                                            class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label">Tempat, tanggal lahir</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">TTL</span>
                                        <input type="text" wire:model="tempat_lahir" id="tempat_lahir"
                                            class="form-control @error('tempat_lahir') is-invalid @enderror" placeholder="Tempat Lahir">
                                        @error('tempat_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <input type="date" wire:model="tanggal_lahir" id="tanggal_lahir"
                                            class="form-control @error('tanggal_lahir') is-invalid @enderror">
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="ayah" class="form-label">Nama Ayah (Opsional)</label>
                                        <input type="text" wire:model="ayah" id="ayah"
                                            class="form-control @error('ayah') is-invalid @enderror">
                                        @error('ayah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="ibu" class="form-label">Nama Ibu (Opsional)</label>
                                        <input type="text" wire:model="ibu" id="ibu"
                                            class="form-control @error('ibu') is-invalid @enderror">
                                        @error('ibu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                                        <textarea wire:model="alamat_lengkap" id="alamat_lengkap"
                                            class="form-control @error('alamat_lengkap') is-invalid @enderror" rows="4"></textarea>
                                        @error('alamat_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="pas_foto" class="form-label">Pas Foto</label>
                                        @if($existing_foto)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($existing_foto) }}" alt="Foto saat ini" 
                                                     class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                                <p class="text-muted small mt-1">Foto saat ini</p>
                                            </div>
                                        @endif
                                        <input type="file" wire:model="pas_foto" id="pas_foto"
                                            class="form-control @error('pas_foto') is-invalid @enderror">
                                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto</div>
                                        @error('pas_foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        @if($pas_foto)
                                            <div class="mt-2">
                                                <img src="{{ $pas_foto->temporaryUrl() }}" alt="Preview foto baru" 
                                                     class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                                <p class="text-muted small mt-1">Preview foto baru</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('daftar-anak') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i> Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

