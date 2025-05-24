<div>
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Tambah Anak Baru</h4>
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form wire:submit.prevent="save">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Orang Tua</label>
                                        <select wire:model="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                            <option value="">-- Pilih Orang Tua --</option>
                                            @foreach($orangTuaList as $orangTua)
                                                <option value="{{ $orangTua->id }}">{{ $orangTua->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text" wire:model="nama_lengkap" id="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror">
                                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_panggilan" class="form-label">Nama Panggilan</label>
                                        <input type="text" wire:model="nama_panggilan" id="nama_panggilan" class="form-control @error('nama_panggilan') is-invalid @enderror">
                                        @error('nama_panggilan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nomor_induk" class="form-label">Nomor Induk</label>
                                        <input type="text" wire:model="nomor_induk" id="nomor_induk" class="form-control @error('nomor_induk') is-invalid @enderror">
                                        @error('nomor_induk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="nisn" class="form-label">NISN (Opsional)</label>
                                        <input type="text" wire:model="nisn" id="nisn" class="form-control @error('nisn') is-invalid @enderror">
                                        @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select wire:model="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" wire:model="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror">
                                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" wire:model="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror">
                                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="ayah" class="form-label">Nama Ayah (Opsional)</label>
                                        <input type="text" wire:model="ayah" id="ayah" class="form-control @error('ayah') is-invalid @enderror">
                                        @error('ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="ibu" class="form-label">Nama Ibu (Opsional)</label>
                                        <input type="text" wire:model="ibu" id="ibu" class="form-control @error('ibu') is-invalid @enderror">
                                        @error('ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                                        <textarea wire:model="alamat_lengkap" id="alamat_lengkap" class="form-control @error('alamat_lengkap') is-invalid @enderror" rows="4"></textarea>
                                        @error('alamat_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
