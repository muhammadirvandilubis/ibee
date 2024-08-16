<h1>Sarang 2</h1>
<div class="cards">
    <div class="card">
        <div class="card-body blue">
            <h2>26.00 &deg;C</h2>
            <p>Suhu Ruang</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body green">
            <h2>60.00 %</h2>
            <p>Kelembapan Ruang</p>
        </div>
    </div>
</div>
<h2>Grafik Suara</h2>
<canvas id="soundChart"></canvas>
<script>
    const ctx = document.getElementById('soundChart').getContext('2d');
    const soundChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Sensor Suara',
                data: [15, 25, 20, 30, 35, 25, 15],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
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
