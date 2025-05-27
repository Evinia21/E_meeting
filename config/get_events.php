<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_meeting";

// Set header untuk JSON
header('Content-Type: application/json');

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    // Kirim respons error JSON
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit();
}

$sql = "SELECT id, title, date_time FROM meetings";
$result = $conn->query($sql);

$events = [];
if ($result) { // Pastikan objek hasil query valid
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = [
                'title' => $row['title'],
                'start' => $row['date_time'],
                'url' => 'list_meetings.php?id=' . $row['id']
            ];
        }
    }
    $result->free(); // Bebaskan memori hasil query
} else {
    // Kirim respons error JSON jika query gagal
    http_response_code(500);
    echo json_encode(['error' => 'Gagal menjalankan query: ' . $conn->error]);
    $conn->close();
    exit();
}

// Mengirim data JSON
echo json_encode($events);

// Menutup koneksi
$conn->close();
?>