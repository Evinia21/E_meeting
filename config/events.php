<?php
session_start();
require 'google-config.php';

if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);

    // Periksa apakah token kedaluwarsa
    if ($client->isAccessTokenExpired()) {
        // Jika ada refresh token, gunakan untuk mendapatkan token akses baru
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $_SESSION['access_token'] = $client->getAccessToken(); // Perbarui token di sesi
        } else {
            // Jika tidak ada refresh token, paksa pengguna untuk login lagi
            unset($_SESSION['access_token']);
            die('Token akses kedaluwarsa. Harap login ulang.');
            // Atau, redirect ke halaman login:
            // header('Location: login.php'); // Ganti dengan halaman login Anda
            // exit();
        }
    }
} else {
    // Jika tidak ada token akses, arahkan pengguna ke halaman login
    die('Harap login dengan Google!');
    // Atau, redirect ke halaman login:
    // header('Location: login.php'); // Ganti dengan halaman login Anda
    // exit();
}

$calendarService = new Google_Service_Calendar($client);

$calendarId = 'primary';
try {
    $eventsResult = $calendarService->events->listEvents($calendarId);
    $events = $eventsResult->getItems();

    if (empty($events)) {
        echo "Tidak ada acara yang ditemukan.";
    } else {
        echo "<h1>Daftar Acara</h1>";
        echo "<ul>";
        foreach ($events as $event) {
            $eventSummary = $event->getSummary();
            $eventId = $event->getId(); // Dapatkan ID acara
            $eventStart = $event->getStart()->getDateTime();
            $eventStartFormatted = $eventStart ? $eventStart : $event->getStart()->getDate(); // Format start date if not datetime

            echo "<li>";
            echo $eventSummary . " - " . $eventStartFormatted;
            echo " <a href='delete_event.php?id=" . $eventId . "'>Hapus</a>"; // Tambahkan link hapus
            echo "</li>";
        }
        echo "</ul>";
    }

    // Tambahkan pesan sukses jika ada parameter deleted di URL
    if (isset($_GET['deleted']) && $_GET['deleted'] == 'true') {
        echo "<p style='color: green;'>Acara berhasil dihapus.</p>";
    }
} catch (Google_Service_Exception $e) {
    echo 'Terjadi kesalahan saat mengakses Google Calendar API: ' . $e->getMessage();
}
?>