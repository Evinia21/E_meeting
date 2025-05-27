<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "<h2>Profil Pengguna</h2>";
echo "ID: " . $_SESSION['user_id'] . "<br>";
echo "Nama: " . $_SESSION['user_name'] . "<br>";
?>
<br><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a>
