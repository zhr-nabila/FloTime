<?php
session_start();
include "koneksi.php";

if(isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("Location: dashboard.php");
    exit();
}

$error = "";
if(isset($_GET['wrong'])) {
    $error = "Email atau password salah";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List App</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="proses_login.php" method="POST" autocomplete="off">
                    <h2>Login</h2>
                    
                    <?php if(!empty($error)): ?>
                        <div class="error-message" style="color: red; margin-bottom: 15px;">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required autocomplete="off" value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : '' ?>">
                        <label>Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required autocomplete="new-password">
                        <label>Password</label>
                    </div>
                    <div class="forget">
                        <label><input type="checkbox" name="remember"> Remember Me</label>
                        <a href="forgot_password.php">Forgot Password?</a>
                    </div>
                    <button type="submit">Log in</button>
                    <div class="register">
                        <p>Don't have an account? <a href="register.php">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>