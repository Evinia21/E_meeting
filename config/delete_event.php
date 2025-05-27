<?php
session_start();
require 'google-config.php';

if (!isset($_SESSION['access_token'])) {
    die('Harap login dengan Google.');
}

// Periksa apakah parameter 'id' ada dan tidak kosong
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID acara tidak valid atau tidak ditemukan.');
}

$event_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING); // Sanitisasi input

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);

try {
    $calendarService->events->delete('primary', $event_id);
    header("Location: events.php?deleted=true"); // Tambahkan parameter sukses
    exit();
} catch (Google_Service_Exception $e) {
    echo 'Terjadi kesalahan saat menghapus acara dengan ID ' . htmlspecialchars($event_id) . ': ' . $e->getMessage();
    exit();
}
?>