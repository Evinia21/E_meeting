<?php
date_default_timezone_set('Asia/Jakarta'); // Atur timezone ke WIB

include 'config/db.php';
include 'app/helper.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>E-Meeting</title>
    <link rel='stylesheet' href='public/style.css'>
</head>
<body>";

echo "<h1>Selamat Datang di Aplikasi E-Meeting</h1>";
echo "<p>Ini adalah platform untuk mengatur jadwal meeting dengan Google Calendar API.</p>";
echo "<p>Waktu Server : " . date("d F Y, H:i:s") . "</p>";

echo "<p><a href='views/about.php'>Tentang Aplikasi</a></p>";

echo "</body></html>";
?>
