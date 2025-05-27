<?php
session_start();

require_once __DIR__ . '/google-config.php';

if (!isset($_GET['code'])) {
    // Langkah 1: Redirect ke Google OAuth
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();
} else {
    // Langkah 2: Tukar kode dengan token akses
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        echo "Gagal mendapatkan token: " . htmlspecialchars($token['error_description']);
        exit();
    }

    $_SESSION['access_token'] = $token;

    // Redirect kembali ke dashboard
    header('Location: dashboard.php');
    exit();
}
