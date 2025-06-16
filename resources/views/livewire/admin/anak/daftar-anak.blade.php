<div>
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


    <div class="card">
        <div class="card-header border fw-bold fs-4 d-flex justify-content-between align-items-center">
            <span>Daftar Anak</span>
            <div>
                <a href="{{ route('create-anak') }}" class="btn btn-primary fw-bold" ><i
                        class="bi bi-person-add me-2"></i>Tambah Anak</a>
                <button class="btn btn-success fw-bold" wire:click="exportExcel"><i class="bi bi-table me-2"></i>Unduh
                    Excel</button>
            </div>
        </div>
        <div class="card-body">
            {{-- Import Excel, Search by Name, Hide/Unhide Column, Filter by Jenis Kelamin, Sort by Name  --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="file" wire:model="importExcel" class="form-control" id="importExcel">
                        <button class="btn btn-outline-secondary" type="button" wire:click="importExcelFile"
                            wire:loading.attr="disabled">
                            <span wire:loading wire:target="importExcelFile" class="spinner-border spinner-border-sm"
                                role="status" aria-hidden="true"></span>
                            Import
                        </button>
                    </div>
                    @error('importExcel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                        placeholder="Cari nama, nomor induk, atau NISN...">
                </div>
                <div class="col-md-4">
                    <select wire:model.live="filterJenisKelamin" class="form-select">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="row mb-3">
                <div class="col-md-2">
                    <select wire:model.live="perPage" class="form-select">
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                        <option value="100">100 per halaman</option>
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive" style="overflow-x: auto; width: 100;">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr class="text-center align-middle">
                            <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer;">
                                Nama Lengkap
                                @if ($sortField === 'nama_lengkap')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('nomor_induk')" style="cursor: pointer;">
                                Nomor Induk
                                @if ($sortField === 'nomor_induk')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('nisn')" style="cursor: pointer;">
                                NISN
                                @if ($sortField === 'nisn')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('jenis_kelamin')" style="cursor: pointer;">
                                Jenis Kelamin
                                @if ($sortField === 'jenis_kelamin')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>TTL</th>
                            <th>Email Orang Tua</th>
                            <th>Nama Ayah</th>
                            <th>Nama Ibu</th>
                            {{-- <th>Alamat</th> --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anak as $item)
                            <tr class="align-middle">
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->nomor_induk }}</td>
                                <td>{{ $item->nisn }}</td>
                                <td class="text-center">
                                    @if ($item->jenis_kelamin == 'Laki-laki')
                                        L
                                    @else
                                        P
                                    @endif
                                <td>{{ $item->tempat_lahir }},
                                    {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                                <td>{{ $item->orangTua?->email }}</td>
                                <td>{{ $item->ayah }}</td>
                                <td>{{ $item->ibu }}</td>
                                {{-- <td>{{ $item->alamat_lengkap }}</td> --}}
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm"
                                            wire:click="editAnak({{ $item->id }})"><i
                                                class="bi bi-pencil-square"></i></button>
                                        <button class="btn btn-danger btn-sm" wire:click="deleteAnak({{ $item->id }})"
                                            wire:confirm='Apa kamu yakin ingin menghapus ini?'><i
                                                class="bi bi-trash"></i></button>
                                        <button class="btn btn-info btn-sm" wire:click="showAnak({{ $item->id }})"><i
                                                class="bi bi-eye"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data anak</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $anak->links() }}
            </div>
        </div>
    </div>


</div>
