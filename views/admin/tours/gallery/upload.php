<?php
/**
 * File: views/admin/tour/gallery/upload.php
 * Form upload ảnh - Dùng với layout 1 file
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li><a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $idGoi ?>">Gallery</a></li>
    <li class="active">Upload ảnh</li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">📤 Upload ảnh mới</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $idGoi ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Form Upload -->
<div class="panel panel-default">
    <div class="panel-body">
        <form action="<?= BASE_URL ?>?act=tour-gallery-them&id_goi=<?= $idGoi ?>" 
              method="POST" 
              enctype="multipart/form-data"
              id="uploadForm">
            
            <!-- Chọn ảnh -->
            <div class="form-group">
                <label>Chọn ảnh <span class="text-danger">*</span></label>
                <input type="file" 
                       name="images[]" 
                       id="imageInput"
                       class="form-control" 
                       accept="image/*"
                       multiple
                       required>
                <small class="help-block">
                    Cho phép: JPG, PNG, GIF, WEBP. Có thể chọn nhiều ảnh cùng lúc.
                </small>
            </div>

            <!-- Preview -->
            <div id="previewContainer" style="margin-top: 20px; display: none;">
                <label>Xem trước:</label>
                <div id="previewImages" class="row"></div>
            </div>

            <!-- Mô tả chung (tùy chọn) -->
            <div class="form-group">
                <label>Mô tả chung (tùy chọn)</label>
                <textarea name="mota_chung" 
                          class="form-control" 
                          rows="3"
                          placeholder="Nhập mô tả chung cho tất cả ảnh..."></textarea>
            </div>

            <!-- Buttons -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-upload"></i> Upload ngay
                </button>
                <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $idGoi ?>" class="btn btn-default">
                    <i class="fa fa-times"></i> Hủy
                </a>
            </div>

        </form>
    </div>
</div>

<!-- Hướng dẫn -->
<div class="alert alert-info">
    <strong><i class="fa fa-info-circle"></i> Lưu ý:</strong>
    <ul style="margin: 10px 0 0 20px;">
        <li>Chọn nhiều ảnh bằng cách giữ <kbd>Ctrl</kbd> (Windows) hoặc <kbd>Cmd</kbd> (Mac)</li>
        <li>Kích thước ảnh đề xuất: 1200x800px</li>
        <li>Dung lượng tối đa mỗi ảnh: 5MB</li>
        <li>Sau khi upload, bạn có thể chọn ảnh đại diện trong trang Gallery</li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');
    const previewImages = document.getElementById('previewImages');

    imageInput.addEventListener('change', function(e) {
        const files = e.target.files;
        
        if (files.length === 0) {
            previewContainer.style.display = 'none';
            return;
        }

        previewImages.innerHTML = '';
        previewContainer.style.display = 'block';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            if (!file.type.startsWith('image/')) {
                continue;
            }

            const reader = new FileReader();
            
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-2 col-sm-3 col-xs-4';
                col.style.marginBottom = '10px';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '150px';
                img.style.objectFit = 'cover';
                img.style.border = '2px solid #ddd';
                img.style.borderRadius = '5px';
                
                const caption = document.createElement('p');
                caption.style.fontSize = '11px';
                caption.style.marginTop = '5px';
                caption.style.textAlign = 'center';
                caption.textContent = file.name;
                
                col.appendChild(img);
                col.appendChild(caption);
                previewImages.appendChild(col);
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Validate trước khi submit
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const files = imageInput.files;
        let totalSize = 0;
        let hasError = false;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileSize = file.size / 1024 / 1024; // MB
            
            if (fileSize > 5) {
                alert('File "' + file.name + '" vuot qua 5MB!');
                hasError = true;
                break;
            }
            
            totalSize += fileSize;
        }

        if (totalSize > 50) {
            alert('Tong dung luong vuot qua 50MB! Hay chon it anh hon.');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>