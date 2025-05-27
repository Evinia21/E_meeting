<?php
session_start();
require 'google-config.php';

// Cek apakah token akses ada di sesi
if (!isset($_SESSION['access_token'])) {
    die('Harap login dengan Google!');
}

// Set token akses untuk client Google
$client->setAccessToken($_SESSION['access_token']);

// Jika token akses sudah kedaluwarsa, perbarui token dengan refresh token
if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
        // Perbarui token akses menggunakan refresh token
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        $_SESSION['access_token'] = $client->getAccessToken(); // Simpan token yang baru
    } else {
        // Jika tidak ada refresh token, arahkan pengguna untuk login lagi
        header('Location: login_google.php');
        exit();
    }
}

// Inisialisasi Google Calendar service
$calendarService = new Google_Service_Calendar($client);

// Membuat acara baru
$event = new Google_Service_Calendar_Event([
    'summary' => 'Rapat E-Meeting',
    'start' => [
        'dateTime' => '2025-03-18T10:00:00+07:00', // Waktu mulai acara
    ],
    'end' => [
        'dateTime' => '2025-03-18T11:00:00+07:00', // Waktu selesai acara
    ]
]);

$calendarId = 'primary'; // ID calendar (primary untuk calendar utama pengguna)

// Menambahkan acara ke kalender
try {
    $event = $calendarService->events->insert($calendarId, $event);
    echo "Acara berhasil ditambahkan: <a href='" . $event->htmlLink . "' target='_blank'>Lihat di Google Calendar</a>";
} catch (Google_Service_Exception $e) {
    echo 'Terjadi kesalahan saat menambahkan acara: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Kesalahan umum: ' . $e->getMessage();
}
?>
