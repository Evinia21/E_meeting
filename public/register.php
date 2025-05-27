<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Registrasi</title>
</head>
<body>
    <h2>Form Registrasi</h2>
    <form action="register_process.php" method="POST">
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>
