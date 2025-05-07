<?php
// Determine the password input type based on the toggle state
$passwordType = isset($_POST['toggle_password']) && $_POST['toggle_password'] === 'show' ? 'text' : 'password';
$toggleState = $passwordType === 'text' ? 'hide' : 'show';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <div class="container">
        <div class="box">
            <img src="img/picture/logo.png" alt="Logo" class="logo">
            <h1>ThaiTaste & Theory</h1>
            <p class="tagline">Twist of Taste</p>
            <form action="backend/login.php" method="POST" method="POST">
                <div class="input-group">
                    <img src="img/picture/Person_pictogram.png" alt="Person Icon" class="icon">
                    <input type="text" name="ID" placeholder="ID" required>
                </div>
                <div class="input-group">
                    <img src="img/picture/Lock.png" alt="Lock Icon" class="icon">
                    <input type="<?php echo $passwordType; ?>" name="password" placeholder="Password" required>
                </div>
                <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
                <button type="submit" class="login-button">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>