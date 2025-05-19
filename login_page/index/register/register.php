<?php
session_start();

// Add CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once('../config/db.php');
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akaun | Pustaka Pro</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <div class="back-button">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        
        <h1>Daftar Akaun <i class="fas fa-user-plus"></i></h1>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="register_process.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            
            <div class="form-group">
                <label for="role">
                    <i class="fas fa-users"></i> Kategori Pengguna
                </label>
                <select name="role" id="role" required onchange="toggleFields()">
                    <option value="">Pilih kategori</option>
                    <option value="student">Pelajar</option>
                    <option value="lecturer">Pensyarah</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">
                    <i class="fas fa-user"></i> Nama Penuh
                </label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="ic">
                    <i class="fas fa-id-card"></i> Nombor IC
                </label>
                <input type="text" name="ic" id="ic" required pattern="[0-9]{12}">
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Emel
                </label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Kata Laluan
                </label>
                <input type="password" name="password" id="password" required minlength="8">
            </div>

            <div class="student-fields" style="display: none;">
                <div class="form-group">
                    <label for="class">
                        <i class="fas fa-school"></i> Kelas
                    </label>
                    <input type="text" name="class" id="class">
                </div>
                <div class="form-group">
                    <label for="student_id">
                        <i class="fas fa-id-badge"></i> ID Pelajar
                    </label>
                    <input type="text" name="student_id" id="student_id">
                </div>
            </div>

            <div class="lecturer-fields" style="display: none;">
                <div class="form-group">
                    <label for="staff_id">
                        <i class="fas fa-id-badge"></i> ID Staf
                    </label>
                    <input type="text" name="staff_id" id="staff_id">
                </div>
                <div class="form-group">
                    <label for="department">
                        <i class="fas fa-building"></i> Jabatan
                    </label>
                    <input type="text" name="department" id="department">
                </div>
            </div>

            <button type="submit">Daftar <i class="fas fa-arrow-right"></i></button>
        </form>

        <div class="login-link">
            Sudah mempunyai akaun? <a href="../login/login.php">Log masuk di sini</a>
        </div>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            const studentFields = document.querySelector('.student-fields');
            const lecturerFields = document.querySelector('.lecturer-fields');
            
            if (role === 'student') {
                studentFields.style.display = 'block';
                lecturerFields.style.display = 'none';
                document.getElementById('class').required = true;
                document.getElementById('student_id').required = true;
                document.getElementById('staff_id').required = false;
                document.getElementById('department').required = false;
            } else if (role === 'lecturer') {
                studentFields.style.display = 'none';
                lecturerFields.style.display = 'block';
                document.getElementById('class').required = false;
                document.getElementById('student_id').required = false;
                document.getElementById('staff_id').required = true;
                document.getElementById('department').required = true;
            } else {
                studentFields.style.display = 'none';
                lecturerFields.style.display = 'none';
            }
        }
    </script>
</body>
</html>