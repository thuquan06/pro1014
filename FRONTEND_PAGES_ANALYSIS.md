# PHÂN TÍCH CÁC TRANG DÀNH CHO NGƯỜI DÙNG (FRONTEND)

## TỔNG QUAN
Dựa trên phân tích các chức năng và models trong project, đây là danh sách các trang mà người dùng cuối (khách hàng) sẽ cần để sử dụng website du lịch.

---

## 1. TRANG CHỦ (HOME PAGE)
**Route:** `?act=home`
**File:** `views/trangchu.php`
**Controller:** `ProductController::Home()`

### Chi tiết trang chủ:
- **Hero Section**: Banner giới thiệu, ảnh đẹp về du lịch
- **Tour Nổi bật**: Hiển thị các tour hot, tour đang khuyến mãi
- **Tour theo danh mục**: Tour trong nước, tour quốc tế
- **Tour theo địa điểm**: Tour theo tỉnh/thành phố
- **Thống kê nhanh**: Số lượng tour, số khách hàng, đánh giá
- **Tin tức/Blog**: Bài viết mới nhất về du lịch
- **Đăng ký nhận tin**: Form đăng ký email để nhận thông tin tour mới

---

## 2. DANH SÁCH TOUR (TOUR LISTING)
**Route đề xuất:** `?act=tours` hoặc `?act=tour-list`
**Controller đề xuất:** `TourController::listTours()`

### Chi tiết trang danh sách tour:
- **Bộ lọc tour**:
  - Lọc theo loại: Trong nước / Quốc tế
  - Lọc theo tỉnh/thành phố (từ bảng `tinhthanh`)
  - Lọc theo giá: Dưới 5 triệu, 5-10 triệu, 10-20 triệu, Trên 20 triệu
  - Lọc theo số ngày: 1-2 ngày, 3-5 ngày, 6-10 ngày, Trên 10 ngày
  - Lọc theo khuyến mãi: Có khuyến mãi / Không khuyến mãi
  - Sắp xếp: Mới nhất, Giá thấp → cao, Giá cao → thấp, Phổ biến nhất

- **Danh sách tour**:
  - Hiển thị dạng grid/list
  - Mỗi tour card hiển thị:
    - Hình ảnh tour (từ `goidulich.hinhanh`)
    - Tên tour (`tengoi`)
    - Mã tour (`mato`)
    - Tuyến điểm (`tuyendiem`)
    - Địa điểm (`vitri`, `ten_tinh`)
    - Số ngày (`songay`)
    - Giá tour (`giagoi`, `giatreem`, `giatrenho`)
    - Giá khuyến mãi (nếu có `khuyenmai = 1`)
    - Ngày khởi hành gần nhất (từ bảng `lich_khoi_hanh`)
    - Trạng thái: Mở bán / Hết chỗ / Đóng

- **Phân trang**: Hiển thị 12-20 tour mỗi trang

---

## 3. CHI TIẾT TOUR (TOUR DETAIL)
**Route đề xuất:** `?act=tour-detail&id=X`
**Controller đề xuất:** `TourController::detail()`

### Chi tiết trang chi tiết tour:
- **Thông tin cơ bản**:
  - Tên tour (`tengoi`)
  - Mã tour (`mato`)
  - Tuyến điểm (`tuyendiem`)
  - Địa điểm xuất phát (`noixuatphat`)
  - Vị trí địa điểm (`vitri`, `ten_tinh`)
  - Quốc gia (`quocgia`)
  - Số ngày (`songay`)
  - Phương tiện (`phuongtien`)
  - Số chỗ (`socho`)

- **Gallery ảnh**: 
  - Ảnh đại diện chính
  - Thư viện ảnh tour (từ bảng gallery)

- **Giá tour**:
  - Giá người lớn (`giagoi`)
  - Giá trẻ em (`giatreem`)
  - Giá trẻ nhỏ (`giatrenho`)
  - Khuyến mãi (nếu có)

- **Lịch trình tour**:
  - Hiển thị chi tiết từng ngày (từ bảng `lichtrinhtheoday`)
  - Mỗi ngày hiển thị:
    - Ngày thứ mấy
    - Tiêu đề
    - Mô tả chi tiết
    - Điểm đến
    - Thời lượng
    - Hoạt động
    - Bữa ăn
    - Nơi nghỉ
    - Ghi chú HDV

- **Lịch khởi hành**:
  - Bảng hiển thị các ngày khởi hành (từ `lich_khoi_hanh`)
  - Thông tin mỗi lịch:
    - Ngày khởi hành (`ngay_khoi_hanh`)
    - Giờ khởi hành (`gio_khoi_hanh`)
    - Giờ tập trung (`gio_tap_trung`)
    - Điểm tập trung (`diem_tap_trung`)
    - Số chỗ còn trống (`so_cho_con_trong`)
    - Trạng thái (Mở bán / Đóng / Hết chỗ)
    - Nút "Đặt tour" cho từng lịch

