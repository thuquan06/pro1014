# HƯỚNG DẪN SỬ DỤNG HÀM TÍNH GIÁ TOUR

## 📦 File đã tạo: `commons/price_helper.php`

File này chứa các hàm helper để tính toán và hiển thị giá tour có khuyến mãi.

## 🎯 Các hàm có sẵn:

### 1. `calculatePromotionPrice($originalPrice, $tour)`
**Tính giá sau khuyến mãi**

```php
// Ví dụ:
$tour = [
    'giagoi' => 5000000,
    'khuyenmai' => 1,
    'khuyenmai_phantram' => 20,
    'khuyenmai_tungay' => '2024-12-01',
    'khuyenmai_denngay' => '2024-12-31'
];

$giaSauKM = calculatePromotionPrice($tour['giagoi'], $tour);
// Kết quả: 4,000,000 (giảm 20%)
```

### 2. `isPromotionActive($tour)`
**Kiểm tra khuyến mãi còn hiệu lực không**

```php
if (isPromotionActive($tour)) {
    echo "Khuyến mãi đang hoạt động!";
}
```

### 3. `formatPrice($price)`
**Format giá tiền VNĐ**

```php
echo formatPrice(5000000);
// Output: 5,000,000 VNĐ
```

### 4. `displayTourPrice($originalPrice, $tour)`
**Hiển thị giá với HTML (gạch ngang giá cũ nếu có KM)**

```php
echo displayTourPrice($tour['giagoi'], $tour);
// Output: <span style="text-decoration: line-through;">5,000,000 VNĐ</span>
//         <span style="color: red; font-weight: bold;">4,000,000 VNĐ</span>
```

### 5. `displayPromotionBadge($tour)`
**Hiển thị badge "Giảm X%"**

```php
echo displayPromotionBadge($tour);
// Output: <span class="badge">🔥 Giảm 20%</span>
```

### 6. `displayPromotionCountdown($tour)`
**Hiển thị countdown thời gian còn lại**

```php
echo displayPromotionCountdown($tour);
// Output: ⏰ Còn 15 ngày
```

### 7. `calculateBookingTotal($tour, $nguoilon, $treem, $trenho)`
**Tính tổng tiền đặt tour (có tính khuyến mãi)**

```php
$total = calculateBookingTotal($tour, 2, 1, 0);
// 2 người lớn, 1 trẻ em, 0 trẻ nhỏ
echo formatPrice($total);
```

## 💡 Cách sử dụng trong Views:

### Trong danh sách tour (`views/admin/tours/list.php`):

```php
<?php
// Load helper
require_once './commons/price_helper.php';

// Lấy danh sách tour
$tours = $tourModel->getAllTours();

foreach ($tours as $tour) {
    // Hiển thị badge khuyến mãi
    echo displayPromotionBadge($tour);

    // Hiển thị giá
    echo displayTourPrice($tour['giagoi'], $tour);

    // Hiển thị countdown
    echo displayPromotionCountdown($tour);
}
?>
```

### Trong chi tiết tour (`views/client/tour-detail.php`):

```php
<?php
require_once './commons/price_helper.php';

// Hiển thị giá người lớn
echo '<div class="price-section">';
echo '<h3>Giá tour:</h3>';
echo displayPromotionBadge($tour);
echo '<p>Người lớn: ' . displayTourPrice($tour['giagoi'], $tour) . '</p>';
echo '<p>Trẻ em: ' . displayTourPrice($tour['giatreem'], $tour) . '</p>';
echo '<p>Trẻ nhỏ: ' . displayTourPrice($tour['giatrenho'], $tour) . '</p>';
echo displayPromotionCountdown($tour);
echo '</div>';
?>
```

### Trong trang đặt tour (`views/client/booking.php`):

```php
<?php
require_once './commons/price_helper.php';

// Tính tổng tiền khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nguoiLon = (int)$_POST['nguoilon'];
    $treEm = (int)$_POST['treem'];
    $treNho = (int)$_POST['trenho'];

    $total = calculateBookingTotal($tour, $nguoiLon, $treEm, $treNho);

    echo '<h3>Tổng tiền: ' . formatPrice($total) . '</h3>';
}
?>
```

## 🔧 Tích hợp vào Controller:

### ProductController.php (trang chi tiết tour):

```php
public function detailTour() {
    $tourId = $_GET['id'] ?? null;
    $tour = $this->tourModel->getTourByID($tourId);

    // Load price helper
    require_once './commons/price_helper.php';

    // Tính giá sau khuyến mãi
    $tour['gia_sau_km'] = calculatePromotionPrice($tour['giagoi'], $tour);
    $tour['co_khuyen_mai'] = isPromotionActive($tour);

    // Load view
    $this->loadView('client/tour-detail', compact('tour'));
}
```

### HoadonController.php (tính tổng tiền):

```php
public function calculateTotal($hoadonId) {
    $hoadon = $this->getHoadonById($hoadonId);
    $tour = $this->tourModel->getTourByID($hoadon['id_goi']);

    // Load price helper
    require_once './commons/price_helper.php';

    // Tính tổng với giá khuyến mãi
    $total = calculateBookingTotal(
        $tour,
        $hoadon['nguoilon'],
        $hoadon['treem'],
        $hoadon['trenho']
    );

    return $total;
}
```

## 📊 Logic tính giá:

### 1. Kiểm tra điều kiện:
- Tour có `khuyenmai = 1`
- Có `khuyenmai_phantram` > 0
- Ngày hiện tại nằm trong khoảng `khuyenmai_tungay` → `khuyenmai_denngay`

### 2. Công thức:
```
Giá sau KM = Giá gốc - (Giá gốc × Phần trăm / 100)
```

### 3. Ví dụ cụ thể:
```
Giá gốc: 5,000,000 VNĐ
Khuyến mãi: 20%
Số tiền giảm: 5,000,000 × 20 / 100 = 1,000,000 VNĐ
Giá sau KM: 5,000,000 - 1,000,000 = 4,000,000 VNĐ
```

## 🎨 Style CSS (tùy chọn):

```css
/* Badge khuyến mãi */
.badge-promotion {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    display: inline-block;
    margin-bottom: 8px;
}

/* Giá cũ (gạch ngang) */
.price-old {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
    margin-right: 8px;
}

/* Giá mới (sau KM) */
.price-new {
    color: #e74c3c;
    font-weight: bold;
    font-size: 18px;
}

/* Countdown */
.countdown {
    color: #f39c12;
    font-size: 12px;
    margin-top: 4px;
}
```

## ⚠️ Lưu ý:

1. **Phải chạy SQL trước** để có các cột khuyến mãi
2. **Load helper** trước khi sử dụng: `require_once './commons/price_helper.php';`
3. **Tour cũ** (chưa có khuyến mãi) sẽ trả về giá gốc
4. **Khuyến mãi hết hạn** tự động không áp dụng nữa

## 🚀 Sử dụng nhanh:

```php
<?php
// Load helper
require_once './commons/price_helper.php';

// Hiển thị đầy đủ
echo displayPromotionBadge($tour);           // Badge "Giảm 20%"
echo displayTourPrice($tour['giagoi'], $tour); // Giá (có gạch ngang nếu KM)
echo displayPromotionCountdown($tour);       // Countdown "Còn X ngày"
?>
```

---

**🎉 Hoàn thành! Bây giờ bạn có thể tính và hiển thị giá khuyến mãi ở mọi nơi!**
