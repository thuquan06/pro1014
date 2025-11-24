# Hệ thống Quản lý Hóa đơn/Booking

## 📋 Tổng quan

Hệ thống quản lý hóa đơn/booking cho phép quản lý đơn đặt tour du lịch, bao gồm thông tin khách hàng, số lượng người, ngày đi, và trạng thái đơn hàng.

## 🗂️ Cấu trúc File

### Models
- `models/HoadonModel.php` - Model xử lý dữ liệu hóa đơn

### Controllers  
- `controllers/HoadonController.php` - Controller xử lý logic nghiệp vụ

### Views
- `views/admin/hoadon/list.php` - Danh sách hóa đơn
- `views/admin/hoadon/detail.php` - Chi tiết hóa đơn
- `views/admin/hoadon/create.php` - Tạo hóa đơn mới
- `views/admin/hoadon/edit.php` - Chỉnh sửa hóa đơn

### Database
- `database_hoadon.sql` - File SQL tạo bảng và dữ liệu mẫu

## 🗄️ Cấu trúc Database

### Bảng: `hoadon`

| Trường | Kiểu | Mô tả |
|--------|------|-------|
| id_hoadon | int(11) PK | ID hóa đơn (auto increment) |
| id_goi | int(11) | ID gói tour (foreign key) |
| id_ks | int(11) | ID khách sạn (tùy chọn) |
| email_nguoidung | varchar(100) | Email người đặt |
| nguoilon | int(11) | Số người lớn |
| treem | int(11) | Số trẻ em (6-11 tuổi) |
| trenho | int(11) | Số trẻ nhỏ (2-5 tuổi) |
| embe | int(11) | Số em bé (< 2 tuổi) |
| phongdon | int(3) | Số phòng đơn |
| ngayvao | date | Ngày check-in |
| ngayra | date | Ngày check-out |
| sophong | int(3) | Tổng số phòng |
| ghichu | varchar(100) | Ghi chú đặc biệt |
| huy | varchar(100) | Trạng thái hủy (0/1) |
| ngaydat | timestamp | Ngày đặt hóa đơn |
| ngaycapnhat | timestamp | Ngày cập nhật |
| trangthai | int(11) | Trạng thái đơn hàng |

### Trạng thái (trangthai)
- `0` - Chờ xác nhận
- `1` - Đã xác nhận
- `2` - Hoàn thành
- `3` - Đã hủy

## 🚀 Cài đặt

### Bước 1: Import Database

```bash
# Import file SQL vào database
mysql -u root -p starvel < database_hoadon.sql
```

Hoặc import qua phpMyAdmin:
1. Mở phpMyAdmin
2. Chọn database `starvel`
3. Vào tab Import
4. Chọn file `database_hoadon.sql`
5. Click Go

### Bước 2: Kiểm tra kết nối

Đảm bảo file `commons/env.php` có cấu hình đúng:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', 8889);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'starvel');
```

### Bước 3: Truy cập hệ thống

Các URL để truy cập:

- **Danh sách hóa đơn**: `?act=hoadon-list`
- **Tạo hóa đơn mới**: `?act=hoadon-create`
- **Chi tiết hóa đơn**: `?act=hoadon-detail&id={id}`
- **Chỉnh sửa hóa đơn**: `?act=hoadon-edit&id={id}`

## 📚 Hướng dẫn sử dụng

### 1. Xem danh sách hóa đơn

- Truy cập: `?act=hoadon-list`
- Hiển thị tất cả hóa đơn với thống kê tổng quan
- Có thể lọc theo trạng thái
- Tìm kiếm theo email khách hàng

### 2. Tạo hóa đơn mới

- Truy cập: `?act=hoadon-create`
- Điền đầy đủ thông tin:
  - Email khách hàng (bắt buộc)
  - Chọn tour (bắt buộc)
  - Số lượng người (người lớn, trẻ em, trẻ nhỏ, em bé)
  - Ngày vào/ra
  - Số phòng
  - Ghi chú (tùy chọn)
  - Trạng thái
- Hệ thống tự động tính tổng tiền dự kiến

### 3. Xem chi tiết hóa đơn

- Truy cập: `?act=hoadon-detail&id={id}`
- Hiển thị đầy đủ thông tin hóa đơn:
  - Thông tin khách hàng
  - Thông tin tour
  - Chi tiết số người và giá
  - Ghi chú (nếu có)
  - Tổng tiền

### 4. Chỉnh sửa hóa đơn

- Truy cập: `?act=hoadon-edit&id={id}`
- Có thể sửa tất cả thông tin
- Không thể sửa hóa đơn đã bị hủy

### 5. Cập nhật trạng thái

Từ danh sách hóa đơn:
- Click nút refresh (🔄)
- Nhập trạng thái mới (0, 1, hoặc 2)
- Hệ thống cập nhật qua AJAX

### 6. Hủy hóa đơn

- Từ trang chi tiết, click nút "Hủy hóa đơn"
- Xác nhận hủy
- Hóa đơn sẽ được đánh dấu là đã hủy

### 7. Xóa hóa đơn

- Từ danh sách, click nút xóa (🗑️)
- Xác nhận xóa
- Hóa đơn sẽ bị xóa vĩnh viễn

## 🔧 Các chức năng chính

### HoadonModel

```php
// Lấy tất cả hóa đơn
$hoadons = $hoadonModel->getAllHoadon();

