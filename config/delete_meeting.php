<?php
$servername = "localhost"; 
$username = "root";      
$password = "";          
$dbname = "e_meeting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = $_GET["id"];

    $stmt = $conn->prepare("DELETE FROM meetings WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Jadwal Meeting berhasil dihapus!";
        echo '<p><a href="list_meetings.php">Kembali ke Daftar Jadwal</a></p>';
    } else {
        echo "Gagal menghapus jadwal: " . $stmt->error;
        echo '<p><a href="list_meetings.php">Kembali ke Daftar Jadwal</a></p>';
    }

    $stmt->close();
} else {
    echo "ID jadwal tidak valid.";
    echo '<p><a href="list_meetings.php">Kembali ke Daftar Jadwal</a></p>';
}

$conn->close();
?>