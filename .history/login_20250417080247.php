<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Todolist</title>
</head>
<body>
    <h2>Login</h2>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>";
        if ($_GET['error'] == 1) {
            echo "Username atau password salah!";
        } elseif ($_GET['error'] == 2) {
            echo "Anda harus login terlebih dahulu!";
        }
        echo "</p>";
    }
    ?>
    <form action="proses_login.php" method="POST">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <br>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </form>
</body>
</html>