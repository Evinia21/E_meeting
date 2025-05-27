<?php

// email_notifikasi.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Pastikan path ini benar
require __DIR__ . '/../vendor/autoload.php'; // Sesuaikan path jika perlu

function sendMeetingReminder($to, $meetingTitle, $meetingTime) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Ganti dengan server SMTP Anda
        $mail->SMTPAuth = true;
        $mail->Username = 'youremail@gmail.com';  // Ganti dengan email Anda
        $mail->Password = 'yourpassword';  // Ganti dengan password Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('youremail@gmail.com', 'E-Meeting App');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Reminder: ' . $meetingTitle;
        $mail->Body    = "Hi,<br><br>This is a reminder for the meeting titled '$meetingTitle' scheduled on $meetingTime.<br><br>Best Regards,<br>E-Meeting App";

        $mail->send();
        echo 'Reminder email has been sent.';
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
