<?php
/**
 * File: views/admin/tours/phanloai/index.php
 * Quản lý Phân loại & Tags của Tour - CÓ THÊM LOẠI TOUR MỚI & TAG MỚI
 */
ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Quản lý Tour</a></li>
    <li class="active">Phân loại & Tags - Tour #<?= $idGoi ?></li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">🏷️ Quản lý Phân loại & Tags</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Thông báo -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="succWrap">
        <i class="fa fa-check-circle"></i> <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="errorWrap">
        <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row">
    <!-- ========== LOẠI TOUR ========== -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-list"></i> Loại Tour
                </h3>
            </div>
            <div class="panel-body">
                <p class="text-muted" style="margin-bottom: 20px;">
                    Chọn các loại tour phù hợp để phân loại tour này
                </p>
                
                <form action="<?= BASE_URL ?>?act=tour-capnhat-loai" method="POST" id="formLoaiTour">
                    <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
                    
                    <?php if (empty($tatCaLoai)): ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            Chưa có loại tour nào. Hãy tạo loại tour mới bên dưới!
                        </div>
                    <?php else: ?>
                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9; margin-bottom: 20px;">
                            <?php foreach ($tatCaLoai as $loai): ?>
                                <div class="checkbox" style="margin: 10px 0;">
                                    <label style="font-size: 15px; padding: 8px 10px; display: block; border-radius: 5px; transition: all 0.2s;" 
                                           onmouseover="this.style.background='#e3f2fd'" 
                                           onmouseout="this.style.background='transparent'">
                                        <input 
                                            type="checkbox" 
                                            name="loai_ids[]" 
                                            value="<?= $loai['id'] ?>"
                                            <?= in_array($loai['id'], $loaiIds) ? 'checked' : '' ?>
                                            style="margin-right: 10px;"
                                        >
                                        <strong><?= htmlspecialchars($loai['ten_loai']) ?></strong>
                                        <?php if (!empty($loai['mota'])): ?>
                                            <br>
                                            <small class="text-muted" style="margin-left: 24px;">
                                                <?= htmlspecialchars($loai['mota']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-save"></i> Cập nhật Loại Tour
                        </button>
                    <?php endif; ?>
                </form>

                <!-- THÊM LOẠI TOUR MỚI -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px dashed #ddd;">
                    <h4 style="margin-bottom: 15px;">
                        <i class="fa fa-plus-circle"></i> Tạo Loại Tour Mới
                    </h4>
                    
                    <form action="<?= BASE_URL ?>?act=tour-tao-loai" method="POST" id="formThemLoai">
                        <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
                        
                        <div class="form-group">
                            <label for="ten_loai">
                                <i class="fa fa-tag"></i> Tên Loại Tour:
                            </label>
                            <input 
                                type="text" 
                                id="ten_loai" 
                                name="ten_loai" 
                                class="form-control" 
                                placeholder="VD: Tour miền Bắc, Tour Châu Âu, Tour nghỉ dưỡng..."
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="mota_loai">
                                <i class="fa fa-align-left"></i> Mô tả (tùy chọn):
                            </label>
                            <textarea 
                                id="mota_loai" 
                                name="mota_loai" 
                                class="form-control" 
                                rows="2"
                                placeholder="Mô tả ngắn về loại tour này..."
                            ></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Tạo Loại Tour & Gán
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== TAGS ========== -->
    <div class="col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-tags"></i> Tags
                </h3>
            </div>
            <div class="panel-body">
                <p class="text-muted" style="margin-bottom: 20px;">
                    Thêm các từ khóa để dễ tìm kiếm tour
                </p>
                
                <form action="<?= BASE_URL ?>?act=tour-capnhat-tags" method="POST" id="formTags">
                    <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
                    
                    <?php if (empty($tatCaTags)): ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            Chưa có tag nào. Hãy tạo tag mới bên dưới!
                        </div>
                    <?php else: ?>
                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9; margin-bottom: 20px;">
                            <?php foreach ($tatCaTags as $tag): ?>
                                <div class="checkbox" style="margin: 10px 0;">
                                    <label style="font-size: 15px; padding: 8px 10px; display: block; border-radius: 5px; transition: all 0.2s;" 
                                           onmouseover="this.style.background='#e8f5e9'" 
                                           onmouseout="this.style.background='transparent'">
                                        <input 
                                            type="checkbox" 
                                            name="tag_ids[]" 
                                            value="<?= $tag['id'] ?>"
                                            <?= in_array($tag['id'], $tagIds) ? 'checked' : '' ?>
                                            style="margin-right: 10px;"
                                        >
                                        <span class="label label-success" style="font-size: 13px; padding: 5px 10px;">
                                            #<?= htmlspecialchars($tag['ten_tag']) ?>
                                        </span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-save"></i> Cập nhật Tags
                        </button>
                    <?php endif; ?>
                </form>

                <!-- THÊM TAG MỚI -->
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px dashed #ddd;">
                    <h4 style="margin-bottom: 15px;">
                        <i class="fa fa-plus-circle"></i> Tạo Tag Mới
                    </h4>
                    
                    <form action="<?= BASE_URL ?>?act=tour-tao-tag" method="POST" id="formThemTag">
                        <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
                        
                        <div class="form-group">
                            <label for="ten_tag">
                                <i class="fa fa-hashtag"></i> Tên Tag:
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input 
                                    type="text" 
                                    id="ten_tag" 
                                    name="ten_tag" 
                                    class="form-control" 
                                    placeholder="VD: Du lịch biển, Phượt, Gia đình..."
                                    required
                                >
                            </div>
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> 
                                Tag giúp khách hàng tìm kiếm tour dễ dàng hơn
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-info btn-block">
                            <i class="fa fa-plus"></i> Tạo Tag & Gán
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hướng dẫn -->
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">
            <h4><i class="fa fa-lightbulb-o"></i> Hướng dẫn sử dụng:</h4>
            <ul style="margin-bottom: 0;">
                <li><strong>Loại Tour:</strong> Phân loại tour theo khu vực hoặc đặc điểm (VD: Tour trong nước, Tour nước ngoài, Tour nghỉ dưỡng...)</li>
                <li><strong>Tags:</strong> Thêm các từ khóa ngắn gọn để dễ tìm kiếm (VD: #Biển, #Núi, #Phượt, #GiaDinh...)</li>
                <li><strong>Tạo Mới:</strong> Nếu chưa có loại tour hoặc tag phù hợp, hãy tạo mới và nó sẽ tự động được gán cho tour này</li>
            </ul>
        </div>
    </div>
</div>

<!-- CSS bổ sung -->
<style>
.checkbox label {
    cursor: pointer;
    user-select: none;
}

.checkbox input[type="checkbox"] {
    cursor: pointer;
    transform: scale(1.2);
}

.panel-heading {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
    color: white !important;
}

.panel-primary .panel-heading {
    background: linear-gradient(135deg, #2196F3, #1976D2) !important;
}

.panel-success .panel-heading {
    background: linear-gradient(135deg, #4CAF50, #388E3C) !important;
}

.input-group-addon {
    background: #4CAF50;
    color: white;
    border-color: #4CAF50;
    font-weight: bold;
}

.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-group label i {
    color: #667eea;
    margin-right: 5px;
}

/* Custom scrollbar */
div[style*="overflow-y: auto"]::-webkit-scrollbar {
    width: 8px;
}

div[style*="overflow-y: auto"]::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<!-- JavaScript -->
<script>
// Validation cho form thêm loại tour
document.getElementById('formThemLoai').addEventListener('submit', function(e) {
    var tenLoai = document.getElementById('ten_loai').value.trim();
    
    if (tenLoai.length < 3) {
        e.preventDefault();
        alert('Tên loại tour phải có ít nhất 3 ký tự!');
        return false;
    }
    
    if (tenLoai.length > 100) {
        e.preventDefault();
        alert('Tên loại tour không được quá 100 ký tự!');
        return false;
    }
});

// Validation cho form thêm tag
document.getElementById('formThemTag').addEventListener('submit', function(e) {
    var tenTag = document.getElementById('ten_tag').value.trim();
    
    if (tenTag.length < 2) {
        e.preventDefault();
        alert('Tên tag phải có ít nhất 2 ký tự!');
        return false;
    }
    
    if (tenTag.length > 50) {
        e.preventDefault();
        alert('Tên tag không được quá 50 ký tự!');
        return false;
    }
});

// Thêm hiệu ứng khi hover checkbox
document.querySelectorAll('.checkbox label').forEach(function(label) {
    label.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(5px)';
    });
    
    label.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>