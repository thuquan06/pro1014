<style>
.blog-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.blog-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.blog-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.blog-table {
  width: 100%;
  border-collapse: collapse;
}

.blog-table thead {
  background: var(--bg-light);
}

.blog-table th {
  padding: 14px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.blog-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
  vertical-align: middle;
}

.blog-table tbody tr:hover {
  background: var(--bg-light);
}

.blog-title-cell {
  font-weight: 600;
  color: var(--text-dark);
  max-width: 300px;
}

.blog-summary {
  max-width: 250px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  color: var(--text-light);
  font-size: 13px;
}

.blog-content {
  max-width: 300px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  color: var(--text-light);
  font-size: 13px;
}

.btn-action {
  padding: 6px 12px;
  border: none;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-right: 4px;
}

.btn-action.edit {
  background: #fef3c7;
  color: #78350f;
}

.btn-action.edit:hover {
  background: #f59e0b;
  color: white;
}

.btn-action.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action.delete:hover {
  background: #ef4444;
  color: white;
}

.empty-state {
  text-align: center;
  padding: 80px 20px;
  color: var(--text-light);
}

.empty-state i {
  font-size: 80px;
  opacity: 0.3;
  margin-bottom: 20px;
}

.empty-state h3 {
  font-size: 20px;
  margin: 20px 0 12px;
  color: var(--text-dark);
}
</style>

<!-- Page Header -->
<div class="blog-header">
  <h1 class="blog-title">
    <i class="fas fa-blog" style="color: var(--primary);"></i>
    Quản lý Blog
  </h1>
  <a href="<?= BASE_URL ?>?act=blog-create" class="btn btn-primary">
    <i class="fas fa-plus-circle"></i>
    Tạo blog mới
  </a>
</div>

<!-- Table -->
<div class="blog-card">
  <?php if (empty($blogs)): ?>
    <div class="empty-state">
      <i class="fas fa-blog"></i>
      <h3>Chưa có bài viết nào</h3>
      <p>Hãy tạo bài viết đầu tiên</p>
      <br>
      <a href="<?= BASE_URL ?>?act=blog-create" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i>
        Tạo blog mới
      </a>
    </div>
  <?php else: ?>
    <table class="blog-table">
      <thead>
        <tr>
          <th width="60">STT</th>
          <th>Chủ đề</th>
          <th>Người viết</th>
          <th>Tóm tắt</th>
          <th width="150">Ngày đăng</th>
          <th width="150">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($blogs as $b): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td>
              <div class="blog-title-cell"><?= htmlspecialchars($b['chude']) ?></div>
            </td>
            <td><?= htmlspecialchars($b['nguoiviet']) ?></td>
            <td>
              <div class="blog-summary"><?= htmlspecialchars($b['tomtat']) ?></div>
            </td>
            <td><?= date("d/m/Y", strtotime($b['ngaydang'])) ?></td>
            <td>
              <a href="<?= BASE_URL ?>?act=blog-edit&id=<?= $b['id_blog'] ?>" class="btn-action edit">
                <i class="fas fa-edit"></i>
              </a>
              <a href="<?= BASE_URL ?>?act=blog-delete&id=<?= $b['id_blog'] ?>" 
                 onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')" 
                 class="btn-action delete">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
