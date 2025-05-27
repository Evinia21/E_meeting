<!DOCTYPE html>
<html>
<head>
    <title>Daftar Jadwal Meeting</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color:rgb(236, 206, 226);
        }
    </style>
</head>
<body>
    <h1>Daftar Jadwal Meeting</h1>
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "e_meeting";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Koneksi database gagal: " . $conn->connect_error);
            }

            $sql = "SELECT id, title, date_time, location FROM meetings ORDER BY date_time ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>";
                    echo "<a href='edit_meeting.php?id=" . $row['id'] . "'>Edit</a> | ";
                    echo "<a href='delete_meeting.php?id=" . $row['id'] . "' onclick=\"return confirm('Yakin ingin menghapus?')\">Hapus</a> | ";
                    echo "<a href='config/sync_google_calendar.php?id=" . $row['id'] . "' target='_blank'>Sync</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada jadwal meeting.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
    <p><a href="add_meeting.php">Tambah Jadwal Meeting Baru</a></p>
</body>
</html>
