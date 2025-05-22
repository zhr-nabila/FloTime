<?php
session_start();
include "koneksi.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  
  $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        if (mysqli_query($koneksi, $query)) {
            $success = "Registrasi berhasil. Silakan login.";
        } else {
            $error = "Registrasi gagal. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Todo List</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <section>
    <div class="form-box">
      <div class="form-value">
        <form action="register.php" method="POST" autocomplete="off">
          <h2>Register</h2>

          <?php if(!empty($error)): ?>
            <div class="error-message" style="color: red; margin-bottom: 15px;">
              <?= htmlspecialchars($error) ?>
            </div>
          <?php elseif(!empty($success)): ?>
            <div class="success-message" style="color: green; margin-bottom: 15px;">
              <?= htmlspecialchars($success) ?>
            </div>
          <?php endif; ?>

          <div class="inputbox">
            <ion-icon name="mail-outline"></ion-icon>
            <input type="email" name="email" required>
            <label>Email</label>
          </div>
          <div class="inputbox">
            <ion-icon name="lock-closed-outline"></ion-icon>
            <input type="password" name="password" required>
            <label>Password</label>
          </div>
          <button type="submit">Register</button>
          <div class="register">
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
          </div>
        </form>
      </div>
    </div>
  </section>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
