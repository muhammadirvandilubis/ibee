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

// Ambil data dari tabel sarang1 dengan urutan terbaru di atas
$sql1 = "SELECT id, temperature, humidity, sound_value, timestamp FROM sarang1 ORDER BY timestamp DESC";
$result1 = $conn->query($sql1);

// Ambil data dari tabel sarang2 dengan urutan terbaru di atas
$sql2 = "SELECT id, temperature, humidity, sound_value, timestamp FROM sarang2 ORDER BY timestamp DESC";
$result2 = $conn->query($sql2);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Sensor</title>
    <style>
        body {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .table-container {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="table-container">
    <h2>Data Sensor Sarang 1</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Temperature (°C)</th>
            <th>Humidity (%)</th>
            <th>Sound Value</th>
            <th>Timestamp</th>
        </tr>
        <?php
        if ($result1->num_rows > 0) {
            // Output data setiap baris dari sarang1
            while($row = $result1->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["temperature"] . "</td>";
                echo "<td>" . $row["humidity"] . "</td>";
                echo "<td>" . $row["sound_value"] . "</td>";
                echo "<td>" . $row["timestamp"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
</div>

<div class="table-container">
    <h2>Data Sensor Sarang 2</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Temperature (°C)</th>
            <th>Humidity (%)</th>
            <th>Sound Value</th>
            <th>Timestamp</th>
        </tr>
        <?php
        if ($result2->num_rows > 0) {
            // Output data setiap baris dari sarang2
            while($row = $result2->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["temperature"] . "</td>";
                echo "<td>" . $row["humidity"] . "</td>";
                echo "<td>" . $row["sound_value"] . "</td>";
                echo "<td>" . $row["timestamp"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
</div>

<?php
$conn->close();
?>

</body>
</html>
