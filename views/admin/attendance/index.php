<?php
/**
 * Attendance Page - Trang điểm danh cho HDV
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : 'N/A';
}

function formatDateTime($datetime) {
    return $datetime ? date('d/m/Y H:i', strtotime($datetime)) : 'N/A';
}

function getTrangThaiBadge($trangThai) {
    $badges = [
        1 => ['text' => 'Có mặt', 'class' => 'success', 'icon' => 'check-circle'],
        2 => ['text' => 'Vắng mặt', 'class' => 'danger', 'icon' => 'times-circle'],
        3 => ['text' => 'Có lý do', 'class' => 'warning', 'icon' => 'exclamation-circle']
    ];
    $badge = $badges[$trangThai] ?? $badges[1];
    return '<span class="status-badge ' . $badge['class'] . '"><i class="fas fa-' . $badge['icon'] . '"></i> ' . $badge['text'] . '</span>';
}

function getLoaiKhachBadge($loaiKhach) {
    $badges = [
        1 => ['text' => 'Người lớn', 'class' => 'primary'],
        2 => ['text' => 'Trẻ em', 'class' => 'info'],
        3 => ['text' => 'Em bé', 'class' => 'secondary']
    ];
    $badge = $badges[$loaiKhach] ?? $badges[1];
    return '<span class="badge badge-' . $badge['class'] . '">' . $badge['text'] . '</span>';
}

$departurePlan = $departurePlan ?? null;
$tour = $tour ?? null;
$members = $members ?? [];
$attendanceMap = $attendanceMap ?? [];
$assignments = $assignments ?? [];
$isAdmin = $isAdmin ?? false;
$canAttend = $canAttend ?? false;
$ngay_diem_danh = $ngay_diem_danh ?? date('Y-m-d');

if (!$departurePlan) {
    echo '<div class="alert alert-danger">Không tìm thấy lịch trình</div>';
    return;
}
?>

<style>
.attendance-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.attendance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.attendance-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 24px;
    overflow: hidden;
}

.info-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    background: #f8fafc;
}

.info-card-title {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.info-card-body {
    padding: 24px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
}

.info-value {
    font-size: 16px;
    color: #1f2937;
    font-weight: 500;
}

.attendance-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.attendance-table thead {
    background: #f8fafc;
}

.attendance-table th {
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    border-bottom: 2px solid #e5e7eb;
}

.attendance-table td {
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.attendance-table tbody tr:hover {
    background: #f9fafb;
}

.attendance-table tbody tr:last-child td {
    border-bottom: none;
}

.status-select {
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    min-width: 120px;
}

.status-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.note-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    resize: vertical;
    min-height: 60px;
}

.note-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
}

.status-badge.success {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.danger {
    background: #fee2e2;
    color: #991b1b;
}

.status-badge.warning {
    background: #fef3c7;
    color: #92400e;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.badge-primary {
    background: #dbeafe;
    color: #1e40af;
}

.badge-info {
    background: #cffafe;
    color: #155e75;
}

.badge-secondary {
    background: #f3f4f6;
    color: #374151;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6b7280;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    color: #d1d5db;
}

.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert-info {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

@media (max-width: 768px) {
    .attendance-table {
        font-size: 12px;
    }
    
    .attendance-table th,
    .attendance-table td {
        padding: 8px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="attendance-container">
    <div class="attendance-header">
        <h1 class="attendance-title">
            <i class="fas fa-clipboard-check"></i> Điểm danh thành viên
        </h1>
        <div>
            <a href="<?= BASE_URL ?>?act=admin-departure-plan-detail&id=<?= $departurePlan['id'] ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= safe_html($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= safe_html($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Form chọn ngày điểm danh -->
    <div class="info-card" style="margin-bottom: 24px;">
        <div class="info-card-body">
            <form method="GET" action="" style="display: flex; gap: 12px; align-items: end;">
                <input type="hidden" name="act" value="admin-attendance">
                <input type="hidden" name="id_lich_khoi_hanh" value="<?= $departurePlan['id'] ?>">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">
                        <i class="fas fa-calendar"></i> Ngày điểm danh
                    </label>
                    <input type="date" name="ngay_diem_danh" value="<?= htmlspecialchars($ngay_diem_danh ?? date('Y-m-d')) ?>" 
                           style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;" required>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; height: fit-content;">
                    <i class="fas fa-search"></i> Xem danh sách
                </button>
            </form>
        </div>
    </div>

    <!-- Thông tin lịch trình -->
    <div class="info-card">
        <div class="info-card-header">
            <h2 class="info-card-title">
                <i class="fas fa-info-circle"></i> Thông tin lịch trình
            </h2>
        </div>
        <div class="info-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Mã lịch trình</span>
                    <span class="info-value"><?= safe_html($departurePlan['ma_lich'] ?? 'LKH-' . $departurePlan['id']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tour</span>
                    <span class="info-value">
                        <?php 
                        $tourName = null;
                        if ($tour && !empty($tour['tengoi'])) {
                            $tourName = $tour['tengoi'];
                        } elseif (!empty($departurePlan['tengoi'])) {
                            $tourName = $departurePlan['tengoi'];
                        } else {
                            $tourName = 'Tour chưa có tên';
                        }
                        echo safe_html($tourName);
                        ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày khởi hành</span>
                    <span class="info-value"><?= formatDate($departurePlan['ngay_khoi_hanh']) ?> <?= $departurePlan['gio_khoi_hanh'] ? date('H:i', strtotime($departurePlan['gio_khoi_hanh'])) : '' ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Số chỗ</span>
                    <span class="info-value">
                        <?php 
                        $so_cho = $departurePlan['so_cho'] ?? 0;
                        $so_cho_da_dat = $departurePlan['so_cho_da_dat'] ?? 0;
                        $so_cho_con_lai = max(0, $so_cho - $so_cho_da_dat);
                        echo number_format($so_cho) . ' / ' . number_format($so_cho_da_dat) . ' đã đặt';
                        if ($so_cho > 0) {
                            echo ' (' . number_format($so_cho_con_lai) . ' còn lại)';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form điểm danh -->
    <?php if (!empty($members)): ?>
    <div class="info-card">
        <div class="info-card-header">
            <h2 class="info-card-title">
                <i class="fas fa-users"></i> Danh sách thành viên (<?= count($members) ?> người)
                <span style="font-size: 14px; font-weight: normal; color: #6b7280; margin-left: 12px;">
                    - Ngày điểm danh: <?= date('d/m/Y', strtotime($ngay_diem_danh)) ?>
                </span>
            </h2>
        </div>
        <div class="info-card-body">
            <form id="attendanceForm">
                <input type="hidden" name="id_lich_khoi_hanh" value="<?= $departurePlan['id'] ?>">

                <div style="overflow-x: auto;">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã booking</th>
                                <th>Họ tên</th>
                                <th>Giới tính</th>
                                <th>Ngày sinh</th>
                                <th>SĐT</th>
                                <th>Loại khách</th>
                                <th>Trạng thái điểm danh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $index => $member): 
                                $key = $member['id_booking'] . '_' . $member['id_thanh_vien'];
                                $currentAttendance = $attendanceMap[$key] ?? null;
                            ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>?act=admin-booking-detail&id=<?= $member['id_booking'] ?>" target="_blank">
                                        <?= safe_html($member['ma_booking']) ?>
                                    </a>
                                </td>
                                <td><strong><?= safe_html($member['ho_ten']) ?></strong></td>
                                <td>
                                    <?php 
                                    if (isset($member['gioi_tinh'])) {
                                        if ($member['gioi_tinh'] == 1) {
                                            echo '<span style="display: inline-flex; align-items: center; gap: 6px;"><i class="fas fa-mars" style="color: #3b82f6;"></i> Nam</span>';
                                        } elseif ($member['gioi_tinh'] == 0) {
                                            echo '<span style="display: inline-flex; align-items: center; gap: 6px;"><i class="fas fa-venus" style="color: #ec4899;"></i> Nữ</span>';
                                        } else {
                                            echo '-';
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?= !empty($member['ngay_sinh']) ? date('d/m/Y', strtotime($member['ngay_sinh'])) : '-' ?></td>
                                <td><?= safe_html($member['so_dien_thoai']) ?></td>
                                <td><?= getLoaiKhachBadge($member['loai_khach']) ?></td>
                                <td>
                                    <?php if ($currentAttendance): ?>
                                        <?= getTrangThaiBadge($currentAttendance['trang_thai']) ?>
                                        <br>
                                        <small style="color: #6b7280;">
                                            <?= formatDateTime($currentAttendance['thoi_gian_diem_dan']) ?>
                                            <?php if ($currentAttendance['ten_hdv']): ?>
                                                <br>Bởi: <?= safe_html($currentAttendance['ten_hdv']) ?>
                                            <?php endif; ?>
                                            <?php if ($currentAttendance['ghi_chu']): ?>
                                                <br><span style="font-style: italic;"><?= safe_html($currentAttendance['ghi_chu']) ?></span>
                                            <?php endif; ?>
                                        </small>
                                    <?php else: ?>
                                        <span style="color: #9ca3af;">Chưa điểm danh</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="info-card">
        <div class="info-card-body">
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <h3>Chưa có thành viên nào</h3>
                <p>Lịch trình này chưa có booking nào hoặc tất cả booking đã bị hủy.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('attendanceForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const attendanceData = [];
    
    // Lấy tất cả các select và textarea
    const selects = this.querySelectorAll('select[name^="attendance"]');
    const textareas = this.querySelectorAll('textarea[name^="attendance"]');
    
    // Tạo map để nhóm dữ liệu
    const dataMap = {};
    
    selects.forEach(select => {
        const name = select.name;
        const match = name.match(/attendance\[(\d+)\]\[(\w+)\]/);
        if (match) {
            const index = match[1];
            const field = match[2];
            if (!dataMap[index]) dataMap[index] = {};
            dataMap[index][field] = select.value;
        }
    });
    
    // Lấy các hidden input
    const hiddenInputs = this.querySelectorAll('input[type="hidden"][name^="attendance"]');
    hiddenInputs.forEach(input => {
        const name = input.name;
        const match = name.match(/attendance\[(\d+)\]\[(\w+)\]/);
        if (match) {
            const index = match[1];
            const field = match[2];
            if (!dataMap[index]) dataMap[index] = {};
            dataMap[index][field] = input.value;
        }
    });
    
    // Thêm ghi chú
    textareas.forEach(textarea => {
        const name = textarea.name;
        const match = name.match(/attendance\[(\d+)\]\[(\w+)\]/);
        if (match) {
            const index = match[1];
            const field = match[2];
            if (!dataMap[index]) dataMap[index] = {};
            dataMap[index][field] = textarea.value;
        }
    });
    
    // Chuyển đổi map thành array
    Object.keys(dataMap).forEach(index => {
        attendanceData.push(dataMap[index]);
    });
    
    // Gửi request
    try {
        const response = await fetch('<?= BASE_URL ?>?act=admin-attendance-submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                id_lich_khoi_hanh: formData.get('id_lich_khoi_hanh'),
                id_hdv: formData.get('id_hdv') || '',
                attendance: JSON.stringify(attendanceData)
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Điểm danh thành công!');
            location.reload();
        } else {
            alert('Lỗi: ' + (result.message || 'Không thể lưu điểm danh'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Lỗi kết nối. Vui lòng thử lại.');
    }
});
</script>

