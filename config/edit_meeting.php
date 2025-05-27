<?php
require 'db.php';

$meeting = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM meetings WHERE id = $id");

    if ($result && $result->num_rows > 0) {
        $meeting = $result->fetch_assoc();
    } else {
        // Meeting tidak ditemukan
        echo "<p style='color:red;'>Meeting dengan ID tersebut tidak ditemukan.</p>";
        echo "<p><a href='list_meeting.php'>Kembali ke daftar meeting</a></p>";
        exit;
    }
} else {
    // ID tidak valid
    echo "<p style='color:red;'>ID meeting tidak valid.</p>";
    echo "<p><a href='list_meeting.php'>Kembali ke daftar meeting</a></p>";
    exit;
}
?>

<form action="update_meeting.php" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($meeting['id']) ?>">
    <label>Judul Meeting:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($meeting['title']) ?>" required><br>

    <label>Deskripsi:</label>
    <textarea name="description"><?= htmlspecialchars($meeting['description']) ?></textarea><br>

    <label>Waktu Meeting:</label>
    <input type="datetime-local" name="date_time" value="<?= date('Y-m-d\TH:i', strtotime($meeting['date_time'])) ?>" required><br>

    <label>Lokasi:</label>
    <input type="text" name="location" value="<?= htmlspecialchars($meeting['location']) ?>"><br>

    <button type="submit">Update</button>
</form>
