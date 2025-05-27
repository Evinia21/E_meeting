<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];

// Contoh data meeting yang sudah tersinkronisasi
$meetings = [
    [
        'title' => 'Rapat Proyek A',
        'date' => '2024-06-05',
        'time' => '09:00 - 10:00',
        'location' => 'Ruang Meeting 1',
        'status' => 'Tersinkronisasi'
    ],
    [
        'title' => 'Diskusi Tim Marketing',
        'date' => '2024-06-06',
        'time' => '14:00 - 15:30',
        'location' => 'Ruang Meeting 2',
        'status' => 'Tersinkronisasi'
    ],
    [
        'title' => 'Evaluasi Bulanan',
        'date' => '2024-06-10',
        'time' => '11:00 - 12:00',
        'location' => 'Ruang Meeting Virtual',
        'status' => 'Tersinkronisasi'
    ],
];
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
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #343a40;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex-wrap: wrap;
        }

        .navbar h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .nav-item {
            position: relative;
        }

        .nav-item > a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .nav-item > a:hover {
            background-color: #495057;
        }

        .submenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #f8f9fa;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            min-width: 180px;
        }

        .submenu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
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
            max-width: 960px;
            margin: 40px auto 80px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .logout-link {
            padding: 8px 14px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            white-space: nowrap;
        }

        .logout-link:hover {
            background-color: #c82333;
        }

        .welcome-message {
            font-size: 28px;
            margin-bottom: 20px;
            color: #343a40;
        }

        .subtext {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 40px;
        }

        /* Daftar meeting table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #28a745;
            color: white;
        }

        thead th {
            padding: 12px 15px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        tbody tr:hover {
            background-color: #d2e8d2;
        }

        /* Responsive styling for mobile */
        @media (max-width: 767px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                width: 100%;
                gap: 10px;
                margin-top: 10px;
                flex-direction: column;
            }

            .nav-item > a {
                width: 100%;
                padding: 10px 20px;
                box-sizing: border-box;
            }

            .submenu {
                position: relative;
                box-shadow: none;
                border-radius: 0;
                min-width: 100%;
            }

            .nav-item:hover .submenu {
                display: none;
            }

            /* Show submenu on tap/click - workaround for mobile */
            .nav-item.active .submenu {
                display: block;
            }

            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }

            thead tr {
                display: none; /* Hide header on mobile */
            }

            tbody tr {
                margin-bottom: 20px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 15px;
            }

            tbody td {
                padding: 8px 15px;
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: left;
            }

            tbody td::before {
                position: absolute;
                top: 12px;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                content: attr(data-label);
                color: #343a40;
            }
        }
    </style>
    <script>
        // Toggle submenu on mobile tap/click
        document.addEventListener('DOMContentLoaded', function () {
            if (window.innerWidth <= 767) {
                const navItems = document.querySelectorAll('.nav-item');
                navItems.forEach(function (item) {
                    item.querySelector('a').addEventListener('click', function (e) {
                        const submenu = item.querySelector('.submenu');
                        if (submenu) {
                            e.preventDefault();
                            item.classList.toggle('active');
                        }
                    });
                });
            }
        });
    </script>
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

        <h3 style="text-align:left; color:#343a40; margin-bottom: 20px;">Daftar Meeting yang Tersinkronisasi</h3>
        <?php if (count($meetings) > 0): ?>
            <table aria-label="Daftar Meeting">
                <thead>
                    <tr>
                        <th>Judul Meeting</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($meetings as $meeting): ?>
                    <tr>
                        <td data-label="Judul Meeting"><?php echo htmlspecialchars($meeting['title']); ?></td>
                        <td data-label="Tanggal"><?php echo htmlspecialchars($meeting['date']); ?></td>
                        <td data-label="Waktu"><?php echo htmlspecialchars($meeting['time']); ?></td>
                        <td data-label="Lokasi"><?php echo htmlspecialchars($meeting['location']); ?></td>
                        <td data-label="Status"><?php echo htmlspecialchars($meeting['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada meeting yang tersinkronisasi saat ini.</p>
        <?php endif; ?>
    </div>

</body>
</html>

