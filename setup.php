<?php
/**
 * BookStore Online - Setup Script
 * Kiểm tra và thiết lập môi trường cho ứng dụng
 */

// Thiết lập encoding
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - BookStore Online</title>
    <link rel="stylesheet" href="css/material-design.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="mdl-layout">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout__title">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">settings</i>
                    BookStore Online - Setup
                </span>
            </div>
        </header>

        <main class="mdl-layout__content">
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h2><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">build</i>Kiểm Tra Hệ Thống</h2>
                </div>
                <div class="mdl-card__content">
                    <?php
                    $errors = [];
                    $warnings = [];
                    $success = [];
                    
                    // Kiểm tra phiên bản PHP
                    if (version_compare(PHP_VERSION, '7.4.0', '>=')) {
                        $success[] = "✓ PHP Version: " . PHP_VERSION . " (OK)";
                    } else {
                        $errors[] = "✗ PHP Version: " . PHP_VERSION . " (Cần >= 7.4.0)";
                    }
                    
                    // Kiểm tra extension MySQLi
                    if (extension_loaded('mysqli')) {
                        $success[] = "✓ MySQLi Extension: Có sẵn";
                    } else {
                        $errors[] = "✗ MySQLi Extension: Không có sẵn";
                    }
                    
                    // Kiểm tra extension GD (cho xử lý ảnh)
                    if (extension_loaded('gd')) {
                        $success[] = "✓ GD Extension: Có sẵn";
                    } else {
                        $warnings[] = "⚠ GD Extension: Không có sẵn (Tùy chọn cho xử lý ảnh)";
                    }
                    
                    // Kiểm tra thư mục upload
                    if (is_dir('upload')) {
                        if (is_writable('upload')) {
                            $success[] = "✓ Thư mục upload/: Có sẵn và có thể ghi";
                        } else {
                            $warnings[] = "⚠ Thư mục upload/: Không có quyền ghi";
                        }
                    } else {
                        if (mkdir('upload', 0755, true)) {
                            $success[] = "✓ Thư mục upload/: Đã tạo thành công";
                        } else {
                            $errors[] = "✗ Thư mục upload/: Không thể tạo";
                        }
                    }
                    
                    // Kiểm tra file cấu hình
                    if (file_exists('DataProvider.php')) {
                        $success[] = "✓ DataProvider.php: Có sẵn";
                    } else {
                        $errors[] = "✗ DataProvider.php: Không tìm thấy";
                    }
                    
                    // Kiểm tra kết nối database
                    try {
                        include_once('DataProvider.php');
                        $testQuery = DataProvider::ExecuteQuery("SELECT 1");
                        if ($testQuery) {
                            $success[] = "✓ Kết nối Database: Thành công";
                            
                            // Kiểm tra các bảng
                            $tables = ['Book', 'Category', 'Publisher', 'User'];
                            foreach ($tables as $table) {
                                $checkTable = DataProvider::ExecuteQuery("SHOW TABLES LIKE '$table'");
                                if ($checkTable && $checkTable->num_rows > 0) {
                                    $success[] = "✓ Bảng $table: Có sẵn";
                                } else {
                                    $warnings[] = "⚠ Bảng $table: Chưa tồn tại";
                                }
                            }
                        } else {
                            $errors[] = "✗ Kết nối Database: Thất bại";
                        }
                    } catch (Exception $e) {
                        $errors[] = "✗ Kết nối Database: " . $e->getMessage();
                    }
                    
                    // Hiển thị kết quả
                    if (!empty($success)) {
                        echo '<div style="background-color: #e8f5e8; border-left: 4px solid #4caf50; padding: 16px; margin-bottom: 16px; border-radius: 4px;">';
                        echo '<h4 style="color: #2e7d32; margin-top: 0;"><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">check_circle</i>Thành Công</h4>';
                        foreach ($success as $msg) {
                            echo '<p style="margin: 4px 0; color: #2e7d32;">' . htmlspecialchars($msg) . '</p>';
                        }
                        echo '</div>';
                    }
                    
                    if (!empty($warnings)) {
                        echo '<div style="background-color: #fff3e0; border-left: 4px solid #ff9800; padding: 16px; margin-bottom: 16px; border-radius: 4px;">';
                        echo '<h4 style="color: #e65100; margin-top: 0;"><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">warning</i>Cảnh Báo</h4>';
                        foreach ($warnings as $msg) {
                            echo '<p style="margin: 4px 0; color: #e65100;">' . htmlspecialchars($msg) . '</p>';
                        }
                        echo '</div>';
                    }
                    
                    if (!empty($errors)) {
                        echo '<div style="background-color: #ffebee; border-left: 4px solid #f44336; padding: 16px; margin-bottom: 16px; border-radius: 4px;">';
                        echo '<h4 style="color: #c62828; margin-top: 0;"><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">error</i>Lỗi</h4>';
                        foreach ($errors as $msg) {
                            echo '<p style="margin: 4px 0; color: #c62828;">' . htmlspecialchars($msg) . '</p>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Hướng dẫn cài đặt -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">help</i>Hướng Dẫn Cài Đặt</h3>
                </div>
                <div class="mdl-card__content">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--6-col">
                            <h4>1. Chuẩn Bị Database</h4>
                            <ol>
                                <li>Mở phpMyAdmin</li>
                                <li>Tạo database tên <code>ebookDB</code></li>
                                <li>Import file <code>database_setup.sql</code></li>
                                <li>Hoặc chạy script SQL để tạo bảng</li>
                            </ol>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--6-col">
                            <h4>2. Cấu Hình Quyền</h4>
                            <ol>
                                <li>Đảm bảo thư mục <code>upload/</code> có quyền ghi</li>
                                <li>Kiểm tra PHP extensions (mysqli, gd)</li>
                                <li>Verify database connection settings</li>
                                <li>Test upload functionality</li>
                            </ol>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--12-col">
                            <h4>3. Tài Khoản Demo</h4>
                            <div style="background-color: #e3f2fd; padding: 16px; border-radius: 8px;">
                                <p><strong>Admin:</strong> username = <code>admin</code>, password = <code>admin123</code></p>
                                <p><strong>User:</strong> username = <code>user1</code>, password = <code>user123</code></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thao tác tiếp theo -->
            <div class="mdl-card">
                <div class="mdl-card__content">
                    <div style="text-align: center;">
                        <?php if (empty($errors)): ?>
                            <p style="color: #2e7d32; font-size: 18px; margin-bottom: 24px;">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">check_circle</i>
                                Hệ thống đã sẵn sàng sử dụng!
                            </p>
                            <a href="TrangChu.php" class="mdl-button mdl-button--raised mdl-button--accent">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                Vào Trang Chủ
                            </a>
                        <?php else: ?>
                            <p style="color: #c62828; font-size: 18px; margin-bottom: 24px;">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">error</i>
                                Vui lòng khắc phục các lỗi trước khi sử dụng
                            </p>
                            <button onclick="location.reload()" class="mdl-button mdl-button--raised">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">refresh</i>
                                Kiểm Tra Lại
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <footer style="background-color: #3f51b5; color: white; padding: 24px; text-align: center; margin-top: 40px;">
            <p>&copy; 2025 BookStore Online Setup. Được thiết kế với Material Design.</p>
        </footer>
    </div>

    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
