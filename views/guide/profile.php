<?php
// views/guide/profile.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-user"></i> Thông tin cá nhân</h3>
    <button onclick="toggleEditMode()" class="btn btn-primary btn-sm" id="editBtn">
      <i class="fas fa-edit"></i> Chỉnh sửa
    </button>
  </div>
  <div class="card-body">
    <!-- View Mode -->
    <div id="viewMode">
      <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
        <div style="position: relative;">
          <div class="avatar-large" style="width: 120px; height: 120px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 700; border: 4px solid var(--bg-light);">
            <?php 
            $guideName = $guide['ho_ten'] ?? 'G';
            echo strtoupper(substr($guideName, 0, 1)); 
            ?>
          </div>
          <?php if (!empty($guide['anh_dai_dien'])): ?>
            <img src="<?= BASE_URL . htmlspecialchars($guide['anh_dai_dien']) ?>" alt="Avatar" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--bg-light); position: absolute; top: 0; left: 0;">
          <?php endif; ?>
        </div>
        <div style="flex: 1;">
          <h2 style="font-size: 28px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">
            <?= htmlspecialchars($guide['ho_ten'] ?? 'N/A') ?>
          </h2>
          <p style="color: var(--text-light); margin: 0;">
            <i class="fas fa-envelope"></i> <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
          </p>
        </div>
      </div>
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số điện thoại</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
            <?= htmlspecialchars($guide['so_dien_thoai'] ?? 'Chưa cập nhật') ?>
          </p>
        </div>
        
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Địa chỉ</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
            <?= htmlspecialchars($guide['dia_chi'] ?? 'Chưa cập nhật') ?>
          </p>
        </div>
        
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Kinh nghiệm</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
            <?= htmlspecialchars($guide['kinh_nghiem'] ?? 0) ?> năm
          </p>
        </div>
        
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Đánh giá</strong>
          <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--warning);">
            <?php if (!empty($guide['danh_gia'])): ?>
              <?= number_format($guide['danh_gia'], 1) ?> 
              <i class="fas fa-star" style="color: #f59e0b;"></i>
            <?php else: ?>
              Chưa có đánh giá
            <?php endif; ?>
          </p>
        </div>
        
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Trạng thái</strong>
          <p style="margin-top: 4px;">
            <?php if ($guide['trang_thai'] == 1): ?>
              <span style="background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">
                <i class="fas fa-check-circle"></i> Hoạt động
              </span>
            <?php else: ?>
              <span style="background: #fee2e2; color: #991b1b; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">
                <i class="fas fa-times-circle"></i> Tạm dừng
              </span>
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
    
    <!-- Edit Mode -->
    <div id="editMode" style="display: none;">
      <form method="POST" action="?act=guide-profile-update" enctype="multipart/form-data" id="profileForm">
        <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
          <div style="position: relative;">
            <div class="avatar-large" id="avatarPreview" style="width: 120px; height: 120px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 700; border: 4px solid var(--bg-light); cursor: pointer; position: relative;">
              <?php 
              $guideName = $guide['ho_ten'] ?? 'G';
              echo strtoupper(substr($guideName, 0, 1)); 
              ?>
              <?php if (!empty($guide['anh_dai_dien'])): ?>
                <img src="<?= BASE_URL . htmlspecialchars($guide['anh_dai_dien']) ?>" alt="Avatar" id="avatarImg" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--bg-light); position: absolute; top: 0; left: 0;">
              <?php endif; ?>
              <div style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white;">
                <i class="fas fa-camera"></i>
              </div>
            </div>
            <input type="file" name="anh_dai_dien" id="avatarInput" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
          </div>
          <div style="flex: 1;">
            <div class="form-group-modern">
              <label>Họ tên <span style="color: #ef4444;">*</span></label>
              <input type="text" name="ho_ten" value="<?= htmlspecialchars($guide['ho_ten'] ?? '') ?>" required>
            </div>
          </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
          <div class="form-group-modern">
            <label>Số điện thoại</label>
            <input type="tel" name="so_dien_thoai" value="<?= htmlspecialchars($guide['so_dien_thoai'] ?? '') ?>">
          </div>
          
          <div class="form-group-modern">
            <label>Địa chỉ</label>
            <textarea name="dia_chi" rows="3"><?= htmlspecialchars($guide['dia_chi'] ?? '') ?></textarea>
          </div>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Lưu thay đổi
          </button>
          <button type="button" class="btn" style="background: var(--bg-light); color: var(--text-dark);" onclick="toggleEditMode()">
            <i class="fas fa-times"></i> Hủy
          </button>
        </div>
      </form>
    </div>

    <?php if (!empty($guide['ky_nang']) && is_array($guide['ky_nang']) && count($guide['ky_nang']) > 0): ?>
      <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-star" style="color: var(--primary);"></i> Kỹ năng
        </h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
          <?php foreach ($guide['ky_nang'] as $skill): ?>
            <span style="background: #d1fae5; color: #065f46; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;">
              <?= htmlspecialchars($skill) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($guide['tuyen_chuyen']) && is_array($guide['tuyen_chuyen']) && count($guide['tuyen_chuyen']) > 0): ?>
      <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-route" style="color: var(--primary);"></i> Tuyến chuyên
        </h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
          <?php foreach ($guide['tuyen_chuyen'] as $route): ?>
            <span style="background: #dbeafe; color: #1e40af; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;">
              <?= htmlspecialchars($route) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($guide['ngon_ngu']) && is_array($guide['ngon_ngu']) && count($guide['ngon_ngu']) > 0): ?>
      <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-language" style="color: var(--primary);"></i> Ngôn ngữ
        </h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
          <?php foreach ($guide['ngon_ngu'] as $lang): ?>
            <span style="background: #e9d5ff; color: #9333ea; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;">
              <?= htmlspecialchars($lang) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($guide['ghi_chu'])): ?>
      <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-sticky-note" style="color: var(--primary);"></i> Ghi chú
        </h4>
        <p style="color: var(--text-dark); line-height: 1.6; padding: 16px; background: var(--bg-light); border-radius: 8px;">
          <?= nl2br(htmlspecialchars($guide['ghi_chu'])) ?>
        </p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Đổi mật khẩu -->
