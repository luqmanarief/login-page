<?php

session_start();

// Database connection with error handling
require_once('../config/db.php');

// Check if connection exists
if (!isset($conn) || $conn->connect_error) {
    die("Database connection failed: " . ($conn->connect_error ?? "Connection variable not set"));
}

// Get statistics from database
function getStatistics($conn) {
    $stats = [
        'books' => 0,
        'users' => 0
    ];
    
    // Get total books
    $result = $conn->query("SELECT COUNT(*) as total FROM books");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['books'] = $row['total'];
    } else {
        error_log("Database query error: " . $conn->error);
    }
    
    // Get active users
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    if ($result) {
        $row = $result->fetch_assoc();
        $stats['users'] = $row['total'];
    } else {
        error_log("Database query error: " . $conn->error);
    }
    
    return $stats;
}

// Get statistics safely
try {
    $statistics = getStatistics($conn);
} catch (Exception $e) {
    error_log("Error getting statistics: " . $e->getMessage());
    $statistics = ['books' => 0, 'users' => 0]; // Default values
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pustaka Pro - Digital Library Management</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50">
    <a href="../index.php" class="back-button">
        <i data-feather="arrow-left"></i>
        Kembali
    </a>
    
    <section class="hero-section" x-data="{ activeTab: 'about' }">
        <div class="content-wrapper max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Content -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Kami Membina Pustaka Digital Masa Depan
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Platform pengurusan perpustakaan digital yang direka untuk memudahkan 
                    pengalaman pembelajaran dan pembacaan anda.
                </p>
            </div>

            <!-- Feature Cards -->
            <div class="feature-grid">
                <?php
                $features = [
                    [
                        'icon' => 'book',
                        'title' => 'Koleksi Digital',
                        'description' => 'Akses kepada ribuan buku digital dan sumber pembelajaran'
                    ],
                    [
                        'icon' => 'search',
                        'title' => 'Carian Pintar',
                        'description' => 'Cari buku dengan mudah menggunakan carian pintar kami'
                    ],
                    [
                        'icon' => 'users',
                        'title' => 'Pengurusan Pengguna',
                        'description' => 'Urus akaun dan aktiviti peminjaman dengan efisien'
                    ]
                ];

                foreach ($features as $feature): ?>
                    <div class="feature-card">
                        <div class="icon-box">
                            <i data-feather="<?php echo htmlspecialchars($feature['icon'], ENT_QUOTES, 'UTF-8'); ?>" class="text-primary w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3"><?php echo htmlspecialchars($feature['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="text-gray-600"><?php echo htmlspecialchars($feature['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <div class="text-center">
                    <h4 class="text-4xl font-bold text-primary mb-2"><?php echo number_format((int)$statistics['books']); ?>+</h4>
                    <p class="text-gray-600">Buku Digital</p>
                </div>
                <div class="text-center">
                    <h4 class="text-4xl font-bold text-primary mb-2"><?php echo number_format((int)$statistics['users']); ?>+</h4>
                    <p class="text-gray-600">Pengguna Aktif</p>
                </div>
                <div class="text-center">
                    <h4 class="text-4xl font-bold text-primary mb-2">24/7</h4>
                    <p class="text-gray-600">Sokongan</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
</body>
</html>