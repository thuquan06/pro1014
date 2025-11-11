<?php
// File: views/admin/tours/create.php (File mới)

/*
 * Biến $provinces (danh sách tỉnh) đã được AdminController::createTour()
 * chuẩn bị và truyền vào file layout.php
 */
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?act=admin">Trang chủ</a><i class="fa fa-angle-right"></i>Tạo tour</li>
</ol>
<div class="grid-form">
    <div class="grid-form1">
        <h3>Tạo tour</h3>
        <?php if(isset($error) && $error){?><div class="errorWrap"><strong>LỖI</strong>:<?php echo htmlentities($error); ?> </div><?php } 
        else if(isset($msg) && $msg){?><div class="succWrap"><strong>THÀNH CÔNG</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
        
        <div class="tab-content">
            <div class="tab-pane active" id="horizontal-form">
                
                <form class="form-horizontal" name="package" method="post" action="<?php echo BASE_URL; ?>?act=admin-tour-store" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="max-width: 18.666667%;">Loại tour:</label>
                        <div class="col-sm-8">
                            <input type="radio" value="0" name="nuocngoai" id="tour_trongnuoc" checked="checked"> Trong nước
                            &nbsp;&nbsp;&nbsp;
                            <input style=" margin-left: 55px;" type="radio" value="1" name="nuocngoai" id="tour_quocte"> Quốc tế
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="max-width: 18.666667%;">Khuyến mãi:</label>
                        <div class="col-sm-8">
                            <input type="radio" value="1" name="khuyenmai"> Có
                            &nbsp;&nbsp;&nbsp;
                            <input style=" margin-left: 95px;" type="radio" value="0" name="khuyenmai" checked="checked"> Không 
                        </div>
                    </div>

                    <div class="form-group" id="field_quocgia" style="display: none;">
                        <label for="quocgia" class="col-sm-2 control-label">Quốc gia</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="quocgia" id="quocgia" placeholder="Tên quốc gia">
                        </div>
                    </div>
                    
                    <div class="form-group" id="field_tinh">
                        <label for="ten_tinh" class="col-sm-2 control-label" style="max-width: 18.666667%;">Tỉnh:</label>
                        <div class="col-sm-8">
                            <select name="ten_tinh" class="form-control" id="ten_tinh" style="font-size: 16px;"> 
                                <option value="0">Chọn tỉnh</option>
                                <?php 
                                if (!empty($provinces)) {
                                    foreach ($provinces as $province) {
                                        echo "<option value='".$province['ten_tinh']."'>".$province['ten_tinh']."</option>";
                                    }
                                }
                                ?>
                            </select> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tengoi" class="col-sm-2 control-label">Tên gói</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="tengoi" id="tengoi" placeholder="Tên tour" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="noixuatphat" class="col-sm-2 control-label">Điểm khởi hành</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="noixuatphat" id="noixuatphat" placeholder="Nơi xuất phát" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="vitri" class="col-sm-2 control-label">Điểm đến</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="vitri" id="vitri" placeholder="Vị trí" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="giagoi" class="col-sm-2 control-label">Giá người lớn</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giagoi" id="giagoi" placeholder="Giá gói VND" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="giatreem" class="col-sm-2 control-label">Giá trẻ em</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giatreem" id="giatreem" placeholder="Giá trẻ em VND" required>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="giatrenho" class="col-sm-2 control-label">Giá trẻ nhỏ</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giatrenho" id="giatrenho" placeholder="Giá trẻ nhỏ VND" required>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="giaphongdon" class="col-sm-2 control-label">Giá phòng đơn</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="giaphongdon" id="giaphongdon" placeholder="Giá phòng đơn VND" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="chitietgoi" class="col-sm-2 control-label">Chi tiết</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="5" cols="50" name="chitietgoi" id="packagedetails" placeholder="Chi tiết" required></textarea> 
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="chuongtrinh" class="col-sm-2 control-label">Chương trình</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="5" cols="50" name="chuongtrinh" id="packagedetails1" placeholder="Chương trình" required></textarea> 
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="luuy" class="col-sm-2 control-label">Lưu ý</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="5" cols="50" name="luuy" id="packagedetails2" placeholder="Lưu ý" required></textarea> 
                        </div>
                    </div>	

                    <div class="form-group">
                        <label for="songay" class="col-sm-2 control-label">Số ngày</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control1" name="songay" id="songay" placeholder="Số ngày" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="giodi" class="col-sm-2 control-label">Giờ xuất phát</label>
                        <div class="col-sm-8">
                            <input type="time" class="form-control1" name="giodi" id="giodi" placeholder="Giờ đi" required>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="ngayxuatphat" class="col-sm-2 control-label">Ngày xuất phát</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control1" name="ngayxuatphat" id="ngayxuatphat" placeholder="Ngày đi" required>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="ngayve" class="col-sm-2 control-label">Ngày về</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control1" name="ngayve" id="ngayve" placeholder="Ngày về" required>
                        </div>
                    </div>		
                    <div class="form-group">
                        <label for="phuongtien" class="col-sm-2 control-label">Phương tiện</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control1" name="phuongtien" id="phuongtien" placeholder="Phương tiện" required>
                        </div>
                    </div>	
                    <div class="form-group">
                        <label for="packageimage" class="col-sm-2 control-label">Hình ảnh</label>
                        <div class="col-sm-8">
                            <input type="file" name="packageimage" id="packageimage" required>
                        </div>
                    </div>	

                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" name="submit" class="btn-primary btn">Tạo</button>
                            <button type="reset" class="btn-inverse btn">Làm mới</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> </div>
</div>

<script src="assets/js/nicEdit.js"></script>
<script>
    // Thay vì CKEditor, file gốc của bạn dùng nicEdit.js
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
                selectTinh.value = '0'; // Reset Tỉnh
                inputQuocGia.value = ''; // Xóa Quốc gia
            } else {
                fieldTinh.style.display = 'block';
                fieldQuocGia.style.display = 'none';
                selectTinh.value = '0'; 
                inputQuocGia.value = 'Việt Nam'; // Tự điền 'Việt Nam'
            }
        }

        // Đặt giá trị mặc định khi tải trang
        toggleFields();

        // Thêm sự kiện
        radioTrongNuoc.addEventListener('change', toggleFields);
        radioQuocTe.addEventListener('change', toggleFields);
    });
</script>