- **Chính sách tour**:
  - Chính sách hủy
  - Chính sách đổi ngày
  - Chính sách hoàn tiền
  - Điều kiện đặt tour

- **Lưu ý**:
  - Các lưu ý quan trọng (`luuy`)

- **Tour liên quan**: Gợi ý các tour tương tự

- **Nút hành động**:
  - "Đặt tour ngay"
  - "Gọi tư vấn"
  - "Chat với tư vấn viên"

---

## 4. ĐẶT TOUR / BOOKING (BOOKING PAGE)
**Route đề xuất:** `?act=booking&departure_id=X`
**Controller đề xuất:** `BookingController::create()`

### Chi tiết trang đặt tour:
- **Thông tin lịch khởi hành đã chọn**:
  - Tour đã chọn
  - Ngày khởi hành
  - Giờ khởi hành
  - Giờ tập trung
  - Điểm tập trung
  - Số chỗ còn trống

- **Form thông tin khách hàng**:
  - Họ tên (*)
  - Email (*)
  - Số điện thoại (*)
  - Địa chỉ
  - Ngày sinh
  - Ghi chú thêm (dị ứng, yêu cầu đặc biệt...)

- **Thông tin người đi cùng**:
  - Số lượng người lớn
  - Số lượng trẻ em (và độ tuổi)
  - Số lượng trẻ nhỏ (và độ tuổi)
  - Form điền thông tin từng người (nếu cần)

- **Tính giá**:
  - Giá người lớn × số lượng
  - Giá trẻ em × số lượng
  - Giá trẻ nhỏ × số lượng
  - Phụ phí (nếu có)
  - Tổng tiền

- **Phương thức thanh toán**:
  - Thanh toán online (nếu có)
  - Chuyển khoản ngân hàng
  - Thanh toán tại văn phòng
  - Thanh toán khi lên xe

- **Xác nhận điều khoản**:
  - Checkbox đồng ý với điều khoản
  - Checkbox đồng ý nhận email marketing

- **Nút xác nhận**: "Xác nhận đặt tour"

---

## 5. XÁC NHẬN ĐẶT TOUR (BOOKING CONFIRMATION)
**Route đề xuất:** `?act=booking-confirm&booking_id=X`
**Controller đề xuất:** `BookingController::confirm()`

### Chi tiết trang xác nhận:
- **Thông báo đặt tour thành công**
- **Mã đặt tour / Hóa đơn** (từ bảng `hoadon`)
- **Thông tin đơn hàng**:
  - Tour đã đặt
  - Lịch khởi hành
  - Thông tin khách hàng
  - Danh sách người đi
  - Tổng tiền
  - Trạng thái thanh toán

- **Hướng dẫn tiếp theo**:
  - Cách thanh toán (nếu chưa thanh toán)
  - Thời gian chờ xác nhận
  - Liên hệ hỗ trợ

- **Nút hành động**:
  - "In hóa đơn"
  - "Xem chi tiết đơn hàng"
  - "Về trang chủ"

---

## 6. TRA CỨU ĐƠN HÀNG (ORDER TRACKING)
**Route đề xuất:** `?act=order-tracking`
**Controller đề xuất:** `BookingController::trackOrder()`

### Chi tiết trang tra cứu:
- **Form tra cứu**:
  - Nhập mã đơn hàng / Mã hóa đơn
  - Hoặc nhập email + số điện thoại

- **Kết quả tra cứu** (từ bảng `hoadon`):
  - Mã đơn hàng
  - Tour đã đặt
  - Ngày khởi hành
  - Trạng thái đơn hàng (Chờ xác nhận / Đã xác nhận / Đã thanh toán / Đã hủy)
  - Thông tin thanh toán
  - Nút "Xem chi tiết"

---

## 7. CHI TIẾT ĐƠN HÀNG (ORDER DETAIL)
**Route đề xuất:** `?act=order-detail&id=X`
**Controller đề xuất:** `BookingController::orderDetail()`

### Chi tiết trang chi tiết đơn hàng:
- **Thông tin đơn hàng** (từ `hoadon`):
  - Mã hóa đơn
  - Ngày đặt
  - Trạng thái
  - Thông tin khách hàng

- **Thông tin tour**:
  - Tên tour
  - Lịch khởi hành
  - Danh sách người đi

- **Thông tin thanh toán**:
  - Tổng tiền
  - Phương thức thanh toán
  - Trạng thái thanh toán
  - Lịch sử thanh toán

