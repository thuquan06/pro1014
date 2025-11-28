<?php
// views/guide/incidents/create.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-exclamation-triangle"></i> Tạo báo cáo sự cố</h3>
    <a href="?act=guide-incidents" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>
  <div class="card-body">
    <form method="POST" style="max-width: 800px;">
      <!-- Assignment Selection -->
      <?php if (empty($assignment)): ?>
        <div style="background: #fee2e2; padding: 20px; border-radius: 10px; margin-bottom: 24px; border-left: 4px solid var(--danger);">
          <p style="margin: 0; color: #991b1b;">
            <i class="fas fa-exclamation-circle"></i> Vui lòng chọn phân công để tạo báo cáo sự cố.
          </p>
        </div>
        
        <div style="margin-bottom: 24px;">
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Chọn phân công <span style="color: var(--danger);">*</span>
          </label>
          <select name="assignment_id" id="assignment_select" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;" onchange="window.location.href='?act=guide-incident-create&assignment_id=' + this.value">
            <option value="">-- Chọn phân công --</option>
            <?php if (!empty($assignments)): ?>
              <?php foreach ($assignments as $ass): ?>
                <option value="<?= $ass['id'] ?>">
                  <?= htmlspecialchars($ass['ten_tour'] ?? 'Tour #' . $ass['id']) ?> - 
                  KH: <?= $ass['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($ass['ngay_khoi_hanh'])) : 'N/A' ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <small style="color: var(--text-light); margin-top: 4px; display: block;">
            Hoặc <a href="?act=guide-assignments" style="color: var(--primary);">chọn từ danh sách phân công</a>
          </small>
        </div>
        
        <div style="text-align: center; padding: 40px; color: var(--text-light);">
          <i class="fas fa-info-circle" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
          <p>Vui lòng chọn phân công để tiếp tục</p>
        </div>
      <?php else: ?>
        <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
        <!-- Tour Info -->
        <div style="background: var(--bg-light); padding: 20px; border-radius: 10px; margin-bottom: 24px;">
          <h4 style="margin: 0 0 12px 0; color: var(--primary);">
            <i class="fas fa-map-marked-alt"></i> <?= htmlspecialchars($tour['tengoi'] ?? 'Tour') ?>
          </h4>
          <p style="margin: 0; color: var(--text-light); font-size: 14px;">
            Ngày khởi hành: <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : 'N/A' ?>
          </p>
        </div>

        <div style="display: grid; gap: 24px;">
          <!-- Ngày giờ xảy ra -->
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
            <div>
              <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
                Ngày xảy ra <span style="color: var(--danger);">*</span>
              </label>
              <input type="date" name="ngay_xay_ra" value="<?= date('Y-m-d') ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            </div>
            <div>
              <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
                Giờ xảy ra
              </label>
              <input type="time" name="gio_xay_ra" value="<?= date('H:i') ?>" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            </div>
          </div>

          <!-- Loại sự cố -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Loại sự cố <span style="color: var(--danger);">*</span>
            </label>
            <select name="loai_su_co" id="loai_su_co" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;" onchange="updateSuggestion()">
              <option value="">-- Chọn loại sự cố --</option>
              <?php foreach ($incidentTypes as $key => $label): ?>
                <option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <!-- Gợi ý xử lý tự động (sẽ hiển thị khi chọn loại sự cố) -->
          <div id="suggestion_box" style="display: none; background: #dbeafe; padding: 16px; border-left: 4px solid #3b82f6; border-radius: 8px; margin-top: -16px;">
            <h4 style="margin: 0 0 12px 0; color: #1e40af;">
              <i class="fas fa-lightbulb"></i> Gợi ý xử lý từ hệ thống
            </h4>
            <div id="suggestion_content"></div>
          </div>

          <!-- Mô tả -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Mô tả sự cố <span style="color: var(--danger);">*</span>
            </label>
            <textarea name="mo_ta" id="mo_ta" rows="6" placeholder="Mô tả chi tiết về sự cố đã xảy ra..." required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;" onkeyup="updateSuggestion()"></textarea>
            <small style="color: var(--text-light); margin-top: 4px; display: block;">
              <i class="fas fa-info-circle"></i> Hệ thống sẽ tự động đề xuất mức độ nghiêm trọng dựa trên mô tả của bạn
            </small>
          </div>
          
          <!-- Thông tin khách liên quan -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Thông tin khách liên quan
            </label>
            <textarea name="thong_tin_khach" rows="3" placeholder="Tên khách, số điện thoại, thông tin liên quan đến sự cố..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
          </div>
          
          <!-- Vị trí GPS -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Vị trí xảy ra sự cố
            </label>
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 12px;">
              <input type="text" name="vi_tri_gps" id="vi_tri_gps" placeholder="Địa chỉ hoặc tọa độ GPS (lat,lng)" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
              <button type="button" onclick="getCurrentLocation()" class="btn" style="background: var(--info); color: white; white-space: nowrap;">
                <i class="fas fa-map-marker-alt"></i> Lấy vị trí
              </button>
            </div>
            <small style="color: var(--text-light); margin-top: 4px; display: block;">
              <i class="fas fa-info-circle"></i> Cho phép trình duyệt truy cập vị trí để tự động điền
            </small>
          </div>
          
          <!-- Upload hình ảnh -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Hình ảnh đính kèm
            </label>
            <input type="file" name="hinh_anh[]" multiple accept="image/*" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <small style="color: var(--text-light); margin-top: 4px; display: block;">
              Có thể chọn nhiều ảnh (JPG, PNG, GIF, tối đa 5MB/ảnh). Ảnh sẽ được dùng làm bằng chứng.
            </small>
          </div>

          <!-- Cách xử lý -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Cách xử lý <span style="color: var(--danger);">*</span>
            </label>
            <textarea name="cach_xu_ly" rows="6" placeholder="Mô tả cách bạn đã xử lý sự cố này..." required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
          </div>

          <!-- Mức độ -->
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Mức độ nghiêm trọng <span style="color: var(--danger);">*</span>
            </label>
            <select name="muc_do" id="muc_do" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;" onchange="updateSuggestion()">
              <?php foreach ($severityLevels as $key => $level): ?>
                <option value="<?= $key ?>" <?= $key == 'thap' ? 'selected' : '' ?>>
                  <?= htmlspecialchars($level['label']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <small style="color: var(--text-light); margin-top: 4px; display: block;">
              <i class="fas fa-info-circle"></i> 
              Thấp: Sự cố nhỏ, không ảnh hưởng nhiều | 
              Trung bình: Sự cố có ảnh hưởng nhưng đã xử lý | 
              Cao: Sự cố nghiêm trọng, cần theo dõi (tự động gửi email) | 
              Nghiêm trọng: Sự cố rất nghiêm trọng, cần can thiệp ngay (tự động gửi email khẩn)
            </small>
          </div>

          <!-- Buttons -->
          <div style="display: flex; gap: 12px; margin-top: 8px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
              <i class="fas fa-save"></i> Lưu báo cáo
            </button>
            <a href="?act=guide-incidents" class="btn" style="background: var(--bg-light); color: var(--text-dark); padding: 10px 20px;">
              Hủy
            </a>
          </div>
        </div>
      <?php endif; ?>
    </form>
  </div>
</div>

<script>
// Lấy vị trí GPS hiện tại
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('vi_tri_gps').value = lat + ',' + lng;
                
                // Có thể reverse geocode để lấy địa chỉ
                // fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                //     .then(response => response.json())
                //     .then(data => {
                //         if (data.display_name) {
                //             document.getElementById('vi_tri_gps').value = data.display_name;
                //         }
                //     });
            },
            function(error) {
                alert('Không thể lấy vị trí. Vui lòng nhập thủ công.');
            }
        );
    } else {
        alert('Trình duyệt không hỗ trợ lấy vị trí.');
    }
}

