<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập - Hướng dẫn viên | StarVel Travel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', -apple-system, sans-serif;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .login-container {
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 420px;
      padding: 40px;
    }
    
    .login-header {
      text-align: center;
      margin-bottom: 32px;
    }
    
    .login-icon {
      width: 64px;
      height: 64px;
      background: #10b981;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      color: white;
      font-size: 28px;
    }
    
    .login-header h1 {
      font-size: 24px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 8px;
    }
    
    .login-header p {
      font-size: 14px;
      color: #6b7280;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #1f2937;
      font-size: 14px;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.2s;
      background: white;
      color: #1f2937;
    }
    
    .form-control:focus {
      outline: none;
      border-color: #10b981;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    .btn-login {
      width: 100%;
      padding: 12px;
      background: #10b981;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      margin-top: 8px;
    }
    
    .btn-login:hover {
      background: #059669;
      transform: translateY(-1px);
    }
    
    .alert-error {
      padding: 12px 16px;
      background: #fee2e2;
      border: 1px solid #ef4444;
      border-radius: 8px;
      color: #991b1b;
      margin-bottom: 20px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .alert-error i {
      color: #ef4444;
    }
    
    .login-footer {
      text-align: center;
      margin-top: 24px;
      padding-top: 24px;
      border-top: 1px solid #e5e7eb;
    }
    
    .login-footer a {
      color: #10b981;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
    }
    
    .login-footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <div class="login-icon">
        <i class="fas fa-user-tie"></i>
      </div>
      <h1>Đăng nhập</h1>
      <p>Hướng dẫn viên</p>
    </div>
    
    <?php if (!empty($error)): ?>
      <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
    <?php endif; ?>
    
    <form method="POST" action="?act=guide">
      <div class="form-group">
        <label class="form-label" for="email">
          <i class="fas fa-envelope"></i> Email
        </label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          class="form-control" 
          placeholder="Nhập email của bạn"
          required
          autofocus
        >
      </div>
      
      <div class="form-group">
        <label class="form-label" for="password">
          <i class="fas fa-lock"></i> Mật khẩu (CMND/CCCD)
        </label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          class="form-control" 
          placeholder="Nhập CMND/CCCD"
          required
        >
      </div>
      
      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập
      </button>
    </form>
    
    <div class="login-footer">
      <a href="<?= BASE_URL ?>?act=home">
        <i class="fas fa-arrow-left"></i> Về trang chủ
      </a>
    </div>
  </div>
</body>
</html>

