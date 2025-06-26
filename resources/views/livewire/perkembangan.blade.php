<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Grafik Perkembangan Anak
                    </h5>
                </div>

                <div class="card-body">
                    {{-- Filter Section --}}
                    <div class="row mb-4">
                        {{-- Pilih Anak --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Pilih Anak</label>
                            <select wire:model.live="selectedAnak" class="form-select">
                                <option value="">-- Pilih Anak --</option>
                                @foreach ($anakList as $anak)
                                    <option value="{{ $anak['id'] }}">{{ $anak['nama_lengkap'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pilih Tahun --}}
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <select wire:model.live="selectedTahun" class="form-select">
                                @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Pilih Semester --}}
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Semester</label>
                            <select wire:model.live="selectedSemester" class="form-select">
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>

                        {{-- Pilih Bulan --}}
                        <div class="col-md-2 mb-3">
                            <label class="form-label fw-bold">Bulan</label>
                            <select wire:model.live="selectedBulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @if ($selectedSemester == 'ganjil')
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                @else
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                @endif
                            </select>
                        </div>

                        {{-- Pilih Aspek --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Aspek Perkembangan</label>
                            <select wire:model.live="selectedAspek" class="form-select">
                                <option value="">Semua Aspek</option>
                                @foreach ($aspekList as $aspek)
                                    <option value="{{ $aspek['id'] }}">{{ $aspek['nama_aspek'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Chart Container --}}
                    <div class="row">
                        <div class="col-12">
                            @if ($selectedAnak && count($chartData['labels']) > 0)
                                <div class="chart-container" style="position: relative; height: 400px;">
                                    <canvas id="perkembanganChart" wire:ignore></canvas>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                        <h5>Tidak Ada Data</h5>
                                        <p>
                                            @if (!$selectedAnak)
                                                Silakan pilih anak terlebih dahulu
                                            @else
                                                Belum ada data perkembangan untuk periode yang dipilih
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                    {{-- Keterangan Nilai --}}
                    @if ($selectedAnak && count($chartData['labels']) > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Keterangan Nilai:</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small>
                                                <strong>1 = BB:</strong> Belum Berkembang<br>
                                                <strong>2 = MB:</strong> Mulai Berkembang
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <small>
                                                <strong>3 = BSH:</strong> Berkembang Sesuai Harapan<br>
                                                <strong>4 = BSB:</strong> Berkembang Sangat Baik
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let perkembanganChart = null;

            function updateChart() {
                const ctx = document.getElementById('perkembanganChart');
                if (!ctx) return;

                // Get chart data from Livewire component
                const chartData = @json($chartData);

                // Check if data exists
                if (!chartData || !chartData.labels || chartData.labels.length === 0) {
                    return;
                }

                // Destroy existing chart
                if (perkembanganChart) {
                    perkembanganChart.destroy();
                }

                // Create new chart
                perkembanganChart = new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grafik Perkembangan Anak',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 4,
                                ticks: {
                                    stepSize: 1,
                                    callback: function(value) {
                                        const labels = {
                                            1: '1 (BB)',
                                            2: '2 (MB)',
                                            3: '3 (BSH)',
                                            4: '4 (BSB)'
                                        };
                                        return labels[value] || value;
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Nilai Perkembangan'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Aspek/Indikator'
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            }

            // Initialize chart when page loads
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(updateChart, 500);
            });

            // Listen for chart updates
            window.addEventListener('chart-update', function() {
                setTimeout(updateChart, 100);
            });

            // Livewire hooks
            document.addEventListener('livewire:init', () => {
                Livewire.on('chart-update', () => {
                    setTimeout(updateChart, 100);
                });
            });
        </script>
    @endpush


    @push('styles')
        <style>
            .chart-container {
                background: #fff;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .form-label {
                color: #495057;
                font-size: 0.875rem;
            }

            .form-select {
                border: 1px solid #ced4da;
                border-radius: 6px;
                font-size: 0.875rem;
            }

            .form-select:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .card {
                /* border: none; */
                border-radius: 10px;
            }

            .card-header {
                border-radius: 10px 10px 0 0 !important;
                border-bottom: none;
            }

            .alert-info {
                background-color: #e7f3ff;
                border-color: #b8daff;
                color: #004085;
                border-radius: 8px;
            }
        </style>
    @endpush
</div>
