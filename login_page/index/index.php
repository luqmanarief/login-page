<?php
session_start();

// Add CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="overlay">
        <header class="top-nav">
            <div class="logo"><?php echo "pustakaPRO📚"; ?></div>
            <nav>
                <select id="role" name="role">
                    <option value="user">Pengguna</option>
                    <option value="admin">Admin</option>
                </select>
                <a href="register/register.php">Register</a>
                <a href="about_us/aboutus.php">About Us</a>
            </nav>
        </header>

        <div class="login-container">
            <img src="https://breyer.edu.my/wp-content/uploads/2021/11/logo-002.png" alt="HTML5 Logo" class="login-logo">

            <form class="login-form" method="POST" action="login_process.php">
                <!-- Add CSRF token field -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">GET STARTED</button>

                <div class="options">
                    <label><input type="checkbox" name="remember"> Keep Logged In</label>
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>

                <div class="links">
                    <a href="forgotten_account.php">FORGOTTEN ACCOUNT</a>
                    <a href="help.php">NEED HELP?</a>
                </div>
            </form>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="error">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <footer>
            <p><a href="about_us/aboutus.php">About Us</a> | Privacy Policy | Terms Of Use</p>
            <p>&copy; <?php echo date('Y'); ?> pustakaPRO📚. All Rights Reserved | Design by <a href="#">W3layouts</a></p>
        </footer>
    </div>
</body>
</html>