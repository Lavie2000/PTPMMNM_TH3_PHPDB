# BookStore Online - Hệ Thống Quản Lý Sách Trực Tuyến

## Tổng Quan

BookStore Online là một ứng dụng web quản lý sách được xây dựng bằng PHP và MySQL với giao diện Material Design hiện đại. Hệ thống cung cấp các chức năng đầy đủ để quản lý thông tin sách, bao gồm tìm kiếm, thêm mới, cập nhật và xóa sách.

## Đặc Điểm Nổi Bật

### 🎨 Giao Diện Material Design
- Thiết kế hiện đại, responsive
- Tuân thủ nguyên tắc Material Design của Google
- Tối ưu cho cả desktop và mobile
- Màu sắc và typography nhất quán

### 🔍 Tìm Kiếm Nâng Cao
- Tìm kiếm theo tên sách
- Lọc theo tác giả, thể loại, nhà xuất bản
- Tìm kiếm theo năm xuất bản
- Phân trang kết quả tìm kiếm
- Hiển thị thông tin chi tiết và hình ảnh

### 📚 Quản Lý Sách Toàn Diện
- Thêm sách mới với upload hình ảnh
- Cập nhật thông tin sách
- Xóa sách với xác nhận
- Quản lý thể loại và nhà xuất bản
- Đánh giá sao cho từng cuốn sách

### 🔐 Hệ Thống Đăng Nhập
- Xác thực người dùng an toàn
- Phân quyền admin/user
- Session management
- Giao diện đăng nhập thân thiện

### 📊 Thống Kê và Báo Cáo
- Thống kê tổng số sách, thể loại, nhà xuất bản
- Hiển thị sách mới nhất
- Sách phổ biến (theo đánh giá)
- Dashboard trực quan

## Cấu Trúc Thư Mục

```
BookStoreOnline/
├── css/
│   └── material-design.css          # CSS Material Design tùy chỉnh
├── script/                          # JavaScript files (tùy chọn)
├── BookImages/                      # Thư mục chứa hình ảnh mặc định
├── upload/                          # Thư mục upload hình ảnh sách
├── TrangChu.php                     # Trang chủ
├── DangNhap.php                     # Trang đăng nhập
├── TimSach.php                      # Trang tìm kiếm sách
├── xlTimSach.php                    # Xử lý kết quả tìm kiếm
├── ThemSach.php                     # Trang thêm sách mới
├── CapNhatSach.php                  # Trang cập nhật thông tin sách
├── xlXoa.php                        # Xử lý xóa sách
├── DataProvider.php                 # Class kết nối cơ sở dữ liệu
├── database_setup.sql               # Script tạo cơ sở dữ liệu
└── README.md                        # Tài liệu hướng dẫn
```

## Cơ Sở Dữ Liệu

### Thiết Kế Database

Hệ thống sử dụng MySQL với 4 bảng chính:

#### 1. Bảng `Book` (Sách)
```sql
BookID (int, AUTO_INCREMENT, PRIMARY KEY)
BookTitle (varchar(256), NOT NULL)
BookDesc (varchar(1024), NOT NULL)
BookCatID (int, NOT NULL, FOREIGN KEY)
BookAuthor (varchar(256), NOT NULL)
BookPubID (int, NOT NULL, FOREIGN KEY)
BookYear (int, NOT NULL)
BookPic (varchar(256), NOT NULL)
BookPrice (float, NOT NULL)
BookRate (float, DEFAULT 0)
```

#### 2. Bảng `Category` (Thể Loại)
```sql
CategoryID (int, AUTO_INCREMENT, PRIMARY KEY)
CategoryName (varchar(100), NOT NULL)
CategoryDesc (varchar(1024), NOT NULL)
```

#### 3. Bảng `Publisher` (Nhà Xuất Bản)
```sql
PublisherID (int, AUTO_INCREMENT, PRIMARY KEY)
PublisherName (varchar(256), NOT NULL)
PublisherAddress (varchar(256), NOT NULL)
```

