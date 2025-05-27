<?php
require 'db.php';

$keyword = $_GET['keyword'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$query = "SELECT * FROM meetings WHERE 1=1";

if ($keyword) {
    $query .= " AND title LIKE '%$keyword%'";
}

if ($start_date && $end_date) {
    $query .= " AND date_time BETWEEN '$start_date' AND '$end_date'";
}

$query .= " ORDER BY date_time ASC";
$result = $conn->query($query);

echo "<h2>📋 Hasil Filter Jadwal</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<p><strong>{$row['title']}</strong> - {$row['date_time']}</p>";
}
?>
<form method="get">
    <input type="text" name="keyword" placeholder="Cari judul..." value="<?= htmlspecialchars($keyword) ?>">
    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
    <button type="submit">🔍 Filter</button>
</form>
