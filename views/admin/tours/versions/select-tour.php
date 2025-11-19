<?php ob_start(); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Chọn tour để quản lý phiên bản</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên tour</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allTours as $tour): ?>
                    <tr>
                        <td><?= $tour['id'] ?></td>
                        <td><?= htmlspecialchars($tour['ten_goi']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>?act=tour-versions&id_goi=<?= $tour['id'] ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-code-fork"></i> Quản lý Versions
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>