#### 4. Bảng `User` (Người Dùng)
```sql
UserID (int, AUTO_INCREMENT, PRIMARY KEY)
UserName (varchar(50), NOT NULL, UNIQUE)
Password (varchar(255), NOT NULL)
FullName (varchar(100), NOT NULL)
Email (varchar(100), NOT NULL, UNIQUE)
Role (varchar(20), DEFAULT 'user')
CreatedDate (datetime, DEFAULT CURRENT_TIMESTAMP)
```

### Dữ Liệu Mẫu

Hệ thống đi kèm với dữ liệu mẫu:
- 8 thể loại sách (Văn học, Khoa học kỹ thuật, Kinh tế, v.v.)
- 8 nhà xuất bản (NXB Trẻ, NXB Kim Đồng, v.v.)
- 10 cuốn sách mẫu với đầy đủ thông tin
- 3 tài khoản người dùng (1 admin, 2 user)

## Cài Đặt và Triển Khai

### Yêu Cầu Hệ Thống

- **Web Server**: Apache/Nginx
- **PHP**: Phiên bản 7.4 trở lên
- **Database**: MySQL 5.7 trở lên hoặc MariaDB
- **Extensions**: mysqli, gd (cho xử lý ảnh)

### Hướng Dẫn Cài Đặt

#### Bước 1: Chuẩn Bị Môi Trường
```bash
# Với XAMPP (Windows)
1. Tải và cài đặt XAMPP
2. Khởi động Apache và MySQL
3. Truy cập http://localhost/phpmyadmin

# Với WAMP (Windows)
1. Tải và cài đặt WAMP
2. Khởi động các dịch vụ
3. Truy cập http://localhost/phpmyadmin
```

#### Bước 2: Tạo Cơ Sở Dữ Liệu
1. Mở phpMyAdmin
2. Tạo database mới tên `ebookDB`
3. Import file `database_setup.sql`
4. Hoặc chạy script SQL trong file để tạo bảng và dữ liệu mẫu

#### Bước 3: Cấu Hình Ứng Dụng
1. Copy toàn bộ thư mục dự án vào `htdocs` (XAMPP) hoặc `www` (WAMP)
2. Đảm bảo thư mục `upload/` có quyền ghi (chmod 755 trên Linux)
3. Kiểm tra cấu hình database trong `DataProvider.php`:
```php
private static $host = "localhost";
private static $username = "root";
private static $password = "";
private static $database = "ebookDB";
```

#### Bước 4: Truy Cập Ứng Dụng
- Mở trình duyệt và truy cập: `http://localhost/BookStoreOnline/TrangChu.php`
- Hoặc: `http://localhost/[tên_thư_mục]/TrangChu.php`

### Tài Khoản Demo

#### Quản Trị Viên
- **Username**: `admin`
- **Password**: `admin123`
- **Quyền**: Toàn quyền (CRUD sách)

#### Người Dùng
- **Username**: `user1` hoặc `user2`
- **Password**: `user123`
- **Quyền**: Xem và tìm kiếm sách

## Tính Năng Chi Tiết

### 1. Trang Chủ (TrangChu.php)
- **Giao diện tổng quan**: Hiển thị thông tin chào mừng
- **Thống kê hệ thống**: Số lượng sách, thể loại, nhà xuất bản
- **Sách mới nhất**: Hiển thị 6 cuốn sách được thêm gần đây
- **Navigation**: Menu điều hướng Material Design
- **Responsive**: Tối ưu cho mọi kích thước màn hình

### 2. Đăng Nhập (DangNhap.php)
- **Form đăng nhập**: Material Design với validation
- **Xử lý session**: Lưu trữ thông tin người dùng
- **Phân quyền**: Hiển thị chức năng theo role
- **Bảo mật**: Prepared statements chống SQL injection
- **UX**: Thông báo lỗi và thành công rõ ràng

### 3. Tìm Kiếm Sách (TimSach.php & xlTimSach.php)

#### TimSach.php - Form Tìm Kiếm
- **Tìm kiếm cơ bản**: Theo tên sách
- **Tìm kiếm nâng cao**: 
  - Theo tác giả
  - Theo thể loại (dropdown)
  - Theo nhà xuất bản (dropdown)
  - Theo năm xuất bản
