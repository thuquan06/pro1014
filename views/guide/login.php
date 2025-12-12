<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập - Hướng dẫn viên | StarVel Travel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: moveBackground 20s linear infinite;
      pointer-events: none;
    }
    
    @keyframes moveBackground {
      0% { transform: translate(0, 0); }
      100% { transform: translate(50px, 50px); }
    }
    
    .login-wrapper {
      width: 100%;
      max-width: 440px;
      animation: fadeInUp 0.6s ease-out;
      position: relative;
      z-index: 1;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .login-container {
      background: #ffffff;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1);
      width: 100%;
      padding: 48px 40px;
      backdrop-filter: blur(10px);
    }
    
    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }
    
    .login-icon-wrapper {
      width: 100px;
      height: 100px;
      margin: 0 auto 24px;
    }
    
    .login-icon-bg {
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 12px 40px rgba(16, 185, 129, 0.3);
      transition: transform 0.3s ease;
    }
    
    .login-icon-wrapper:hover .login-icon-bg {
      transform: scale(1.05) rotate(5deg);
    }
    
    .login-icon-bg i {
      font-size: 48px;
      color: white;
    }
    
    .login-header h1 {
      font-size: 32px;
      font-weight: 800;
      color: #1f2937;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }
    
    .login-header p {
      font-size: 15px;
      color: #6b7280;
      font-weight: 500;
    }
    
    .alert-error {
      padding: 16px 20px;
      background: #fee2e2;
      border: 1px solid #ef4444;
      border-radius: 14px;
      color: #991b1b;
      margin-bottom: 24px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }
    
    .alert-error i {
      color: #ef4444;
      font-size: 20px;
    }
    
    .form-group {
      margin-bottom: 24px;
    }
    
    .form-label {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 10px;
      font-weight: 600;
      color: #1f2937;
      font-size: 14px;
    }
    
    .form-label i {
      color: #10b981;
      font-size: 16px;
    }
    
    .input-wrapper {
      position: relative;
    }
    
    .form-control {
      width: 100%;
      padding: 16px 20px 16px 50px;
      border: 2px solid #e5e7eb;
      border-radius: 14px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f9fafb;
      color: #1f2937;
      font-weight: 500;
    }
    
    .form-control::placeholder {
      color: #9ca3af;
      font-weight: 400;
    }
    
    .form-control:focus {
      outline: none;
      border-color: #10b981;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }
    
    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      font-size: 18px;
      pointer-events: none;
      transition: color 0.3s;
    }
    
    .form-control:focus + .input-icon,
    .input-wrapper:focus-within .input-icon {
      color: #10b981;
    }
    
    .password-toggle {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #9ca3af;
      font-size: 18px;
      transition: color 0.3s;
      z-index: 10;
    }
    
    .password-toggle:hover {
      color: #10b981;
    }
    
    .btn-login {
      width: 100%;
      padding: 18px;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      border: none;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      letter-spacing: 0.3px;
      box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }
    
    .btn-login:hover {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      box-shadow: 0 8px 28px rgba(16, 185, 129, 0.4);
      transform: translateY(-2px);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    .btn-login.loading {
      opacity: 0.7;
      cursor: not-allowed;
    }
    
    .login-footer {
      text-align: center;
      margin-top: 32px;
      padding-top: 24px;
      border-top: 1px solid #e5e7eb;
    }
    
    .login-footer a {
      color: #10b981;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
    }
    
    .login-footer a:hover {
      color: #059669;
      text-decoration: underline;
      transform: translateX(-4px);
    }
    
    @media (max-width: 480px) {
      .login-container {
        padding: 40px 28px;
      }
      
      .login-header h1 {
        font-size: 28px;
      }
      
      .login-icon-wrapper {
        width: 80px;
        height: 80px;
      }
      
      .login-icon-bg i {
        font-size: 40px;
      }
      
      .form-control {
        padding: 14px 18px 14px 48px;
        font-size: 14px;
      }
      
      .btn-login {
        padding: 16px;
        font-size: 15px;
      }
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-container">
      <div class="login-header">
        <div class="login-icon-wrapper">
          <div class="login-icon-bg">
            <i class="fas fa-user-tie"></i>
          </div>
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
      
      <form method="POST" action="?act=guide" id="loginForm">
        <div class="form-group">
          <label class="form-label" for="email">
            <i class="fas fa-envelope"></i> Email
          </label>
          <div class="input-wrapper">
            <input 
              type="email" 
              id="email" 
              name="email" 
              class="form-control" 
              placeholder="Nhập email của bạn"
              required
              autofocus
              autocomplete="email"
            >
            <i class="fas fa-envelope input-icon"></i>
          </div>
        </div>
        
        <div class="form-group">
          <label class="form-label" for="password">
            <i class="fas fa-lock"></i> Mật khẩu
          </label>
          <div class="input-wrapper">
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-control" 
              placeholder="Nhập mật khẩu"
              required
              autocomplete="current-password"
            >
            <i class="fas fa-lock input-icon"></i>
            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
          </div>
        </div>
        
        <button type="submit" class="btn-login" id="btnSubmit">
          <span>Đăng nhập</span>
          <i class="fas fa-arrow-right"></i>
        </button>
      </form>
      
      <div class="login-footer">
        <a href="<?= BASE_URL ?>?act=home">
          <i class="fas fa-arrow-left"></i> Về trang chủ
        </a>
      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });
    }
    
    // Form submission loading state
    const loginForm = document.getElementById('loginForm');
    const btnSubmit = document.getElementById('btnSubmit');
    
    if (loginForm && btnSubmit) {
      loginForm.addEventListener('submit', function() {
        btnSubmit.classList.add('loading');
        const span = btnSubmit.querySelector('span');
        if (span) {
          span.textContent = 'Đang xử lý...';
        }
      });
    }
    
    // Auto focus on email input
    window.addEventListener('load', function() {
      const emailInput = document.getElementById('email');
      if (emailInput) {
        emailInput.focus();
      }
    });
  </script>
</body>
</html>

