<?php
session_start();
unset($_SESSION['access_token']); // hapus token dari sesi
session_destroy(); // hancurkan sesi
header("Location: login.php"); // kembali ke login
exit();
?>
