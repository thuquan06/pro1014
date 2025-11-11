<!-- File: views/admin/login.php -->
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đăng nhập quản trị</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;background:#f5f6f8}
    .box{max-width:420px;margin:10vh auto;background:#fff;border-radius:12px;padding:28px;
         box-shadow:0 10px 30px rgba(0,0,0,.06)}
    h1{margin:0 0 18px;font-size:22px}
    .field{margin-bottom:14px}
    label{display:block;margin-bottom:6px;color:#333}
    input{width:100%;padding:10px 12px;border:1px solid #dcdfe5;border-radius:8px}
    .btn{width:100%;padding:10px 14px;border:0;border-radius:8px;background:#0d6efd;color:#fff;cursor:pointer}
    .err{background:#fff0f0;border:1px solid #ffc9c9;color:#c00;padding:10px 12px;border-radius:8px;margin-bottom:12px}
  </style>
</head>
<body>
  <div class="box">
    <h1>Đăng nhập quản trị</h1>
    <?php if (!empty($error)): ?>
      <div class="err"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="<?= BASE_URL ?>?act=login-handle" method="post">
      <div class="field">
        <label for="username">Tài khoản</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="field">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button class="btn" type="submit">Đăng nhập</button>
    </form>
  </div>
</body>
</html>
