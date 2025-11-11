<?php
// File: views/admin/tours/edit.php (File mới)

/*
 * Biến $tour (chi tiết tour) và $provinces (danh sách tỉnh)
 * đã được AdminController::editTour() chuẩn bị.
 */

// Đặt sẵn giá trị (nếu có)
$ten_tinh = $tour['ten_tinh'] ?? '';
$quocgia = $tour['quocgia'] ?? 'Việt Nam';
$nuocngoai = $tour['nuocngoai'] ?? 0;
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?act=admin">Trang chủ</a><i class="fa fa-angle-right"></i>Cập nhật tour</li>
</ol>
<div class="grid-form">
    <div class="grid-form1">
        <h3>Cập nhật tour</h3>
        <?php if(isset($error) && $error){?><div class="errorWrap"><strong>LỖI</strong>:<?php echo htmlentities($error); ?> </div><?php } 
        else if(isset($msg) && $msg){?><div class="succWrap"><strong>THÀNH CÔNG</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
        
        <div class="tab-content">
            <div class="tab-pane active" id="horizontal-form">
                
                <?php if ($tour) { ?>
                <form class="form-horizontal" name="package" method="post" action="<?php echo BASE_URL; ?>?act=admin-tour-update">
                    <input type="hidden" name="id_goi" value="<?php echo htmlentities($tour['id_goi']); ?>">

                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="max-width: 18.666667%;">Loại tour:</label>
                        <div class="col-sm-8">
                            <input type="radio" value="0" name="nuocngoai" id="tour_trongnuoc" <?php echo ($nuocngoai == 0) ? 'checked' : ''; ?>> Trong nước
                            &nbsp;&nbsp;&nbsp;
                            <input style=" margin-left: 55px;" type="radio" value="1" name="nuocngoai" id="tour_quocte" <?php echo ($nuocngoai == 1) ? 'checked' : ''; ?>> Quốc tế
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="max-width: 18.666667%;">Khuyến mãi:</label>
                        <div class="col-sm-8">
                            <input type="radio" value="1" name="khuyenmai" <?php echo ($tour['khuyenmai'] == 1) ? 'checked' : ''; ?>> Có
                            &nbsp;&nbsp;&nbsp;
                            <input style=" margin-left: 95px;" type="radio" value="0" name="khuyenmai" <?php echo ($tour['khuyenmai'] == 0) ? 'checked' : ''; ?>> Không 
                        </div>
                    </div>

                    <div class="form-group" id="field_quocgia" style="display: <?php echo ($nuocngoai == 1) ? 'block' : 'none'; ?>;">
                        <label for="quocgia" class="col-sm-2 control-label">Quốc gia</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="quocgia" id="quocgia" placeholder="Tên quốc gia" value="<?php echo htmlentities($tour['quocgia']);?>">
                        </div>
                    </div>
                    
                    <div class="form-group" id="field_tinh" style="display: <?php echo ($nuocngoai == 0) ? 'block' : 'none'; ?>;">
                        <label for="ten_tinh" class="col-sm-2 control-label" style="max-width: 18.666667%;">Tỉnh:</label>
                        <div class="col-sm-8">
                            <select name="ten_tinh" class="form-control" id="ten_tinh" style="font-size: 16px;"> 
                                <option value="0">Chọn tỉnh</option>
                                <?php 
                                if (!empty($provinces)) {
                                    foreach ($provinces as $province) {
                                        $selected = ($province['ten_tinh'] == $tour['ten_tinh']) ? " selected" : "";
                                        echo "<option value='".$province['ten_tinh']."'".$selected.">".$province['ten_tinh']."</option>";
                                    }
                                }
                                ?>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tengoi" class="col-sm-2 control-label">Tên gói</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="tengoi" id="tengoi" placeholder="Tên tour" value="<?php echo htmlentities($tour['tengoi']);?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="noixuatphat" class="col-sm-2 control-label">Điểm khởi hành</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="noixuatphat" id="noixuatphat" value="<?php echo htmlentities($tour['noixuatphat']);?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vitri" class="col-sm-2 control-label">Điểm đến</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="vitri" id="vitri" value="<?php echo htmlentities($tour['vitri']);?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="giagoi" class="col-sm-2 control-label">Giá người lớn</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giagoi" id="giagoi" value="<?php echo htmlentities($tour['giagoi']);?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="giatreem" class="col-sm-2 control-label">Giá trẻ em</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giatreem" id="giatreem" value="<?php echo htmlentities($tour['giatreem']);?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="giatrenho" class="col-sm-2 control-label">Giá trẻ nhỏ</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giatrenho" id="giatrenho" value="<?php echo htmlentities($tour['giatrenho']);?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="giaphongdon" class="col-sm-2 control-label">Giá phòng đơn</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giaphongdon" id="giaphongdon" value="<?php echo htmlentities($tour['giaphongdon']);?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="sonhan" class="col-sm-2 control-label">Số người nhận</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control1" name="sonhan" id="sonhan" value="<?php echo htmlentities($tour['sonhan']);?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="chitietgoi" class="col-sm-2 control-label">Chi tiết</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="5" cols="50" name="chitietgoi" id="packagedetails" required><?php echo htmlentities($tour['chitietgoi']);?></textarea> 
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="chuongtrinh" class="col-sm-2 control-label">Chương trình</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="5" cols="50" name="chuongtrinh" id="packagedetails1" required><?php echo htmlentities($tour['chuongtrinh']);?></textarea> 
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="luuy" class="col-sm-2 control-label">Lưu ý</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="5" cols="50" name="luuy" id="packagedetails2" required><?php echo htmlentities($tour['luuy']);?></textarea> 
                        </div>
                    </div>		
                    <div class="form-group">
                        <label for="songay" class="col-sm-2 control-label">Số ngày</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control1" name="songay" id="songay" value="<?php echo htmlentities($tour['songay']);?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="giodi" class="col-sm-2 control-label">Giờ xuất phát</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control1" name="giodi" id="giodi" value="<?php echo htmlentities($tour['giodi']);?>" required>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="ngayxuatphat" class="col-sm-2 control-label">Ngày xuất phát</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control1" name="ngayxuatphat" id="ngayxuatphat" value="<?php echo htmlentities($tour['ngayxuatphat']);?>" required>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="ngayve" class="col-sm-2 control-label">Ngày về</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control1" name="ngayve" id="ngayve" value="<?php echo htmlentities($tour['ngayve']);?>" required>
                        </div>
                    </div>		
                    <div class="form-group">
                        <label for="phuongtien" class="col-sm-2 control-label">Phương tiện</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="phuongtien" id="phuongtien" value="<?php echo htmlentities($tour['phuongtien']);?>" required>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="focusedinput" class="col-sm-2 control-label">Hình ảnh</label>
                        <div class="col-sm-8">
                            <img src="<?php echo htmlentities($tour['hinhanh']);?>" width="200">
                            &nbsp;&nbsp;&nbsp;<a href="<?php echo BASE_URL; ?>?act=admin-tour-update-image&id=<?php echo htmlentities($tour['id_goi']);?>">Thay đổi ảnh</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="focusedinput" class="col-sm-2 control-label">Cập nhật lần cuối</Ghi></label>
                        <div class="col-sm-8">
                            <?php echo htmlentities($tour['ngaycapnhat']);?>
                        </div>
                    </div>	

                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" name="submit" class="btn-primary btn">Cập nhật</button>
                        </div>
                    </div>
                </form>
                <?php } else { ?>
                    <div class="errorWrap"><strong>LỖI</strong>: Không tìm thấy thông tin tour.</div>
                <?php } ?>
            </div>
        </div> </div>
</div>

<script src="assets/js/nicEdit.js"></script>
<script>
    bkLib.onDomLoaded(function() {
        new nicEditor({fullPanel : true}).panelInstance('packagedetails');
        new nicEditor({fullPanel : true}).panelInstance('packagedetails1');
        new nicEditor({fullPanel : true}).panelInstance('packagedetails2');
    });

    // JS để ẩn/hiện trường Tỉnh/Quốc gia
    document.addEventListener('DOMContentLoaded', function() {
        var radioTrongNuoc = document.getElementById('tour_trongnuoc');
        var radioQuocTe = document.getElementById('tour_quocte');
        var fieldTinh = document.getElementById('field_tinh');
        var fieldQuocGia = document.getElementById('field_quocgia');
        var inputQuocGia = document.getElementById('quocgia');
        var selectTinh = document.getElementById('ten_tinh');

        function toggleFields() {
            if (radioQuocTe.checked) {
                fieldTinh.style.display = 'none';
                fieldQuocGia.style.display = 'block';
                if(inputQuocGia.value === 'Việt Nam') inputQuocGia.value = '';
            } else {
                fieldTinh.style.display = 'block';
                fieldQuocGia.style.display = 'none';
                inputQuocGia.value = 'Việt Nam'; 
            }
        }
        
        radioTrongNuoc.addEventListener('change', toggleFields);
        radioQuocTe.addEventListener('change', toggleFields);
    });
</script>