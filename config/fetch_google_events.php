<?php
require 'google-config.php';

if (!isset($_SESSION['access_token'])) {
    die("Silakan login ke Google.");
}

$client->setAccessToken($_SESSION['access_token']);
$calendarService = new Google_Service_Calendar($client);

$events = $calendarService->events->listEvents('primary');

echo "<h2>Jadwal Meeting di Google Calendar</h2>";

foreach ($events->getItems() as $event) {
    echo "<p><strong>" . $event->getSummary() . "</strong> - " . $event->getStart()->getDateTime() . "</p>";
}
?>