// Cập nhật gợi ý xử lý tự động
function updateSuggestion() {
    const loaiSuCo = document.getElementById('loai_su_co').value;
    const mucDo = document.getElementById('muc_do').value;
    const moTa = document.getElementById('mo_ta').value;
    
    const suggestionBox = document.getElementById('suggestion_box');
    const suggestionContent = document.getElementById('suggestion_content');
    
    if (!loaiSuCo) {
        suggestionBox.style.display = 'none';
        return;
    }
    
    // Gọi AJAX để lấy gợi ý (hoặc có thể load từ PHP)
    // Tạm thời hiển thị thông báo
    suggestionBox.style.display = 'block';
    suggestionContent.innerHTML = '<p style="margin: 0; color: #1e40af;"><i class="fas fa-spinner fa-spin"></i> Đang tải gợi ý xử lý...</p>';
    
    // Nếu có mô tả, có thể đề xuất mức độ tự động
    if (moTa && moTa.length > 10) {
        // Có thể gọi API để đề xuất mức độ
        // Tạm thời chỉ hiển thị gợi ý dựa trên loại sự cố
    }
    
    // Load gợi ý từ server (cần tạo endpoint API)
    // fetch(`?act=api-incident-suggestion&loai=${loaiSuCo}&muc_do=${mucDo}`)
    //     .then(response => response.json())
    //     .then(data => {
    //         displaySuggestion(data);
    //     });
    
    // Tạm thời hiển thị thông báo
    setTimeout(() => {
        suggestionContent.innerHTML = `
            <p style="margin: 0 0 8px 0; font-weight: 600;">Gợi ý xử lý sẽ được hiển thị sau khi bạn chọn loại sự cố và mức độ.</p>
            <p style="margin: 0; font-size: 13px; color: #6b7280;">Hệ thống sẽ tự động đề xuất các bước xử lý chuẩn theo nghiệp vụ.</p>
        `;
    }, 500);
}

// Gọi updateSuggestion khi trang load nếu đã có giá trị
document.addEventListener('DOMContentLoaded', function() {
    const loaiSuCo = document.getElementById('loai_su_co');
    if (loaiSuCo && loaiSuCo.value) {
        updateSuggestion();
    }
});
</script>

