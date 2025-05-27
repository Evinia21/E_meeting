<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php'; // Ubah path ini
require_once __DIR__ . '/../config/db.php';              // Pastikan path ke db.php juga benar

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/../config/credentials.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');

if (!isset($_SESSION['access_token'])) {
    header('Location: ../config/login_google.php');
    exit();
}

$client->setAccessToken($_SESSION['access_token']);

if ($client->isAccessTokenExpired()) {
    unset($_SESSION['access_token']);
    header('Location: ../config/login_google.php');
    exit();
}

$service = new Google_Service_Calendar($client);

$sql = "SELECT * FROM meetings";
$result = $conn->query($sql);

$success = 0;
$fail = 0;

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        try {
            $event = new Google_Service_Calendar_Event([
                'summary' => $row['title'],
                'description' => $row['description'],
                'location' => $row['location'],
                'start' => [
                    'dateTime' => date('c', strtotime($row['date_time'])),
                    'timeZone' => 'Asia/Jakarta',
                ],
                'end' => [
                    'dateTime' => date('c', strtotime('+1 hour', strtotime($row['date_time']))),
                    'timeZone' => 'Asia/Jakarta',
                ],
            ]);

            $calendarId = 'primary';
            $service->events->insert($calendarId, $event);
            $success++;
        } catch (Exception $e) {
            $fail++;
        }
    }
}

header("Location: dashboard.php?sync_success=$success&sync_fail=$fail");
exit();
