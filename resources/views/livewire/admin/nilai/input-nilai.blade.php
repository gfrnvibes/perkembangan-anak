<div>
    <div class="card">
        <div class="card-header fw-bold fs-4">
               Input Nilai Perkembangan Anak
        </div>
        <div class="card-body p5">
            <div class="d-flex gap-3 mb-3">
                <div class="col">
                    <label for="anak" class="form-label">Nama Anak</label>
                    <select wire:model="selectedAnak" class="form-select">
                        <option value="">-- Pilih Anak --</option>
                        @foreach($anakList as $anak)
                            <option value="{{ $anak->id }}">{{ $anak->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="minggu" class="form-label">Minggu Ke</label>
                    <select wire:model="selectedMinggu" class="form-select">
                        <option value="">-- Pilih Minggu --</option>
                        @foreach($mingguList as $key => $minggu)
                            <option value="{{ $key }}">{{ $minggu }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>Aspek Perkembangan</th>
                            <th>Indikator</th>
                            <th>Nilai</th>
                            <th>Rata-rata</th>
                            <th>Predikat</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aspekList as $aspek)
                            @foreach($aspek->indikator as $indikator)
                                <tr class="text-center align-middle">
                                    @if($loop->first)
                                        <td rowspan="{{ $aspek->indikator->count() }}">{{ $aspek->nama_aspek }}</td>
                                    @endif
                                    <td class="text-start">{{ $indikator->deskripsi }}</td>
                                    <td>
                                        <input type="number" class="form-control" 
                                            wire:model="nilai.{{ $indikator->id }}">
                                    </td>
                                    <td>
                                        {{ isset($nilai[$indikator->id]) ? number_format($nilai[$indikator->id], 2) : '0.00' }}
                                    </td>
                                    <td>
                                        @php
                                            $predikat = '';
                                            $badgeClass = 'text-bg-secondary';
                                            
                                            if(isset($nilai[$indikator->id])) {
                                                $nilai_angka = $nilai[$indikator->id];
                                                if($nilai_angka >= 3.5) {
                                                    $predikat = 'BSB';
                                                    $badgeClass = 'text-bg-success';
                                                } elseif($nilai_angka >= 2.5) {
                                                    $predikat = 'BSH';
                                                    $badgeClass = 'text-bg-primary';
                                                } elseif($nilai_angka >= 1.5) {
                                                    $predikat = 'MB';
                                                    $badgeClass = 'text-bg-warning';
                                                } else {
                                                    $predikat = 'BB';
                                                    $badgeClass = 'text-bg-danger';
                                                }
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $predikat ?: '-' }}</span>
                                    </td>
                                    <td class="text-start">
                                        @if(isset($nilai[$indikator->id]))
                                            @php
                                                $nilai_angka = $nilai[$indikator->id];
                                                if($nilai_angka >= 3.5) {
                                                    echo "Berkembang Sangat Baik";
                                                } elseif($nilai_angka >= 2.5) {
                                                    echo "Berkembang Sesuai Harapan";
                                                } elseif($nilai_angka >= 1.5) {
                                                    echo "Mulai Berkembang";
                                                } else {
                                                    echo "Belum Berkembang";
                                                }
                                            @endphp
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data aspek dan indikator</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- @if($selectedAnak && $selectedMinggu) --}}
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary fw-bold" wire:click="simpanNilai">Simpan Nilai</button>
                </div>
            {{-- @endif --}}
        </div>
    </div>
</div>
