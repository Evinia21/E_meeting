<?php
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/../vendor/autoload.php';
session_start();

// Konfigurasi Client Google
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/../config/credentials.json'); // Lokasi absolut relatif
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setRedirectUri('http://localhost/e_meeting/public/google-callback.php'); // Sesuaikan dengan lokasi public/
$client->setAccessType('offline');

// Arahkan ke login jika token belum ada atau expired
if (!isset($_SESSION['access_token'])) {
    header('Location: ../public/login.php');
    exit();
}

$client->setAccessToken($_SESSION['access_token']);

if ($client->isAccessTokenExpired()) {
    header('Location: ../public/login.php');
    exit();
}

$service = new Google_Service_Calendar($client);

$errors = [];
$success = '';

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $summary = trim($_POST['summary'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $start = trim($_POST['start'] ?? '');
    $end = trim($_POST['end'] ?? '');

    if ($summary === '') {
        $errors[] = "Judul acara harus diisi.";
    }
    if ($start === '') {
        $errors[] = "Waktu mulai harus diisi.";
    }
    if ($end === '') {
        $errors[] = "Waktu selesai harus diisi.";
    }
    if ($start !== '' && $end !== '' && strtotime($start) >= strtotime($end)) {
        $errors[] = "Waktu selesai harus lebih besar dari waktu mulai.";
    }

    if (empty($errors)) {
        $event = new Google_Service_Calendar_Event([
            'summary' => $summary,
            'location' => $location,
            'start' => [
                'dateTime' => date(DATE_RFC3339, strtotime($start)),
                'timeZone' => 'Asia/Jakarta',
            ],
            'end' => [
                'dateTime' => date(DATE_RFC3339, strtotime($end)),
                'timeZone' => 'Asia/Jakarta',
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 30],
                    ['method' => 'popup', 'minutes' => 10],
                ],
            ],
        ]);

        $calendarId = 'primary';

        try {
            $createdEvent = $service->events->insert($calendarId, $event);
            $success = "Acara berhasil ditambahkan. <a href='" . htmlspecialchars($createdEvent->htmlLink) . "' target='_blank'>Lihat di Google Calendar</a>";
            $summary = $location = $start = $end = '';
        } catch (Exception $e) {
            $errors[] = "Gagal menambahkan acara: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Buat Event Google Calendar</title>

    <style>
       
    </style>
</head>
<body>
    <h2>Buat Event Google Calendar</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <form action="" method="POST" novalidate>
        <label for="summary">Judul Acara <span style="color:red;">*</span>:</label>
        <input type="text" id="summary" name="summary" value="<?= htmlspecialchars($summary ?? '') ?>" required />

        <label for="location">Lokasi:</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($location ?? '') ?>" />

        <label for="start">Waktu Mulai <span style="color:red;">*</span>:</label>
        <input type="datetime-local" id="start" name="start" value="<?= htmlspecialchars($start ?? '') ?>" required />

        <label for="end">Waktu Selesai <span style="color:red;">*</span>:</label>
        <input type="datetime-local" id="end" name="end" value="<?= htmlspecialchars($end ?? '') ?>" required />

        <button type="submit">Buat Event</button>
    </form>
</body>
</html>
