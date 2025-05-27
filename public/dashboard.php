<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard E-Meeting</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #5cc25c;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: rgba(21, 50, 28, 0.68);
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
            position: sticky;
            top: 0;
            z-index: 10000;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .navbar h2 {
            margin: 0;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            position: relative;
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
        }

        .nav-item {
            position: relative;
        }

        .nav-item > a {
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            background-color: rgb(113, 222, 94);
            border-radius: 5px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .nav-item > a:hover {
            background-color: rgb(27, 94, 30);
        }

        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fdfdfd;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            min-width: 160px;
        }

        .submenu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            background-color: #f5f5f5;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.2s, color 0.2s;
        }

        .submenu a:hover {
            background-color: #d2e8d2;
            color: #000;
        }

        .nav-item:hover .submenu {
            display: block;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .logout-link {
            padding: 8px 14px;
            background-color: rgb(201, 150, 193);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-link:hover {
            background-color: rgb(195, 127, 162);
        }

        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .subtext {
            font-size: 16px;
            color: #666;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 20px;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }

            .nav-item {
                width: 100%;
            }

            .container {
                padding: 20px;
            }

            .welcome-message {
                font-size: 20px;
            }

            .subtext {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .welcome-message {
                font-size: 18px;
            }

            .subtext {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>Dashboard E-Meeting</h2>
        <div class="nav-links">

            <!-- MEETING -->
            <div class="nav-item">
                <a href="#">Meeting</a>
                <div class="submenu">
                    <a href="/e_meeting/config/list_meetings.php">Daftar Meeting</a>
                    <a href="/e_meeting/config/add_meeting.php">Tambah Meeting</a>
                    <a href="/e_meeting/config/edit_meeting.php">Edit Meeting</a>
                    <a href="/e_meeting/config/filter_meetings.php">Filter Tanggal</a>
                    <a href="/e_meeting/config/sync_google_calendar.php">Sinkron Google Calendar</a>
                    <a href="/e_meeting/config/sent_email_notification.php">Notifikasi Email</a>
                </div>
            </div>

            <!-- EVENT -->
            <div class="nav-item">
                <a href="#">Event</a>
                <div class="submenu">
                    <a href="/e_meeting/config/events.php">Daftar Event</a>
                    <a href="/e_meeting/config/create_event.php">Tambah Event</a>
                </div>
            </div>

            <!-- CALENDAR -->
            <div class="nav-item">
                <a href="/e_meeting/config/calendar.php" class="calendar-link">Calendar</a>
            </div>

            <!-- LOGOUT -->
            <div class="nav-item">
                <a href="logout.php" class="logout-link">Logout</a>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="welcome-message">Selamat datang, <strong><?php echo htmlspecialchars($userName); ?></strong>!</div>
        <div class="subtext">Silakan gunakan menu di atas untuk mengelola jadwal meeting dan event Anda.</div>
    </div>

</body>
</html>