- **Gợi ý tìm kiếm**: Hiển thị sách phổ biến
- **Validation**: JavaScript kiểm tra input

#### xlTimSach.php - Kết Quả Tìm Kiếm
- **Hiển thị kết quả**: Bảng responsive với đầy đủ thông tin
- **Phân trang**: 10 kết quả/trang với navigation
- **Sắp xếp**: Theo tên sách (alphabetical)
- **Thao tác**: Xem chi tiết, xóa trực tiếp
- **Hình ảnh**: Thumbnail sách hoặc placeholder
- **Đánh giá**: Hiển thị sao và điểm số

### 4. Thêm Sách Mới (ThemSach.php)

#### Form Nhập Liệu
- **Thông tin bắt buộc**:
  - Tựa sách
  - Nội dung tóm tắt
  - Tác giả
  - Thể loại (dropdown từ DB)
  - Nhà xuất bản (dropdown từ DB)
  - Năm xuất bản
  - Giá tiền
- **Thông tin tùy chọn**:
  - Hình bìa sách
  - Đánh giá (0-5 sao)

#### Xử Lý Upload
- **File validation**: Kiểm tra định dạng (JPG, PNG, GIF)
- **Size limit**: Tối đa 5MB
- **Unique naming**: Tránh trùng lặp file
- **Error handling**: Thông báo lỗi chi tiết
- **Success feedback**: Xác nhận thêm thành công

### 5. Cập Nhật Sách (CapNhatSach.php)

#### Hiển Thị Thông Tin
- **Chi tiết sách**: Bảng thông tin đầy đủ
- **Hình ảnh**: Hiển thị ảnh bìa hiện tại
- **Đánh giá**: Sao và điểm số
- **Metadata**: Thể loại, nhà xuất bản

#### Form Cập Nhật
- **Pre-filled data**: Dữ liệu hiện tại
- **Selective update**: Chỉ cập nhật field thay đổi
- **Image replacement**: Thay đổi ảnh tùy chọn
- **Validation**: Kiểm tra dữ liệu trước khi lưu
- **Confirmation**: Xác nhận cập nhật thành công

### 6. Xóa Sách (xlXoa.php)

#### Quy Trình Xóa
- **Confirmation**: JavaScript confirm dialog
- **Info display**: Hiển thị thông tin sách bị xóa
- **File cleanup**: Xóa file ảnh liên quan
- **Database cleanup**: Xóa record khỏi DB
- **Success/Error handling**: Thông báo kết quả

#### An Toàn Dữ Liệu
- **Soft delete**: Có thể mở rộng thành soft delete
- **Backup warning**: Cảnh báo về tính không thể hoàn tác
- **Quick actions**: Liên kết đến các tác vụ khác

## Tính Năng Kỹ Thuật

### 1. DataProvider Class
```php
// Kết nối database với MySQLi
// Prepared statements cho bảo mật
// Error handling toàn diện
// UTF-8 support cho tiếng Việt
```

### 2. Material Design CSS
```css
// Component-based styling
// Responsive grid system
// Material color palette
// Typography scale
// Animation và transitions
```

### 3. JavaScript Enhancements
```javascript
// Form validation
// File upload preview
// Interactive elements
// Responsive behavior
// User experience improvements
```

### 4. Security Features
- **SQL Injection Prevention**: Prepared statements
- **XSS Protection**: HTML escaping
- **File Upload Security**: Type và size validation
- **Session Management**: Secure session handling
- **Input Validation**: Client và server-side

### 5. Performance Optimizations
- **Database Indexing**: Indexes trên các field tìm kiếm
- **Image Optimization**: Resize và compression
- **Caching**: Browser caching cho static assets
- **Pagination**: Giảm load database
- **Lazy Loading**: Tải ảnh theo nhu cầu

## Hướng Dẫn Sử Dụng

### Dành Cho Người Dùng

#### Tìm Kiếm Sách
1. Truy cập menu "Tìm Sách"
2. Nhập từ khóa tìm kiếm
3. Sử dụng bộ lọc nâng cao nếu cần
4. Click "Tìm Sách" để xem kết quả
5. Sử dụng phân trang để duyệt nhiều kết quả

