<?php
// File: views/admin/tours/list.php (ĐÃ THÊM CỘT CHI TIẾT)

/*
 * Biến $tours (chứa mảng tour) đã được AdminController::listTours()
 * chuẩn bị và truyền vào file layout.php
 */

// Helper function để tránh lỗi htmlentities với null
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<div class="agile-grids">	
    <div class="agile-tables" style="padding: 0px;">
        <div class="w3l-table-info">
            <h2>Quản lý tour</h2>
            <table id="table">
                <thead>
                    <tr>
                        <th style="text-align: center;">STT</th>
                        <th style="text-align: center;">Quốc gia</th>
                        <th style="text-align: center;">Tỉnh</th>
                        <th style="text-align: center;">Tên</th>
                        <th style="text-align: center;">Điểm đi</th>
                        <th style="text-align: center;">Điểm đến</th>
                        <th style="text-align: center;">Giá</th>
                        <th style="text-align: center;">Số ngày</th>
                        <th style="text-align: center;">Giờ đi/ Ngày đi</th>
                        <th style="text-align: center;">Ngày về</th>
                        <th style="text-align: center;">Phương tiện</th>
                        <th style="text-align: center;">Ngày tạo</th>
                        <th style="text-align: center;">Trạng thái</th>
                        <th style="text-align: center;">Hoạt động</th>
                        <!-- ✨ CỘT MỚI -->
                        <th style="text-align: center;">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $cnt = 1;
                    if (!empty($tours)) {
                        foreach ($tours as $tour) { 
                            // Lấy giá trị an toàn
                            $quocgia = $tour['quocgia'] ?? '';
                            $ten_tinh = $tour['ten_tinh'] ?? '';
                            $tengoi = $tour['tengoi'] ?? '';
                            $noixuatphat = $tour['noixuatphat'] ?? '';
                            $vitri = $tour['vitri'] ?? '';
                            $giagoi = $tour['giagoi'] ?? '';
                            $giatreem = $tour['giatreem'] ?? '';
                            $giatrenho = $tour['giatrenho'] ?? '';
                            $songay = $tour['songay'] ?? '';
                            $giodi = $tour['giodi'] ?? '';
                            $ngayxuatphat = $tour['ngayxuatphat'] ?? '';
                            $ngayve = $tour['ngayve'] ?? '';
                            $phuongtien = $tour['phuongtien'] ?? '';
                            $ngaydang = $tour['ngaydang'] ?? '';
                            $id_goi = $tour['id_goi'] ?? '';
                    ?>		
                        <tr>
                            <td><?php echo safe_html($cnt);?></td>
                            <td><?php echo safe_html($quocgia);?></td>
                            <td><?php echo safe_html($ten_tinh);?></td>
                            <td><?php echo safe_html($tengoi);?></td>
                            <td><?php echo safe_html($noixuatphat);?></td>
                            <td><?php echo safe_html($vitri);?></td>
                            <td style="width: 150px;">Người lớn: <?php echo safe_html($giagoi);?>
                                <br>Trẻ em: <?php echo safe_html($giatreem);?>
                                <br>Trẻ nhỏ: <?php echo safe_html($giatrenho);?>
                            </td>
                            <td><?php echo safe_html($songay);?></td>
                            <td><?php echo safe_html($giodi);?>
                                <br><?php echo $ngayxuatphat ? date("d-m-Y", strtotime($ngayxuatphat)) : ''; ?>
                            </td>
                            <td><?php echo $ngayve ? date("d-m-Y", strtotime($ngayve)) : ''; ?></td>
                            <td><?php echo safe_html($phuongtien);?></td>
                            <td><?php echo $ngaydang ? date("d-m-Y", strtotime($ngaydang)) : ''; ?></td>
                            <td style="text-align:center;">
                                <?php if (!empty($tour['trangthai']) && $tour['trangthai'] == 1): ?>
                                    <a href="<?= BASE_URL ?>?act=admin-tour-toggle&id=<?= $id_goi ?>" class="btn btn-success btn-xs">Hiển thị</a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>?act=admin-tour-toggle&id=<?= $id_goi ?>" class="btn btn-warning btn-xs">Ẩn</a>
                                <?php endif; ?>
                            </td>

                            <td style="width: 194px;">
                                <a href="<?php echo BASE_URL; ?>?act=admin-tour-delete&id=<?php echo safe_html($id_goi);?>" onclick="return confirm('Bạn có chắc chắn xóa')">
                                    <button type="button" class="btn btn-primary btn-block" style="border-bottom: 2px solid;">Xóa</button>
                                </a>
                                <a href="<?php echo BASE_URL; ?>?act=admin-tour-edit&id=<?php echo safe_html($id_goi);?>">
                                    <button type="button" class="btn btn-primary btn-block" >Chỉnh sửa</button>
                                </a>
                            </td>

                            <!-- ✨ CỘT CHI TIẾT MỚI -->
                            <td style="text-align: center; width: 120px;">
                                <div class="btn-group-vertical" style="width: 100%;">
                                    <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $id_goi ?>" 
                                       class="btn btn-info btn-xs" 
                                       style="margin-bottom: 3px;"
                                       title="Quản lý lịch trình tour">
                                        <i class="fa fa-calendar"></i> Lịch trình
                                    </a>
                                    <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $id_goi ?>" 
                                       class="btn btn-success btn-xs"
                                       style="margin-bottom: 3px;"
                                       title="Quản lý hình ảnh tour">
                                        <i class="fa fa-picture-o"></i> Gallery
                                    </a>
                                    <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $id_goi ?>" 
                                       class="btn btn-warning btn-xs"
                                       style="margin-bottom: 3px;"
                                       title="Quản lý chính sách hủy/đổi">
                                        <i class="fa fa-file-text"></i> Chính sách
                                    </a>
                                    <a href="<?= BASE_URL ?>?act=tour-versions&id_goi=<?= $id_goi ?>" 
                                        class="btn btn-danger btn-xs"
                                        style="margin-bottom: 3px;"
                                        title="Quản lý phiên bản">
                                        <i class="fa fa-code-fork"></i> Versions
                                    </a>
                                    <a href="<?= BASE_URL ?>?act=tour-phanloai&id_goi=<?= $id_goi ?>" 
                                       class="btn btn-primary btn-xs"
                                       title="Quản lý loại tour & tags">
                                        <i class="fa fa-tags"></i> Phân loại
                                    </a>
                                    <a href="<?= BASE_URL ?>?act=tour-publish&id_goi=<?= $id_goi ?>" 
                                       class="btn btn-dark btn-xs"
                                       title="Kiểm tra & Publish">
                                        <i class="fa fa-rocket"></i> Publish
                                    </a>
                                </div>
                            </td>
                            
                        </tr>
                    <?php 
                        $cnt++;
                        } // end foreach
                    } // end if
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="assets/css/table-style.css" />
<link rel="stylesheet" type="text/css" href="assets/css/basictable.css" />
<script type="text/javascript" src="assets/js/jquery.basictable.min.js"></script>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="http://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
     var dt= $('#table').DataTable({
     	"dom": '<"pull-right"f>t<"row mt-1" <"col-sm-3" l><"col-sm-6" <"pull-right" p>>>',
     	"language": {
     		"lengthMenu": "Hiển thị _MENU_ trên 1 trang",
     		"zeroRecords": "Không tìm thấy nội dung cần tìm",
     		"infoEmpty": "Chưa có nội dung",
     		"infoFiltered": "(filtered from _MAX_ total records)",
     		"sSearch":"Tìm kiếm",
     		"oPaginate": {
                "sFirst": "Đầu",
                "sPrevious": "Trước",
                "sNext": "Tiếp",
                "sLast": "Cuối"
            }
        }
     });
 });
</script>
<style type="text/css">
	.dataTables_wrapper{ margin-top: 20px; }
    
    /* ✨ CSS CHO CỘT CHI TIẾT MỚI */
    .btn-group-vertical .btn {
        display: block;
        width: 100%;
        text-align: left;
        border-radius: 3px;
    }
    
    .btn-group-vertical .btn i {
        margin-right: 5px;
        width: 15px;
        text-align: center;
    }
    
    .btn-xs {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
    }
</style>