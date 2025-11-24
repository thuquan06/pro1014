<!-- File: views/admin/login.php -->
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đăng nhập quản trị - StarVel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    /* Background Animation */
    body::before {
      content: '';
      position: absolute;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: moveBackground 20s linear infinite;
    }

    @keyframes moveBackground {
      0% { transform: translate(0, 0); }
      100% { transform: translate(50px, 50px); }
    }

    .login-container {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 50px 40px;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 450px;
      animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .login-logo {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .login-logo i {
      font-size: 40px;
      color: white;
    }

    .login-header h1 {
      color: #333;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .login-header p {
      color: #666;
      font-size: 15px;
      font-weight: 400;
    }

    /* Alert Error */
    .alert-error {
      background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
      color: white;
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      box-shadow: 0 4px 15px rgba(238, 90, 111, 0.3);
      animation: shake 0.5s;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }

    .alert-error i {
      font-size: 20px;
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    .form-group label {
      display: block;
      color: #333;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-group label i {
      color: #667eea;
      font-size: 16px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
      font-size: 18px;
      transition: color 0.3s;
    }

    .form-control {
      width: 100%;
      padding: 14px 15px 14px 45px;
      border: 2px solid #e1e4e8;
      border-radius: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: white;
    }

    .form-control:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-control:focus + .input-icon {
      color: #667eea;
    }

    /* Password Toggle */
    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #999;
      font-size: 18px;
      transition: color 0.3s;
    }

    .password-toggle:hover {
      color: #667eea;
    }

    /* Remember Me */
    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .checkbox-wrapper input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .checkbox-wrapper label {
      color: #666;
      font-size: 14px;
      cursor: pointer;
      margin: 0;
    }

    .forgot-link {
      color: #667eea;
      font-size: 14px;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    .forgot-link:hover {
      color: #5568d3;
      text-decoration: underline;
    }

    /* Button */
    .btn-login {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login i {
      font-size: 18px;
    }

    /* Footer */
    .login-footer {
      text-align: center;
      margin-top: 30px;
      padding-top: 25px;
      border-top: 1px solid #e1e4e8;
    }

    .login-footer p {
      color: #999;
      font-size: 13px;
      margin: 0;
    }

    .login-footer a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }

    /* Loading State */
    .btn-login.loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .btn-login.loading::after {
      content: '';
      width: 16px;
      height: 16px;
      border: 2px solid white;
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
      margin-left: 10px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 480px) {
      .login-container {
        padding: 40px 30px;
      }

      .login-header h1 {
        font-size: 24px;
      }

      .remember-forgot {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- Header -->
    <div class="login-header">
      <div class="login-logo">
        <i class="fas fa-plane-departure"></i>
      </div>
      <h1>Admin</h1>
      <p>Hệ thống quản lý đặt tour</p>
    </div>

    <!-- Error Message -->
    <?php if (!empty($error)): ?>
      <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
    <?php endif; ?>
    
    <!-- Debug Link (chỉ hiện khi đang dev) -->
    <?php if (!empty($error)): ?>
      <div style="text-align:center; margin-top:10px; font-size:12px;">
        <a href="check_rate_limit.php" style="color:#666; text-decoration:none;" target="_blank">
          🔍 Check Rate Limit Status
        </a>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= htmlspecialchars($_SESSION['error']) ?></span>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="<?= BASE_URL ?>?act=login-handle" method="post" id="loginForm">
      <!-- Username -->
      <div class="form-group">
        <label for="username">
          <i class="fas fa-user"></i>
          Tài khoản
        </label>
        <div class="input-wrapper">
          <input 
            type="text" 
            id="username" 
            name="username" 
            class="form-control"
            placeholder="Nhập tên đăng nhập"
            required
            autocomplete="username"
          >
          <i class="fas fa-user input-icon"></i>
        </div>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password">
          <i class="fas fa-lock"></i>
          Mật khẩu
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

      <!-- Remember & Forgot -->
      <div class="remember-forgot">
        <div class="checkbox-wrapper">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember">Ghi nhớ đăng nhập</label>
        </div>
        <a href="<?= BASE_URL ?>?act=forgot-password" class="forgot-link">
          Quên mật khẩu?
        </a>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn-login" id="btnSubmit">
        <span>Đăng nhập</span>
        <i class="fas fa-arrow-right"></i>
      </button>
    </form>

    <!-- Footer -->
    <div class="login-footer">
      <p>© 2025 <a href="#">StarVel</a>. All rights reserved.</p>
    </div>
  </div>

  <script>
    // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    // Form Submit Loading
    const loginForm = document.getElementById('loginForm');
    const btnSubmit = document.getElementById('btnSubmit');

    loginForm.addEventListener('submit', function() {
      btnSubmit.classList.add('loading');
      btnSubmit.querySelector('span').textContent = 'Đang xử lý...';
    });

    // Auto focus on username
    document.getElementById('username').focus();
  </script>
</body>
</html>