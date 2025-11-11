<?php
// File: views/admin/login.php (File mới)

// Biến $error được truyền từ AdminController::handleLogin() nếu đăng nhập thất bại
$error = $error ?? null; 
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Trang quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    
    <link href="assets/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <link href="assets/css/style.css" rel='stylesheet' type='text/css' />
    <link href="assets/css/font-awesome.css" rel="stylesheet"> 
    <script src="assets/js/jquery-2.1.4.min.js"></script>
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
    <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="assets/css/icon-font.min.css" type='text/css' />
    
    <style>
         .main-wthree{
			background-color: whitesmoke !important;
		 }
         .back a{
			width: 150px !important;
			background-color: #005377 !important;
			height: 47px !important;
			color: whitesmoke !important;
			line-height: 30px !important;
			text-decoration: none !important;
			position: relative !important;
			top: -80px !important;
		 }
		 .back a:hover{
			background-color: #e74c3c !important;
		 }
		 .main-wthree h2{
			color: #005377 !important;
		 }
		 .main-wthree form span{
           background-color: #005377 !important;
		 }
         /* Thêm style cho thông báo lỗi */
         .errorWrap {
            padding: 10px;
            margin: 10px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            color: #dd3d36;
            font-weight: bold;
         }
	</style>
</head> 
<body>
    <div class="main-wthree">
        <div class="container">
            <div class="sin-w3-agile">
                <h2>Đăng nhập</h2>
                
                <?php if($error){?><div class="errorWrap"><strong>LỖI</strong>: <?php echo htmlentities($error); ?> </div><?php } ?>

                <form method="post" action="<?php echo BASE_URL; ?>?act=login-handle">
                    <div class="username">
                        <span class="username">Tài khoản:</span>
                        <input type="text" name="username" class="name" placeholder="" required="">
                        <div class="clearfix"></div>
                    </div>
                    <div class="password-agileits">
                        <span class="username">Mật khẩu:</span>
                        <input type="password" name="password" class="password" placeholder="" required="">
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="login-w3">
                        <input type="submit" class="login" name="login" value="Đăng nhập">
                    </div>
                    <div class="clearfix"></div>
                </form>
                <div class="back">
                    <a href="<?php echo BASE_URL; ?>?act=home">Trở lại</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>