<?php
// File: views/admin/tours/update-image.php (File mới)

/*
 * Biến $tour (chi tiết tour) đã được AdminController::updateTourImage()
 * chuẩn bị (ở phương thức GET).
 * Biến $msg/$error cũng được controller truyền vào nếu có.
 */
?>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?act=admin">Home</a><i class="fa fa-angle-right"></i>Cập nhật hình ảnh</li>
</ol>
<div class="grid-form">
    <div class="grid-form1">
        <h3>Cập nhật hình ảnh </h3>
        <?php if(isset($error) && $error){?><div class="errorWrap"><strong>LỖI</strong>:<?php echo htmlentities($error); ?> </div><?php } 
        else if(isset($msg) && $msg){?><div class="succWrap"><strong>THÀNH CÔNG</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
        
        <div class="tab-content">
            <div class="tab-pane active" id="horizontal-form">
                
                <form class="form-horizontal" name="package" method="post" action="<?php echo BASE_URL; ?>?act=admin-tour-update-image" enctype="multipart/form-data">
                    
                    <input type="hidden" name="id" value="<?php echo htmlentities($tour['id_goi']); ?>">

                    <?php if ($tour) { ?>	
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label"> Package Image </label>
                            <div class="col-sm-8">
                                <img src="<?php echo htmlentities($tour['hinhanh']);?>" width="200">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="packageimage" class="col-sm-2 control-label">Ảnh mới</label>
                            <div class="col-sm-8">
                                <input type="file" name="packageimage" id="packageimage" required>
                            </div>
                        </div>	
                    <?php } ?>

                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">
                            <button type="submit" name="submit" class="btn-primary btn">Cập nhật</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>