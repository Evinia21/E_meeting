<?php
require 'google-config.php';

if (!isset($_SESSION['google_token'])) {
    die("Silakan login ke Google.");
}

$client->setAccessToken($_SESSION['google_token']);
$calendarService = new Google_Service_Calendar($client);

$eventId = $_GET['event_id'];
$calendarService->events->delete('primary', $eventId);

echo "Event berhasil dihapus dari Google Calendar.";
?>
