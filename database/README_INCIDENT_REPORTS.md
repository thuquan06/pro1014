# Hướng dẫn tạo bảng bao_cao_su_co

## Vấn đề
Bảng `bao_cao_su_co` chưa tồn tại trong database, gây ra lỗi khi truy cập tính năng báo cáo sự cố.

## Giải pháp

### Cách 1: Chạy script PHP qua trình duyệt (Khuyến nghị)

1. Đảm bảo MAMP đã được khởi động
2. Truy cập URL sau trong trình duyệt:
   ```
   http://localhost:8888/pro1014/database/create_incident_reports_table.php
   ```
3. Script sẽ tự động tạo bảng và hiển thị kết quả

### Cách 2: Chạy file SQL trực tiếp

1. Mở phpMyAdmin hoặc MySQL client
2. Chọn database `starvel`
3. Mở file `database/incident_reports.sql`
4. Copy và paste toàn bộ nội dung vào SQL tab
5. Nhấn "Go" để thực thi

### Cách 3: Sử dụng MySQL command line

```bash
mysql -h localhost -P 8889 -u root -proot starvel < database/incident_reports.sql
```

## Kiểm tra

Sau khi tạo bảng, bạn có thể kiểm tra bằng cách:

1. Truy cập lại trang báo cáo sự cố
2. Hoặc chạy query trong phpMyAdmin:
   ```sql
   SHOW TABLES LIKE 'bao_cao_su_co';
   DESCRIBE bao_cao_su_co;
   ```

## Lưu ý

- Foreign key constraint sẽ được tạo tự động nếu bảng `phan_cong_hdv` đã tồn tại
- Nếu bảng `phan_cong_hdv` chưa tồn tại, foreign key sẽ không được tạo nhưng bảng vẫn hoạt động bình thường

