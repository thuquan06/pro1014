<?php
/**
 * edit.php - FINAL VERSION (CKEDITOR + Không NICEDIT)
 */
function safe_value($value, $default = '') {
    if ($value === null || $value === '') return $default;
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$id_goi=safe_value($tour['id_goi']??'');
$nuocngoai=isset($tour['nuocngoai'])?(int)$tour['nuocngoai']:0;
$khuyenmai=isset($tour['khuyenmai'])?(int)$tour['khuyenmai']:0;
$quocgia=safe_value($tour['quocgia']??'Việt Nam');
$ten_tinh=safe_value($tour['ten_tinh']??'');
$tengoi=safe_value($tour['tengoi']??'');
$noixuatphat=safe_value($tour['noixuatphat']??'');
$vitri=safe_value($tour['vitri']??'');
$giagoi=safe_value($tour['giagoi']??'');
$giatreem=safe_value($tour['giatreem']??'');
$giatrenho=safe_value($tour['giatrenho']??'');
$chitietgoi=safe_value($tour['chitietgoi']??'');
$chuongtrinh=safe_value($tour['chuongtrinh']??'');
$luuy=safe_value($tour['luuy']??'');
$songay=safe_value($tour['songay']??'');
$giodi=safe_value($tour['giodi']??'');
$ngayxuatphat=safe_value($tour['ngayxuatphat']??'');
$ngayve=safe_value($tour['ngayve']??'');
$phuongtien=safe_value($tour['phuongtien']??'');
$hinhanh=safe_value($tour['hinhanh']??'');
$ngaycapnhat=safe_value($tour['ngaycapnhat']??'');
$ngaydang=safe_value($tour['ngaydang']??date('Y-m-d'));
?>

<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?=BASE_URL?>?act=admin">Trang chủ</a>
        <i class="fa fa-angle-right"></i>Cập nhật tour
    </li>
</ol>

<div class="grid-form">
<div class="grid-form1">
<h3>Cập nhật tour</h3>

<?php if(isset($error)&&$error): ?>
<div class="errorWrap"><strong>LỖI</strong>: <?= safe_value($error) ?></div>
<?php endif; ?>

<?php if(isset($msg)&&$msg): ?>
<div class="succWrap"><strong>THÀNH CÔNG</strong>: <?= safe_value($msg) ?></div>
<?php endif; ?>

<div class="tab-content">
<div class="tab-pane active">

<?php if($tour): ?>

<form class="form-horizontal" method="post" action="<?=BASE_URL?>?act=admin-tour-update">
<input type="hidden" name="id_goi" value="<?=$id_goi?>">

<!-- LOẠI TOUR -->
<div class="form-group">
    <label class="col-sm-2 control-label" style="max-width:18.666667%">Loại tour:</label>
    <div class="col-sm-8">
        <input type="radio" value="0" name="nuocngoai" id="tour_trongnuoc" <?=$nuocngoai==0?'checked':''?>> Trong nước
        &nbsp;&nbsp;&nbsp;
        <input style="margin-left:55px" type="radio" value="1" name="nuocngoai" id="tour_quocte" <?=$nuocngoai==1?'checked':''?>> Quốc tế
    </div>
</div>

<!-- KHUYẾN MÃI -->
<div class="form-group">
    <label class="col-sm-2 control-label" style="max-width:18.666667%">Khuyến mãi:</label>
    <div class="col-sm-8">
        <input type="radio" value="1" name="khuyenmai" <?=$khuyenmai==1?'checked':''?>> Có
        &nbsp;&nbsp;&nbsp;
        <input style="margin-left:95px" type="radio" value="0" name="khuyenmai" <?=$khuyenmai==0?'checked':''?>> Không
    </div>
</div>

<!-- QUỐC GIA -->
<div class="form-group" id="field_quocgia" style="display:<?=$nuocngoai==1?'block':'none'?>">
    <label for="quocgia" class="col-sm-2 control-label">Quốc gia</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="quocgia" id="quocgia" value="<?=$quocgia?>">
    </div>
</div>

<!-- TỈNH -->
<div class="form-group" id="field_tinh" style="display:<?=$nuocngoai==0?'block':'none'?>">
<label for="ten_tinh" class="col-sm-2 control-label" style="max-width:18.666667%">Tỉnh:</label>
<div class="col-sm-8">
    <select name="ten_tinh" class="form-control" id="ten_tinh">
        <option value="">Chọn tỉnh</option>
        <?php if(!empty($provinces)) foreach($provinces as $p): 
            $pn = safe_value($p['ten_tinh']);
        ?>
            <option value="<?=$pn?>" <?=$pn==$ten_tinh?'selected':''?>><?=$pn?></option>
        <?php endforeach; ?>
    </select>
</div>
</div>

<!-- TÊN GÓI -->
<div class="form-group">
    <label class="col-sm-2 control-label">Tên gói</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="tengoi" value="<?=$tengoi?>" required>
    </div>
</div>

<!-- KHỞI HÀNH -->
<div class="form-group">
    <label class="col-sm-2 control-label">Điểm khởi hành</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="noixuatphat" value="<?=$noixuatphat?>" required>
    </div>
</div>

<!-- ĐIỂM ĐẾN -->
<div class="form-group">
    <label class="col-sm-2 control-label">Điểm đến</label>
    <div class="col-sm-8">
        <input type="text" class="form-control1" name="vitri" value="<?=$vitri?>" required>
    </div>
</div>

<!-- GIÁ -->
<div class="form-group"><label class="col-sm-2 control-label">Giá người lớn</label>
<div class="col-sm-8"><input type="text" class="form-control1" name="giagoi" value="<?=$giagoi?>" required></div></div>

<div class="form-group"><label class="col-sm-2 control-label">Giá trẻ em</label>
<div class="col-sm-8"><input type="text" class="form-control1" name="giatreem" value="<?=$giatreem?>" required></div></div>

<div class="form-group"><label class="col-sm-2 control-label">Giá trẻ nhỏ</label>
<div class="col-sm-8"><input type="text" class="form-control1" name="giatrenho" value="<?=$giatrenho?>" required></div></div>

<!-- CKEDITOR AREA 1 -->
<div class="form-group">
    <label class="col-sm-2 control-label">Chi tiết</label>
    <div class="col-sm-8">
        <textarea class="form-control" name="chitietgoi" id="packagedetails" required><?=$chitietgoi?></textarea>
    </div>
</div>

<!-- CKEDITOR AREA 2 -->
<div class="form-group">
    <label class="col-sm-2 control-label">Chương trình</label>
    <div class="col-sm-8">
        <textarea class="form-control" name="chuongtrinh" id="packagedetails1" required><?=$chuongtrinh?></textarea>
    </div>
</div>

<!-- CKEDITOR AREA 3 -->
<div class="form-group">
    <label class="col-sm-2 control-label">Lưu ý</label>
    <div class="col-sm-8">
        <textarea class="form-control" name="luuy" id="packagedetails2" required><?=$luuy?></textarea>
    </div>
</div>

<!-- THÔNG SỐ -->
<div class="form-group"><label class="col-sm-2 control-label">Số ngày</label>
<div class="col-sm-8"><input type="number" class="form-control1" name="songay" value="<?=$songay?>" required></div></div>

<div class="form-group"><label class="col-sm-2 control-label">Giờ xuất phát</label>
<div class="col-sm-8"><input type="time" class="form-control1" name="giodi" value="<?=$giodi?>" required></div></div>

<div class="form-group"><label class="col-sm-2 control-label">Ngày xuất phát</label>
<div class="col-sm-8"><input type="date" class="form-control1" name="ngayxuatphat" value="<?=$ngayxuatphat?>" required></div></div>

<div class="form-group"><label class="col-sm-2 control-label">Ngày về</label>
<div class="col-sm-8"><input type="date" class="form-control1" name="ngayve" value="<?=$ngayve?>" required></div></div>

<div class="form-group"><label class="col-sm-2 control-label">Phương tiện</label>
<div class="col-sm-8"><input type="text" class="form-control1" name="phuongtien" value="<?=$phuongtien?>" required></div></div>

<div class="form-group"><label class="col-sm-2 control-label">Ngày đăng</label>
<div class="col-sm-8"><input type="date" class="form-control1" name="ngaydang" value="<?=$ngaydang?>" required></div></div>

<!-- ẢNH -->
<div class="form-group">
    <label class="col-sm-2 control-label">Hình ảnh</label>
    <div class="col-sm-8">
        <?php if($hinhanh): ?>
            <img src="<?=$hinhanh?>" width="200">
        <?php endif; ?>
        &nbsp;
        <a href="<?=BASE_URL?>?act=admin-tour-update-image&id=<?=$id_goi?>">Thay đổi ảnh</a>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Cập nhật lần cuối</label>
    <div class="col-sm-8"><?=$ngaycapnhat?></div>
</div>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <button type="submit" class="btn-primary btn">Cập nhật</button>
    </div>
</div>

</form>

<?php else: ?>
<div class="errorWrap"><strong>LỖI</strong>: Không tìm thấy tour.</div>
<?php endif; ?>

</div></div></div></div>

<script src="assets/ckeditor/ckeditor.js"></script>

<script>
// Danh sách textarea cần dùng CKEditor + CKFinder
const editorIDs = ['packagedetails', 'packagedetails1', 'packagedetails2'];

editorIDs.forEach(id => {
    CKEDITOR.replace(id, {
        height: 350,
        filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl: 'ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    var r1 = document.getElementById('tour_trongnuoc'),
        r2 = document.getElementById('tour_quocte'),
        f1 = document.getElementById('field_tinh'),
        f2 = document.getElementById('field_quocgia'),
        iq = document.getElementById('quocgia');

    function toggle() {
        if (r2 && r2.checked) {
            f1.style.display = 'none';
            f2.style.display = 'block';
            if (iq.value === 'Việt Nam') iq.value = '';
        } else {
            f1.style.display = 'block';
            f2.style.display = 'none';
            iq.value = 'Việt Nam';
        }
    }

    r1.addEventListener('change', toggle);
    r2.addEventListener('change', toggle);
    toggle();
});
</script>