<div class="card" style="margin-top: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-lock"></i> Đổi mật khẩu</h3>
  </div>
  <div class="card-body">
    <form id="changePasswordForm">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div class="form-group-modern">
          <label>Mật khẩu hiện tại <span style="color: #ef4444;">*</span></label>
          <input type="password" id="currentPassword" required placeholder="Nhập CMND/CCCD hoặc số điện thoại">
        </div>
        
        <div class="form-group-modern">
          <label>Mật khẩu mới <span style="color: #ef4444;">*</span></label>
          <input type="password" id="newPassword" required minlength="6" placeholder="Tối thiểu 6 ký tự">
        </div>
        
        <div class="form-group-modern">
          <label>Xác nhận mật khẩu mới <span style="color: #ef4444;">*</span></label>
          <input type="password" id="confirmPassword" required minlength="6" placeholder="Nhập lại mật khẩu mới">
        </div>
      </div>
      
      <div style="margin-top: 24px;">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-key"></i> Đổi mật khẩu
        </button>
      </div>
    </form>
  </div>
</div>

<style>
.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
}

.form-group-modern input,
.form-group-modern textarea {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-group-modern input:focus,
.form-group-modern textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

#avatarPreview:hover {
  opacity: 0.9;
}

@media (max-width: 768px) {
  .card-body > div {
    grid-template-columns: 1fr !important;
  }
  
  .avatar-large {
    width: 100px !important;
    height: 100px !important;
    font-size: 40px !important;
  }
}
</style>

<script>
let editMode = false;

function toggleEditMode() {
  editMode = !editMode;
  document.getElementById('viewMode').style.display = editMode ? 'none' : 'block';
  document.getElementById('editMode').style.display = editMode ? 'block' : 'none';
  document.getElementById('editBtn').innerHTML = editMode 
    ? '<i class="fas fa-times"></i> Hủy' 
    : '<i class="fas fa-edit"></i> Chỉnh sửa';
}

document.getElementById('avatarPreview').addEventListener('click', function() {
  document.getElementById('avatarInput').click();
});

function previewAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      let img = document.getElementById('avatarImg');
      if (!img) {
        img = document.createElement('img');
        img.id = 'avatarImg';
        img.style.cssText = 'width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--bg-light); position: absolute; top: 0; left: 0;';
        document.getElementById('avatarPreview').appendChild(img);
      }
      img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Đổi mật khẩu
document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const currentPassword = document.getElementById('currentPassword').value;
  const newPassword = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  
  if (newPassword !== confirmPassword) {
    alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
    return;
  }
  
  if (newPassword.length < 6) {
    alert('Mật khẩu mới phải có ít nhất 6 ký tự!');
    return;
  }
  
  try {
    const response = await fetch('?act=guide-change-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        current_password: currentPassword,
        new_password: newPassword
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      alert(result.message);
      document.getElementById('changePasswordForm').reset();
    } else {
      alert(result.message);
    }
  } catch (error) {
    alert('Có lỗi xảy ra. Vui lòng thử lại.');
    console.error(error);
  }
});
</script>


