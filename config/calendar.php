<!DOCTYPE html>
<html>
<head>
    <title>Kalender Jadwal Meeting</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'get_events.php' // Sumber data event dari file PHP
            });
            calendar.render();
        });
    </script>
    <style>
        #calendar {
            width: 80%;
            margin: 0 auto;
        }

        /* Tambahkan aturan berikut untuk mengubah latar belakang */
        .fc-view-harness {
            background-color: lightgreen !important; /* Ganti lightgreen dengan warna yang Anda inginkan */
        }
    </style>
</head>
<body>
    <h1>Kalender Jadwal Meeting</h1>
    <div id="calendar"></div>
    <p><a href="list_meetings.php">Lihat dalam Bentuk Tabel</a> | <a href="add_meeting.php">Tambah Jadwal Meeting Baru</a></p>
</body>
</html>