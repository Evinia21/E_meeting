<?php
require __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('http://localhost/e_meeting/google-callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');
$client->setPrompt('consent');

// Buat instance calendar service
$service = new Google_Service_Calendar($client);
?>
