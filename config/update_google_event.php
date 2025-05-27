<?php
session_start();
require 'google-config.php';
require 'db.php';

if (!isset($_SESSION['access_token'])) {
    die("Silakan login ke Google.");
}

if (!isset($_GET['id'])) {
    die("ID meeting tidak ditemukan.");
}

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);

// Ambil data meeting dari DB
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM meetings WHERE id = $id");
$meeting = $result->fetch_assoc();

if (!$meeting || empty($meeting['event_id'])) {
    die("Data meeting atau event_id tidak ditemukan.");
}

// Update event
try {
    $event = $calendarService->events->get('primary', $meeting['event_id']);
    $event->setSummary($meeting['title']);
    $event->setDescription($meeting['description']);
    $event->setStart(new Google_Service_Calendar_EventDateTime([
        'dateTime' => $meeting['date_time'],
        'timeZone' => 'Asia/Jakarta'
    ]));
    $event->setEnd(new Google_Service_Calendar_EventDateTime([
        'dateTime' => date('Y-m-d H:i:s', strtotime($meeting['date_time'] . ' +1 hour')),
        'timeZone' => 'Asia/Jakarta'
    ]));
    $event->setLocation($meeting['location']);

    $updatedEvent = $calendarService->events->update('primary', $event->getId(), $event);
    echo "Event berhasil diperbarui: <a href='" . $updatedEvent->htmlLink . "'>Lihat di Google Calendar</a>";
} catch (Exception $e) {
    echo "Error saat update: " . $e->getMessage();
}
?>