#### Xem Thông Tin Sách
1. Click vào tên sách trong kết quả tìm kiếm
2. Xem thông tin chi tiết
3. Xem hình ảnh và đánh giá
4. Quay lại danh sách hoặc tìm kiếm mới

### Dành Cho Quản Trị Viên

#### Thêm Sách Mới
1. Đăng nhập với tài khoản admin
2. Truy cập "Thêm Sách"
3. Điền đầy đủ thông tin bắt buộc
4. Upload hình ảnh (tùy chọn)
5. Click "Thêm Mới" để lưu

#### Cập Nhật Thông Tin Sách
1. Tìm sách cần cập nhật
2. Click vào tên sách hoặc icon "Sửa"
3. Thay đổi thông tin cần thiết
4. Upload hình ảnh mới (nếu có)
5. Click "Cập Nhật" để lưu

#### Xóa Sách
1. Tìm sách cần xóa
2. Click icon "Xóa" hoặc button trong trang chi tiết
3. Xác nhận trong dialog
4. Kiểm tra thông báo kết quả

## Khắc Phục Sự Cố

### Lỗi Kết Nối Database
```
Triệu chứng: "Connection failed" hoặc không load được dữ liệu
Giải pháp:
1. Kiểm tra MySQL service đang chạy
2. Xác nhận thông tin kết nối trong DataProvider.php
3. Kiểm tra database ebookDB đã được tạo
4. Verify user permissions
```

### Lỗi Upload File
```
Triệu chứng: Không upload được hình ảnh
Giải pháp:
1. Kiểm tra thư mục upload/ có quyền ghi
2. Verify file size < 5MB
3. Kiểm tra định dạng file (JPG, PNG, GIF)
4. Xem PHP upload settings (upload_max_filesize)
```

### Lỗi Hiển Thị Tiếng Việt
```
Triệu chứng: Ký tự tiếng Việt bị lỗi
Giải pháp:
1. Đảm bảo database charset = utf8
2. Kiểm tra file PHP được lưu với encoding UTF-8
3. Verify browser encoding settings
4. Check MySQL connection charset
```

### Lỗi Responsive Design
```
Triệu chứng: Giao diện không responsive trên mobile
Giải pháp:
1. Kiểm tra viewport meta tag
2. Verify CSS media queries
3. Test trên different screen sizes
4. Check Material Design CSS loading
```

## Mở Rộng và Tùy Chỉnh

### Thêm Tính Năng Mới

#### 1. Hệ Thống Bình Luận
```php
// Tạo bảng Comments
// Liên kết với Book và User
// Form submit comments
// Display comments với pagination
```

#### 2. Giỏ Hàng và Thanh Toán
```php
// Tạo bảng Cart, Orders
// Session-based cart
// Payment integration
// Order management
```

#### 3. Báo Cáo và Thống Kê
```php
// Chart.js integration
// Sales reports
// Popular books analytics
// User activity tracking
```

### Tùy Chỉnh Giao Diện

#### 1. Color Theme
```css
/* Thay đổi màu chủ đạo trong material-design.css */
:root {
  --primary-color: #3f51b5;
  --accent-color: #ff4081;
  --background-color: #f5f5f5;
}
```

#### 2. Typography
```css
/* Custom fonts */
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
```

#### 3. Layout Modifications
```css
/* Responsive breakpoints */
/* Component spacing */
/* Animation timing */
```

## Bảo Trì và Cập Nhật

### Backup Định Kỳ
```bash
# Database backup
mysqldump -u root -p ebookDB > backup_$(date +%Y%m%d).sql

# File backup
tar -czf files_backup_$(date +%Y%m%d).tar.gz upload/ BookImages/
```

### Monitoring và Logs
```php
// Error logging
// Performance monitoring
// User activity logs
// Security audit trails
```

### Updates và Patches
```
1. Backup trước khi update
2. Test trên environment riêng
3. Update dependencies
4. Verify functionality
5. Deploy to production
```

## Đóng Góp và Phát Triển

### Coding Standards
- **PHP**: PSR-12 coding standard
- **HTML**: Semantic markup
- **CSS**: BEM methodology
- **JavaScript**: ES6+ features
- **Database**: Normalized design

