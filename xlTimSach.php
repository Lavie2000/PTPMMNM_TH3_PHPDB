<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết Quả Tìm Kiếm - BookStore Online</title>
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
                    BookStore Online - Kết Quả Tìm Kiếm
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
            // Pagination settings
            $rowsPerPage = 10;
            $pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($pageNum - 1) * $rowsPerPage;
            
            // Get search parameters
            $tenSach = isset($_REQUEST["txtTenSach"]) ? trim($_REQUEST["txtTenSach"]) : "";
            $tacGia = isset($_REQUEST["txtTacGia"]) ? trim($_REQUEST["txtTacGia"]) : "";
            $theLoai = isset($_REQUEST["cmbTheLoai"]) ? $_REQUEST["cmbTheLoai"] : "";
            $nhaXuatBan = isset($_REQUEST["cmbNhaXuatBan"]) ? $_REQUEST["cmbNhaXuatBan"] : "";
            $namXuatBan = isset($_REQUEST["txtNamXuatBan"]) ? $_REQUEST["txtNamXuatBan"] : "";
            
            include_once("DataProvider.php");
            
            // Build search query
            $whereConditions = [];
            $searchParams = [];
            
            if (!empty($tenSach)) {
                $whereConditions[] = "b.BookTitle LIKE ?";
                $searchParams[] = "%" . $tenSach . "%";
            }
            
            if (!empty($tacGia)) {
                $whereConditions[] = "b.BookAuthor LIKE ?";
                $searchParams[] = "%" . $tacGia . "%";
            }
            
            if (!empty($theLoai)) {
                $whereConditions[] = "b.BookCatID = ?";
                $searchParams[] = $theLoai;
            }
            
            if (!empty($nhaXuatBan)) {
                $whereConditions[] = "b.BookPubID = ?";
                $searchParams[] = $nhaXuatBan;
            }
            
            if (!empty($namXuatBan)) {
                $whereConditions[] = "b.BookYear = ?";
                $searchParams[] = $namXuatBan;
            }
            
            // If no search criteria provided, show error
            if (empty($whereConditions)) {
                echo '<div class="mdl-card">';
                echo '<div class="mdl-card__content">';
                echo '<div style="text-align: center; padding: 40px;">';
                echo '<i class="material-icons" style="font-size: 64px; color: #f44336; margin-bottom: 16px;">error</i>';
                echo '<h3>Không có tiêu chí tìm kiếm</h3>';
                echo '<p>Vui lòng nhập ít nhất một tiêu chí để tìm kiếm sách.</p>';
                echo '<a href="TimSach.php" class="mdl-button mdl-button--raised">Quay lại tìm kiếm</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                exit;
            }
            
            $whereClause = implode(" AND ", $whereConditions);
            
            // Build parameter types string for prepared statement
            $paramTypes = str_repeat('s', count($searchParams));
            
            // Main query for results
            $sql = "SELECT b.*, c.CategoryName, p.PublisherName 
                    FROM Book b 
                    LEFT JOIN Category c ON b.BookCatID = c.CategoryID 
                    LEFT JOIN Publisher p ON b.BookPubID = p.PublisherID 
                    WHERE " . $whereClause . " 
                    ORDER BY b.BookTitle 
                    LIMIT " . $offset . ", " . $rowsPerPage;
            
            // Count query for pagination
            $countSql = "SELECT COUNT(*) as total 
                         FROM Book b 
                         WHERE " . $whereClause;
            
            try {
                // Get total count
                $countResult = DataProvider::ExecutePreparedQuery($countSql, $searchParams, $paramTypes);
                $totalRows = 0;
                if ($countResult && $row = $countResult->fetch_assoc()) {
                    $totalRows = $row['total'];
                }
                
                // Get search results
                $dsSach = DataProvider::ExecutePreparedQuery($sql, $searchParams, $paramTypes);
                ?>

                <!-- Search Summary -->
                <div class="mdl-card">
                    <div class="mdl-card__content">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            <div>
                                <h3 style="margin: 0; color: #3f51b5;">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">search</i>
                                    Kết Quả Tìm Kiếm
                                </h3>
                                <p style="margin: 8px 0 0 0; color: #666;">
                                    Tìm thấy <strong><?php echo $totalRows; ?></strong> cuốn sách
                                    <?php if (!empty($tenSach)): ?>
                                        với tên chứa "<strong><?php echo htmlspecialchars($tenSach); ?></strong>"
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div>
                                <a href="TimSach.php" class="mdl-button mdl-button--flat">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                                    Tìm kiếm mới
                                </a>
                                <a href="ThemSach.php" class="mdl-button mdl-button--raised mdl-button--accent">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">add</i>
                                    Thêm sách mới
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if ($dsSach && $dsSach->num_rows > 0) {
                    // Display results in a responsive table
                    echo '<div class="mdl-card">';
                    echo '<div class="mdl-card__content">';
                    echo '<div style="overflow-x: auto;">';
                    echo '<table class="mdl-data-table" style="width: 100%;">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>STT</th>';
                    echo '<th>Mã sách</th>';
                    echo '<th class="mdl-data-table__cell--non-numeric">Hình ảnh</th>';
                    echo '<th class="mdl-data-table__cell--non-numeric">Tựa sách</th>';
                    echo '<th class="mdl-data-table__cell--non-numeric">Tác giả</th>';
                    echo '<th class="mdl-data-table__cell--non-numeric">Thể loại</th>';
                    echo '<th>Giá tiền</th>';
                    echo '<th>Đánh giá</th>';
                    echo '<th>Thao tác</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    $stt = $offset + 1;
                    while ($row = $dsSach->fetch_assoc()) {
                        $maSach = $row["BookID"];
                        $tenSachResult = $row["BookTitle"];
                        $tacGiaResult = $row["BookAuthor"];
                        $theLoaiResult = $row["CategoryName"] ?? "N/A";
                        $giaTien = $row["BookPrice"];
                        $danhGia = $row["BookRate"];
                        $hinhAnh = $row["BookPic"];
                        
                        echo '<tr>';
                        echo '<td>' . $stt . '</td>';
                        echo '<td>' . $maSach . '</td>';
                        echo '<td class="mdl-data-table__cell--non-numeric">';
                        if (!empty($hinhAnh) && file_exists($hinhAnh)) {
                            echo '<img src="' . htmlspecialchars($hinhAnh) . '" alt="' . htmlspecialchars($tenSachResult) . '" style="width: 40px; height: 60px; object-fit: cover; border-radius: 4px;">';
                        } else {
                            echo '<div style="width: 40px; height: 60px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">';
                            echo '<i class="material-icons" style="color: #ccc;">book</i>';
                            echo '</div>';
                        }
                        echo '</td>';
                        echo '<td class="mdl-data-table__cell--non-numeric">';
                        echo '<a href="CapNhatSach.php?BookID=' . $maSach . '" style="color: #3f51b5; text-decoration: none; font-weight: 500;">';
                        echo htmlspecialchars($tenSachResult);
                        echo '</a>';
                        echo '</td>';
                        echo '<td class="mdl-data-table__cell--non-numeric">' . htmlspecialchars($tacGiaResult) . '</td>';
                        echo '<td class="mdl-data-table__cell--non-numeric">' . htmlspecialchars($theLoaiResult) . '</td>';
                        echo '<td>' . number_format($giaTien, 0, ',', '.') . ' VND</td>';
                        echo '<td>';
                        // Display star rating
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $danhGia) {
                                echo '<i class="material-icons" style="color: #ffc107; font-size: 16px;">star</i>';
                            } else {
                                echo '<i class="material-icons" style="color: #e0e0e0; font-size: 16px;">star</i>';
                            }
                        }
                        echo '<br><small>(' . $danhGia . '/5)</small>';
                        echo '</td>';
                        echo '<td>';
                        echo '<div style="display: flex; gap: 4px;">';
                        echo '<a href="CapNhatSach.php?BookID=' . $maSach . '" class="mdl-button mdl-button--flat mdl-button--colored" title="Xem/Sửa">';
                        echo '<i class="material-icons">edit</i>';
                        echo '</a>';
                        echo '<form method="post" action="xlXoa.php" style="display: inline;" onsubmit="return confirm(\'Bạn có chắc chắn muốn xóa sách này?\');">';
                        echo '<input type="hidden" name="BookIDDeleted" value="' . $maSach . '">';
                        echo '<button type="submit" name="btnXoa" class="mdl-button mdl-button--flat" style="color: #f44336;" title="Xóa">';
                        echo '<i class="material-icons">delete</i>';
                        echo '</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                        
                        $stt++;
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Pagination
                    $maxPage = ceil($totalRows / $rowsPerPage);
                    if ($maxPage > 1) {
                        echo '<div class="pagination">';
                        
                        // Build query string for pagination links
                        $queryParams = [];
                        if (!empty($tenSach)) $queryParams[] = "txtTenSach=" . urlencode($tenSach);
                        if (!empty($tacGia)) $queryParams[] = "txtTacGia=" . urlencode($tacGia);
                        if (!empty($theLoai)) $queryParams[] = "cmbTheLoai=" . urlencode($theLoai);
                        if (!empty($nhaXuatBan)) $queryParams[] = "cmbNhaXuatBan=" . urlencode($nhaXuatBan);
                        if (!empty($namXuatBan)) $queryParams[] = "txtNamXuatBan=" . urlencode($namXuatBan);
                        $queryString = implode("&", $queryParams);
                        
                        // Previous page
                        if ($pageNum > 1) {
                            echo '<a href="?page=' . ($pageNum - 1) . '&' . $queryString . '" title="Trang trước">';
                            echo '<i class="material-icons">chevron_left</i>';
                            echo '</a>';
                        }
                        
                        // Page numbers
                        $startPage = max(1, $pageNum - 2);
                        $endPage = min($maxPage, $pageNum + 2);
                        
                        if ($startPage > 1) {
                            echo '<a href="?page=1&' . $queryString . '">1</a>';
                            if ($startPage > 2) {
                                echo '<span>...</span>';
                            }
                        }
                        
                        for ($page = $startPage; $page <= $endPage; $page++) {
                            if ($page == $pageNum) {
                                echo '<span class="current">' . $page . '</span>';
                            } else {
                                echo '<a href="?page=' . $page . '&' . $queryString . '">' . $page . '</a>';
                            }
                        }
                        
                        if ($endPage < $maxPage) {
                            if ($endPage < $maxPage - 1) {
                                echo '<span>...</span>';
                            }
                            echo '<a href="?page=' . $maxPage . '&' . $queryString . '">' . $maxPage . '</a>';
                        }
                        
                        // Next page
                        if ($pageNum < $maxPage) {
                            echo '<a href="?page=' . ($pageNum + 1) . '&' . $queryString . '" title="Trang sau">';
                            echo '<i class="material-icons">chevron_right</i>';
                            echo '</a>';
                        }
                        
                        echo '</div>';
                        
                        // Pagination info
                        echo '<div style="text-align: center; margin-top: 16px; color: #666;">';
                        echo 'Trang ' . $pageNum . ' / ' . $maxPage . ' (Tổng ' . $totalRows . ' kết quả)';
                        echo '</div>';
                    }
                    
                } else {
                    // No results found
                    echo '<div class="mdl-card">';
                    echo '<div class="mdl-card__content">';
                    echo '<div style="text-align: center; padding: 40px;">';
                    echo '<i class="material-icons" style="font-size: 64px; color: #9e9e9e; margin-bottom: 16px;">search_off</i>';
                    echo '<h3>Không tìm thấy kết quả</h3>';
                    echo '<p>Không tìm thấy sách nào phù hợp với tiêu chí tìm kiếm của bạn.</p>';
                    echo '<div style="margin-top: 24px;">';
                    echo '<a href="TimSach.php" class="mdl-button mdl-button--raised">Tìm kiếm lại</a>';
                    echo '<a href="ThemSach.php" class="mdl-button mdl-button--flat">Thêm sách mới</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                
            } catch (Exception $e) {
                echo '<div class="mdl-card">';
                echo '<div class="mdl-card__content">';
                echo '<div style="text-align: center; padding: 40px;">';
                echo '<i class="material-icons" style="font-size: 64px; color: #f44336; margin-bottom: 16px;">error</i>';
                echo '<h3>Lỗi tìm kiếm</h3>';
                echo '<p>Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại sau.</p>';
                echo '<a href="TimSach.php" class="mdl-button mdl-button--raised">Quay lại</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
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