// Lấy hóa đơn theo ID
$hoadon = $hoadonModel->getHoadonById($id);

// Lấy hóa đơn theo email
$hoadons = $hoadonModel->getHoadonByEmail($email);

// Lấy hóa đơn theo trạng thái
$hoadons = $hoadonModel->getHoadonByStatus($trangthai);

// Tạo hóa đơn mới
$id = $hoadonModel->createHoadon($data);

// Cập nhật hóa đơn
$result = $hoadonModel->updateHoadon($id, $data);

// Cập nhật trạng thái
$result = $hoadonModel->updateStatus($id, $trangthai);

// Hủy hóa đơn
$result = $hoadonModel->cancelHoadon($id);

// Xóa hóa đơn
$result = $hoadonModel->deleteHoadon($id);

// Tính tổng tiền
$total = $hoadonModel->calculateTotal($id_hoadon);

// Thống kê
$stats = $hoadonModel->getStatistics();
```

### HoadonController

- `list()` - Hiển thị danh sách hóa đơn
- `detail()` - Hiển thị chi tiết hóa đơn
- `create()` - Tạo hóa đơn mới
- `edit()` - Chỉnh sửa hóa đơn
- `updateStatus()` - Cập nhật trạng thái (AJAX)
- `cancel()` - Hủy hóa đơn
- `delete()` - Xóa hóa đơn
- `filterByStatus()` - Lọc theo trạng thái
- `searchByEmail()` - Tìm kiếm theo email

## 🎨 Giao diện

### Danh sách hóa đơn
- Bảng hiển thị tất cả hóa đơn
- Thống kê nhanh ở đầu trang
- Dropdown lọc theo trạng thái
- Nút tạo hóa đơn mới
- Các nút hành động: Xem, Sửa, Cập nhật, Xóa

### Chi tiết hóa đơn
- Panel thông tin khách hàng
- Panel thông tin tour
- Bảng chi tiết giá theo loại khách
- Hiển thị tổng tiền
- Panel ghi chú (nếu có)
- Các nút: Quay lại, Chỉnh sửa, Hủy hóa đơn

### Form tạo/sửa
- Form chia thành các panel rõ ràng
- Tự động tính tổng tiền khi chọn tour và nhập số người
- Validation form
- Bootstrap styling

## 🔐 Bảo mật

- Validate input trước khi lưu database
- Sử dụng prepared statements (PDO)
- XSS protection với `htmlentities()`
- CSRF protection (nên thêm token)

## 📈 Thống kê

Hệ thống cung cấp các thống kê:
- Tổng số hóa đơn
- Số hóa đơn chờ xác nhận
- Số hóa đơn đã xác nhận
- Số hóa đơn hoàn thành
- Số hóa đơn đã hủy

## 🐛 Xử lý lỗi

- Tất cả lỗi database được log vào error_log
- Hiển thị thông báo lỗi thân thiện cho user
- Try-catch trong tất cả methods quan trọng

## 🔄 Tích hợp

Hệ thống tích hợp với:
- **TourModel**: Lấy thông tin tour và giá
- **BaseController**: Sử dụng loadView() và redirect()
- **BaseModel**: Kế thừa kết nối database

## 📝 Ghi chú

- Trường `huy` là string để tương thích với cấu trúc database hiện tại
- Em bé (< 2 tuổi) thường miễn phí
- Giá được lấy từ bảng `goidulich`
- Thời gian sử dụng timezone mặc định của server

## 🚦 Trạng thái phát triển

✅ Hoàn thành:
- Model với đầy đủ CRUD operations
- Controller với tất cả các actions
- Views: list, detail, create, edit
- Routing trong index.php
- Database migration
- Tính năng thống kê
- Lọc và tìm kiếm

🔜 Có thể mở rộng:
- Export hóa đơn ra PDF
- Gửi email xác nhận cho khách
- Thanh toán online
- Lịch sử thay đổi trạng thái
- Báo cáo doanh thu
- API cho mobile app

## 👤 Tác giả

Phát triển bởi Cursor AI Assistant
Created: 2025-11-24

## 📞 Hỗ trợ

Nếu có vấn đề, vui lòng:
1. Kiểm tra log errors
2. Xem lại cấu hình database
3. Đảm bảo tất cả files đã được tạo đúng vị trí
4. Import SQL file đầy đủ
