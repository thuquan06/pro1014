<div class="container-fluid">
  <h2>Danh sách blog</h2>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>STT</th>
        <th>Người viết</th>
        <th>Chủ đề</th>
        <th>Tóm tắt</th>
        <th>Nội dung</th>
        <th>Ngày đăng</th>
        <th>Hành động</th>
      </tr>
    </thead>

    <tbody>
    <?php $i=1; foreach($blogs as $b): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= $b['nguoiviet'] ?></td>
        <td><?= $b['chude'] ?></td>
        <td style="max-width:200px; overflow:auto"><?= $b['tomtat'] ?></td>
        <td style="max-width:300px; overflow:auto"><?= $b['noidung'] ?></td>
        <td><?= date("d-m-Y", strtotime($b['ngaydang'])) ?></td>

        <td>
          <a href="<?= BASE_URL ?>?act=blog-edit&id=<?= $b['id_blog'] ?>" class="btn btn-primary btn-sm">Sửa</a>
          <a href="<?= BASE_URL ?>?act=blog-delete&id=<?= $b['id_blog'] ?>" onclick="return confirmDelete()" class="btn btn-danger btn-sm">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
