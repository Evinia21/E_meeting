<!DOCTYPE html>
<html>
<head>
    <title>Tambah Jadwal Meeting</title>
</head>
<body>
    <h1>Tambah Jadwal Meeting</h1>
    <form action="process_meeting.php" method="post">
        <div>
            <label for="title">Judul Meeting:</label>
            <input type="text" id="title" name="title" required><br><br>
        </div>
        <div>
            <label for="description">Deskripsi:</label><br>
            <textarea id="description" name="description"></textarea><br><br>
        </div>
        <div>
            <label for="date_time">Waktu Meeting:</label>
            <input type="datetime-local" id="date_time" name="date_time" required><br><br>
        </div>
        <div>
            <label for="location">Lokasi:</label>
            <input type="text" id="location" name="location"><br><br>
        </div>
        <input type="submit" value="Simpan Jadwal">
    </form>
</body>
</html>