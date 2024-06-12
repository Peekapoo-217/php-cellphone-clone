-- Tạo cơ sở dữ liệu CellPhone_K
CREATE DATABASE IF NOT EXISTS CellPhone_K;
USE CellPhone_K;

-- Tạo bảng Thông tin admin
CREATE TABLE Admin_inf(
    MaAdmin INT AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(50) UNIQUE,
    MatKhau NVARCHAR(50), -- Thêm trường mật khẩu
    HoTen NVARCHAR(255),
    Email NVARCHAR(100)
);

-- Tạo bảng Khách hàng
CREATE TABLE KhachHang (
	MaKhachHang INT AUTO_INCREMENT,
   TenDangNhap VARCHAR(50) UNIQUE not null,
    MatKhau NVARCHAR(50),
    HoTen NVARCHAR(255),
    GioiTinh NVARCHAR(10),
    SoDienThoai VARCHAR(15) not null,
    DiaChi NVARCHAR(255),
    Email NVARCHAR(100) not null,
    NgaySinh DATE,
    TongTienThanhToan DECIMAL(18, 2),
    HangThanhVien VARCHAR(50),
    PRIMARY KEY(MaKhachHang, TenDangNhap)
);

-- Tạo bảng Sản phẩm
CREATE TABLE SanPham (
    MaSanPham INT AUTO_INCREMENT PRIMARY KEY,
    TenSanPham NVARCHAR(255),
    Hang NVARCHAR(100),
    NgayNhap DATE
);

-- Tạo bảng Chi tiết sản phẩm
CREATE TABLE ChiTietSanPham (
    MaSanPham INT PRIMARY KEY,
    KichThuocManHinh NVARCHAR(100),
    CongNgheManHinh NVARCHAR(100),
    DoPhanGiaiManHinh NVARCHAR(100),
    TinhNangManHinh NVARCHAR(255),
    CameraSau NVARCHAR(100),
    QuayVideoSau NVARCHAR(100),
    CameraTruoc NVARCHAR(100),
    QuayVideoTruoc NVARCHAR(100),
    ChipSet NVARCHAR(100),
    Pin VARCHAR(100),
    CongNgheSac VARCHAR(100),
    TheSim VARCHAR(100),
    HeDieuHanh VARCHAR(100),
    HoTroMang NVARCHAR(100),
    Wifi VARCHAR(100),
    Bluetooth VARCHAR(100),
    Gps VARCHAR(100),
    KhangNuocBui VARCHAR(100),
    CongNgheAmThanh NVARCHAR(100),
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham)
);

-- Tạo bảng màu sắc của điện thoại
CREATE TABLE Colors (
    MaMau INT PRIMARY KEY,
    MaSanPham INT,
    TenMau NVARCHAR(30) ,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham)    
);
	
-- Tạo bảng Tùy chọn Ram và bộ nhớ trong
CREATE TABLE RAM_ROM_Option (
    MaRam INT  PRIMARY KEY,
    MaSanPham INT,
    KichThuoc VARCHAR(30),
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham)
);

-- Tạo bảng danh sách hình ảnh
CREATE TABLE Image (
    MaHinhAnh INT AUTO_INCREMENT PRIMARY KEY,
    MaSanPham INT,
    MaMau INT,
    DiaChiAnh TEXT,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham),
    FOREIGN KEY (MaMau) REFERENCES Colors(MaMau)
);

-- Tạo bảng danh sách video
CREATE TABLE Video (
    MaVideo INT AUTO_INCREMENT PRIMARY KEY,
    MaSanPham INT,
    DiaChiVideo TEXT,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham)
);

-- Tạo lại bảng GiaSanPham với MaMau
CREATE TABLE GiaSanPham (
    MaGia INT AUTO_INCREMENT PRIMARY KEY,
    MaRam INT,
    MaSanPham INT,
    MaMau INT,
    GiaCu INT,  -- Giá sản phẩm chưa giảm giá
    GiaMoi INT, -- Giá sản phẩm giảm giá
    SoLuong INT,
    FOREIGN KEY (MaRam) REFERENCES RAM_ROM_Option(MaRam),
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham),
    FOREIGN KEY (MaMau) REFERENCES Colors(MaMau)
);
 
-- Tạo bảng Hóa đơn
CREATE TABLE HoaDon (
    MaHoaDon INT AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(50),
    NgayLap DATE,
    TongTien DECIMAL(18, 2),
    TrangThai NVARCHAR(50),
    FOREIGN KEY (TenDangNhap) REFERENCES KhachHang(TenDangNhap) ON DELETE CASCADE
);

-- Tạo bảng Chi tiết hóa đơn
CREATE TABLE ChiTietHoaDon (
    MaCTHD INT AUTO_INCREMENT PRIMARY KEY,
    MaHoaDon INT,
    MaSanPham INT,
    TenMau NVARCHAR(30),
    KichThuoc VARCHAR(30),
    SoLuong INT,
    ThanhTien DECIMAL(18, 2),
    FOREIGN KEY (MaHoaDon) REFERENCES HoaDon(MaHoaDon) ON DELETE CASCADE,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham)
);


CREATE TABLE giohang (
    MaHang INT AUTO_INCREMENT PRIMARY KEY,
    TenDangNhap VARCHAR(50) not null,
    MaSanPham INT,
    DiaChiAnh TEXT,
    TenSanPham NVARCHAR(255),
    MauSac NVARCHAR(30),
    KichThuoc VARCHAR(30),
    GiaMoi INT,
    Soluong INT,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham),
    FOREIGN KEY (TenDangNhap) REFERENCES KhachHang(TenDangNhap) ON DELETE CASCADE
);

-- Tạo bảng phản hồi (Feedback)
CREATE TABLE Feedback (
    FeedbackID INT AUTO_INCREMENT PRIMARY KEY,
    MaKhachHang INT,
    MaHoaDon INT,
    HoTen NVARCHAR(255),
    MaSanPham INT,
    SoSao INT,
    BinhLuan TEXT,
    Ngay DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MaSanPham) REFERENCES SanPham(MaSanPham),
    FOREIGN KEY (MaHoaDon) REFERENCES HoaDon(MaHoaDon),
    FOREIGN KEY (MaKhachHang) REFERENCES KhachHang(MaKhachHang) ON DELETE CASCADE
);












-- Nhập dữ liệu 
INSERT INTO admin_inf(TenDangNhap,MatKhau,HoTen,Email)
VALUES ('admin','admin','ADMIN ĐẸP TRAI','admin@gmail.com')


 