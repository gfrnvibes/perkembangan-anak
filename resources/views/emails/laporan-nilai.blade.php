<!DOCTYPE html>
<html>
<head>
    <title>Laporan Nilai {{ $anak->nama_lengkap }}</title>
</head>
<body>
    <h2>Laporan Nilai Anak</h2>
    <h3>Periode Penilaian:</h3>
    <p><strong>{{ ucfirst($periode) }}</strong></p> {{-- Periode ditampilkan di sini --}}
    <p><strong>Nama:</strong> {{ $anak->nama_lengkap }}</p>
    <p><strong>NISN:</strong> {{ $anak->nisn }}</p>
    <p><strong>Jenis Kelamin:</strong> {{ $anak->jenis_kelamin }}</p>
    <p><strong>Tempat, Tanggal Lahir:</strong> {{ $anak->tempat_lahir }}, {{ $anak->tanggal_lahir }}</p>
    

    <p>Silakan cek file PDF terlampir untuk detail lengkap.</p>
</body>
</html>
