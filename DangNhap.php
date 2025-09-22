<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - BookStore Online</title>
    <link rel="stylesheet" href="css/material-design.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="mdl-layout">
        <!-- Header -->
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout__title">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">book</i>
                    BookStore Online - Đăng Nhập
                </span>
                <div class="mdl-layout-spacer"></div>
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="TrangChu.php">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                        Trang Chủ
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mdl-layout__content">
            <?php
            $tenDangNhap = isset($_POST["txtTenDangNhap"]) ? $_POST["txtTenDangNhap"] : "";
            $matKhau = isset($_POST["txtMatKhau"]) ? $_POST["txtMatKhau"] : "";
            $ketQuaDangNhap = false;
            $errorMessage = "";

            if (isset($_POST["btnDangNhap"]) && !empty($tenDangNhap) && !empty($matKhau)) {
                include_once("DataProvider.php");
                
                try {
                    // Use prepared statement for security
                    $dsNguoiDung = DataProvider::ExecutePreparedQuery(
                        "SELECT * FROM User WHERE UserName = ? AND Password = ?", 
                        [$tenDangNhap, $matKhau], 
                        "ss"
                    );
                    
                    if ($dsNguoiDung && $dsNguoiDung->num_rows > 0) {
                        $user = $dsNguoiDung->fetch_assoc();
                        $ketQuaDangNhap = true;
                        
                        // Start session and store user info
                        session_start();
                        $_SESSION['user_id'] = $user['UserID'];
                        $_SESSION['username'] = $user['UserName'];
                        $_SESSION['fullname'] = $user['FullName'];
                        $_SESSION['role'] = $user['Role'];
                    } else {
                        $errorMessage = "Tên đăng nhập hoặc mật khẩu không đúng!";
                    }
                } catch (Exception $e) {
                    $errorMessage = "Lỗi kết nối cơ sở dữ liệu!";
                }
            }

            if (!$ketQuaDangNhap) {
            ?>
                <div class="mdl-card" style="max-width: 500px; margin: 40px auto;">
                    <div class="mdl-card__title">
                        <h2><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">login</i>Đăng Nhập Hệ Thống</h2>
                    </div>
                    <div class="mdl-card__content">
                        <?php if (!empty($errorMessage)): ?>
                            <div style="background-color: #ffebee; border-left: 4px solid #f44336; padding: 16px; margin-bottom: 24px; border-radius: 4px;">
                                <i class="material-icons" style="vertical-align: middle; color: #f44336; margin-right: 8px;">error</i>
                                <span style="color: #c62828;"><?php echo htmlspecialchars($errorMessage); ?></span>
                            </div>
                        <?php endif; ?>

                        <form name="formDangnhap" method="post" action="DangNhap.php">
                            <div class="mdl-textfield">
                                <input type="text" name="txtTenDangNhap" id="txtTenDangNhap" 
                                       class="mdl-textfield__input" 
                                       value="<?php echo htmlspecialchars($tenDangNhap); ?>" 
                                       placeholder=" " required>
                                <label class="mdl-textfield__label" for="txtTenDangNhap">Tên đăng nhập</label>
                            </div>

                            <div class="mdl-textfield">
                                <input type="password" name="txtMatKhau" id="txtMatKhau" 
                                       class="mdl-textfield__input" 
                                       placeholder=" " required>
                                <label class="mdl-textfield__label" for="txtMatKhau">Mật khẩu</label>
                            </div>

                            <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 32px;">
                                <button type="submit" name="btnDangNhap" class="mdl-button mdl-button--raised">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">login</i>
                                    Đăng Nhập
                                </button>
                                <button type="reset" name="btnLamLai" class="mdl-button mdl-button--flat">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">refresh</i>
                                    Làm Lại
                                </button>
                            </div>
                        </form>

                        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                            <a href="TrangChu.php" class="mdl-button mdl-button--flat">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                Trở về trang chủ
                            </a>
                        </div>

                        <!-- Demo accounts info -->
                        <div style="background-color: #e3f2fd; border-left: 4px solid #2196f3; padding: 16px; margin-top: 24px; border-radius: 4px;">
                            <h4 style="margin-top: 0; color: #1976d2;">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">info</i>
                                Tài khoản demo
                            </h4>
                            <p style="margin-bottom: 8px;"><strong>Quản trị viên:</strong></p>
                            <p style="margin: 4px 0;">Tên đăng nhập: <code>admin</code></p>
                            <p style="margin: 4px 0;">Mật khẩu: <code>admin123</code></p>
                            
                            <p style="margin: 16px 0 8px 0;"><strong>Người dùng:</strong></p>
                            <p style="margin: 4px 0;">Tên đăng nhập: <code>user1</code></p>
                            <p style="margin: 4px 0;">Mật khẩu: <code>user123</code></p>
                        </div>
                    </div>
                </div>

            <?php
            } else {
                // Successful login
            ?>
                <div class="mdl-card" style="max-width: 600px; margin: 40px auto;">
                    <div class="mdl-card__title" style="background-color: #4caf50;">
                        <h2 style="color: white;">
                            <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">check_circle</i>
                            Đăng Nhập Thành Công
                        </h2>
                    </div>
                    <div class="mdl-card__content">
                        <div style="text-align: center; padding: 24px;">
                            <i class="material-icons" style="font-size: 64px; color: #4caf50; margin-bottom: 16px;">account_circle</i>
                            <h3>Xin chào, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h3>
                            <p style="color: #666; margin-bottom: 32px;">
                                Bạn đã đăng nhập thành công với tư cách: 
                                <strong><?php echo $_SESSION['role'] == 'admin' ? 'Quản trị viên' : 'Người dùng'; ?></strong>
                            </p>
                            
                            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                                <a href="TrangChu.php" class="mdl-button mdl-button--raised">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                    Trang Chủ
                                </a>
                                <a href="TimSach.php" class="mdl-button mdl-button--raised">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                                    Tìm Sách
                                </a>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                <a href="ThemSach.php" class="mdl-button mdl-button--raised mdl-button--accent">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">add</i>
                                    Thêm Sách
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- User info card -->
                        <div style="background-color: #f5f5f5; padding: 16px; border-radius: 8px; margin-top: 24px;">
                            <h4 style="margin-top: 0; color: #3f51b5;">Thông tin tài khoản</h4>
                            <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                            <p><strong>Vai trò:</strong> <?php echo $_SESSION['role'] == 'admin' ? 'Quản trị viên' : 'Người dùng'; ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </main>

        <!-- Footer -->
        <footer style="background-color: #3f51b5; color: white; padding: 24px; text-align: center; margin-top: 40px;">
            <p>&copy; 2025 BookStore Online. Được thiết kế với Material Design.</p>
        </footer>
    </div>

    <!-- Material Design Lite JavaScript -->
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
