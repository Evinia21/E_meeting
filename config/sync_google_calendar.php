<?php
session_start();
require_once 'db.php'; // koneksi ke database
require_once 'google-config.php'; // konfigurasi Google API

// Cek apakah user sudah login Google
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] == '') {
    header('Location: login_google.php');
    exit();
}

$client->setAccessToken($_SESSION['access_token']);
$service = new Google_Service_Calendar($client);

// Ambil data dari database
$id = $_GET['id'] ?? null; // ID meeting yang ingin dikirim
if (!$id) {
    die("ID meeting tidak ditemukan.");
}

$query = $conn->prepare("SELECT * FROM meetings WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$meeting = $result->fetch_assoc();

if (!$meeting) {
    die("Data meeting tidak ditemukan.");
}

// Konversi waktu dari MySQL ke ISO 8601 (RFC3339)
function toRFC3339($datetime) {
    return date('c', strtotime($datetime));
}

$attendees = [];

if (!empty($meeting['participant_email']) && filter_var($meeting['participant_email'], FILTER_VALIDATE_EMAIL)) {
    $attendees[] = ['email' => $meeting['participant_email']];
}

$event = new Google_Service_Calendar_Event([
    'summary'     => $meeting['title'],
    'location'    => $meeting['location'],
    'description' => $meeting['description'],
    'start' => [
        'dateTime' => toRFC3339($meeting['start_time']),
        'timeZone' => 'Asia/Jakarta',
    ],
    'end' => [
        'dateTime' => toRFC3339($meeting['end_time']),
        'timeZone' => 'Asia/Jakarta',
    ],
    // hanya tambahkan attendees jika valid
    'attendees' => $attendees,
]);


// Kirim ke Google Calendar
$calendarId = 'primary';
$service->events->insert($calendarId, $event);

echo "✅ Meeting '{$meeting['title']}' berhasil dikirim ke Google Calendar!";
?>
