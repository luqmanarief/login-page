<?php
<?php
session_start();
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid request verification token";
        header("Location: register.php");
        exit();
    }

    // Sanitize and validate input
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $ic = filter_input(INPUT_POST, 'ic', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    
    // Additional fields based on role
    $class = isset($_POST['class']) ? filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING) : null;
    $student_id = isset($_POST['student_id']) ? filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_STRING) : null;
    $staff_id = isset($_POST['staff_id']) ? filter_input(INPUT_POST, 'staff_id', FILTER_SANITIZE_STRING) : null;
    $department = isset($_POST['department']) ? filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING) : null;

    // Validation
    $errors = [];
    
    if (!$email) {
        $errors[] = "Format emel tidak sah";
    }
    
    if (strlen($password) < 8) {
        $errors[] = "Kata laluan mestilah sekurang-kurangnya 8 aksara";
    }
    
    if (!preg_match("/^[0-9]{12}$/", $ic)) {
        $errors[] = "Nombor IC tidak sah";
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Emel telah didaftarkan";
    }
    $stmt->close();

    // Check if IC already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE ic = ?");
    $stmt->bind_param("s", $ic);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "Nombor IC telah didaftarkan";
    }
    $stmt->close();

    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare insert statement
        $sql = "INSERT INTO users (role, name, ic, email, password, class, student_id, staff_id, department) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", 
            $role, 
            $name, 
            $ic, 
            $email, 
            $hashed_password, 
            $class, 
            $student_id, 
            $staff_id, 
            $department
        );
        
        try {
            if ($stmt->execute()) {
                $_SESSION['success'] = "Pendaftaran berjaya! Sila log masuk.";
                header("Location: ../login/login.php");
                exit();
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Ralat pendaftaran: " . $e->getMessage();
        }
        
        $stmt->close();
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    
    // If there were errors, redirect back to registration form
    if (!empty($_SESSION['error'])) {
        header("Location: register.php");
        exit();
    }
} else {
    // If not POST request, redirect to registration page
    header("Location: register.php");
    exit();
}

$conn->close();
?>