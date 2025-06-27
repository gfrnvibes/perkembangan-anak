<div>
    <div class="card">
        <div class="card-header">
            <div class="card-title mb-0">
                <h5>Kontak Orang Tua</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                                </td>
                                <td>
                                   {{ $item->nama_lengkap ?? 'Tidak ada anak' }}
                                </td>
                                <td>
                                    @if ($item->orangTua?->email)
                                        <div class="d-flex align-items-center gap-2">
                                            <span>{{ $item->orangTua->email }}</span>
                                            <button type="button" class="btn btn-link p-0" title="Salin Email" onclick="navigator.clipboard.writeText('{{ $item->orangTua->email }}')">
                                                <i class="uil-copy"></i>
                                            </button>
                                            <a href="https://mail.google.com/mail/u/0/#inbox?compose=new&to={{ $item->orangTua->email }}" target="_blank" class="btn btn-link p-0" title="Kirim Email via Gmail">
                                                <i class="uil-envelope"></i>
                                            </a>
                                        </div>
                                    @else
                                        -
                                    @endif

                                </td>
                                <td>
                                    @if ($item->phone_number)
                                        @php
                                            $waNumber = preg_replace('/^0/', '+62', $item->phone_number);
                                        @endphp
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}" target="_blank">
                                            <i class="uil-whatsapp"></i>
                                            <span>{{ $item->phone_number }}</span>
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- Detail menuju ke livewire/admin/anak/detai-anak.php sesuai id anak --}}
                                    <a class="btn btn-primary btn-sm" href="{{ route('detail', $item->nama_lengkap) }}">
                                        <i class="uil-eye"></i> Detail Anak
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
