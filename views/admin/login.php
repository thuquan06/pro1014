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
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e8ba3 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow: hidden;
    }

    /* Animated Background Shapes */
    body::before {
      content: '';
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      border-radius: 50%;
      top: -250px;
      right: -250px;
      animation: float 20s ease-in-out infinite;
    }

    body::after {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
      border-radius: 50%;
      bottom: -200px;
      left: -200px;
      animation: float 15s ease-in-out infinite reverse;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(30px, 30px) scale(1.1); }
    }

    .login-wrapper {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 420px;
      animation: fadeInUp 0.6s ease-out;
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
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 48px 40px;
      box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25),
                  0 0 0 1px rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .login-icon-wrapper {
      width: 100px;
      height: 100px;
      margin: 0 auto 24px;
      position: relative;
    }

    .login-icon-bg {
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #2563eb, #3b82f6);
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 12px 40px rgba(37, 99, 235, 0.3);
      transform: rotate(-5deg);
      transition: transform 0.3s ease;
    }

    .login-icon-wrapper:hover .login-icon-bg {
      transform: rotate(0deg) scale(1.05);
    }

    .login-icon-bg i {
      font-size: 48px;
      color: white;
      transform: rotate(5deg);
    }

    .login-header h1 {
      color: #1e293b;
      font-size: 32px;
      font-weight: 800;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .login-header p {
      color: #64748b;
      font-size: 15px;
      font-weight: 500;
    }

    /* Alert Error */
    .alert-error {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      padding: 16px 20px;
      border-radius: 14px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      font-weight: 500;
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.25);
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-error i {
      font-size: 20px;
      flex-shrink: 0;
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      display: block;
      color: #334155;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-group label i {
      color: #2563eb;
      font-size: 16px;
    }

    .input-wrapper {
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 16px 20px 16px 50px;
      border: 2px solid #e2e8f0;
      border-radius: 14px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8fafc;
      color: #1e293b;
      font-weight: 500;
    }

    .form-control::placeholder {
      color: #94a3b8;
      font-weight: 400;
    }

    .form-control:focus {
      outline: none;
      border-color: #2563eb;
      background: white;
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
      transform: translateY(-1px);
    }

    .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 18px;
      transition: color 0.3s;
      pointer-events: none;
    }

    .form-control:focus ~ .input-icon {
      color: #2563eb;
    }

    .password-toggle {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #94a3b8;
      font-size: 18px;
      transition: color 0.3s;
      z-index: 10;
    }

    .password-toggle:hover {
      color: #2563eb;
    }

    /* Remember & Forgot */
    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .checkbox-wrapper input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #2563eb;
    }

    .checkbox-wrapper label {
      color: #64748b;
      font-size: 14px;
      cursor: pointer;
      margin: 0;
      font-weight: 500;
    }

    .forgot-link {
      color: #2563eb;
      font-size: 14px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s;
    }

    .forgot-link:hover {
      color: #1d4ed8;
      text-decoration: underline;
    }

    /* Button */
    .btn-login {
      width: 100%;
      padding: 18px;
      background: linear-gradient(135deg, #2563eb, #3b82f6);
      border: none;
      border-radius: 14px;
      color: white;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 24px rgba(37, 99, 235, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      letter-spacing: 0.3px;
      text-transform: uppercase;
      font-size: 15px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(37, 99, 235, 0.4);
      background: linear-gradient(135deg, #1d4ed8, #2563eb);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .btn-login i {
      font-size: 18px;
    }

    .btn-login.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    .btn-login.loading::after {
      content: '';
      width: 18px;
      height: 18px;
      border: 2px solid white;
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
      margin-left: 8px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Footer */
    .login-footer {
      text-align: center;
      margin-top: 32px;
      padding-top: 24px;
      border-top: 1px solid #e2e8f0;
    }

    .login-footer p {
      color: #94a3b8;
      font-size: 13px;
      margin: 0;
      font-weight: 500;
    }

    .login-footer a {
      color: #2563eb;
      text-decoration: none;
      font-weight: 700;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }

    /* Responsive */
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

      .remember-forgot {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-container">
      <!-- Header -->
      <div class="login-header">
        <div class="login-icon-wrapper">
          <div class="login-icon-bg">
            <i class="fas fa-shield-alt"></i>
          </div>
        </div>
        <h1>Đăng nhập</h1>
        <p>Hệ thống quản lý StarVel</p>
      </div>

      <!-- Error Message -->
      <?php if (!empty($error)): ?>
        <div class="alert-error">
          <i class="fas fa-exclamation-circle"></i>
          <span><?= htmlspecialchars($error) ?></span>
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
      <form action="<?= BASE_URL ?>?act=login" method="post" id="loginForm">
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
    window.addEventListener('load', function() {
      document.getElementById('username').focus();
    });

    // Add enter key support
    document.addEventListener('keypress', function(e) {
      if (e.key === 'Enter' && document.activeElement.tagName !== 'BUTTON') {
        loginForm.requestSubmit();
      }
    });
  </script>
</body>
</html>
