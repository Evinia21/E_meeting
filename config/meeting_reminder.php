<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php'; // Perbaikan di sini
$host = 'localhost';
$dbname = 'e_meeting';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi untuk mengambil meeting yang akan datang
function getUpcomingMeetings($pdo, $reminderTimeMinutes = 30) {
    $currentTime = new DateTime();
    $reminderThreshold = $currentTime->modify("+$reminderTimeMinutes minutes")->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("SELECT * FROM meetings WHERE date_time <= :reminder_threshold AND reminder_sent = 0");
    $stmt->bindParam(':reminder_threshold', $reminderThreshold);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mengirim email pengingat
function sendReminderEmail($meeting) {
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP (sesuaikan dengan penyedia email Anda)
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Ganti dengan host SMTP Anda
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com'; // Ganti dengan alamat email Anda
        $mail->Password   = 'your_password'; // Ganti dengan kata sandi email Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Penerima
        $mail->setFrom('no-reply@emeeting.com', 'E-Meeting Reminder');
        $mail->addAddress('participant@example.com', 'Peserta Meeting'); // Ganti dengan alamat email peserta (Anda perlu mengambil ini dari database atau input)

        // Subjek
        $mail->Subject = 'Pengingat Meeting: ' . $meeting['title'];

        // Isi email
        $mail->isHTML(true);
        $mail->Body    = "Halo,<br><br>Ini adalah pengingat untuk meeting berikut:<br><br>" .
                         "**Judul:** " . $meeting['title'] . "<br>" .
                         "**Waktu:** " . (new DateTime($meeting['date_time']))->format('d F Y, H:i') . "<br>" .
                         "**Lokasi:** " . $meeting['location'] . "<br><br>" .
                         "Jangan sampai terlewat!";
        $mail->AltBody = "Halo,\n\nIni adalah pengingat untuk meeting berikut:\n\n" .
                         "Judul: " . $meeting['title'] . "\n" .
                         "Waktu: " . (new DateTime($meeting['date_time']))->format('d F Y, H:i') . "\n" .
                         "Lokasi: " . $meeting['location'] . "\n\n" .
                         "Jangan sampai terlewat!";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Gagal mengirim email untuk meeting ID " . $meeting['id'] . ": " . $mail->ErrorInfo);
        return false;
    }
}

// Fungsi untuk menandai pengingat sudah dikirim
function markReminderAsSent($pdo, $meetingId) {
    $stmt = $pdo->prepare("UPDATE meetings SET reminder_sent = 1 WHERE id = :id");
    $stmt->bindParam(':id', $meetingId);
    return $stmt->execute();
}

// Main logic
$upcomingMeetings = getUpcomingMeetings($pdo);

if (!empty($upcomingMeetings)) {
    foreach ($upcomingMeetings as $meeting) {
        if (sendReminderEmail($meeting)) {
            markReminderAsSent($pdo, $meeting['id']);
            echo "Pengingat berhasil dikirim untuk meeting: " . $meeting['title'] . "\n";
        } else {
            echo "Gagal mengirim pengingat untuk meeting: " . $meeting['title'] . "\n";
        }
    }
} else {
    echo "Tidak ada meeting yang perlu diingatkan saat ini.\n";
}

$pdo = null; // Tutup koneksi database

?>