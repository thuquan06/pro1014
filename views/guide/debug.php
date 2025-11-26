<?php
// Trang debug để kiểm tra dữ liệu guide
// Chỉ dùng trong môi trường development
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Debug Guide Data</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Debug Guide Data</h1>
    
    <?php
    require_once '../commons/env.php';
    require_once '../commons/function.php';
    require_once '../models/BaseModel.php';
    require_once '../models/GuideModel.php';
    require_once '../models/AssignmentModel.php';
    
    $guideModel = new GuideModel();
    $assignmentModel = new AssignmentModel();
    
    // Lấy tất cả guides
    $allGuides = $guideModel->getAllGuides();
    ?>
    
    <h2>1. Tất cả Guides trong hệ thống (<?= count($allGuides) ?>)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>CMND/CCCD</th>
            <th>Trạng thái</th>
            <th>Kinh nghiệm</th>
        </tr>
        <?php foreach ($allGuides as $g): ?>
        <tr>
            <td><?= $g['id'] ?></td>
            <td><?= htmlspecialchars($g['ho_ten'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($g['email'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($g['so_dien_thoai'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($g['cmnd_cccd'] ?? 'N/A') ?></td>
            <td><?= $g['trang_thai'] == 1 ? '<span class="success">Hoạt động</span>' : '<span class="error">Tạm dừng</span>' ?></td>
            <td><?= $g['kinh_nghiem'] ?? 0 ?> năm</td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>2. Tất cả Phân công HDV</h2>
    <?php
    $allAssignments = $assignmentModel->getAllAssignments();
    ?>
    <table>
        <tr>
            <th>ID</th>
            <th>ID HDV</th>
            <th>Họ tên HDV</th>
            <th>Tour</th>
            <th>Ngày khởi hành</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
        </tr>
        <?php foreach ($allAssignments as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= $a['id_hdv'] ?? 'N/A' ?></td>
            <td><?= htmlspecialchars($a['ho_ten'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($a['ten_tour'] ?? 'N/A') ?></td>
            <td><?= $a['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($a['ngay_khoi_hanh'])) : 'N/A' ?></td>
            <td><?= htmlspecialchars($a['vai_tro'] ?? 'N/A') ?></td>
            <td><?= $a['trang_thai'] == 1 ? '<span class="success">Hoạt động</span>' : '<span class="error">Tạm dừng</span>' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>3. Kiểm tra đăng nhập</h2>
    <p>Để đăng nhập, bạn cần:</p>
    <ul>
        <li>Email: Email của guide (không phân biệt hoa thường)</li>
        <li>Mật khẩu: CMND/CCCD hoặc số điện thoại của guide</li>
    </ul>
    
    <h2>4. Test đăng nhập cho từng guide</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Email để đăng nhập</th>
            <th>Mật khẩu (CMND/CCCD)</th>
            <th>Mật khẩu (SĐT)</th>
            <th>Trạng thái</th>
        </tr>
        <?php foreach ($allGuides as $g): ?>
        <tr>
            <td><?= $g['id'] ?></td>
            <td><?= htmlspecialchars($g['ho_ten'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($g['email'] ?? '<span class="error">Chưa có email</span>') ?></td>
            <td><?= !empty($g['cmnd_cccd']) ? htmlspecialchars($g['cmnd_cccd']) : '<span class="error">Chưa có</span>' ?></td>
            <td><?= !empty($g['so_dien_thoai']) ? htmlspecialchars($g['so_dien_thoai']) : '<span class="error">Chưa có</span>' ?></td>
            <td><?= $g['trang_thai'] == 1 ? '<span class="success">Có thể đăng nhập</span>' : '<span class="error">Trạng thái không hoạt động</span>' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <p><a href="?act=guide">← Quay lại trang đăng nhập</a></p>
</body>
</html>


