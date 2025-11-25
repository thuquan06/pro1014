<?php
/**
 * Upload ảnh Tour - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.upload-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.upload-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.upload-subtitle {
  color: var(--text-light);
  font-size: 14px;
  margin-top: 4px;
}

.upload-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 32px;
}

.upload-zone {
  border: 2px dashed var(--border);
  border-radius: 12px;
  padding: 40px;
  text-align: center;
  background: var(--bg-light);
  cursor: pointer;
  transition: all 0.3s;
  margin-bottom: 24px;
}

.upload-zone:hover {
  border-color: var(--primary);
  background: #eff6ff;
}

.upload-zone.dragover {
  border-color: var(--primary);
  background: #dbeafe;
}

.upload-zone-icon {
  font-size: 48px;
  color: var(--primary);
  margin-bottom: 16px;
}

.upload-zone-text {
  font-size: 16px;
  color: var(--text-dark);
  margin-bottom: 8px;
  font-weight: 600;
}

.upload-zone-hint {
  font-size: 13px;
  color: var(--text-light);
}

.preview-section {
  margin-bottom: 24px;
}

.preview-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 16px;
}

.preview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 16px;
}

.preview-item {
  background: white;
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 12px;
}

.preview-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 12px;
}

.preview-input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-size: 13px;
}

.preview-input:focus {
  outline: none;
  border-color: var(--primary);
}

.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
  margin-bottom: 8px;
}

.form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  resize: vertical;
  font-family: inherit;
  line-height: 1.6;
}

.form-textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 24px;
  border-top: 2px solid var(--bg-light);
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

.btn-submit:disabled {
  background: var(--border);
  cursor: not-allowed;
  transform: none;
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
}
</style>

<!-- Page Header -->
<div class="upload-header">
  <div>
    <h1 class="upload-title">
      <i class="fas fa-cloud-upload-alt" style="color: var(--primary);"></i>
      Upload ảnh mới
    </h1>
    <p class="upload-subtitle">Tour ID: <?= $idGoi ?></p>
  </div>
  <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $idGoi ?>" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Form Upload -->
<div class="upload-card">
  <form action="<?= BASE_URL ?>?act=tour-gallery-them&id_goi=<?= $idGoi ?>" 
        method="POST" 
        enctype="multipart/form-data" 
        id="uploadForm">

    <!-- Upload Zone -->
    <div class="upload-zone" onclick="document.getElementById('imageInput').click()">
      <div class="upload-zone-icon">
        <i class="fas fa-cloud-upload-alt"></i>
      </div>
      <p class="upload-zone-text">Nhấp để chọn ảnh hoặc kéo thả vào đây</p>
      <p class="upload-zone-hint">Hỗ trợ: JPG, PNG, GIF, WEBP (Tối đa 50MB)</p>
      <input 
        type="file" 
        name="images[]" 
        id="imageInput"
        accept="image/*" 
        multiple 
        required
        style="display: none;"
      >
    </div>

    <!-- Preview -->
    <div id="previewContainer" class="preview-section" style="display:none;">
      <p class="preview-title">
        <i class="fas fa-images"></i>
        Ảnh đã chọn (<span id="imageCount">0</span>)
      </p>
      <div id="previewImages" class="preview-grid"></div>
    </div>

    <!-- Mô tả chung -->
    <div class="form-group">
      <label class="form-label" for="mota_chung">
        <i class="fas fa-align-left"></i>
        Mô tả chung (tùy chọn)
      </label>
      <textarea 
        name="mota_chung" 
        id="mota_chung" 
        class="form-textarea" 
        rows="4"
        placeholder="Nhập mô tả chung cho tất cả ảnh..."
      ></textarea>
    </div>

    <!-- Buttons -->
    <div class="form-actions">
      <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $idGoi ?>" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit" id="submitBtn" disabled>
        <i class="fas fa-upload"></i>
        Upload ngay
      </button>
    </div>

  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let fileInput = document.getElementById("imageInput");
  let previewContainer = document.getElementById("previewContainer");
  let previewImages = document.getElementById("previewImages");
  let uploadZone = document.querySelector(".upload-zone");
  let submitBtn = document.getElementById("submitBtn");
  let imageCount = document.getElementById("imageCount");

  // Click to select
  fileInput.addEventListener("change", handleFiles);

  // Drag and drop
  uploadZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadZone.classList.add('dragover');
  });

  uploadZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
  });

  uploadZone.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    
    let dt = e.dataTransfer;
    let files = dt.files;
    
    fileInput.files = files;
    handleFiles();
  });

  function handleFiles() {
    let files = fileInput.files;
    previewImages.innerHTML = "";
    
    if (files.length === 0) {
      previewContainer.style.display = "none";
      submitBtn.disabled = true;
      return;
    }

    previewContainer.style.display = "block";
    submitBtn.disabled = false;
    imageCount.textContent = files.length;

    Array.from(files).forEach((file, index) => {
      if (!file.type.startsWith("image/")) return;

      let reader = new FileReader();

      reader.onload = function(e) {
        let div = document.createElement("div");
        div.className = "preview-item";

        div.innerHTML = `
          <img src="${e.target.result}" class="preview-image">
          <input 
            type="text" 
            name="caption[]" 
            class="preview-input" 
            placeholder="Mô tả cho ảnh này..."
          >
        `;

        previewImages.appendChild(div);
      };

      reader.readAsDataURL(file);
    });
  }

  // Validate size
  document.getElementById("uploadForm").addEventListener("submit", function(e) {
    let files = fileInput.files;
    let totalSize = 0;
    
    for (let file of files) {
      totalSize += file.size / 1024 / 1024;
    }
    
    if (totalSize > 50) {
      alert("Tổng dung lượng vượt quá 50MB!");
      e.preventDefault();
      return false;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang upload...';
  });
});
</script>

<!-- CKEditor -->
<script src="<?= BASE_URL ?>assets/ckeditor/ckeditor.js"></script>
<script>
  CKEDITOR.replace("mota_chung", {
    height: 200
  });
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
