<?php
/**
 * Script kiểm tra cấu trúc bảng nguoidung
 * Chạy file này trong browser để xem cấu trúc bảng
 */

require_once './commons/env.php';
require_once './commons/function.php';

$conn = connectDB();

echo "<h1>Cấu trúc bảng nguoidung</h1>";
echo "<style>
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #2563eb; color: white; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    .code { background: #f4f4f4; padding: 20px; border-radius: 8px; font-family: monospace; }
</style>";

try {
    // Lấy thông tin cột
    $stmt = $conn->query("DESCRIBE nguoidung");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>1. Danh sách các cột:</h2>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($col['Field']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($col['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Lấy CREATE TABLE statement
    $stmt = $conn->query("SHOW CREATE TABLE nguoidung");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>2. CREATE TABLE Statement:</h2>";
    echo "<div class='code'>";
    echo "<pre>" . htmlspecialchars($result['Create Table']) . "</pre>";
    echo "</div>";
    
    // Lấy số lượng records
    $stmt = $conn->query("SELECT COUNT(*) as total FROM nguoidung");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<h2>3. Số lượng records: " . $count['total'] . "</h2>";
    
    // Lấy mẫu dữ liệu (nếu có)
    $stmt = $conn->query("SELECT * FROM nguoidung LIMIT 3");
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($samples)) {
        echo "<h2>4. Mẫu dữ liệu (3 records đầu):</h2>";
        echo "<table>";
        // Header
        echo "<tr>";
        foreach (array_keys($samples[0]) as $key) {
            echo "<th>" . htmlspecialchars($key) . "</th>";
        }
        echo "</tr>";
        // Data
        foreach ($samples as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<hr>";
    echo "<h2>📋 Copy đoạn này gửi cho tôi:</h2>";
    echo "<div class='code'>";
    echo "<pre>";
    echo "CÁC CỘT TRONG BẢNG nguoidung:\n";
    echo "============================\n\n";
    foreach ($columns as $col) {
        echo $col['Field'] . " | " . $col['Type'] . " | " . $col['Null'] . " | " . $col['Key'] . "\n";
    }
    echo "</pre>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='color: red; padding: 20px; background: #fee; border-radius: 8px;'>";
    echo "<h2>❌ Lỗi:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Có thể bảng <code>nguoidung</code> chưa tồn tại hoặc tên bảng khác.</p>";
    echo "</div>";
}
?>

