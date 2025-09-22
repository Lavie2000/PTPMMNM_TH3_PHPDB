<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Thông Tin Sách - BookStore Online</title>
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
                    BookStore Online - Cập Nhật Sách
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
            
            $bookID = isset($_GET["BookID"]) ? (int)$_GET["BookID"] : 0;
            $successMessage = "";
            $errorMessage = "";
            $bookData = null;
            
            // Fetch book data
            if ($bookID > 0) {
                try {
                    $sql = "SELECT b.*, c.CategoryName, p.PublisherName 
                            FROM Book b 
                            LEFT JOIN Category c ON b.BookCatID = c.CategoryID 
                            LEFT JOIN Publisher p ON b.BookPubID = p.PublisherID 
                            WHERE b.BookID = ?";
                    $result = DataProvider::ExecutePreparedQuery($sql, [$bookID], "i");
                    
                    if ($result && $result->num_rows > 0) {
                        $bookData = $result->fetch_assoc();
                    } else {
                        $errorMessage = "Không tìm thấy sách với mã số: " . $bookID;
                    }
                } catch (Exception $e) {
                    $errorMessage = "Lỗi truy vấn dữ liệu: " . $e->getMessage();
                }
            } else {
                $errorMessage = "Mã sách không hợp lệ!";
            }
            
            // Process form submission for update
            if (isset($_POST["btnCapNhat"]) && $bookData) {
                try {
                    // Validate required fields
                    $tenSach = trim($_POST["txtTenSach"]);
                    $noiDungTomTat = trim($_POST["txtNoiDungTomTat"]);
                    $theLoai = $_POST["cmbTheLoai"];
                    $tacGia = trim($_POST["txtTacGia"]);
                    $nhaXuatBan = $_POST["cmbNhaXuatBan"];
                    $namXuatBan = $_POST["txtNamXuatBan"];
                    $giaTien = $_POST["txtGiaTien"];
                    $danhGia = isset($_POST["txtDanhGia"]) ? $_POST["txtDanhGia"] : 0;
                    
                    if (empty($tenSach) || empty($noiDungTomTat) || empty($tacGia)) {
                        throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc!");
                    }
                    
                    if (!is_numeric($namXuatBan) || !is_numeric($giaTien)) {
                        throw new Exception("Năm xuất bản và giá tiền phải là số!");
                    }
                    
                    // Handle file upload if new image is provided
                    $hinhBia = $bookData['BookPic']; // Keep existing image by default
                    if (isset($_FILES['fileUploadHinhBia']) && $_FILES['fileUploadHinhBia']['error'] == UPLOAD_ERR_OK) {
                        $fileName = $_FILES['fileUploadHinhBia']['name'];
                        $fileTmp = $_FILES['fileUploadHinhBia']['tmp_name'];
                        $fileSize = $_FILES['fileUploadHinhBia']['size'];
                        $fileType = $_FILES['fileUploadHinhBia']['type'];
                        
                        // Validate file type
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!in_array($fileType, $allowedTypes)) {
                            throw new Exception("Chỉ chấp nhận file ảnh định dạng JPG, PNG, GIF!");
                        }
                        
                        // Validate file size (max 5MB)
                        if ($fileSize > 5 * 1024 * 1024) {
                            throw new Exception("Kích thước file không được vượt quá 5MB!");
                        }
                        
                        // Create upload directory if it doesn't exist
                        if (!is_dir("upload")) {
                            mkdir("upload", 0777, true);
                        }
                        
                        // Delete old image file if exists
                        if (!empty($bookData['BookPic']) && file_exists($bookData['BookPic'])) {
                            unlink($bookData['BookPic']);
                        }
                        
                        // Generate unique filename
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $newFileName = $bookID . "_" . time() . "." . $fileExtension;
                        $hinhBia = "upload/" . $newFileName;
                        
                        if (!move_uploaded_file($fileTmp, $hinhBia)) {
                            throw new Exception("Không thể upload file ảnh!");
                        }
                    }
                    
                    // Update book record
                    $updateSql = "UPDATE Book SET BookTitle = ?, BookDesc = ?, BookCatID = ?, BookAuthor = ?, 
                                  BookPubID = ?, BookYear = ?, BookPrice = ?, BookRate = ?, BookPic = ? 
                                  WHERE BookID = ?";
                    $params = [$tenSach, $noiDungTomTat, $theLoai, $tacGia, $nhaXuatBan, $namXuatBan, $giaTien, $danhGia, $hinhBia, $bookID];
                    $types = "ssisisddsi";
                    
                    $result = DataProvider::ExecutePreparedQuery($updateSql, $params, $types);
                    
                    if ($result) {
                        $successMessage = "Đã cập nhật thông tin sách thành công!";
                        
                        // Refresh book data
                        $sql = "SELECT b.*, c.CategoryName, p.PublisherName 
                                FROM Book b 
                                LEFT JOIN Category c ON b.BookCatID = c.CategoryID 
                                LEFT JOIN Publisher p ON b.BookPubID = p.PublisherID 
                                WHERE b.BookID = ?";
                        $result = DataProvider::ExecutePreparedQuery($sql, [$bookID], "i");
                        if ($result && $result->num_rows > 0) {
                            $bookData = $result->fetch_assoc();
                        }
                    } else {
                        throw new Exception("Không thể cập nhật thông tin sách!");
                    }
                    
                } catch (Exception $e) {
                    $errorMessage = $e->getMessage();
                }
            }
            ?>

            <?php if (!$bookData): ?>
                <!-- Error: Book not found -->
                <div class="mdl-card">
                    <div class="mdl-card__content">
                        <div style="text-align: center; padding: 40px;">
                            <i class="material-icons" style="font-size: 64px; color: #f44336; margin-bottom: 16px;">error</i>
                            <h3>Không Tìm Thấy Sách</h3>
                            <p><?php echo htmlspecialchars($errorMessage); ?></p>
                            <div style="margin-top: 24px;">
                                <a href="TimSach.php" class="mdl-button mdl-button--raised">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                                    Tìm sách khác
                                </a>
                                <a href="TrangChu.php" class="mdl-button mdl-button--flat">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                    Trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Success/Error Messages -->
                <?php if (!empty($successMessage)): ?>
                <div class="mdl-card">
                    <div class="mdl-card__content">
                        <div style="background-color: #e8f5e8; border-left: 4px solid #4caf50; padding: 16px; border-radius: 4px;">
                            <i class="material-icons" style="vertical-align: middle; color: #4caf50; margin-right: 8px;">check_circle</i>
                            <span style="color: #2e7d32; font-weight: 500;"><?php echo htmlspecialchars($successMessage); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($errorMessage)): ?>
                <div class="mdl-card">
                    <div class="mdl-card__content">
                        <div style="background-color: #ffebee; border-left: 4px solid #f44336; padding: 16px; border-radius: 4px;">
                            <i class="material-icons" style="vertical-align: middle; color: #f44336; margin-right: 8px;">error</i>
                            <span style="color: #c62828; font-weight: 500;"><?php echo htmlspecialchars($errorMessage); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Book Information Display -->
                <div class="mdl-card">
                    <div class="mdl-card__title">
                        <h2><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">info</i>Thông Tin Chi Tiết Sách</h2>
                    </div>
                    <div class="mdl-card__content">
                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--4-col">
                                <div style="text-align: center;">
                                    <?php if (!empty($bookData['BookPic']) && file_exists($bookData['BookPic'])): ?>
                                        <img src="<?php echo htmlspecialchars($bookData['BookPic']); ?>" 
                                             alt="<?php echo htmlspecialchars($bookData['BookTitle']); ?>" 
                                             style="max-width: 200px; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                    <?php else: ?>
                                        <div style="width: 200px; height: 280px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin: 0 auto;">
                                            <i class="material-icons" style="font-size: 64px; color: #ccc;">book</i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--8-col">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5; width: 30%;">Mã sách:</td>
                                        <td style="padding: 8px;"><?php echo htmlspecialchars($bookData['BookID']); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5;">Tựa sách:</td>
                                        <td style="padding: 8px; font-weight: 500;"><?php echo htmlspecialchars($bookData['BookTitle']); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5;">Tác giả:</td>
                                        <td style="padding: 8px;"><?php echo htmlspecialchars($bookData['BookAuthor']); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5;">Thể loại:</td>
                                        <td style="padding: 8px;"><?php echo htmlspecialchars($bookData['CategoryName'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5;">Nhà xuất bản:</td>
                                        <td style="padding: 8px;"><?php echo htmlspecialchars($bookData['PublisherName'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5;">Năm xuất bản:</td>
                                        <td style="padding: 8px;"><?php echo htmlspecialchars($bookData['BookYear']); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5;">Giá tiền:</td>
                                        <td style="padding: 8px; color: #4caf50; font-weight: 500;"><?php echo number_format($bookData['BookPrice'], 0, ',', '.'); ?> VND</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; font-weight: 500; color: #3f51b5;">Đánh giá:</td>
                                        <td style="padding: 8px;">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $bookData['BookRate']) {
                                                    echo '<i class="material-icons" style="color: #ffc107; font-size: 18px;">star</i>';
                                                } else {
                                                    echo '<i class="material-icons" style="color: #e0e0e0; font-size: 18px;">star</i>';
                                                }
                                            }
                                            echo ' (' . $bookData['BookRate'] . '/5)';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <div style="margin-top: 16px;">
                                    <strong style="color: #3f51b5;">Mô tả:</strong>
                                    <p style="margin: 8px 0; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($bookData['BookDesc'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Form -->
                <div class="mdl-card">
                    <div class="mdl-card__title">
                        <h2><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">edit</i>Cập Nhật Thông Tin Sách</h2>
                    </div>
                    <div class="mdl-card__content">
                        <form action="" method="post" enctype="multipart/form-data" name="formCapNhat">
                            <div class="mdl-grid">
                                <!-- Book Title -->
                                <div class="mdl-cell mdl-cell--12-col">
                                    <div class="mdl-textfield">
                                        <input type="text" name="txtTenSach" id="txtTenSach" 
                                               class="mdl-textfield__input" 
                                               value="<?php echo htmlspecialchars($bookData['BookTitle']); ?>" 
                                               placeholder=" " required>
                                        <label class="mdl-textfield__label" for="txtTenSach">Tựa sách *</label>
                                    </div>
                                </div>

                                <!-- Book Cover Image -->
                                <div class="mdl-cell mdl-cell--12-col">
                                    <label style="display: block; margin-bottom: 8px; color: #3f51b5; font-size: 12px;">Hình bìa</label>
                                    <div class="file-upload">
                                        <input type="file" name="fileUploadHinhBia" id="fileUploadHinhBia" accept="image/*">
                                        <div class="file-upload-button">
                                            <i class="material-icons">cloud_upload</i>
                                            <span>Thay đổi hình ảnh (tùy chọn)</span>
                                        </div>
                                    </div>
                                    <small style="color: #666;">Để trống nếu không muốn thay đổi hình ảnh. Chấp nhận JPG, PNG, GIF. Tối đa 5MB.</small>
                                </div>

                                <!-- Book Description -->
                                <div class="mdl-cell mdl-cell--12-col">
                                    <div class="mdl-textfield">
                                        <textarea name="txtNoiDungTomTat" id="txtNoiDungTomTat" 
                                                  class="mdl-textfield__input" 
                                                  rows="4" placeholder=" " required><?php echo htmlspecialchars($bookData['BookDesc']); ?></textarea>
                                        <label class="mdl-textfield__label" for="txtNoiDungTomTat">Nội dung tóm tắt *</label>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-selectfield">
                                        <label class="mdl-selectfield__label">Thể loại *</label>
                                        <select name="cmbTheLoai" id="cmbTheLoai" class="mdl-selectfield__select" required>
                                            <option value="">-- Chọn thể loại --</option>
                                            <?php
                                            try {
                                                $dsTheLoai = DataProvider::ExecuteQuery("SELECT * FROM Category ORDER BY CategoryName");
                                                if ($dsTheLoai) {
                                                    while ($row = $dsTheLoai->fetch_assoc()) {
                                                        $selected = ($bookData['BookCatID'] == $row["CategoryID"]) ? 'selected' : '';
                                                        echo '<option value="' . $row["CategoryID"] . '" ' . $selected . '>' . 
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

                                <!-- Author -->
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield">
                                        <input type="text" name="txtTacGia" id="txtTacGia" 
                                               class="mdl-textfield__input" 
                                               value="<?php echo htmlspecialchars($bookData['BookAuthor']); ?>" 
                                               placeholder=" " required>
                                        <label class="mdl-textfield__label" for="txtTacGia">Tác giả *</label>
                                    </div>
                                </div>

                                <!-- Publisher -->
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-selectfield">
                                        <label class="mdl-selectfield__label">Nhà xuất bản *</label>
                                        <select name="cmbNhaXuatBan" id="cmbNhaXuatBan" class="mdl-selectfield__select" required>
                                            <option value="">-- Chọn nhà xuất bản --</option>
                                            <?php
                                            try {
                                                $dsNhaXuatBan = DataProvider::ExecuteQuery("SELECT * FROM Publisher ORDER BY PublisherName");
                                                if ($dsNhaXuatBan) {
                                                    while ($row = $dsNhaXuatBan->fetch_assoc()) {
                                                        $selected = ($bookData['BookPubID'] == $row["PublisherID"]) ? 'selected' : '';
                                                        echo '<option value="' . $row["PublisherID"] . '" ' . $selected . '>' . 
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

                                <!-- Publication Year -->
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield">
                                        <input type="number" name="txtNamXuatBan" id="txtNamXuatBan" 
                                               class="mdl-textfield__input" 
                                               value="<?php echo htmlspecialchars($bookData['BookYear']); ?>" 
                                               min="1900" max="2030" placeholder=" " required>
                                        <label class="mdl-textfield__label" for="txtNamXuatBan">Năm xuất bản *</label>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield">
                                        <input type="number" name="txtGiaTien" id="txtGiaTien" 
                                               class="mdl-textfield__input" 
                                               value="<?php echo htmlspecialchars($bookData['BookPrice']); ?>" 
                                               min="0" step="1000" placeholder=" " required>
                                        <label class="mdl-textfield__label" for="txtGiaTien">Giá tiền (VND) *</label>
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="mdl-cell mdl-cell--6-col">
                                    <div class="mdl-textfield">
                                        <input type="number" name="txtDanhGia" id="txtDanhGia" 
                                               class="mdl-textfield__input" 
                                               value="<?php echo htmlspecialchars($bookData['BookRate']); ?>" 
                                               min="0" max="5" step="0.1" placeholder=" ">
                                        <label class="mdl-textfield__label" for="txtDanhGia">Đánh giá (0-5 sao)</label>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="mdl-cell mdl-cell--12-col">
                                    <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                                        <button type="submit" name="btnCapNhat" class="mdl-button mdl-button--raised mdl-button--accent">
                                            <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">save</i>
                                            Cập Nhật
                                        </button>
                                        <button type="reset" name="btnLamLai" class="mdl-button mdl-button--flat">
                                            <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">refresh</i>
                                            Làm Lại
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                            <a href="TimSach.php" class="mdl-button mdl-button--flat">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                                Danh sách sách
                            </a>
                            <a href="TrangChu.php" class="mdl-button mdl-button--flat">
                                <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                                Trang chủ
                            </a>
                            <form method="post" action="xlXoa.php" style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này? Hành động này không thể hoàn tác!');">
                                <input type="hidden" name="BookIDDeleted" value="<?php echo $bookData['BookID']; ?>">
                                <button type="submit" name="btnXoa" class="mdl-button mdl-button--flat" style="color: #f44336;">
                                    <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">delete</i>
                                    Xóa sách này
                                </button>
                            </form>
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
    
    <!-- Enhanced form functionality -->
    <script>
        // File upload preview
        document.getElementById('fileUploadHinhBia').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const button = document.querySelector('.file-upload-button span');
            
            if (file) {
                button.textContent = file.name;
                
                // Validate file size
                if (file.size > 5 * 1024 * 1024) {
                    alert('File quá lớn! Vui lòng chọn file nhỏ hơn 5MB.');
                    e.target.value = '';
                    button.textContent = 'Thay đổi hình ảnh (tùy chọn)';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Định dạng file không được hỗ trợ! Vui lòng chọn file JPG, PNG hoặc GIF.');
                    e.target.value = '';
                    button.textContent = 'Thay đổi hình ảnh (tùy chọn)';
                    return;
                }
            } else {
                button.textContent = 'Thay đổi hình ảnh (tùy chọn)';
            }
        });
        
        // Form validation
        document.querySelector('form[name="formCapNhat"]').addEventListener('submit', function(e) {
            const requiredFields = ['txtTenSach', 'txtNoiDungTomTat', 'txtTacGia', 'cmbTheLoai', 'cmbNhaXuatBan', 'txtNamXuatBan', 'txtGiaTien'];
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(function(fieldName) {
                const field = document.querySelector('[name="' + fieldName + '"]');
                const value = field.value.trim();
                
                if (!value) {
                    isValid = false;
                    field.style.borderColor = '#f44336';
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc (đánh dấu *)!');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }
        });
    </script>
</body>
</html>
