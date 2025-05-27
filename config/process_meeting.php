<?php
$servername = "localhost"; 
$username = "root";      
$password = "";          
$dbname = "e_meeting";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $date_time = $_POST["date_time"];
    $location = $_POST["location"];

    // Prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("INSERT INTO meetings (title, description, date_time, location) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $date_time, $location);

    if ($stmt->execute()) {
        echo "Jadwal Meeting berhasil disimpan!";
    } else {
        echo "Gagal menyimpan jadwal: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>