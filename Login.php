<?php
session_start();
$passwordType = 'password';
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">

</head>
<body>
    <div class="container">
        <div class="box">
            <img src="img/picture/logo.png" class="logo">
            <h1>ThaiTaste & Theory</h1>
            <p class="tagline">Twist of Taste</p>
            <form action="backend/login.php" method="POST">
                <div class="input-group">
                    <div class="input-wrapper">
                        <img src="img/picture/Person_pictogram.png" class="icon">
                        <input type="text" name="ID" placeholder="ID" required>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <img src="img/picture/Lock.png" class="icon">
                        <input type="<?php echo $passwordType; ?>" name="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="submit-wrapper">
                    <button type="submit" class="login-button">Log in</button>
                </div>
                <div class="error-msg">
                    <?php if ($error === 'pass' || $error === 'user'): ?>
                        <p class="error">ไอดีหรือรหัสผ่านไม่ถูกต้อง</p>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
