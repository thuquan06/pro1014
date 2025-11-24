<?php
// views/admin/province/list.php

// Helper an toàn
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Các biến nhận từ Controller:
// $provinces, $total, $page, $limit, $keyword
$page   = $page   ?? 1;
$limit  = $limit  ?? 10;
$total  = $total  ?? 0;
$keyword = $keyword ?? '';

$totalPages = $limit > 0 ? ceil($total / $limit) : 1;
if ($totalPages < 1) $totalPages = 1;
$startIndex = ($page - 1) * $limit;
?>

<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Trang chủ</a></li>
    <li class="active">Danh sách tỉnh/thành phố</li>
</ol>

<div class="forms-main">
    <div class="graph-form">
        <div class="form-body">

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-6">
                    <h2 style="margin-top: 0;">Quản lý tỉnh/thành phố</h2>
                    <a href="<?= BASE_URL ?>?act=province-create"
                       class="btn btn-primary">
                        + Thêm tỉnh/thành phố
                    </a>
                </div>

                <div class="col-md-6 text-right">
                    <form class="form-inline"
                          method="get"
                          action="<?= BASE_URL ?>">

                        <input type="hidden" name="act" value="province-list">

                        <div class="form-group">
                            <input type="text"
                                   name="keyword"
                                   value="<?= h($keyword) ?>"
                                   class="form-control"
                                   style="min-width: 260px;"
                                   placeholder="Nhập tên tỉnh để tìm...">
                        </div>
                        <button type="submit" class="btn btn-default">
                            Tìm kiếm
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
    <tr>
        <th class="text-center" style="width: 20%;">STT</th>
        <th class="text-center" style="width: 50%;">TÊN TỈNH</th>
        <th class="text-center" style="width: 30%;">HOẠT ĐỘNG</th>
    </tr>
</thead>
                    <tbody>
<?php if (!empty($provinces)): ?>
    <?php foreach ($provinces as $idx => $p): ?>
        <tr>
            <td class="text-center"><?= $startIndex + $idx + 1 ?></td>

            <td class="text-center">
                <?= htmlspecialchars($p['ten_tinh']) ?>
            </td>

            <td class="text-center">
                <a href="<?= BASE_URL ?>?act=province-edit&id=<?= $p['id_tinh'] ?>"
                   class="btn btn-info btn-sm">
                   Chỉnh sửa
                </a>

                <a onclick="return confirm('Xóa tỉnh này?')"
                   href="<?= BASE_URL ?>?act=province-delete&id=<?= $p['id_tinh'] ?>"
                   class="btn btn-danger btn-sm">
                   Xóa
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="3" class="text-center text-muted">Không có dữ liệu</td>
    </tr>
<?php endif; ?>
</tbody>

                </table>
            </div>

            <!-- Phân trang -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Page navigation" class="text-right">
                    <ul class="pagination" style="margin: 0;">
                        <?php
                        // giữ từ khóa khi chuyển trang
                        $baseQuery = 'act=province-list';
                        if ($keyword !== '') {
                            $baseQuery .= '&keyword=' . urlencode($keyword);
                        }
                        ?>

                        <li class="<?= $page <= 1 ? 'disabled' : '' ?>">
                            <a href="<?= $page <= 1
                                ? '#'
                                : BASE_URL . '?' . $baseQuery . '&page=' . ($page - 1) ?>">
                                Trước
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="<?= $i == $page ? 'active' : '' ?>">
                                <a href="<?= BASE_URL . '?' . $baseQuery . '&page=' . $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="<?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a href="<?= $page >= $totalPages
                                ? '#'
                                : BASE_URL . '?' . $baseQuery . '&page=' . ($page + 1) ?>">
                                Tiếp
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>