### Git Workflow
```bash
# Feature development
git checkout -b feature/new-feature
git commit -m "Add: new feature description"
git push origin feature/new-feature

# Bug fixes
git checkout -b hotfix/bug-description
git commit -m "Fix: bug description"
git push origin hotfix/bug-description
```

### Testing
```
- Unit testing cho PHP functions
- Integration testing cho database
- UI testing cho responsive design
- Performance testing cho load times
- Security testing cho vulnerabilities
```

## Liên Hệ và Hỗ Trợ

### Thông Tin Dự Án
- **Tên**: BookStore Online
- **Phiên bản**: 1.0.0
- **Ngôn ngữ**: PHP, HTML, CSS, JavaScript
- **Database**: MySQL
- **Framework**: Material Design Lite

### Hỗ Trợ Kỹ Thuật
- **Documentation**: README.md (file này)
- **Code Comments**: Inline documentation
- **Error Messages**: User-friendly messages
- **Debug Mode**: Chi tiết trong development

---

## Kết Luận

BookStore Online là một hệ thống quản lý sách hoàn chỉnh với giao diện hiện đại và tính năng phong phú. Được thiết kế để dễ sử dụng, bảo trì và mở rộng, hệ thống phù hợp cho cả môi trường học tập và ứng dụng thực tế.

Với kiến trúc modular và mã nguồn được tài liệu hóa tốt, dự án có thể được tùy chỉnh và phát triển thêm theo nhu cầu cụ thể của từng tổ chức.

**Chúc bạn sử dụng hệ thống hiệu quả và thành công!** 📚✨

---

## Lịch Sử Cập Nhật

### Version 1.0.1 - Ngày 22/09/2025

#### 🐛 Khắc Phục Lỗi

**Sửa lỗi thông báo "Không thể thêm sách vui lòng thử lại!" trong ThemSach.php**

- **Vấn đề**: Mặc dù dữ liệu được thêm thành công vào cơ sở dữ liệu, trang vẫn hiển thị thông báo lỗi
- **Nguyên nhân**: Phương thức `ExecutePreparedQuery()` trong `DataProvider.php` trả về `false` cho các câu lệnh INSERT do sử dụng `get_result()` không phù hợp
- **Giải pháp**: 
  - Cải thiện phương thức `ExecutePreparedQuery()` để phân biệt giữa câu lệnh SELECT và INSERT/UPDATE/DELETE
  - Trả về kết quả phù hợp cho từng loại câu lệnh
  - Thêm tham số `$returnInsertId` để lấy ID của bản ghi vừa được thêm
  - Tối ưu hóa `ThemSach.php` để sử dụng một kết nối duy nhất cho việc thêm và lấy ID

#### 📝 Thay Đổi Kỹ Thuật

**DataProvider.php**:
```php
// Thêm logic phân biệt loại câu lệnh SQL
$queryType = strtoupper(trim(explode(' ', $sql)[0]));

if ($queryType === 'SELECT') {
    // Trả về result set cho SELECT
    $result = $stmt->get_result();
} elseif ($queryType === 'INSERT' && $returnInsertId && $executeResult) {
    // Trả về insert ID cho INSERT
    $result = $connection->insert_id;
} else {
    // Trả về trạng thái thực thi cho UPDATE/DELETE
    $result = $executeResult;
}
```

**ThemSach.php**:
```php
// Sử dụng phương thức cải tiến để lấy insert ID
$maSach = DataProvider::ExecutePreparedQuery($sql, $params, $types, true);
```

#### ✅ Kết Quả

- ✅ Thêm sách thành công hiển thị đúng thông báo "Đã thêm sách thành công với mã số: X"
- ✅ Không còn hiển thị thông báo lỗi sai
- ✅ Tối ưu hiệu suất bằng cách giảm số lượng kết nối database
- ✅ Cải thiện trải nghiệm người dùng

#### 🔧 Tương Thích

- Thay đổi này không ảnh hưởng đến các chức năng khác
- Tương thích ngược với các phiên bản trước
- Không cần thay đổi cấu trúc database