<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore Online - Trang Chủ</title>
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
                    BookStore Online
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
            <!-- Welcome Section -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h2>Chào Mừng Đến Với BookStore Online</h2>
                </div>
                <div class="mdl-card__content">
                    <p>Hệ thống quản lý sách trực tuyến với giao diện Material Design hiện đại. 
                    Bạn có thể tìm kiếm, thêm mới, cập nhật và xóa thông tin sách một cách dễ dàng.</p>
                    
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--4-col">
                            <div class="mdl-card elevation-2" style="width: 100%;">
                                <div class="mdl-card__content text-center">
                                    <i class="material-icons" style="font-size: 48px; color: #3f51b5;">search</i>
                                    <h3>Tìm Kiếm Sách</h3>
                                    <p>Tìm kiếm sách theo tên, tác giả hoặc thể loại một cách nhanh chóng và chính xác.</p>
                                    <a href="TimSach.php" class="mdl-button mdl-button--raised">Tìm Kiếm</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div class="mdl-card elevation-2" style="width: 100%;">
                                <div class="mdl-card__content text-center">
                                    <i class="material-icons" style="font-size: 48px; color: #4caf50;">add_circle</i>
                                    <h3>Thêm Sách Mới</h3>
                                    <p>Thêm thông tin sách mới vào hệ thống với đầy đủ thông tin và hình ảnh.</p>
                                    <a href="ThemSach.php" class="mdl-button mdl-button--raised">Thêm Sách</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div class="mdl-card elevation-2" style="width: 100%;">
                                <div class="mdl-card__content text-center">
                                    <i class="material-icons" style="font-size: 48px; color: #ff9800;">edit</i>
                                    <h3>Quản Lý Sách</h3>
                                    <p>Cập nhật thông tin sách, chỉnh sửa nội dung và quản lý thư viện sách.</p>
                                    <a href="TimSach.php" class="mdl-button mdl-button--raised">Quản Lý</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h2>Thống Kê Hệ Thống</h2>
                </div>
                <div class="mdl-card__content">
                    <?php
                    include_once("DataProvider.php");
                    
                    // Get statistics
                    $totalBooks = 0;
                    $totalCategories = 0;
                    $totalPublishers = 0;
                    
                    try {
                        // Count books
                        $result = DataProvider::ExecuteQuery("SELECT COUNT(*) as total FROM Book");
                        if ($result && $row = $result->fetch_assoc()) {
                            $totalBooks = $row['total'];
                        }
                        
                        // Count categories
                        $result = DataProvider::ExecuteQuery("SELECT COUNT(*) as total FROM Category");
                        if ($result && $row = $result->fetch_assoc()) {
                            $totalCategories = $row['total'];
                        }
                        
                        // Count publishers
                        $result = DataProvider::ExecuteQuery("SELECT COUNT(*) as total FROM Publisher");
                        if ($result && $row = $result->fetch_assoc()) {
                            $totalPublishers = $row['total'];
                        }
                    } catch (Exception $e) {
                        // Handle database connection errors gracefully
                        echo "<p style='color: #f44336;'>Không thể kết nối đến cơ sở dữ liệu. Vui lòng kiểm tra lại cấu hình.</p>";
                    }
                    ?>
                    
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--4-col">
                            <div class="mdl-card elevation-1" style="width: 100%; background: linear-gradient(135deg, #3f51b5, #5c6bc0);">
                                <div class="mdl-card__content text-center" style="color: white;">
                                    <i class="material-icons" style="font-size: 36px;">book</i>
                                    <h2 style="margin: 8px 0; color: white;"><?php echo $totalBooks; ?></h2>
                                    <p style="color: rgba(255,255,255,0.9);">Tổng số sách</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div class="mdl-card elevation-1" style="width: 100%; background: linear-gradient(135deg, #4caf50, #66bb6a);">
                                <div class="mdl-card__content text-center" style="color: white;">
                                    <i class="material-icons" style="font-size: 36px;">category</i>
                                    <h2 style="margin: 8px 0; color: white;"><?php echo $totalCategories; ?></h2>
                                    <p style="color: rgba(255,255,255,0.9);">Thể loại</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div class="mdl-card elevation-1" style="width: 100%; background: linear-gradient(135deg, #ff9800, #ffb74d);">
                                <div class="mdl-card__content text-center" style="color: white;">
                                    <i class="material-icons" style="font-size: 36px;">business</i>
                                    <h2 style="margin: 8px 0; color: white;"><?php echo $totalPublishers; ?></h2>
                                    <p style="color: rgba(255,255,255,0.9);">Nhà xuất bản</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Books Section -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h2>Sách Mới Nhất</h2>
                </div>
                <div class="mdl-card__content">
                    <?php
                    try {
                        $recentBooks = DataProvider::ExecuteQuery("
                            SELECT b.*, c.CategoryName, p.PublisherName 
                            FROM Book b 
                            LEFT JOIN Category c ON b.BookCatID = c.CategoryID 
                            LEFT JOIN Publisher p ON b.BookPubID = p.PublisherID 
                            ORDER BY b.BookID DESC 
                            LIMIT 6
                        ");
                        
                        if ($recentBooks && $recentBooks->num_rows > 0) {
                            echo '<div class="mdl-grid">';
                            while ($book = $recentBooks->fetch_assoc()) {
                                echo '<div class="mdl-cell mdl-cell--4-col">';
                                echo '<div class="mdl-card elevation-2" style="width: 100%;">';
                                echo '<div class="mdl-card__content">';
                                
                                // Book image
                                $imagePath = !empty($book['BookPic']) ? $book['BookPic'] : 'BookImages/default-book.jpg';
                                echo '<div class="text-center mb-16">';
                                echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($book['BookTitle']) . '" class="book-image" style="max-width: 80px; height: auto;">';
                                echo '</div>';
                                
                                echo '<h4 style="margin: 8px 0; color: #3f51b5;">' . htmlspecialchars($book['BookTitle']) . '</h4>';
                                echo '<p><strong>Tác giả:</strong> ' . htmlspecialchars($book['BookAuthor']) . '</p>';
                                echo '<p><strong>Thể loại:</strong> ' . htmlspecialchars($book['CategoryName'] ?? 'N/A') . '</p>';
                                echo '<p><strong>Giá:</strong> ' . number_format($book['BookPrice'], 0, ',', '.') . ' VND</p>';
                                
                                echo '<div class="text-center mt-16">';
                                echo '<a href="CapNhatSach.php?BookID=' . $book['BookID'] . '" class="mdl-button mdl-button--flat">Xem Chi Tiết</a>';
                                echo '</div>';
                                
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        } else {
                            echo '<p class="text-center">Chưa có sách nào trong hệ thống.</p>';
                        }
                    } catch (Exception $e) {
                        echo '<p style="color: #f44336;">Không thể tải danh sách sách.</p>';
                    }
                    ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer style="background-color: #3f51b5; color: white; padding: 24px; text-align: center; margin-top: 40px;">
            <p>&copy; 2025 BookStore Online. Được thiết kế với Material Design.</p>
            <p>Hệ thống quản lý sách trực tuyến - Phiên bản 1.0</p>
        </footer>
    </div>

    <!-- Material Design Lite JavaScript -->
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</body>
</html>
