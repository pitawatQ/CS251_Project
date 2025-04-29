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
            <img src="img/logo.png" alt="Logo" class="logo">
            <h1>ThaiTaste & Theory</h1>
            <p class="tagline">Twist of Taste</p>
            <form action="authenticate.php" method="POST">
                <div class="input-group">
                    <input type="text" name="id" placeholder="ID" required>
                    <span class="icon">&#128100;</span>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <span class="icon">&#128274;</span>
                </div>
                <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
                <button type="submit" class="login-button">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>