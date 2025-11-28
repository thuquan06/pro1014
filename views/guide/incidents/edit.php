<?php
// views/guide/incidents/edit.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-edit"></i> Chỉnh sửa báo cáo sự cố</h3>
    <a href="?act=guide-incident-detail&id=<?= $incident['id'] ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>
  <div class="card-body">
    <!-- Tour Info -->
    <div style="background: var(--bg-light); padding: 20px; border-radius: 10px; margin-bottom: 24px;">
      <h4 style="margin: 0 0 12px 0; color: var(--primary);">
        <i class="fas fa-map-marked-alt"></i> <?= htmlspecialchars($tour['tengoi'] ?? 'Tour') ?>
      </h4>
      <p style="margin: 0; color: var(--text-light); font-size: 14px;">
        Ngày khởi hành: <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : 'N/A' ?>
      </p>
    </div>

    <form method="POST" style="max-width: 800px;">
      <div style="display: grid; gap: 24px;">
        <!-- Ngày xảy ra -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ngày xảy ra <span style="color: var(--danger);">*</span>
          </label>
          <input type="date" name="ngay_xay_ra" value="<?= htmlspecialchars($incident['ngay_xay_ra'] ?? date('Y-m-d')) ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Loại sự cố -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Loại sự cố <span style="color: var(--danger);">*</span>
          </label>
          <select name="loai_su_co" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">-- Chọn loại sự cố --</option>
            <?php foreach ($incidentTypes as $key => $label): ?>
              <option value="<?= $key ?>" <?= (isset($incident['loai_su_co']) && $incident['loai_su_co'] == $key) ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Mô tả -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Mô tả sự cố <span style="color: var(--danger);">*</span>
          </label>
          <textarea name="mo_ta" rows="6" placeholder="Mô tả chi tiết về sự cố đã xảy ra..." required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= htmlspecialchars($incident['mo_ta'] ?? '') ?></textarea>
        </div>

        <!-- Cách xử lý -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Cách xử lý <span style="color: var(--danger);">*</span>
          </label>
          <textarea name="cach_xu_ly" rows="6" placeholder="Mô tả cách bạn đã xử lý sự cố này..." required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= htmlspecialchars($incident['cach_xu_ly'] ?? '') ?></textarea>
        </div>

        <!-- Mức độ -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Mức độ nghiêm trọng <span style="color: var(--danger);">*</span>
          </label>
          <select name="muc_do" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <?php foreach ($severityLevels as $key => $level): ?>
              <option value="<?= $key ?>" <?= (isset($incident['muc_do']) && $incident['muc_do'] == $key) ? 'selected' : '' ?>>
                <?= htmlspecialchars($level['label']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small style="color: var(--text-light); margin-top: 4px; display: block;">
            <i class="fas fa-info-circle"></i> 
            Thấp: Sự cố nhỏ, không ảnh hưởng nhiều | 
            Trung bình: Sự cố có ảnh hưởng nhưng đã xử lý | 
            Cao: Sự cố nghiêm trọng, cần theo dõi | 
            Nghiêm trọng: Sự cố rất nghiêm trọng, cần can thiệp ngay
          </small>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 12px; margin-top: 8px;">
          <button type="submit" class="btn btn-primary" style="flex: 1;">
            <i class="fas fa-save"></i> Cập nhật báo cáo
          </button>
          <a href="?act=guide-incident-detail&id=<?= $incident['id'] ?>" class="btn" style="background: var(--bg-light); color: var(--text-dark); padding: 10px 20px;">
            Hủy
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

