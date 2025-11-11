<?php
// File: views/admin/tours/list.php (File mới)

/*
 * Biến $tours (chứa mảng tour) đã được AdminController::listTours()
 * chuẩn bị và truyền vào file layout.php
 */
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
                        <th style="text-align: center;">Hoạt động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $cnt = 1;
                    if (!empty($tours)) {
                        foreach ($tours as $tour) { 
                    ?>		
                        <tr>
                            <td><?php echo htmlentities($cnt);?></td>
                            <td><?php echo htmlentities($tour['quocgia']);?></td>
                            <td><?php echo htmlentities($tour['ten_tinh']);?></td>
                            <td><?php echo htmlentities($tour['tengoi']);?></td>
                            <td><?php echo htmlentities($tour['noixuatphat']);?></td>
                            <td><?php echo htmlentities($tour['vitri']);?></td>
                            <td style="width: 150px;">Người lớn: <?php echo htmlentities($tour['giagoi']);?>
                                <br>Trẻ em: <?php echo htmlentities($tour['giatreem']);?>
                                <br>Trẻ nhỏ: <?php echo htmlentities($tour['giatrenho']);?>
                                <br>Phòng: <?php echo htmlentities($tour['giaphongdon']);?>
                            </td>
                            <td><?php echo htmlentities($tour['songay']);?></td>
                            <td><?php echo htmlentities($tour['giodi']);?>
                                <br><?php echo date("d-m-Y", strtotime($tour['ngayxuatphat'])); ?>
                            </td>
                            <td><?php echo date("d-m-Y", strtotime($tour['ngayve'])); ?></td>
                            <td><?php echo htmlentities($tour['phuongtien']);?></td>
                            <td><?php echo date("d-m-Y", strtotime($tour['ngaydang'])); ?></td>
                            <td style="width: 194px;">
                                <a href="<?php echo BASE_URL; ?>?act=admin-tour-delete&id=<?php echo htmlentities($tour['id_goi']);?>" onclick="return confirm('Bạn có chắc chắn xóa')">
                                    <button type="button" class="btn btn-primary btn-block" style="border-bottom: 2px solid;">Xóa</button>
                                </a>
                                <a href="<?php echo BASE_URL; ?>?act=admin-tour-edit&id=<?php echo htmlentities($tour['id_goi']);?>">
                                    <button type="button" class="btn btn-primary btn-block" >Chỉnh sửa</button>
                                </a>
                            </td>
                        </tr>
                    <?php 
                        $cnt=$cnt+1;
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
</style>