<?php
$servername = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "dbibee";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest data
$latest_query = "SELECT temperature, humidity, sound_value FROM sarang1 ORDER BY timestamp DESC LIMIT 1";
$latest_result = $conn->query($latest_query);
$latest_data = $latest_result->fetch_assoc();

// Fetch historical data for the chart
$history_query = "SELECT timestamp, sound_value FROM sarang1 ORDER BY timestamp DESC LIMIT 20";
$history_result = $conn->query($history_query);

$labels = [];
$sound_data = [];

while ($row = $history_result->fetch_assoc()) {
    $labels[] = date('H:i:s', strtotime($row['timestamp']));
    $sound_data[] = $row['sound_value'];
}

// Membalik urutan data agar yang terbaru di kanan
$labels = array_reverse($labels);
$sound_data = array_reverse($sound_data);

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temperature = $_POST["temperature"];
    $humidity = $_POST["humidity"];
    $sound_value = $_POST["sound_value"];

    // Using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO sarang1 (temperature, humidity, sound_value) VALUES (?, ?, ?)");
    $stmt->bind_param("ddd", $temperature, $humidity, $sound_value);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch historical data for the table
$table_query = "SELECT * FROM sarang1 ORDER BY timestamp DESC";
$table_result = $conn->query($table_query);

$conn->close(); // Close the connection at the end

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarang 1 - iBee</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic styling for the table */
        .table-container {
            margin-top: 20px;
            width: 100%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9e9e9;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .chart-container {
            margin-top: 20px;
            width: 100%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .sidebar {
            width: 15%;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f4f4f4;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
        }

        .sidebar-header img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .sidebar-menu a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            font-size: 16px;
        }

        .sidebar-menu a:hover {
            background-color: #ddd;
        }

        .cards {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .card {
            width: 45%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-body {
            text-align: center;
        }

        .card-body.blue {
            background-color: #e3f2fd;
            color: #0277bd;
        }

        .card-body.green {
            background-color: #e8f5e9;
            color: #388e3c;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="logo.jpeg" alt="Logo">
            <span>iBee</span>
        </div>
        <div class="sidebar-menu">
            <a href="index2.php"><i class="fas fa-home"></i> Home</a>
            <a href="sarang1.php"><i class="fas fa-beehive"></i> Sarang 1</a>
            <a href="sarang2.php"><i class="fas fa-beehive"></i> Sarang 2</a>
        </div>
    </div>
    <div class="content" style="margin-left: 260px; padding: 20px;">
        <h1>Data Sarang 1</h1>
        <div class="cards">
            <div class="card">
                <div class="card-body blue">
                    <h2><?php echo $latest_data['temperature']; ?> &deg;C</h2>
                    <p>Suhu Sarang</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body green">
                    <h2><?php echo $latest_data['humidity']; ?> %</h2>
                    <p>Kelembapan Sarang</p>
                </div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="soundChart" width="800" height="400"></canvas>
        </div>
        <div class="table-container">
            <h2>Data Keseluruhan (History)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Suhu (&deg;C)</th>
                        <th>Kelembapan (%)</th>
                        <th>Suara (dB)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($table_result->num_rows > 0) {
                        while($row = $table_result->fetch_assoc()) {
                            $formatted_time = date('d-m-Y H:i:s', strtotime($row['timestamp']));
                            echo "<tr>";
                            echo "<td>{$formatted_time}</td>";
                            echo "<td>{$row['temperature']}</td>";
                            echo "<td>{$row['humidity']}</td>";
                            echo "<td>{$row['sound_value']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('soundChart').getContext('2d');
        const soundChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Sensor Suara',
                    data: <?php echo json_encode($sound_data); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Suara (dB)'
                        },
                        ticks: {
                            stepSize: 1 // Interval of 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
