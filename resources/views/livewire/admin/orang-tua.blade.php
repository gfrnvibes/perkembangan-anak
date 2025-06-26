<div>
    <div class="card">
        <div class="card-header">
            <div class="card-title mb-0">
                <h5>Kontak Orang Tua</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr class="text-center align-middle">
                            <th>No.</th>
                            <th>Wali</th>
                            <th>Nama Anak</th>
                            <th>Email</th>
                            <th>No. WhatsApp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anak as $item)                            
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td> {{ $item->ayah ?? $item->ibu ?? $item->wali }}
                                    {{-- Wali dari Nama Ayah.
                                    Jika ayah tidak ada, maka walinya dari Nama Ibu.
                                    Jika keduanya tidak ada, maka dari nama Wali --}}
                                </td>
                                <td>
                                   {{ $item->nama_lengkap ?? 'Tidak ada anak' }}
                                    {{-- Ambil nama anak yang sesuai dengan Nama Orang Tua nya --}}
                                </td>
                                <td>
                                    {{-- Tampilkan Email Orang Tua --}}
                                    {{-- Tampilkan Button untuk menghubungi orang tua lewat Email --}}
                                    {{-- Jika email tidak ada, tampilkan strip (-) --}}
                                    {{ $item->orangTua?->email }}
                                                                    
                                </td>
                                <td>
                                    {{-- Tampilkan WhatsApp Orang Tua --}}
                                    {{-- Tampilkan Button untuk menghubungi orang tua lewat WhatsApp.
                                        Jika nomor dimulai dengan 0, maka ganti dengan +62.
                                    --}}
                                    {{-- Jika WhatsApp tidak ada, tampilkan strip (-) --}}
                                    @if ($item->orangTua?->no_wa)
                                        <a href="https://wa.me/{{ $item->orangTua?->no_wa }}" target="_blank">
                                            {{ $item->orangTua?->no_wa }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Detail menuju ke livewire/admin/anak/detai-anak.php sesuai id anak --}}
                                    <a class="btn btn-primary btn-sm" href="{{ route('detail', $item->nama_lengkap) }}">
                                        <i class="uil-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
