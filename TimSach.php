<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Sách - BookStore Online</title>
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
                    BookStore Online - Tìm Sách
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
            <!-- Search Form -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h2><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">search</i>Tìm Kiếm Sách</h2>
                </div>
                <div class="mdl-card__content">
                    <form name="form1" method="post" action="xlTimSach.php">
                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--8-col">
                                <div class="mdl-textfield">
                                    <input type="text" name="txtTenSach" id="txtTenSach" 
                                           class="mdl-textfield__input" 
                                           placeholder=" " required>
                                    <label class="mdl-textfield__label" for="txtTenSach">Nhập tên sách cần tìm</label>
                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--4-col" style="display: flex; align-items: flex-end; gap: 8px;">
                                <button type="submit" name="btnTim" id="btnTim" class="mdl-button mdl-button--raised">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                                    Tìm Sách
                                </button>
                                <button type="reset" name="btnTimLai" class="mdl-button mdl-button--flat">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">refresh</i>
                                    Làm Lại
                                </button>
                            </div>
                        </div>
                        
                        <!-- Advanced Search Options -->
                        <details style="margin-top: 24px;">
                            <summary style="cursor: pointer; padding: 12px; background-color: #f5f5f5; border-radius: 4px; margin-bottom: 16px;">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">tune</i>
                                Tùy chọn tìm kiếm nâng cao
                            </summary>
                            
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield">
                                        <input type="text" name="txtTacGia" id="txtTacGia" 
                                               class="mdl-textfield__input" placeholder=" ">
                                        <label class="mdl-textfield__label" for="txtTacGia">Tác giả</label>
                                    </div>
                                </div>
                                
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-selectfield">
                                        <label class="mdl-selectfield__label">Thể loại</label>
                                        <select name="cmbTheLoai" id="cmbTheLoai" class="mdl-selectfield__select">
                                            <option value="">-- Tất cả thể loại --</option>
                                            <?php
                                            include_once("DataProvider.php");
                                            try {
                                                $dsTheLoai = DataProvider::ExecuteQuery("SELECT * FROM Category ORDER BY CategoryName");
                                                if ($dsTheLoai) {
                                                    while ($row = $dsTheLoai->fetch_assoc()) {
                                                        echo '<option value="' . $row["CategoryID"] . '">' . 
                                                             htmlspecialchars($row["CategoryName"]) . '</option>';
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                echo '<option value="">Lỗi tải thể loại</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-selectfield">
                                        <label class="mdl-selectfield__label">Nhà xuất bản</label>
                                        <select name="cmbNhaXuatBan" id="cmbNhaXuatBan" class="mdl-selectfield__select">
                                            <option value="">-- Tất cả nhà xuất bản --</option>
                                            <?php
                                            try {
                                                $dsNhaXuatBan = DataProvider::ExecuteQuery("SELECT * FROM Publisher ORDER BY PublisherName");
                                                if ($dsNhaXuatBan) {
                                                    while ($row = $dsNhaXuatBan->fetch_assoc()) {
                                                        echo '<option value="' . $row["PublisherID"] . '">' . 
                                                             htmlspecialchars($row["PublisherName"]) . '</option>';
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                echo '<option value="">Lỗi tải nhà xuất bản</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield">
                                        <input type="number" name="txtNamXuatBan" id="txtNamXuatBan" 
                                               class="mdl-textfield__input" placeholder=" " min="1900" max="2030">
                                        <label class="mdl-textfield__label" for="txtNamXuatBan">Năm xuất bản</label>
                                    </div>
                                </div>
                            </div>
                        </details>
                        
                        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                            <a href="TrangChu.php" class="mdl-button mdl-button--flat">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                Trở về trang chủ
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search Tips -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">help</i>Hướng Dẫn Tìm Kiếm</h3>
                </div>
                <div class="mdl-card__content">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--4-col">
                            <div style="text-align: center; padding: 16px;">
                                <i class="material-icons" style="font-size: 48px; color: #3f51b5; margin-bottom: 16px;">title</i>
                                <h4>Tìm theo tên sách</h4>
                                <p>Nhập tên sách hoặc một phần của tên sách để tìm kiếm. Hệ thống sẽ tìm tất cả sách có chứa từ khóa.</p>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div style="text-align: center; padding: 16px;">
                                <i class="material-icons" style="font-size: 48px; color: #4caf50; margin-bottom: 16px;">person</i>
                                <h4>Tìm theo tác giả</h4>
                                <p>Sử dụng tùy chọn nâng cao để tìm sách theo tên tác giả. Có thể nhập một phần tên tác giả.</p>
                            </div>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <div style="text-align: center; padding: 16px;">
                                <i class="material-icons" style="font-size: 48px; color: #ff9800; margin-bottom: 16px;">filter_list</i>
                                <h4>Lọc nâng cao</h4>
                                <p>Sử dụng bộ lọc theo thể loại, nhà xuất bản và năm xuất bản để tìm kiếm chính xác hơn.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Searches or Popular Books -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">trending_up</i>Sách Phổ Biến</h3>
                </div>
                <div class="mdl-card__content">
                    <?php
                    try {
                        // Get popular books (highest rated or most recent)
                        $popularBooks = DataProvider::ExecuteQuery("
                            SELECT b.BookID, b.BookTitle, b.BookAuthor, b.BookPrice, b.BookRate,
                                   c.CategoryName, p.PublisherName
                            FROM Book b 
                            LEFT JOIN Category c ON b.BookCatID = c.CategoryID 
                            LEFT JOIN Publisher p ON b.BookPubID = p.PublisherID 
                            ORDER BY b.BookRate DESC, b.BookID DESC 
                            LIMIT 5
                        ");
                        
                        if ($popularBooks && $popularBooks->num_rows > 0) {
                            echo '<div class="mdl-data-table-wrapper">';
                            echo '<table class="mdl-data-table">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th class="mdl-data-table__cell--non-numeric">Tên sách</th>';
                            echo '<th class="mdl-data-table__cell--non-numeric">Tác giả</th>';
                            echo '<th class="mdl-data-table__cell--non-numeric">Thể loại</th>';
                            echo '<th>Giá</th>';
                            echo '<th>Đánh giá</th>';
                            echo '<th>Thao tác</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            
                            while ($book = $popularBooks->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td class="mdl-data-table__cell--non-numeric">';
                                echo '<strong>' . htmlspecialchars($book['BookTitle']) . '</strong>';
                                echo '</td>';
                                echo '<td class="mdl-data-table__cell--non-numeric">' . htmlspecialchars($book['BookAuthor']) . '</td>';
                                echo '<td class="mdl-data-table__cell--non-numeric">' . htmlspecialchars($book['CategoryName'] ?? 'N/A') . '</td>';
                                echo '<td>' . number_format($book['BookPrice'], 0, ',', '.') . ' VND</td>';
                                echo '<td>';
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $book['BookRate']) {
                                        echo '<i class="material-icons" style="color: #ffc107; font-size: 16px;">star</i>';
                                    } else {
                                        echo '<i class="material-icons" style="color: #e0e0e0; font-size: 16px;">star</i>';
                                    }
                                }
                                echo ' (' . $book['BookRate'] . ')';
                                echo '</td>';
                                echo '<td>';
                                echo '<a href="CapNhatSach.php?BookID=' . $book['BookID'] . '" class="mdl-button mdl-button--flat mdl-button--colored">';
                                echo '<i class="material-icons" style="vertical-align: middle;">visibility</i>';
                                echo '</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            
                            echo '</tbody>';
                            echo '</table>';
                            echo '</div>';
                        } else {
                            echo '<p class="text-center">Chưa có dữ liệu sách.</p>';
                        }
                    } catch (Exception $e) {
                        echo '<p style="color: #f44336;">Không thể tải danh sách sách phổ biến.</p>';
                    }
                    ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer style="background-color: #3f51b5; color: white; padding: 24px; text-align: center; margin-top: 40px;">
            <p>&copy; 2025 BookStore Online. Được thiết kế với Material Design.</p>
        </footer>
    </div>

    <!-- Material Design Lite JavaScript -->
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    
    <!-- Enhanced form interactions -->
    <script>
        // Auto-focus on search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('txtTenSach');
            if (searchInput) {
                searchInput.focus();
            }
        });
        
        // Form validation
        document.querySelector('form[name="form1"]').addEventListener('submit', function(e) {
            const tenSach = document.getElementById('txtTenSach').value.trim();
            const tacGia = document.getElementById('txtTacGia').value.trim();
            const theLoai = document.getElementById('cmbTheLoai').value;
            const nhaXuatBan = document.getElementById('cmbNhaXuatBan').value;
            const namXuatBan = document.getElementById('txtNamXuatBan').value;
            
            // Check if at least one search criterion is provided
            if (!tenSach && !tacGia && !theLoai && !nhaXuatBan && !namXuatBan) {
                e.preventDefault();
                alert('Vui lòng nhập ít nhất một tiêu chí tìm kiếm!');
                document.getElementById('txtTenSach').focus();
            }
        });
    </script>
</body>
</html>
