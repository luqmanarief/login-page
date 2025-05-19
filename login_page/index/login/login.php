<?php
session_start();

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Initialize error message variable
$error_msg = '';

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    require_once('../config/db.php');
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validate input
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Format emel tidak sah";
    } else {
        // Query to check user credentials
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                
                // Set remember me cookie if checked
                if ($remember) {
                    setcookie("user_login", $user['id'], time() + (30 * 24 * 60 * 60), "/");
                }
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error_msg = "Kata laluan tidak sah";
            }
        } else {
            $error_msg = "Emel tidak dijumpai";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk | Pustaka Pro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h1>Log Masuk <i class="fas fa-user"></i></h1>
            
            <?php if($error_msg): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_msg); ?>
            </div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label>
                        <i class="fas fa-envelope"></i>
                        Emel
                    </label>
                    <input type="email" name="email" placeholder="Masukkan emel anda" required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i>
                        Kata Laluan
                    </label>
                    <div class="password-input">
                        <input type="password" name="password" placeholder="Masukkan kata laluan" required>
                        <i class="far fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <div class="forgot-password">
                    <a href="forgot-password.php">Lupa kata laluan?</a>
                </div>

                <button type="submit" class="next-btn">
                    Log Masuk <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <p class="login-link">
                Belum mempunyai akaun? <a href="../register/register.php">Daftar di sini</a>
            </p>
        </div>
    </div>

    <script>
    document.querySelector('.toggle-password').addEventListener('click', function() {
        const password = document.querySelector('input[name="password"]');
        if (password.type === 'password') {
            password.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
    </script>
</body>
</html>