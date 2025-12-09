<?php
/**
 * Sửa Lịch khởi hành - Modern Interface
 * UC-Departure-Plan: Cập nhật lịch khởi hành
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$assignments = $assignments ?? [];
$guides = $guides ?? [];
?>

<style>
.departure-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.departure-form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 2px solid var(--bg-light);
}

.card-header i {
  font-size: 20px;
  color: var(--primary);
}

.card-header h3 {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group-modern.full-width {
  grid-column: 1 / -1;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
}

.form-group-modern label .required {
  color: #ef4444;
  margin-left: 4px;
}

.form-group-modern input,
.form-group-modern select,
.form-group-modern textarea {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-group-modern input:focus,
.form-group-modern select:focus,
.form-group-modern textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group-modern select:disabled {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: none;
  cursor: not-allowed;
}

.form-group-modern textarea {
  resize: vertical;
  min-height: 100px;
}

.form-group-modern .help-text {
  font-size: 12px;
  color: var(--text-light);
  margin-top: -4px;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 20px;
}

.btn-submit {
  padding: 12px 32px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-submit:hover {
  background: #1e40af;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-cancel {
  padding: 12px 32px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-cancel:hover {
  background: var(--bg-light);
  border-color: var(--text-light);
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.alert-error {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>

<!-- Page Header -->
<div class="departure-form-header">
  <h1 class="departure-form-title">
    <i class="fas fa-calendar-edit" style="color: var(--primary);"></i>
    Sửa lịch trình
  </h1>
</div>

<?php if (!$departurePlan): ?>
  <div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    Không tìm thấy lịch khởi hành
  </div>
<?php else: ?>
  <!-- Form -->
  <form method="POST" action="<?= BASE_URL ?>?act=admin-departure-plan-update">
    <input type="hidden" name="id" value="<?= $departurePlan['id'] ?>">
    <?php if (isset($tourId)): ?>
      <input type="hidden" name="id_tour" value="<?= $tourId ?>">
    <?php endif; ?>
    
    <div class="form-card">
      <div class="card-header">
        <i class="fas fa-info-circle"></i>
        <h3>Thông tin cơ bản</h3>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Tour <span class="required">*</span>
          </label>
          <?php if ($departurePlan['id_tour']): ?>
            <!-- Nếu đã có tour, chỉ hiển thị tour đó và disable select -->
            <?php 
            $currentTour = null;
            foreach ($tours as $tour) {
              if ($tour['id_goi'] == $departurePlan['id_tour']) {
                $currentTour = $tour;
                break;
              }
            }
            ?>
            <select name="id_tour" required disabled style="background: #f3f4f6; cursor: not-allowed; appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;">
              <option value="<?= $currentTour['id_goi'] ?? $departurePlan['id_tour'] ?>" selected>
                <?= safe_html($currentTour['tengoi'] ?? 'Tour không tồn tại') ?> 
                <?php if (!empty($currentTour['ngayxuatphat'])): ?>
                  (<?= date('d/m/Y', strtotime($currentTour['ngayxuatphat'])) ?>)
                <?php endif; ?>
              </option>
            </select>
            <input type="hidden" name="id_tour" value="<?= $departurePlan['id_tour'] ?>">
          <?php else: ?>
            <!-- Nếu chưa có tour, cho phép chọn -->
            <select name="id_tour" required>
              <option value="">-- Chọn tour --</option>
              <?php foreach ($tours as $tour): ?>
                <option value="<?= $tour['id_goi'] ?>" 
                        <?= ($departurePlan['id_tour'] == $tour['id_goi']) ? 'selected' : '' ?>>
                  <?= safe_html($tour['tengoi']) ?> 
                  <?php if (!empty($tour['ngayxuatphat'])): ?>
                    (<?= date('d/m/Y', strtotime($tour['ngayxuatphat'])) ?>)
                  <?php endif; ?>
                </option>
              <?php endforeach; ?>
            </select>
          <?php endif; ?>
        </div>

        <div class="form-group-modern">
          <label>
            Trạng thái <span class="required">*</span>
          </label>
          <select name="trang_thai" required>
            <option value="1" <?= ($departurePlan['trang_thai'] == 1) ? 'selected' : '' ?>>
              Mở bán
            </option>
            <option value="0" <?= ($departurePlan['trang_thai'] == 0) ? 'selected' : '' ?>>
              Đóng
            </option>
            <option value="2" <?= ($departurePlan['trang_thai'] == 2) ? 'selected' : '' ?>>
              Hết chỗ
            </option>
            <option value="3" <?= ($departurePlan['trang_thai'] == 3) ? 'selected' : '' ?>>
              Gần đầy
            </option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Ngày khởi hành <span class="required">*</span>
          </label>
          <input type="date" 
                 name="ngay_khoi_hanh" 
                 value="<?= safe_html($departurePlan['ngay_khoi_hanh'] ?? '') ?>" 
                 required>
        </div>

        <div class="form-group-modern">
          <label>
            Ngày kết thúc
          </label>
          <input type="date" 
                 name="ngay_ket_thuc" 
                 value="<?= safe_html($departurePlan['ngay_ket_thuc'] ?? '') ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Giờ khởi hành <span class="required">*</span>
          </label>
          <input type="time" 
                 name="gio_khoi_hanh" 
                 value="<?= safe_html($departurePlan['gio_khoi_hanh'] ?? '') ?>" 
                 required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Giờ tập trung <span class="required">*</span>
          </label>
          <input type="time" 
                 name="gio_tap_trung" 
                 value="<?= safe_html($departurePlan['gio_tap_trung'] ?? '') ?>" 
                 required>
          <span class="help-text">Chọn giờ tập trung (ví dụ: 07:30)</span>
        </div>

        <div class="form-group-modern">
          <label>
            Điểm tập trung <span class="required">*</span>
          </label>
          <input type="text" 
                 name="diem_tap_trung" 
                 value="<?= safe_html($departurePlan['diem_tap_trung'] ?? '') ?>" 
                 placeholder="Ví dụ: Sân bay Nội Bài, Hà Nội"
                 required>
          <span class="help-text">Địa điểm tập trung trước khi khởi hành</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Số chỗ tối đa <span class="required">*</span>
          </label>
          <input type="number" 
                 name="so_cho" 
                 id="so_cho"
                 value="<?= safe_html($departurePlan['so_cho'] ?? ($departurePlan['so_cho_con_trong'] ?? '')) ?>" 
                 placeholder="Ví dụ: 30"
                 min="1"
                 required
                 onchange="calculateRemainingSeats()">
          <span class="help-text">Tổng số chỗ tối đa cho lịch khởi hành này</span>
        </div>

        <div class="form-group-modern">
          <label>
            Số chỗ đã đặt
          </label>
          <input type="number" 
                 name="so_cho_da_dat" 
                 id="so_cho_da_dat"
                 value="<?= safe_html($departurePlan['so_cho_da_dat'] ?? '0') ?>" 
                 placeholder="0"
                 min="0"
                 onchange="calculateRemainingSeats()">
          <span class="help-text">Số chỗ đã có người đặt</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Số chỗ còn lại
          </label>
          <input type="number" 
                 name="so_cho_con_lai" 
                 id="so_cho_con_lai"
                 value="<?= safe_html($departurePlan['so_cho_con_lai'] ?? ($departurePlan['so_cho_con_trong'] ?? '')) ?>" 
                 placeholder="Tự động tính"
                 min="0"
                 readonly>
          <span class="help-text">Số chỗ còn trống (tự động = Số chỗ tối đa - Số chỗ đã đặt)</span>
        </div>

        <div class="form-group-modern">
          <label>
            Phương tiện <span class="required">*</span>
          </label>
          <input type="text" 
                 name="phuong_tien" 
                 value="<?= safe_html($departurePlan['phuong_tien'] ?? '') ?>" 
                 placeholder="Ví dụ: Xe khách, Máy bay"
                 required>
          <span class="help-text">Phương tiện di chuyển</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Giá người lớn
          </label>
          <input type="number" 
                 name="gia_nguoi_lon" 
                 value="<?= safe_html($departurePlan['gia_nguoi_lon'] ?? '') ?>" 
                 placeholder="Ví dụ: 2000000"
                 min="0"
                 step="1000">
          <span class="help-text">Giá cho người lớn</span>
        </div>

        <div class="form-group-modern">
          <label>
            Giá trẻ em
          </label>
          <input type="number" 
                 name="gia_tre_em" 
                 value="<?= safe_html($departurePlan['gia_tre_em'] ?? '') ?>" 
                 placeholder="Ví dụ: 1500000"
                 min="0"
                 step="1000">
          <span class="help-text">Giá cho trẻ em</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label>
            Giá trẻ nhỏ
          </label>
          <input type="number" 
                 name="gia_tre_nho" 
                 value="<?= safe_html($departurePlan['gia_tre_nho'] ?? '') ?>" 
                 placeholder="Ví dụ: 1000000"
                 min="0"
                 step="1000">
          <span class="help-text">Giá cho trẻ nhỏ</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern full-width">
          <label>
            Ghi chú nội bộ
          </label>
          <textarea name="ghi_chu" 
                    placeholder="Nhập các ghi chú nội bộ..."><?= safe_html($departurePlan['ghi_chu'] ?? '') ?></textarea>
          <span class="help-text">Ghi chú nội bộ cho nhân viên</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern full-width">
          <label>
            Ghi chú vận hành
          </label>
          <textarea name="ghi_chu_van_hanh" 
                    placeholder="Nhập các ghi chú về vận hành, lưu ý đặc biệt..."><?= safe_html($departurePlan['ghi_chu_van_hanh'] ?? '') ?></textarea>
          <span class="help-text">Các ghi chú về vận hành, lưu ý đặc biệt cho tour này</span>
        </div>
      </div>
    </div>

    <!-- Phân công Hướng dẫn viên -->
    <div class="form-card">
      <div class="card-header">
        <i class="fas fa-user-tie"></i>
        <h3>Phân công Hướng dẫn viên</h3>
      </div>
      
      <div id="hdv-assignments-container">
        <!-- HDV assignments will be added here -->
      </div>
      
      <div style="margin-top: 16px;">
        <button type="button" id="add-hdv-btn" class="btn btn-primary" style="padding: 10px 20px;">
          <i class="fas fa-plus"></i> Thêm HDV
        </button>
      </div>
    </div>

    <!-- Lịch trình tour -->
    <div class="form-card">
      <div class="card-header">
        <i class="fas fa-route"></i>
        <h3>Lịch trình tour</h3>
      </div>
      <div class="form-row">
        <div class="form-group-modern full-width">
          <label>
            Lịch trình tour
          </label>
          
          <!-- Day Builder Interface -->
          <div id="itinerary-builder" style="margin-bottom: 16px;">
            <div style="margin-bottom: 16px;">
              <button type="button" id="add-day-btn" class="btn btn-primary" style="padding: 10px 20px;">
                <i class="fas fa-plus"></i> Thêm ngày
              </button>
            </div>
            <div id="days-container">
              <!-- Days will be added here -->
            </div>
          </div>
          
          <!-- Hidden textarea để lưu HTML cuối cùng -->
          <textarea name="chuongtrinh" id="chuongtrinh-hidden" style="display: none;"><?= htmlspecialchars($departurePlan['chuongtrinh'] ?? '', ENT_NOQUOTES, 'UTF-8') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
      <a href="<?= BASE_URL ?>?act=admin-departure-plans<?= isset($tourId) && $tourId ? '&tour_id=' . $tourId : '' ?>" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy
      </a>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i>
        Cập nhật lịch trình
      </button>
    </div>
  </form>
<?php endif; ?>

<!-- Itinerary Builder -->
<script src="assets/ckeditor/ckeditor.js"></script>
<script>
// Itinerary Day Builder (same as create form)
let dayCounter = 0;
let dayEditors = {};

const dayEditorConfig = {
    height: 300,
    filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: 'assets/ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
};

// Helpers
function htmlToPlain(html) {
    if (!html) return '';
    html = html.replace(/<\s*br\s*\/?>/gi, '\n');
    html = html.replace(/<\/p>/gi, '\n');
    const tmp = document.createElement('textarea');
    tmp.innerHTML = html;
    return tmp.value;
}

function escapeHtml(str) {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function addDay(dayTitle = '', dayContent = '') {
    dayCounter++;
    const dayId = 'day_' + dayCounter;
    const editorId = 'day_editor_' + dayCounter;
    
    // Escape HTML cho title input (chỉ cần escape quotes)
    const escapedTitle = (dayTitle || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    
    const dayHtml = `
        <div class="day-item" id="${dayId}" style="margin-bottom: 20px; padding: 20px; border: 2px solid var(--border); border-radius: 8px; background: #f9fafb;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 style="margin: 0; color: var(--primary); font-size: 16px;">
                    <i class="fas fa-calendar-day"></i> Ngày ${dayCounter}
                </h4>
                <button type="button" onclick="removeDay(${dayCounter})" class="btn btn-sm" style="background: #ef4444; color: white; padding: 6px 12px;">
                    <i class="fas fa-times"></i> Xóa
                </button>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: var(--text-dark);">Tiêu đề ngày (tùy chọn)</label>
                <input type="text" class="day-title-input" data-day="${dayCounter}" placeholder="Ví dụ: Khởi hành, Tham quan thành phố..." 
                       value="${escapedTitle}"
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: var(--text-dark);">Nội dung</label>
                <textarea class="day-content-editor" id="${editorId}" data-day="${dayCounter}" style="width: 100%; min-height: 250px;"></textarea>
            </div>
        </div>
    `;
    
    const daysContainer = document.getElementById('days-container');
    if (daysContainer) {
        daysContainer.insertAdjacentHTML('beforeend', dayHtml);
        
        setTimeout(() => {
            dayEditors[dayCounter] = CKEDITOR.replace(editorId, dayEditorConfig);
            // Set data sau khi editor ready
            if (dayContent && dayContent.trim()) {
                dayEditors[dayCounter].on('instanceReady', function() {
                    // Clean content trước khi set vào editor - loại bỏ ký tự lạ
                    let cleanContent = dayContent;
                    // Loại bỏ header "NGÀY X" nếu có trong content
                    cleanContent = cleanContent.replace(/<h[1-6][^>]*>\s*<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>\s*<\/h[1-6]>/gi, '');
                    cleanContent = cleanContent.replace(/<p[^>]*>\s*<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>\s*<\/p>/gi, '');
                    cleanContent = cleanContent.replace(/<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>/gi, '');
                    // Loại bỏ các ký tự lạ như "Â", "Ã", "DY" đơn lẻ
                    cleanContent = cleanContent.replace(/^[ÂâÃã]\s*$/gm, '').trim();
                    cleanContent = cleanContent.replace(/^\s*[ÂâÃã]\s*$/gm, '').trim();
                    cleanContent = cleanContent.replace(/^DY\s*$/gm, '').trim();
                    // Set data trực tiếp vào CKEditor (CKEditor sẽ tự xử lý HTML)
                    this.setData(cleanContent);
                });
            }
        }, 300);
    }
}

function removeDay(dayNum) {
    if (confirm('Bạn có chắc chắn muốn xóa ngày này?')) {
        const dayId = 'day_' + dayNum;
        const dayElement = document.getElementById(dayId);
        
        if (dayElement) {
            if (dayEditors[dayNum]) {
                dayEditors[dayNum].destroy();
                delete dayEditors[dayNum];
            }
            dayElement.remove();
            updateDayNumbers();
        }
    }
}

function updateDayNumbers() {
    const dayItems = document.querySelectorAll('.day-item');
    dayItems.forEach((item, index) => {
        const newDayNum = index + 1;
        const dayNumAttr = item.getAttribute('id').replace('day_', '');
        const titleInput = item.querySelector('.day-title-input');
        const contentTextarea = item.querySelector('.day-content-editor');
        const header = item.querySelector('h4');
        
        if (header) {
            header.innerHTML = `<i class="fas fa-calendar-day"></i> Ngày ${newDayNum}`;
        }
        
        if (titleInput) {
            titleInput.dataset.day = newDayNum;
        }
        
        if (contentTextarea) {
            contentTextarea.dataset.day = newDayNum;
        }
        
        const removeBtn = item.querySelector('button');
        if (removeBtn) {
            removeBtn.setAttribute('onclick', `removeDay(${newDayNum})`);
        }
    });
    dayCounter = dayItems.length;
}

function buildItineraryHTML() {
    let html = '';
    const dayItems = document.querySelectorAll('.day-item');
    
    dayItems.forEach((item, index) => {
        const dayNum = index + 1;
        const titleInput = item.querySelector('.day-title-input');
        const contentTextarea = item.querySelector('.day-content-editor');
        const dayNumAttr = contentTextarea ? parseInt(contentTextarea.dataset.day) : dayNum;
        
        const title = titleInput ? titleInput.value.trim() : '';
        let content = '';
        
        if (dayEditors[dayNumAttr]) {
            content = dayEditors[dayNumAttr].getData();
        } else if (contentTextarea) {
            content = contentTextarea.value;
        }
        
        if (content.trim()) {
            // Clean content - loại bỏ header "NGÀY X" nếu có trong content (tránh duplicate)
            let cleanContent = content;
            cleanContent = cleanContent.replace(/<h[1-6][^>]*>\s*<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>\s*<\/h[1-6]>/gi, '');
            cleanContent = cleanContent.replace(/<p[^>]*>\s*<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>\s*<\/p>/gi, '');
            cleanContent = cleanContent.replace(/<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>/gi, '');
            cleanContent = cleanContent.trim();
            
            if (cleanContent) {
                let dayHeader = '';
                if (title) {
                    dayHeader = `<h3><strong>NGÀY ${dayNum}: ${title.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</strong></h3>`;
                } else {
                    dayHeader = `<h3><strong>NGÀY ${dayNum}</strong></h3>`;
                }
                
                html += dayHeader + cleanContent;
            }
        }
    });
    
    return html;
}

function parseAndLoadExistingItinerary(html) {
    if (!html || !html.trim()) return;
    
    // Destroy all existing editors first
    Object.keys(dayEditors).forEach(dayNum => {
        if (dayEditors[dayNum]) {
            dayEditors[dayNum].destroy();
        }
    });
    dayEditors = {};
    dayCounter = 0;
    const daysContainer = document.getElementById('days-container');
    if (daysContainer) {
        daysContainer.innerHTML = '';
    }
    
    // Parse HTML to extract days - improved regex (hỗ trợ cả NGÀY và NGÃY do encoding)
    const regex = /<h[1-6][^>]*>\s*<strong[^>]*>\s*NG[ÀÃ]Y\s*(\d+)(?::\s*([^<]+))?\s*<\/strong>\s*<\/h[1-6]>/gi;
    const daySections = [];
    const matches = [];
    let match;
    
    // Collect all matches first
    while ((match = regex.exec(html)) !== null) {
        let title = match[2] ? match[2].trim() : '';
        // Decode HTML entities trong title
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = title;
        title = tempDiv.textContent || tempDiv.innerText || title;
        
        matches.push({
            dayNum: parseInt(match[1]),
            title: title,
            startPos: match.index,
            headerLength: match[0].length
        });
    }
    
    // Extract content for each day
    for (let i = 0; i < matches.length; i++) {
        const currentMatch = matches[i];
        const nextMatch = matches[i + 1];
        
        const contentStart = currentMatch.startPos + currentMatch.headerLength;
        const contentEnd = nextMatch ? nextMatch.startPos : html.length;
        let dayContent = html.substring(contentStart, contentEnd).trim();
        
        // Loại bỏ header "NGÀY X" hoặc các biến thể encode sai nếu còn sót trong content (tránh duplicate khi build lại)
        // Loại bỏ cả các pattern khác nhau của header và phiên bản plain-text không tag
        dayContent = dayContent.replace(/<h[1-6][^>]*>\s*<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>\s*<\/h[1-6]>/gi, '');
        dayContent = dayContent.replace(/<p[^>]*>\s*<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>\s*<\/p>/gi, '');
        dayContent = dayContent.replace(/<strong[^>]*>\s*NG[ÀÃ]Y\s*\d+[^<]*\s*<\/strong>/gi, '');
        // Plain text header dòng đơn (kể cả sai encode: NGÃ̀Y, NGÃY, NGAY)
        dayContent = dayContent.replace(/^\s*NG\S*Y\s*\d+[^\r\n]*$/gim, '');
        
        // Loại bỏ các ký tự lạ như "Â", "Ã", "DY" đơn lẻ (có thể là phần còn lại của "NGÀY" bị decode sai)
        dayContent = dayContent.replace(/^[ÂâÃã\xa0]\s*$/gim, '').trim();
        dayContent = dayContent.replace(/^\s*[ÂâÃã\xa0]\s*$/gim, '').trim();
        dayContent = dayContent.replace(/^DY\s*$/gim, '').trim();
        // Loại bỏ các dòng chỉ có ký tự đơn lẻ hoặc whitespace
        dayContent = dayContent.replace(/^[^\w\s<>&;]{1,2}$/gm, '').trim();
        dayContent = dayContent.trim();
        
        daySections.push({
            dayNum: currentMatch.dayNum,
            title: currentMatch.title,
            content: dayContent
        });
    }
    
    // Load days into editor
    if (daySections.length > 0) {
        // Sort by day number to ensure correct order
        daySections.sort((a, b) => a.dayNum - b.dayNum);
        daySections.forEach(section => {
            addDay(section.title, section.content);
        });
    } else if (html.trim()) {
        // If no day headers found, treat entire content as one day
        addDay('', html);
    }
}

// HDV Assignment Management
let hdvAssignmentCount = 0;
const selectedHdvIds = new Set();

function addHdvAssignment(hdvId = '', vaiTro = '', luong = '', ghiChu = '') {
    hdvAssignmentCount++;
    const container = document.getElementById('hdv-assignments-container');
    
    const hdvRow = document.createElement('div');
    hdvRow.className = 'hdv-assignment-row';
    hdvRow.style.cssText = 'display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr auto; gap: 12px; align-items: end; margin-bottom: 16px; padding: 16px; background: #f9fafb; border-radius: 8px;';
    hdvRow.id = `hdv-row-${hdvAssignmentCount}`;
    
    hdvRow.innerHTML = `
        <div class="form-group-modern">
            <label>Hướng dẫn viên <span class="required">*</span></label>
            <select name="hdv_assignments[${hdvAssignmentCount}][id_hdv]" class="hdv-select" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                <option value="">Chọn HDV</option>
                <?php if (!empty($guides)): ?>
                    <?php foreach ($guides as $guide): ?>
                        <option value="<?= $guide['id'] ?>" data-name="<?= safe_html($guide['ho_ten']) ?>" ${hdvId == '<?= $guide['id'] ?>' ? 'selected' : ''}>
                            <?= safe_html($guide['ho_ten']) ?> - <?= safe_html($guide['so_dien_thoai'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group-modern">
            <label>Vai trò <span class="required">*</span></label>
            <select name="hdv_assignments[${hdvAssignmentCount}][vai_tro]" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                <option value="HDV chính" ${vaiTro == 'HDV chính' ? 'selected' : ''}>HDV chính</option>
                <option value="HDV phụ" ${vaiTro == 'HDV phụ' ? 'selected' : ''}>HDV phụ</option>
                <option value="Trợ lý" ${vaiTro == 'Trợ lý' ? 'selected' : ''}>Trợ lý</option>
            </select>
        </div>
        <div class="form-group-modern">
            <label>Lương</label>
            <input type="number" name="hdv_assignments[${hdvAssignmentCount}][luong]" placeholder="VNĐ" min="0" step="1000" value="${luong}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
        </div>
        <div class="form-group-modern">
            <label>Ghi chú</label>
            <input type="text" name="hdv_assignments[${hdvAssignmentCount}][ghi_chu]" placeholder="Ghi chú..." value="${ghiChu}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
        </div>
        <div>
            <button type="button" class="btn-remove-hdv" onclick="removeHdvAssignment(${hdvAssignmentCount})" style="padding: 10px 15px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(hdvRow);
    
    // Update selected HDV tracking
    const select = hdvRow.querySelector('.hdv-select');
    if (hdvId) {
        select.value = hdvId;
        selectedHdvIds.add(parseInt(hdvId));
    }
    select.addEventListener('change', function() {
        updateHdvDropdowns();
    });
    
    updateHdvDropdowns();
}

function removeHdvAssignment(rowId) {
    const row = document.getElementById(`hdv-row-${rowId}`);
    if (row) {
        const select = row.querySelector('.hdv-select');
        if (select && select.value) {
            selectedHdvIds.delete(parseInt(select.value));
        }
        row.remove();
        updateHdvDropdowns();
    }
}

function updateHdvDropdowns() {
    // Collect all selected HDV IDs
    selectedHdvIds.clear();
    document.querySelectorAll('.hdv-select').forEach(select => {
        if (select.value) {
            selectedHdvIds.add(parseInt(select.value));
        }
    });
    
    // Update all dropdowns to disable selected HDVs
    document.querySelectorAll('.hdv-select').forEach(select => {
        const currentValue = select.value;
        Array.from(select.options).forEach(option => {
            if (option.value && option.value !== currentValue) {
                const hdvId = parseInt(option.value);
                if (selectedHdvIds.has(hdvId)) {
                    option.disabled = true;
                    const originalName = option.getAttribute('data-name') || '';
                    option.textContent = originalName + ' (Đã chọn)';
                } else {
                    option.disabled = false;
                    const originalName = option.getAttribute('data-name') || '';
                    option.textContent = originalName;
                }
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // HDV Assignment - Thêm HDV
    const addHdvBtn = document.getElementById('add-hdv-btn');
    if (addHdvBtn) {
        addHdvBtn.addEventListener('click', function() {
            addHdvAssignment();
        });
    }
    
    // Load các phân công HDV đã có
    <?php if (!empty($assignments)): ?>
        <?php foreach ($assignments as $assignment): ?>
            addHdvAssignment(
                '<?= $assignment['id_hdv'] ?? '' ?>',
                '<?= addslashes($assignment['vai_tro'] ?? 'HDV chính') ?>',
                '<?= $assignment['luong'] ?? '' ?>',
                '<?= addslashes($assignment['ghi_chu'] ?? '') ?>'
            );
        <?php endforeach; ?>
    <?php endif; ?>
    
    // Itinerary Builder - Thêm ngày
    const addDayBtn = document.getElementById('add-day-btn');
    if (addDayBtn) {
        addDayBtn.addEventListener('click', function() {
            addDay();
        });
    }
    
    <?php if (!empty($departurePlan['chuongtrinh'])): ?>
    // Load existing itinerary - use base64 to avoid JSON encoding issues
    // Decode HTML entities trước khi encode base64
    <?php 
    $chuongtrinhRaw = html_entity_decode($departurePlan['chuongtrinh'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $chuongtrinhBase64 = base64_encode($chuongtrinhRaw);
    ?>
    const existingItineraryBase64 = '<?= $chuongtrinhBase64 ?>';
    if (existingItineraryBase64) {
        try {
            const existingItineraryHtml = atob(existingItineraryBase64);
            if (existingItineraryHtml && existingItineraryHtml.trim()) {
                // Wait a bit for DOM to be ready
                setTimeout(() => {
                    parseAndLoadExistingItinerary(existingItineraryHtml);
                }, 500);
            }
        } catch(e) {
            console.error('Error decoding itinerary:', e);
        }
    }
    <?php endif; ?>
    
    const daysContainer = document.getElementById('days-container');
    if (daysContainer && daysContainer.children.length === 0) {
        addDay();
    }
    
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            
            const itineraryHTML = buildItineraryHTML();
            const hiddenField = document.getElementById('chuongtrinh-hidden');
            if (hiddenField) {
                hiddenField.value = itineraryHTML;
            }
        });
    }
});
</script>