-- BookStore Online Database Setup
-- MySQL Database Schema for ebookDB

-- Create database
CREATE DATABASE IF NOT EXISTS ebookDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE ebookDB;

-- Create Category table
CREATE TABLE IF NOT EXISTS Category (
    CategoryID int(11) NOT NULL AUTO_INCREMENT,
    CategoryName varchar(100) COLLATE utf8_bin NOT NULL,
    CategoryDesc varchar(1024) COLLATE utf8_bin NOT NULL,
    PRIMARY KEY (CategoryID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Create Publisher table
CREATE TABLE IF NOT EXISTS Publisher (
    PublisherID int(11) NOT NULL AUTO_INCREMENT,
    PublisherName varchar(256) COLLATE utf8_bin NOT NULL,
    PublisherAddress varchar(256) COLLATE utf8_bin NOT NULL,
    PRIMARY KEY (PublisherID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Create Book table
CREATE TABLE IF NOT EXISTS Book (
    BookID int(11) NOT NULL AUTO_INCREMENT,
    BookTitle varchar(256) COLLATE utf8_unicode_ci NOT NULL,
    BookDesc varchar(1024) COLLATE utf8_bin NOT NULL,
    BookCatID int(11) NOT NULL,
    BookAuthor varchar(256) COLLATE utf8_bin NOT NULL,
    BookPubID int(11) NOT NULL,
    BookYear int(11) NOT NULL,
    BookPic varchar(256) COLLATE utf8_bin NOT NULL,
    BookPrice float NOT NULL,
    BookRate float NOT NULL DEFAULT 0,
    PRIMARY KEY (BookID),
    KEY FK_Book_Category (BookCatID),
    KEY FK_Book_Publisher (BookPubID),
    FOREIGN KEY (BookCatID) REFERENCES Category (CategoryID) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (BookPubID) REFERENCES Publisher (PublisherID) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Create User table for authentication
CREATE TABLE IF NOT EXISTS User (
    UserID int(11) NOT NULL AUTO_INCREMENT,
    UserName varchar(50) COLLATE utf8_bin NOT NULL,
    Password varchar(255) COLLATE utf8_bin NOT NULL,
    FullName varchar(100) COLLATE utf8_bin NOT NULL,
    Email varchar(100) COLLATE utf8_bin NOT NULL,
    Role varchar(20) COLLATE utf8_bin DEFAULT 'user',
    CreatedDate datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (UserID),
    UNIQUE KEY UK_User_UserName (UserName),
    UNIQUE KEY UK_User_Email (Email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- Insert sample data for Category
INSERT INTO Category (CategoryName, CategoryDesc) VALUES
('Văn học', 'Sách văn học trong nước và nước ngoài'),
('Khoa học kỹ thuật', 'Sách về khoa học, công nghệ, kỹ thuật'),
('Kinh tế', 'Sách về kinh tế, quản trị, kinh doanh'),
('Giáo dục', 'Sách giáo khoa, sách tham khảo học tập'),
('Thiếu nhi', 'Sách dành cho trẻ em và thiếu niên'),
('Tâm lý - Kỹ năng sống', 'Sách về tâm lý học và kỹ năng sống'),
('Lịch sử', 'Sách về lịch sử Việt Nam và thế giới'),
('Triết học', 'Sách về triết học và tư tưởng');

-- Insert sample data for Publisher
INSERT INTO Publisher (PublisherName, PublisherAddress) VALUES
('NXB Trẻ', 'Thành phố Hồ Chí Minh'),
('NXB Kim Đồng', 'Hà Nội'),
('NXB Giáo dục Việt Nam', 'Hà Nội'),
('NXB Thế giới', 'Hà Nội'),
('NXB Văn học', 'Hà Nội'),
('NXB Lao động', 'Hà Nội'),
('NXB Khoa học và Kỹ thuật', 'Hà Nội'),
('NXB Tổng hợp TP.HCM', 'Thành phố Hồ Chí Minh');

-- Insert sample data for Books
INSERT INTO Book (BookTitle, BookDesc, BookCatID, BookAuthor, BookPubID, BookYear, BookPic, BookPrice, BookRate) VALUES
('Đắc Nhân Tâm', 'Cuốn sách kinh điển về nghệ thuật giao tiếp và ứng xử', 6, 'Dale Carnegie', 1, 2018, 'upload/dac_nhan_tam.jpg', 89000, 4.8),
('Tôi Thấy Hoa Vàng Trên Cỏ Xanh', 'Tiểu thuyết nổi tiếng về tuổi thơ miền quê', 1, 'Nguyễn Nhật Ánh', 1, 2017, 'upload/hoa_vang_co_xanh.jpg', 78000, 4.9),
('Sapiens: Lược Sử Loài Người', 'Cuốn sách về lịch sử phát triển của loài người', 7, 'Yuval Noah Harari', 4, 2019, 'upload/sapiens.jpg', 169000, 4.7),
('Nhà Giả Kim', 'Tiểu thuyết triết lý về hành trình tìm kiếm ước mơ', 1, 'Paulo Coelho', 4, 2020, 'upload/nha_gia_kim.jpg', 79000, 4.6),
('Clean Code', 'Sách hướng dẫn viết code sạch cho lập trình viên', 2, 'Robert C. Martin', 7, 2021, 'upload/clean_code.jpg', 320000, 4.8),
('Thinking, Fast and Slow', 'Sách về tâm lý học nhận thức và ra quyết định', 6, 'Daniel Kahneman', 4, 2020, 'upload/thinking_fast_slow.jpg', 189000, 4.5),
('Lược Sử Thời Gian', 'Cuốn sách khoa học về vũ trụ và thời gian', 2, 'Stephen Hawking', 7, 2018, 'upload/luoc_su_thoi_gian.jpg', 149000, 4.7),
('Cây Cam Ngọt Của Tôi', 'Tiểu thuyết cảm động về tuổi thơ', 1, 'José Mauro de Vasconcelos', 4, 2019, 'upload/cay_cam_ngot.jpg', 89000, 4.8),
('The Lean Startup', 'Sách về khởi nghiệp và quản trị doanh nghiệp', 3, 'Eric Ries', 6, 2021, 'upload/lean_startup.jpg', 199000, 4.4),
('Dế Mèn Phiêu Lưu Ký', 'Truyện thiếu nhi kinh điển Việt Nam', 5, 'Tô Hoài', 2, 2020, 'upload/de_men_phieu_luu_ky.jpg', 45000, 4.9);

-- Insert sample admin user (password: admin123)
INSERT INTO User (UserName, Password, FullName, Email, Role) VALUES
('admin', 'admin123', 'Quản trị viên', 'admin@bookstore.com', 'admin'),
('user1', 'user123', 'Nguyễn Văn A', 'user1@example.com', 'user'),
('user2', 'user123', 'Trần Thị B', 'user2@example.com', 'user');

-- Create indexes for better performance
CREATE INDEX idx_book_title ON Book(BookTitle);
CREATE INDEX idx_book_author ON Book(BookAuthor);
CREATE INDEX idx_book_year ON Book(BookYear);
CREATE INDEX idx_book_price ON Book(BookPrice);

-- Show table information
SHOW TABLES;
DESCRIBE Book;
DESCRIBE Category;
DESCRIBE Publisher;
DESCRIBE User;
