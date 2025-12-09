<?php
/**
 * Attendance List - Danh sách lịch trình để điểm danh
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDateTime($date, $time) {
    if (!$date) return '-';
    $dateStr = date('d/m/Y', strtotime($date));
    $timeStr = $time ? date('H:i', strtotime($time)) : '';
    return $dateStr . ($timeStr ? ' ' . $timeStr : '');
}

function getTrangThaiText($status) {
    return $status == 1 
        ? '<span class="status-badge success"><i class="fas fa-check-circle"></i> Hoạt động</span>'
        : '<span class="status-badge danger"><i class="fas fa-ban"></i> Tạm dừng</span>';
}

$departurePlans = $departurePlans ?? [];
$tours = $tours ?? [];
$tour = $tour ?? null;
$filters = $filters ?? [];
$isAdmin = $isAdmin ?? false;
?>

<style>
.attendance-list-container {
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

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.filter-form {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 12px;
    align-items: end;
}

.filter-input {
    padding: 10px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    width: 100%;
}

.filter-input:focus {
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

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.attendance-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.attendance-table thead {
    background: #f9fafb;
}

.attendance-table th {
    padding: 14px 16px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e5e7eb;
}

.attendance-table th:last-child {
    text-align: center;
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

.stats-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    background: #dbeafe;
    color: #1e40af;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
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

@media (max-width: 768px) {
    .filter-form {
        grid-template-columns: 1fr;
    }
    
    .attendance-table {
        font-size: 12px;
    }
    
    .attendance-table th,
    .attendance-table td {
        padding: 8px;
    }
}
</style>

<div class="attendance-list-container">
    <div class="attendance-header">
        <h1 class="attendance-title">
            <i class="fas fa-clipboard-check"></i> Điểm danh thành viên
        </h1>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #a7f3d0;">
            <i class="fas fa-check-circle"></i> <?= safe_html($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #fecaca;">
            <i class="fas fa-exclamation-circle"></i> <?= safe_html($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="<?= BASE_URL ?>?act=admin-attendance-list" class="filter-form">
            <input type="hidden" name="act" value="admin-attendance-list">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Tên tour</label>
                <input 
                    type="text" 
                    name="ten_tour" 
                    class="filter-input" 
                    placeholder="Nhập tên tour..." 
                    value="<?= safe_html($filters['ten_tour'] ?? '') ?>"
                >
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
            <a href="<?= BASE_URL ?>?act=admin-attendance-list" class="btn btn-secondary">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </form>
    </div>

    <!-- Table -->
    <?php if (!empty($departurePlans)): ?>
    <div style="overflow-x: auto;">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>TOUR</th>
                    <th>NGÀY/GIỜ KHỞI HÀNH</th>
                    <th>ĐIỂM TẬP TRUNG</th>
                    <th>GHI CHÚ</th>
                    <th>THỐNG KÊ ĐIỂM DANH</th>
                    <th>TRẠNG THÁI</th>
                    <th>THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departurePlans as $index => $plan): 
                    $stats = $plan['attendance_stats'] ?? ['tong_thanh_vien' => 0, 'co_mat' => 0, 'vang_mat' => 0, 'co_ly_do' => 0];
                ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td>
                        <strong><?= safe_html($plan['tengoi'] ?? $plan['ten_tour'] ?? 'N/A') ?></strong>
                        <?php if ($plan['id_tour']): ?>
                        <br>
                        <small style="color: #6b7280;">ID: <?= $plan['id_tour'] ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= formatDateTime($plan['ngay_khoi_hanh'] ?? null, $plan['gio_khoi_hanh'] ?? null) ?></td>
                    <td><?= safe_html($plan['diem_tap_trung'] ?? '-') ?></td>
                    <td><?= safe_html($plan['ghi_chu'] ?? '-') ?></td>
                    <td>
                        <?php if ($stats['tong_thanh_vien'] > 0): ?>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <span class="stats-badge">
                                    <i class="fas fa-users"></i> Tổng: <?= $stats['tong_thanh_vien'] ?>
                                </span>
                                <span style="color: #10b981; font-size: 12px;">
                                    <i class="fas fa-check-circle"></i> Có mặt: <?= $stats['co_mat'] ?? 0 ?>
                                </span>
                                <span style="color: #ef4444; font-size: 12px;">
                                    <i class="fas fa-times-circle"></i> Vắng: <?= $stats['vang_mat'] ?? 0 ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <span style="color: #9ca3af; font-size: 12px;">Chưa có dữ liệu</span>
                        <?php endif; ?>
                    </td>
                    <td><?= getTrangThaiText($plan['trang_thai'] ?? 1) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a 
                                href="<?= BASE_URL ?>?act=admin-attendance&id_lich_khoi_hanh=<?= $plan['id'] ?>" 
                                class="btn <?= $isAdmin ? 'btn-primary' : 'btn-success' ?>"
                                style="padding: 8px 16px; font-size: 13px;"
                            >
                                <i class="fas fa-<?= $isAdmin ? 'eye' : 'clipboard-check' ?>"></i> 
                                <?= $isAdmin ? 'Xem điểm danh' : 'Điểm danh' ?>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-calendar-times"></i>
        <h3>Chưa có lịch trình nào</h3>
        <p>Không tìm thấy lịch trình phù hợp với bộ lọc của bạn.</p>
    </div>
    <?php endif; ?>
</div>

