<div class="p-5 mt-3">
    <div class="text-center">
        <img src="{{ asset('assets/images/paud.png') }}" alt="" style="width: 100px; height: 100px;">
        <div class="mb-4 mt-3">            
            <h2>Selamat Datang di Dashboard Penilaian Perkembangan Anak</h2>
            <h3>RA Nurul Amin Kec.Samarang, Kab. Garut</h3>
        </div>
        <div class="d-flex gap-3 align-items-center justify-content-center">
            <a href="{{ route('input-nilai') }}" class="btn btn-lg btn-primary fw-bold">Input Nilai Anak</a>
            <a href="{{ route('daftar-nilai') }}" class="btn btn-lg btn-warning fw-bold">Lihat Nilai Anak</a>
            <a href="{{ route('daftar-anak') }}" class="btn btn-lg btn-danger fw-bold">Lihat Daftar Anak</a>
        </div>
    </div>
    
</div>
