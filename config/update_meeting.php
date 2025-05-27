<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi sederhana
    if (empty($_POST['title']) || empty($_POST['date_time'])) {
        echo "Judul dan waktu meeting wajib diisi.";
        exit;
    }

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_time = $_POST['date_time'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE meetings SET title=?, description=?, date_time=?, location=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $description, $date_time, $location, $id);

    if ($stmt->execute()) {
        // Redirect setelah sukses update
        header("Location: list_meeting.php?msg=updated");
        exit;
    } else {
        echo "Gagal memperbarui jadwal.";
    }
}
?>
