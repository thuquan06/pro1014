<?php
/**
 * File: views/admin/tour/gallery/upload.php
 * ĐÃ THÊM:
 *  - CKEditor mô tả chung
 *  - Caption riêng cho từng ảnh
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
            method="POST" enctype="multipart/form-data" id="uploadForm">

            <!-- Chọn ảnh -->
            <div class="form-group">
                <label>Chọn ảnh <span class="text-danger">*</span></label>
                <input type="file" 
                    name="images[]" 
                    id="imageInput"
                    class="form-control" 
                    accept="image/*" 
                    multiple required>
                <small class="help-block">Cho phép: JPG, PNG, GIF, WEBP.</small>
            </div>

            <!-- Preview -->
            <div id="previewContainer" style="margin-top: 20px; display:none;">
                <label>Ảnh xem trước + mô tả:</label>
                <div id="previewImages" class="row"></div>
            </div>

            <!-- Mô tả chung -->
            <div class="form-group">
                <label>Mô tả chung (tùy chọn)</label>
                <textarea name="mota_chung" id="mota_chung" class="form-control" rows="4"></textarea>
            </div>

            <!-- Buttons -->
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-upload"></i> Upload ngay
            </button>
            <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $idGoi ?>" class="btn btn-default">
                <i class="fa fa-times"></i> Hủy
            </a>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let fileInput = document.getElementById("imageInput");
    let previewContainer = document.getElementById("previewContainer");
    let previewImages = document.getElementById("previewImages");

    fileInput.addEventListener("change", function(e) {
        let files = e.target.files;
        previewImages.innerHTML = "";
        if (files.length === 0) {
            previewContainer.style.display = "none";
            return;
        }

        previewContainer.style.display = "block";

        Array.from(files).forEach((file, index) => {
            if (!file.type.startsWith("image/")) return;

            let reader = new FileReader();

            reader.onload = function(e) {
                let col = document.createElement("div");
                col.className = "col-md-3";
                col.style.marginBottom = "20px";

                col.innerHTML = `
                    <div style="border:1px solid #ddd; padding:8px; border-radius:5px;">
                        <img src="${e.target.result}"
                            style="width:100%; height:160px; object-fit:cover; border-radius:5px;">
                        <input type="text" name="caption[]" class="form-control" 
                            placeholder="Mô tả riêng cho ảnh (caption)" style="margin-top:8px;">
                    </div>
                `;

                previewImages.appendChild(col);
            };

            reader.readAsDataURL(file);
        });
    });

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
        }
    });
});
</script>

<!-- CKEditor -->
<script src="<?= BASE_URL ?>assets/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace("mota_chung");
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
