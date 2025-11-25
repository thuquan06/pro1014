<!-- File: views/admin/reset-password.php -->
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đặt lại mật khẩu - StarVel</title>
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

    .reset-wrapper {
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

    .reset-container {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 48px 40px;
      box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .reset-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .reset-icon-wrapper {
      width: 100px;
      height: 100px;
      margin: 0 auto 24px;
      position: relative;
    }

    .reset-icon-bg {
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #10b981, #059669);
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 12px 40px rgba(16, 185, 129, 0.3);
      transform: rotate(-5deg);
      transition: transform 0.3s ease;
    }

    .reset-icon-wrapper:hover .reset-icon-bg {
      transform: rotate(0deg) scale(1.05);
    }

    .reset-icon-bg i {
      font-size: 48px;
      color: white;
      transform: rotate(5deg);
    }

    .reset-header h1 {
      color: #1e293b;
      font-size: 32px;
      font-weight: 800;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .reset-header p {
      color: #64748b;
      font-size: 15px;
      font-weight: 500;
    }

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
      color: #10b981;
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
      border-color: #10b981;
      background: white;
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
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
      color: #10b981;
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
      color: #10b981;
    }

    .password-strength {
      margin-top: 8px;
      font-size: 12px;
      color: #64748b;
    }

    .password-strength.weak {
      color: #ef4444;
    }

    .password-strength.medium {
      color: #f59e0b;
    }

    .password-strength.strong {
      color: #10b981;
    }

    .btn-submit {
      width: 100%;
      padding: 18px;
      background: linear-gradient(135deg, #10b981, #059669);
      border: none;
      border-radius: 14px;
      color: white;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      letter-spacing: 0.3px;
      text-transform: uppercase;
      font-size: 15px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(16, 185, 129, 0.4);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .btn-submit.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    .btn-submit.loading::after {
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

    .back-link {
      text-align: center;
      margin-top: 24px;
      padding-top: 24px;
      border-top: 1px solid #e2e8f0;
    }

    .back-link a {
      color: #2563eb;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s;
    }

    .back-link a:hover {
      color: #1d4ed8;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .reset-container {
        padding: 40px 28px;
      }

      .reset-header h1 {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>
  <div class="reset-wrapper">
    <div class="reset-container">
      <!-- Header -->
      <div class="reset-header">
        <div class="reset-icon-wrapper">
          <div class="reset-icon-bg">
            <i class="fas fa-lock"></i>
          </div>
        </div>
        <h1>Đặt lại mật khẩu</h1>
        <p>Nhập mật khẩu mới của bạn</p>
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

      <?php if (empty($error) && !empty($token)): ?>
        <!-- Reset Password Form -->
        <form action="<?= BASE_URL ?>?act=reset-password-handle" method="post" id="resetForm">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

          <div class="form-group">
            <label for="password">
              <i class="fas fa-lock"></i>
              Mật khẩu mới
            </label>
            <div class="input-wrapper">
              <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-control"
                placeholder="Nhập mật khẩu mới"
                required
                autocomplete="new-password"
                minlength="6"
                oninput="checkPasswordStrength(this.value)"
              >
              <i class="fas fa-lock input-icon"></i>
              <i class="fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
            <div class="password-strength" id="passwordStrength"></div>
          </div>

          <div class="form-group">
            <label for="password_confirm">
              <i class="fas fa-lock"></i>
              Xác nhận mật khẩu
            </label>
            <div class="input-wrapper">
              <input 
                type="password" 
                id="password_confirm" 
                name="password_confirm" 
                class="form-control"
                placeholder="Nhập lại mật khẩu mới"
                required
                autocomplete="new-password"
                minlength="6"
                oninput="checkPasswordMatch()"
              >
              <i class="fas fa-lock input-icon"></i>
              <i class="fas fa-eye password-toggle" id="togglePasswordConfirm"></i>
            </div>
            <div id="passwordMatch" style="margin-top: 8px; font-size: 12px;"></div>
          </div>

          <button type="submit" class="btn-submit" id="btnSubmit">
            <span>Đặt lại mật khẩu</span>
            <i class="fas fa-check"></i>
          </button>
        </form>
      <?php endif; ?>

      <!-- Back Link -->
      <div class="back-link">
        <a href="<?= BASE_URL ?>?act=login">
          <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
        </a>
      </div>
    </div>
  </div>

  <script>
    // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirm');

    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });
    }

    if (togglePasswordConfirm && passwordConfirmInput) {
      togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });
    }

    // Check Password Strength
    function checkPasswordStrength(password) {
      const strengthDiv = document.getElementById('passwordStrength');
      if (!strengthDiv) return;

      let strength = 0;
      let text = '';

      if (password.length >= 6) strength++;
      if (password.length >= 8) strength++;
      if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[^a-zA-Z0-9]/.test(password)) strength++;

      if (password.length === 0) {
        text = '';
      } else if (strength <= 2) {
        text = '<i class="fas fa-exclamation-circle"></i> Mật khẩu yếu';
        strengthDiv.className = 'password-strength weak';
      } else if (strength <= 3) {
        text = '<i class="fas fa-info-circle"></i> Mật khẩu trung bình';
        strengthDiv.className = 'password-strength medium';
      } else {
        text = '<i class="fas fa-check-circle"></i> Mật khẩu mạnh';
        strengthDiv.className = 'password-strength strong';
      }

      strengthDiv.innerHTML = text;
    }

    // Check Password Match
    function checkPasswordMatch() {
      const matchDiv = document.getElementById('passwordMatch');
      if (!matchDiv || !passwordInput || !passwordConfirmInput) return;

      if (passwordConfirmInput.value.length === 0) {
        matchDiv.innerHTML = '';
        return;
      }

      if (passwordInput.value === passwordConfirmInput.value) {
        matchDiv.innerHTML = '<span style="color: #10b981;"><i class="fas fa-check-circle"></i> Mật khẩu khớp</span>';
      } else {
        matchDiv.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Mật khẩu không khớp</span>';
      }
    }

    // Form Submit
    const resetForm = document.getElementById('resetForm');
    const btnSubmit = document.getElementById('btnSubmit');

    if (resetForm) {
      resetForm.addEventListener('submit', function(e) {
        if (passwordInput.value !== passwordConfirmInput.value) {
          e.preventDefault();
          alert('Mật khẩu không khớp!');
          return false;
        }

        if (passwordInput.value.length < 6) {
          e.preventDefault();
          alert('Mật khẩu phải có ít nhất 6 ký tự!');
          return false;
        }

        btnSubmit.classList.add('loading');
        btnSubmit.querySelector('span').textContent = 'Đang xử lý...';
      });
    }
  </script>
</body>
</html>

