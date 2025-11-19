<?php
/**
 * File: views/admin/tours/versions/index.php
 * Danh sách phiên bản tour
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li class="active">Phiên bản Tour #<?= $idGoi ?></li>
</ol>

<!-- Header với Dropdown -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-4">
        <h2 style="margin: 0 0 15px 0;">📦 Quản lý Phiên bản Tour</h2>
        
        <!-- Dropdown chọn tour -->
        <div class="form-group" style="margin-bottom: 0;">
            <label for="select-tour" style="font-weight: 600; margin-bottom: 8px; display: block;">
                <i class="fa fa-map"></i> Chọn tour để xem:
            </label>
            <select id="select-tour" class="form-control input-lg" style="font-size: 15px;" onchange="if(this.value) window.location.href='<?= BASE_URL ?>?act=tour-versions&id_goi=' + this.value">
                <option value="">-- Chọn tour --</option>
                <?php if (!empty($allTours)): ?>
                    <?php foreach ($allTours as $tour): ?>
                        <option value="<?= $tour['id'] ?>" <?= $tour['id'] == $idGoi ? 'selected' : '' ?>>
                            #<?= $tour['id'] ?> - <?= htmlspecialchars($tour['ten_goi']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    
    <div class="col-md-8 text-right" style="padding-top: 45px;">
        <a href="<?= BASE_URL ?>?act=tour-version-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm phiên bản
        </a>
        <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Thông báo -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="succWrap">
        <i class="fa fa-check-circle"></i> <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="errorWrap">
        <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Danh sách versions -->
<div class="panel panel-default">
    <div class="panel-body">
        <?php if (empty($versions)): ?>
            <div class="text-center" style="padding: 60px 0;">
                <i class="fa fa-code-fork fa-4x text-muted"></i>
                <h4 class="text-muted" style="margin-top: 20px;">Chưa có phiên bản nào</h4>
                <p class="text-muted">Tạo phiên bản theo mùa hoặc holiday</p>
                <a href="<?= BASE_URL ?>?act=tour-version-them&id_goi=<?= $idGoi ?>" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fa fa-plus"></i> Thêm phiên bản đầu tiên
                </a>
            </div>
        <?php else: ?>
            <!-- Timeline Versions -->
            <div class="versions-timeline">
                <?php foreach ($versions as $index => $ver): ?>
                    <?php
                    $badge_class = '';
                    $icon = '';
                    switch($ver['loai_phienban']) {
                        case 'mua':
                            $badge_class = 'badge-season';
                            $icon = '🌞';
                            break;
                        case 'holiday':
                            $badge_class = 'badge-holiday';
                            $icon = '🎄';
                            break;
                        case 'special':
                            $badge_class = 'badge-special';
                            $icon = '⭐';
                            break;
                        default:
                            $badge_class = 'badge-default';
                            $icon = '📦';
                    }
                    
                    $today = date('Y-m-d');
                    $isActive = ($ver['is_active'] && $today >= $ver['ngay_batdau'] && $today <= $ver['ngay_ketthuc']);
                    ?>
                    
                    <div class="version-item <?= $isActive ? 'active-version' : '' ?>">
                        <div class="version-badge">
                            <span class="<?= $badge_class ?>"><?= $icon ?></span>
                        </div>
                        
                        <div class="version-content">
                            <div class="panel <?= $ver['is_default'] ? 'panel-primary' : 'panel-default' ?>">
                                <!-- Header -->
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 style="margin: 5px 0;">
                                                <?= htmlspecialchars($ver['ten_phienban']) ?>
                                                
                                                <?php if ($ver['is_default']): ?>
                                                    <span class="label label-primary">Mặc định</span>
                                                <?php endif; ?>
                                                
                                                <?php if ($isActive): ?>
                                                    <span class="label label-success">Đang áp dụng</span>
                                                <?php elseif (!$ver['is_active']): ?>
                                                    <span class="label label-default">Tắt</span>
                                                <?php endif; ?>
                                            </h4>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <!-- Dropdown actions -->
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> Thao tác <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a href="<?= BASE_URL ?>?act=tour-version-sua&id=<?= $ver['id'] ?>&id_goi=<?= $idGoi ?>">
                                                            <i class="fa fa-edit"></i> Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?= BASE_URL ?>?act=tour-version-lichsu&id=<?= $ver['id'] ?>&id_goi=<?= $idGoi ?>">
                                                            <i class="fa fa-history"></i> Lịch sử
                                                        </a>
                                                    </li>
                                                    <li role="separator" class="divider"></li>
                                                    <li>
                                                        <a href="#" onclick="cloneVersion(<?= $ver['id'] ?>, '<?= htmlspecialchars($ver['ten_phienban']) ?>'); return false;">
                                                            <i class="fa fa-copy"></i> Clone
                                                        </a>
                                                    </li>
                                                    <?php if (!$ver['is_default']): ?>
                                                        <li>
                                                            <a href="<?= BASE_URL ?>?act=tour-version-macdinh&id=<?= $ver['id'] ?>&id_goi=<?= $idGoi ?>" onclick="return confirm('Đặt làm phiên bản mặc định?')">
                                                                <i class="fa fa-star"></i> Đặt mặc định
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li>
                                                        <a href="<?= BASE_URL ?>?act=tour-version-toggle&id=<?= $ver['id'] ?>&id_goi=<?= $idGoi ?>">
                                                            <i class="fa fa-power-off"></i> <?= $ver['is_active'] ? 'Tắt' : 'Bật' ?>
                                                        </a>
                                                    </li>
                                                    <li role="separator" class="divider"></li>
                                                    <li>
                                                        <a href="<?= BASE_URL ?>?act=tour-version-xoa&id=<?= $ver['id'] ?>&id_goi=<?= $idGoi ?>" 
                                                           onclick="return confirm('Bạn có chắc muốn xóa phiên bản này?')"
                                                           style="color: #d9534f;">
                                                            <i class="fa fa-trash"></i> Xóa
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Body -->
                                <div class="panel-body">
                                    <?php if ($ver['mo_ta']): ?>
                                        <p class="text-muted"><?= nl2br(htmlspecialchars($ver['mo_ta'])) ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="row" style="margin-top: 15px;">
                                        <!-- Thời gian -->
                                        <div class="col-md-4">
                                            <strong><i class="fa fa-calendar"></i> Thời gian áp dụng:</strong><br>
                                            <?= date('d/m/Y', strtotime($ver['ngay_batdau'])) ?> - <?= date('d/m/Y', strtotime($ver['ngay_ketthuc'])) ?>
                                        </div>
                                        
                                        <!-- Giá -->
                                        <div class="col-md-5">
                                            <strong><i class="fa fa-money"></i> Giá:</strong><br>
                                            <?php if ($ver['gia_nguoilon']): ?>
                                                Người lớn: <span class="text-success"><?= number_format($ver['gia_nguoilon']) ?> VNĐ</span>
                                            <?php endif; ?>
                                            <?php if ($ver['gia_treem']): ?>
                                                | Trẻ em: <span class="text-info"><?= number_format($ver['gia_treem']) ?> VNĐ</span>
                                            <?php endif; ?>
                                            <?php if (!$ver['gia_nguoilon'] && !$ver['gia_treem']): ?>
                                                <span class="text-muted">Dùng giá mặc định</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Priority -->
                                        <div class="col-md-3 text-right">
                                            <strong><i class="fa fa-sort-numeric-asc"></i> Ưu tiên:</strong>
                                            <span class="label label-default"><?= $ver['priority'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="panel-footer text-muted">
                                    <small>
                                        <i class="fa fa-clock-o"></i> 
                                        Tạo: <?= date('d/m/Y H:i', strtotime($ver['created_at'])) ?>
                                        | Cập nhật: <?= date('d/m/Y H:i', strtotime($ver['updated_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Tổng kết -->
            <div class="alert alert-info" style="margin-top: 30px;">
                <strong><i class="fa fa-info-circle"></i> Tổng cộng:</strong> <?= count($versions) ?> phiên bản
                
                <?php if (count($versions) >= 2): ?>
                    <div class="pull-right">
                        <button class="btn btn-sm btn-info" onclick="showCompareModal()">
                            <i class="fa fa-exchange"></i> So sánh versions
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Clone -->
<div class="modal fade" id="cloneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="cloneForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-copy"></i> Clone phiên bản</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên phiên bản gốc:</label>
                        <input type="text" class="form-control" id="ten_goc" disabled>
                    </div>
                    <div class="form-group">
                        <label>Tên phiên bản mới: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ten_moi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-copy"></i> Clone
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.versions-timeline {
    position: relative;
    padding-left: 60px;
    padding-top: 20px;
}

.versions-timeline::before {
    content: '';
    position: absolute;
    left: 25px;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to bottom, #3498db, #9b59b6);
}

.version-item {
    position: relative;
    margin-bottom: 30px;
}

.version-badge {
    position: absolute;
    left: -50px;
    top: 15px;
}

.version-badge span {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 50%;
    font-size: 20px;
    border: 4px solid white;
    box-shadow: 0 0 0 3px #3498db;
}

.badge-season { background: #f39c12; box-shadow: 0 0 0 3px #f39c12 !important; }
.badge-holiday { background: #e74c3c; box-shadow: 0 0 0 3px #e74c3c !important; }
.badge-special { background: #9b59b6; box-shadow: 0 0 0 3px #9b59b6 !important; }
.badge-default { background: #3498db; box-shadow: 0 0 0 3px #3498db !important; }

.version-content {
    margin-left: 20px;
}

.version-item:hover .panel {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.active-version .panel {
    border: 2px solid #5cb85c;
    box-shadow: 0 0 10px rgba(92, 184, 92, 0.3);
}

.panel {
    transition: all 0.3s ease;
}

#select-tour {
    border: 2px solid #ddd;
    border-radius: 6px;
    padding: 10px 15px;
    transition: all 0.3s;
}

#select-tour:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}
</style>

<script>
function cloneVersion(id, tenGoc) {
    document.getElementById('ten_goc').value = tenGoc;
    document.getElementById('cloneForm').action = '<?= BASE_URL ?>?act=tour-version-clone&id=' + id + '&id_goi=<?= $idGoi ?>';
    $('#cloneModal').modal('show');
}

function showCompareModal() {
    // TODO: Implement compare modal
    alert('Chức năng so sánh đang được phát triển!');
}
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>