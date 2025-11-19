<?php
// ==========================
// KẾT NỐI DATABASE
// ==========================
$conn = new mysqli("localhost", "root", "", "tour_db");

if ($conn->connect_error) {
    die("Lỗi kết nối DB: " . $conn->connect_error);
}

// ==========================
// XỬ LÝ SUBMIT FORM
// ==========================
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $datetime = $_POST['departure_datetime'];
    $meeting_point = $_POST['meeting_point'];
    $seat_capacity = !empty($_POST['seat_capacity']) ? intval($_POST['seat_capacity']) : null;
    $notes = $_POST['operation_notes'];

    // Kiểm tra ngày/giờ hợp lệ
    if (strtotime($datetime) < time()) {
        $message = "Ngày giờ khởi hành không hợp lệ (nằm trong quá khứ).";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO departure_plans (departure_datetime, meeting_point, seat_capacity, operation_notes)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param("ssis", $datetime, $meeting_point, $seat_capacity, $notes);

        if ($stmt->execute()) {
            $message = "Tạo lịch khởi hành thành công!";
        } else {
            $message = "Lỗi tạo lịch: " . $stmt->error;
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tạo lịch khởi hành</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        form { max-width: 400px; }
        label { font-weight: bold; }
        input, textarea { width: 100%; padding: 6px; margin: 5px 0 15px; }
        button { padding: 8px 16px; }
        .message { font-weight: bold; color: blue; }
    </style>
</head>
<body>

<h2>Tạo lịch khởi hành</h2>

<?php if (!empty($message)): ?>
    <p class="message"><?php echo $message; ?></p>
<?php endif; ?>

<form action="" method="POST">
    <label>Ngày giờ khởi hành:</label>
    <input type="datetime-local" name="departure_datetime" required>

    <label>Điểm tập trung:</label>
    <input type="text" name="meeting_point" required>

    <label>Số chỗ dự kiến (không bắt buộc):</label>
    <input type="number" name="seat_capacity" min="0">

    <label>Ghi chú vận hành:</label>
    <textarea name="operation_notes"></textarea>

    <button type="submit">Tạo lịch</button>
</form>

</body>
</html>