- **Nút hành động** (tùy trạng thái):
  - "Hủy đơn hàng" (nếu chưa xác nhận)
  - "Thanh toán" (nếu chưa thanh toán)
  - "In hóa đơn"

---

## 8. DANH SÁCH TIN TỨC / BLOG (BLOG LISTING)
**Route đề xuất:** `?act=blog` hoặc `?act=news`
**Controller đề xuất:** `BlogController::listPublic()`

### Chi tiết trang blog:
- **Danh mục bài viết**: Lọc theo chủ đề
- **Danh sách bài viết** (từ bảng blog):
  - Ảnh đại diện
  - Tiêu đề
  - Tóm tắt
  - Người viết
  - Ngày đăng
  - Số lượt xem
  - Link "Đọc thêm"

- **Bài viết nổi bật**: Hiển thị ở sidebar
- **Phân trang**

---

## 9. CHI TIẾT TIN TỨC / BLOG (BLOG DETAIL)
**Route đề xuất:** `?act=blog-detail&id=X`
**Controller đề xuất:** `BlogController::detail()`

### Chi tiết trang bài viết:
- **Nội dung bài viết**:
  - Tiêu đề
  - Ảnh đại diện
  - Tóm tắt
  - Nội dung chi tiết
  - Người viết
  - Ngày đăng

- **Bài viết liên quan**
- **Form bình luận** (nếu có)

---

## 10. LIÊN HỆ (CONTACT PAGE)
**Route đề xuất:** `?act=contact`
**Controller đề xuất:** `ContactController::index()`

### Chi tiết trang liên hệ:
- **Thông tin liên hệ**:
  - Địa chỉ văn phòng
  - Số điện thoại
  - Email
  - Giờ làm việc
  - Bản đồ (Google Maps)

- **Form liên hệ**:
  - Họ tên
  - Email
  - Số điện thoại
  - Tiêu đề
  - Nội dung
  - Nút "Gửi liên hệ"

---

## 11. GIỚI THIỆU (ABOUT PAGE)
**Route đề xuất:** `?act=about`
**Controller đề xuất:** `PageController::about()`

### Chi tiết trang giới thiệu:
- **Giới thiệu công ty**
- **Lịch sử hình thành**
- **Đội ngũ nhân viên**
- **Tầm nhìn, sứ mệnh**
- **Thành tựu, giải thưởng**

---

## 12. TÌM KIẾM TOUR (TOUR SEARCH)
**Route đề xuất:** `?act=search&q=...`
**Controller đề xuất:** `TourController::search()`

### Chi tiết trang tìm kiếm:
- **Thanh tìm kiếm**:
  - Ô nhập từ khóa (tên tour, địa điểm)
  - Bộ lọc nâng cao
  - Nút "Tìm kiếm"

- **Kết quả tìm kiếm**:
  - Số lượng kết quả
  - Danh sách tour tìm được
  - Phân trang

---

## TRANG CẦN THIẾT ĐỂ HOÀN THIỆN FRONTEND

### ⚠️ LƯU Ý:
Hiện tại project mới có:
- ✅ Trang chủ cơ bản (`views/trangchu.php`)
- ✅ Hệ thống quản lý admin đầy đủ
- ✅ Hệ thống quản lý guide

### ❌ CHƯA CÓ (Cần phát triển):
1. Controller và View cho các trang tour listing
2. Controller và View cho tour detail
3. Controller và View cho booking system
4. Controller và View cho order tracking
5. Controller và View cho blog public
6. Controller và View cho contact, about pages
7. Controller và View cho search functionality

### 📝 KHUYẾN NGHỊ:
Để hoàn thiện frontend cho người dùng, cần:
1. Tạo `TourController` với các methods:
   - `listTours()` - Danh sách tour
   - `detail()` - Chi tiết tour
   - `search()` - Tìm kiếm tour

2. Tạo `BookingController` với các methods:
   - `create()` - Form đặt tour
   - `store()` - Lưu đơn hàng
   - `confirm()` - Xác nhận đặt tour
   - `trackOrder()` - Tra cứu đơn hàng
   - `orderDetail()` - Chi tiết đơn hàng

3. Tạo `BlogController` cho public blog (hiện có `BlogController` nhưng có thể chỉ dành cho admin)

4. Tạo `ContactController` và `PageController` cho các trang tĩnh

5. Tạo layout chung cho frontend (header, footer, navigation)

6. Tích hợp với hệ thống hóa đơn (`hoadon`) hiện có

---

## KẾT LUẬN
Project hiện tại đã có hệ thống backend quản lý đầy đủ, nhưng frontend cho người dùng cuối còn rất cơ bản. Cần phát triển thêm các trang và chức năng nêu trên để có một website du lịch hoàn chỉnh.
