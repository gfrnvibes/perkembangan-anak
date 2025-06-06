<div>
    <div class="container-fluid bg-danger">
        <div class="container p-5">
            <div class="d-flex justify-content-center align-items-center gap-4">
                <img src="{{ asset('assets/images/users/avatar-2.jpg') }}" alt="" class="img-fluid img-thumbnail" style="width: auto; height: 100px;">
                <h1 class="text-white">Perkembangan Ananda Husni</h1>
            </div>
        </div>
    </div>
    <div class="container p-5">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['BB', 'MB', 'BSH', 'BSB'],
            datasets: [{
                label: '# of Votes',
                data: [9, 12, 3, 5, 2, 3],
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
