<?php
session_start();
require __DIR__ . '/config/google-config.php';

echo "<style>
body {
    font-family: sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}
.error { color: red; font-weight: bold; }
</style>";

if (!isset($_GET['code'])) {
    die('<div class="container"><p class="error">Kode otorisasi tidak ditemukan.</p></div>');
}

try {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    // DEBUG: Tampilkan isi token
    echo "<div class='container'><h3>DEBUG Token Response</h3><pre>";
    var_dump($token);
    echo "</pre></div>";

    // Validasi isi token
    if (!is_array($token) || empty($token)) {
        throw new Exception('Token tidak valid atau kosong. Pastikan konfigurasi OAuth benar.');
    }

    if (isset($token['error'])) {
        throw new Exception($token['error_description'] ?? 'Terjadi kesalahan saat otorisasi.');
    }

    $client->setAccessToken($token);

    // Simpan token ke session
    $_SESSION['access_token'] = $token;
    $_SESSION['refresh_token'] = $token['refresh_token'] ?? null;

    // Redirect ke dashboard
    header('Location: /e_meeting/public/dashboard.php');
    exit();

} catch (Exception $e) {
    die('<div class="container"><p class="error">Terjadi kesalahan saat proses callback:<br>' . htmlspecialchars($e->getMessage()) . '</p></div>');
}
