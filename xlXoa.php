<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Sách - BookStore Online</title>
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
                    BookStore Online - Xóa Sách
                </span>
                <div class="mdl-layout-spacer"></div>
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="TrangChu.php">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                        Trang Chủ
                    </a>
                    <a class="mdl-navigation__link" href="TimSach.php">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                        Tìm Sách
                    </a>
                    <a class="mdl-navigation__link" href="ThemSach.php">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">add</i>
                        Thêm Sách
                    </a>
                    <a class="mdl-navigation__link" href="DangNhap.php">
                        <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">login</i>
                        Đăng Nhập
                    </a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mdl-layout__content">
            <?php
            include_once("DataProvider.php");
            
            $bookIDDeleted = isset($_POST["BookIDDeleted"]) ? (int)$_POST["BookIDDeleted"] : 0;
            $successMessage = "";
            $errorMessage = "";
            $bookInfo = null;
            
            if ($bookIDDeleted > 0) {
                try {
                    // First, get book information before deletion for display
                    $bookQuery = "SELECT BookTitle, BookAuthor, BookPic FROM Book WHERE BookID = ?";
                    $bookResult = DataProvider::ExecutePreparedQuery($bookQuery, [$bookIDDeleted], "i");
                    
                    if ($bookResult && $bookResult->num_rows > 0) {
                        $bookInfo = $bookResult->fetch_assoc();
                        
                        // Delete the book record
                        $deleteSql = "DELETE FROM Book WHERE BookID = ?";
                        $deleteResult = DataProvider::ExecutePreparedQuery($deleteSql, [$bookIDDeleted], "i");
                        
                        if ($deleteResult) {
                            // Delete associated image file if exists
                            if (!empty($bookInfo['BookPic']) && file_exists($bookInfo['BookPic'])) {
                                unlink($bookInfo['BookPic']);
                            }
                            
                            $successMessage = "Đã xóa thành công cuốn sách có mã là " . $bookIDDeleted;
                        } else {
                            throw new Exception("Không thể xóa sách từ cơ sở dữ liệu!");
                        }
                    } else {
                        $errorMessage = "Không tìm thấy sách với mã số: " . $bookIDDeleted;
                    }
                    
                } catch (Exception $e) {
                    $errorMessage = "Lỗi khi xóa sách: " . $e->getMessage();
                }
            } else {
                $errorMessage = "Mã sách không hợp lệ!";
            }
            ?>

            <!-- Result Display -->
            <div class="mdl-card" style="max-width: 600px; margin: 40px auto;">
                <?php if (!empty($successMessage)): ?>
                    <!-- Success Message -->
                    <div class="mdl-card__title" style="background-color: #4caf50;">
                        <h2 style="color: white;">
                            <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">check_circle</i>
                            Xóa Sách Thành Công
                        </h2>
                    </div>
                    <div class="mdl-card__content">
                        <div style="text-align: center; padding: 24px;">
                            <i class="material-icons" style="font-size: 64px; color: #4caf50; margin-bottom: 16px;">delete_forever</i>
                            
                            <?php if ($bookInfo): ?>
                                <div style="background-color: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 24px; text-align: left;">
                                    <h4 style="margin-top: 0; color: #3f51b5;">Thông tin sách đã xóa:</h4>
                                    <p><strong>Mã sách:</strong> <?php echo htmlspecialchars($bookIDDeleted); ?></p>
                                    <p><strong>Tên sách:</strong> <?php echo htmlspecialchars($bookInfo['BookTitle']); ?></p>
                                    <p><strong>Tác giả:</strong> <?php echo htmlspecialchars($bookInfo['BookAuthor']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <p style="color: #2e7d32; font-size: 18px; margin-bottom: 32px;">
                                <?php echo htmlspecialchars($successMessage); ?>
                            </p>
                            
                            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                                <a href="TimSach.php" class="mdl-button mdl-button--raised">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                                    Tiếp tục tìm kiếm
                                </a>
                                <a href="ThemSach.php" class="mdl-button mdl-button--raised mdl-button--accent">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">add</i>
                                    Thêm sách mới
                                </a>
                                <a href="TrangChu.php" class="mdl-button mdl-button--flat">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                    Trở về trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Error Message -->
                    <div class="mdl-card__title" style="background-color: #f44336;">
                        <h2 style="color: white;">
                            <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">error</i>
                            Lỗi Xóa Sách
                        </h2>
                    </div>
                    <div class="mdl-card__content">
                        <div style="text-align: center; padding: 24px;">
                            <i class="material-icons" style="font-size: 64px; color: #f44336; margin-bottom: 16px;">error_outline</i>
                            
                            <p style="color: #c62828; font-size: 18px; margin-bottom: 32px;">
                                <?php echo htmlspecialchars($errorMessage); ?>
                            </p>
                            
                            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                                <a href="TimSach.php" class="mdl-button mdl-button--raised">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                                    Tìm sách khác
                                </a>
                                <a href="TrangChu.php" class="mdl-button mdl-button--flat">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                    Trở về trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Additional Information -->
            <div class="mdl-card" style="max-width: 800px; margin: 0 auto;">
                <div class="mdl-card__title">
                    <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">info</i>Thông Tin Quan Trọng</h3>
                </div>
                <div class="mdl-card__content">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--4-col">
                            <div style="text-align: center; padding: 16px;">
                                <i class="material-icons" style="font-size: 48px; color: #ff9800; margin-bottom: 16px;">warning</i>
                                <h4>Lưu Ý</h4>
                                <p>Việc xóa sách là vĩnh viễn và không thể hoàn tác. Hãy cân nhắc kỹ trước khi thực hiện.</p>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div style="text-align: center; padding: 16px;">
                                <i class="material-icons" style="font-size: 48px; color: #2196f3; margin-bottom: 16px;">backup</i>
                                <h4>Sao Lưu</h4>
                                <p>Thông tin sách đã được xóa khỏi hệ thống. Hãy đảm bảo có bản sao lưu nếu cần thiết.</p>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div style="text-align: center; padding: 16px;">
                                <i class="material-icons" style="font-size: 48px; color: #4caf50; margin-bottom: 16px;">library_books</i>
                                <h4>Quản Lý</h4>
                                <p>Tiếp tục quản lý thư viện sách của bạn bằng cách thêm mới, tìm kiếm hoặc cập nhật thông tin.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <?php if (!empty($successMessage)): ?>
            <div class="mdl-card" style="max-width: 600px; margin: 24px auto;">
                <div class="mdl-card__title">
                    <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">flash_on</i>Thao Tác Nhanh</h3>
                </div>
                <div class="mdl-card__content">
                    <div style="text-align: center;">
                        <p style="margin-bottom: 24px; color: #666;">Bạn có thể thực hiện các thao tác sau:</p>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            <a href="TimSach.php" class="mdl-button mdl-button--raised" style="width: 100%;">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">search</i>
                                Tìm sách khác
                            </a>
                            
                            <a href="ThemSach.php" class="mdl-button mdl-button--raised mdl-button--accent" style="width: 100%;">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">add</i>
                                Thêm sách mới
                            </a>
                            
                            <a href="TrangChu.php" class="mdl-button mdl-button--flat" style="width: 100%;">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">home</i>
                                Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <footer style="background-color: #3f51b5; color: white; padding: 24px; text-align: center; margin-top: 40px;">
            <p>&copy; 2025 BookStore Online. Được thiết kế với Material Design.</p>
        </footer>
    </div>

    <!-- Material Design Lite JavaScript -->
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    
    <!-- Auto redirect after success (optional) -->
    <script>
        <?php if (!empty($successMessage)): ?>
        // Auto redirect to search page after 5 seconds (optional)
        // setTimeout(function() {
        //     window.location.href = 'TimSach.php';
        // }, 5000);
        <?php endif; ?>
        
        // Show confirmation before leaving page if there was an error
        <?php if (!empty($errorMessage)): ?>
        window.addEventListener('beforeunload', function(e) {
            // This will show a confirmation dialog when user tries to leave the page
            // e.preventDefault();
            // e.returnValue = '';
        });
        <?php endif; ?>
    </script>
</body>
</html>
