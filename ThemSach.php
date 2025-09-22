<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sách Mới - BookStore Online</title>
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
                    BookStore Online - Thêm Sách Mới
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
            $successMessage = "";
            $errorMessage = "";
            
            // Process form submission
            if (isset($_POST["btnThemMoi"])) {
                include_once("DataProvider.php");
                
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
                    
                    // Insert book record
                    $sql = "INSERT INTO Book (BookTitle, BookDesc, BookCatID, BookAuthor, BookPubID, BookYear, BookPrice, BookRate, BookPic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, '')";
                    $params = [$tenSach, $noiDungTomTat, $theLoai, $tacGia, $nhaXuatBan, $namXuatBan, $giaTien, $danhGia];
                    $types = "ssisisdd";
                    
                    $result = DataProvider::ExecutePreparedQuery($sql, $params, $types);
                    
                    if ($result) {
                        // Get the last inserted ID
                        $connection = new mysqli("localhost", "root", "", "ebookDB");
                        $maSach = $connection->insert_id;
                        $connection->close();
                        
                        // Handle file upload
                        $hinhBia = "";
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
                            
                            // Generate unique filename
                            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                            $newFileName = $maSach . "_" . time() . "." . $fileExtension;
                            $hinhBia = "upload/" . $newFileName;
                            
                            if (move_uploaded_file($fileTmp, $hinhBia)) {
                                // Update book record with image path
                                $updateSql = "UPDATE Book SET BookPic = ? WHERE BookID = ?";
                                DataProvider::ExecutePreparedQuery($updateSql, [$hinhBia, $maSach], "si");
                            }
                        }
                        
                        $successMessage = "Đã thêm sách thành công với mã số: " . $maSach;
                        
                        // Clear form data after successful submission
                        $_POST = array();
                        
                    } else {
                        throw new Exception("Không thể thêm sách. Vui lòng thử lại!");
                    }
                    
                } catch (Exception $e) {
                    $errorMessage = $e->getMessage();
                }
            }
            ?>

            <!-- Success/Error Messages -->
            <?php if (!empty($successMessage)): ?>
            <div class="mdl-card">
                <div class="mdl-card__content">
                    <div style="background-color: #e8f5e8; border-left: 4px solid #4caf50; padding: 16px; border-radius: 4px;">
                        <i class="material-icons" style="vertical-align: middle; color: #4caf50; margin-right: 8px;">check_circle</i>
                        <span style="color: #2e7d32; font-weight: 500;"><?php echo htmlspecialchars($successMessage); ?></span>
                    </div>
                    <div style="text-align: center; margin-top: 16px;">
                        <a href="TimSach.php" class="mdl-button mdl-button--raised">
                            <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                            Xem danh sách sách
                        </a>
                        <a href="ThemSach.php" class="mdl-button mdl-button--flat">
                            <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">add</i>
                            Thêm sách khác
                        </a>
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

            <!-- Add Book Form -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h2><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">add_circle</i>Thêm Một Đầu Sách Mới</h2>
                </div>
                <div class="mdl-card__content">
                    <form action="" method="post" enctype="multipart/form-data" name="form1">
                        <div class="mdl-grid">
                            <!-- Book Title -->
                            <div class="mdl-cell mdl-cell--12-col">
                                <div class="mdl-textfield">
                                    <input type="text" name="txtTenSach" id="txtTenSach" 
                                           class="mdl-textfield__input" 
                                           value="<?php echo isset($_POST['txtTenSach']) ? htmlspecialchars($_POST['txtTenSach']) : ''; ?>" 
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
                                        <span>Chọn file hình ảnh</span>
                                    </div>
                                </div>
                                <small style="color: #666;">Chấp nhận file JPG, PNG, GIF. Kích thước tối đa 5MB.</small>
                            </div>

                            <!-- Book Description -->
                            <div class="mdl-cell mdl-cell--12-col">
                                <div class="mdl-textfield">
                                    <textarea name="txtNoiDungTomTat" id="txtNoiDungTomTat" 
                                              class="mdl-textfield__input" 
                                              rows="4" placeholder=" " required><?php echo isset($_POST['txtNoiDungTomTat']) ? htmlspecialchars($_POST['txtNoiDungTomTat']) : ''; ?></textarea>
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
                                        include_once("DataProvider.php");
                                        try {
                                            $dsTheLoai = DataProvider::ExecuteQuery("SELECT * FROM Category ORDER BY CategoryName");
                                            if ($dsTheLoai) {
                                                while ($row = $dsTheLoai->fetch_assoc()) {
                                                    $selected = (isset($_POST['cmbTheLoai']) && $_POST['cmbTheLoai'] == $row["CategoryID"]) ? 'selected' : '';
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
                                           value="<?php echo isset($_POST['txtTacGia']) ? htmlspecialchars($_POST['txtTacGia']) : ''; ?>" 
                                           placeholder=" " required>
                                    <label class="mdl-textfield__label" for="txtTacGia">Danh sách tên tác giả *</label>
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
                                                    $selected = (isset($_POST['cmbNhaXuatBan']) && $_POST['cmbNhaXuatBan'] == $row["PublisherID"]) ? 'selected' : '';
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
                                           value="<?php echo isset($_POST['txtNamXuatBan']) ? htmlspecialchars($_POST['txtNamXuatBan']) : date('Y'); ?>" 
                                           min="1900" max="2030" placeholder=" " required>
                                    <label class="mdl-textfield__label" for="txtNamXuatBan">Năm xuất bản *</label>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mdl-cell mdl-cell--6-col">
                                <div class="mdl-textfield">
                                    <input type="number" name="txtGiaTien" id="txtGiaTien" 
                                           class="mdl-textfield__input" 
                                           value="<?php echo isset($_POST['txtGiaTien']) ? htmlspecialchars($_POST['txtGiaTien']) : ''; ?>" 
                                           min="0" step="1000" placeholder=" " required>
                                    <label class="mdl-textfield__label" for="txtGiaTien">Giá tiền (VND) *</label>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="mdl-cell mdl-cell--6-col">
                                <div class="mdl-textfield">
                                    <input type="number" name="txtDanhGia" id="txtDanhGia" 
                                           class="mdl-textfield__input" 
                                           value="<?php echo isset($_POST['txtDanhGia']) ? htmlspecialchars($_POST['txtDanhGia']) : '0'; ?>" 
                                           min="0" max="5" step="0.1" placeholder=" ">
                                    <label class="mdl-textfield__label" for="txtDanhGia">Đánh giá (0-5 sao)</label>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="mdl-cell mdl-cell--12-col">
                                <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e0e0e0;">
                                    <button type="submit" name="btnThemMoi" class="mdl-button mdl-button--raised mdl-button--accent">
                                        <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">save</i>
                                        Thêm Mới
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
                        <a href="TrangChu.php" class="mdl-button mdl-button--flat">
                            <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">home</i>
                            Trở về trang chủ
                        </a>
                        <a href="TimSach.php" class="mdl-button mdl-button--flat">
                            <i class="material-icons" style="vertical-align: middle; margin-right: 4px;">search</i>
                            Danh sách sách
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Guidelines -->
            <div class="mdl-card">
                <div class="mdl-card__title">
                    <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">info</i>Hướng Dẫn Nhập Liệu</h3>
                </div>
                <div class="mdl-card__content">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--4-col">
                            <h4><i class="material-icons" style="vertical-align: middle; margin-right: 8px; color: #f44336;">star</i>Thông tin bắt buộc</h4>
                            <ul>
                                <li>Tựa sách</li>
                                <li>Nội dung tóm tắt</li>
                                <li>Tác giả</li>
                                <li>Thể loại</li>
                                <li>Nhà xuất bản</li>
                                <li>Năm xuất bản</li>
                                <li>Giá tiền</li>
                            </ul>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <h4><i class="material-icons" style="vertical-align: middle; margin-right: 8px; color: #2196f3;">image</i>Hình ảnh</h4>
                            <ul>
                                <li>Định dạng: JPG, PNG, GIF</li>
                                <li>Kích thước tối đa: 5MB</li>
                                <li>Khuyến nghị: 300x400 pixels</li>
                                <li>Hình ảnh rõ nét, chất lượng cao</li>
                            </ul>
                        </div>
                        
                        <div class="mdl-cell mdl-cell--4-col">
                            <h4><i class="material-icons" style="vertical-align: middle; margin-right: 8px; color: #4caf50;">tips_and_updates</i>Lưu ý</h4>
                            <ul>
                                <li>Kiểm tra kỹ thông tin trước khi lưu</li>
                                <li>Tên sách nên đầy đủ và chính xác</li>
                                <li>Mô tả ngắn gọn, súc tích</li>
                                <li>Giá tiền theo đơn vị VND</li>
                            </ul>
                        </div>
                    </div>
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
                    button.textContent = 'Chọn file hình ảnh';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Định dạng file không được hỗ trợ! Vui lòng chọn file JPG, PNG hoặc GIF.');
                    e.target.value = '';
                    button.textContent = 'Chọn file hình ảnh';
                    return;
                }
            } else {
                button.textContent = 'Chọn file hình ảnh';
            }
        });
        
        // Form validation
        document.querySelector('form[name="form1"]').addEventListener('submit', function(e) {
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
        
        // Auto-format price input
        document.getElementById('txtGiaTien').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                // Format with thousand separators for display (optional)
                // e.target.value = parseInt(value).toLocaleString('vi-VN');
            }
        });
    </script>
</body>
</html>
