<!-- File: views/admin/forgot-password.php -->
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Quên mật khẩu - StarVel</title>
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

    .forgot-wrapper {
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

    .forgot-container {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 48px 40px;
      box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .forgot-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .forgot-icon-wrapper {
      width: 100px;
      height: 100px;
      margin: 0 auto 24px;
      position: relative;
    }

    .forgot-icon-bg {
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #f59e0b, #f97316);
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 12px 40px rgba(245, 158, 11, 0.3);
      transform: rotate(-5deg);
      transition: transform 0.3s ease;
    }

    .forgot-icon-wrapper:hover .forgot-icon-bg {
      transform: rotate(0deg) scale(1.05);
    }

    .forgot-icon-bg i {
      font-size: 48px;
      color: white;
      transform: rotate(5deg);
    }

    .forgot-header h1 {
      color: #1e293b;
      font-size: 32px;
      font-weight: 800;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .forgot-header p {
      color: #64748b;
      font-size: 15px;
      font-weight: 500;
    }

    .alert-success {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      padding: 16px 20px;
      border-radius: 14px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      font-weight: 500;
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.25);
      animation: slideDown 0.3s ease-out;
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

    .alert-success i,
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
      color: #f59e0b;
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
      border-color: #f59e0b;
      background: white;
      box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
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
      color: #f59e0b;
    }

    .btn-submit {
      width: 100%;
      padding: 18px;
      background: linear-gradient(135deg, #f59e0b, #f97316);
      border: none;
      border-radius: 14px;
      color: white;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
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
      box-shadow: 0 12px 32px rgba(245, 158, 11, 0.4);
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

    .info-box {
      background: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 24px;
      color: #0369a1;
      font-size: 14px;
      line-height: 1.6;
    }

    .info-box i {
      color: #0284c7;
      margin-right: 8px;
    }

    @media (max-width: 480px) {
      .forgot-container {
        padding: 40px 28px;
      }

      .forgot-header h1 {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>
  <div class="forgot-wrapper">
    <div class="forgot-container">
      <!-- Header -->
      <div class="forgot-header">
        <div class="forgot-icon-wrapper">
          <div class="forgot-icon-bg">
            <i class="fas fa-key"></i>
          </div>
        </div>
        <h1>Quên mật khẩu</h1>
        <p>Nhập tên đăng nhập và email để nhận link reset</p>
      </div>

      <!-- Success Message -->
      <?php if (!empty($success)): ?>
        <div class="alert-success">
          <i class="fas fa-check-circle"></i>
          <span><?= htmlspecialchars($success) ?></span>
        </div>
      <?php endif; ?>

      <!-- Reset Link Display -->
      <?php if (!empty($resetLinkDisplay)): ?>
        <div style="background: #f0f9ff; border: 2px solid #0ea5e9; border-radius: 14px; padding: 24px; margin-bottom: 24px;">
          <h3 style="color: #0369a1; font-size: 18px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-link"></i>
            Link reset mật khẩu
          </h3>
          <div style="background: white; padding: 16px; border-radius: 10px; margin-bottom: 12px; border: 1px solid #bae6fd;">
            <a href="<?= htmlspecialchars($resetLinkDisplay) ?>" 
               target="_blank"
               style="color: #0284c7; word-break: break-all; text-decoration: none; font-weight: 600; display: block; line-height: 1.6;">
              <?= htmlspecialchars($resetLinkDisplay) ?>
            </a>
          </div>
          <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <button onclick="copyToClipboard('<?= htmlspecialchars($resetLinkDisplay) ?>')" 
                    style="flex: 1; padding: 12px; background: #0ea5e9; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
              <i class="fas fa-copy"></i> Copy link
            </button>
            <a href="<?= htmlspecialchars($resetLinkDisplay) ?>" 
               target="_blank"
               style="flex: 1; padding: 12px; background: #10b981; color: white; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; text-align: center; transition: all 0.3s;">
              <i class="fas fa-external-link-alt"></i> Mở link
            </a>
          </div>
          <p style="margin-top: 16px; color: #0369a1; font-size: 13px; margin-bottom: 0;">
            <i class="fas fa-clock"></i> Link này có hiệu lực trong <strong>1 giờ</strong>
          </p>
        </div>
      <?php endif; ?>

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

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success">
          <i class="fas fa-check-circle"></i>
          <span><?= htmlspecialchars($_SESSION['success']) ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (empty($success) && empty($resetLinkDisplay)): ?>
        <!-- Info Box -->
        <div class="info-box">
          <i class="fas fa-info-circle"></i>
          Nhập tên đăng nhập và email đã đăng ký. Chúng tôi sẽ gửi link reset mật khẩu qua email của bạn.
        </div>

        <!-- Forgot Password Form -->
        <form action="<?= BASE_URL ?>?act=forgot-password-handle" method="post" id="forgotForm">
          <div class="form-group">
            <label for="username">
              <i class="fas fa-user"></i>
              Tên đăng nhập <span style="color: #ef4444;">*</span>
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
                autofocus
              >
              <i class="fas fa-user input-icon"></i>
            </div>
          </div>

          <div class="form-group">
            <label for="email">
              <i class="fas fa-envelope"></i>
              Email đã đăng ký <span style="color: #ef4444;">*</span>
            </label>
            <div class="input-wrapper">
              <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control"
                placeholder="Nhập email đã đăng ký với tài khoản"
                required
                autocomplete="email"
              >
              <i class="fas fa-envelope input-icon"></i>
            </div>
            <small style="color: #64748b; font-size: 12px; margin-top: 4px; display: block;">
              Email này phải khớp với email đã đăng ký trong hệ thống
            </small>
          </div>

          <button type="submit" class="btn-submit" id="btnSubmit">
            <span>Gửi link reset</span>
            <i class="fas fa-paper-plane"></i>
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
    const forgotForm = document.getElementById('forgotForm');
    const btnSubmit = document.getElementById('btnSubmit');

    if (forgotForm) {
      forgotForm.addEventListener('submit', function() {
        btnSubmit.classList.add('loading');
        btnSubmit.querySelector('span').textContent = 'Đang xử lý...';
      });
    }

    // Copy to clipboard function
    function copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Đã copy!';
        btn.style.background = '#10b981';
        
        setTimeout(function() {
          btn.innerHTML = originalText;
          btn.style.background = '#0ea5e9';
        }, 2000);
      }).catch(function(err) {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Đã copy!';
        btn.style.background = '#10b981';
        
        setTimeout(function() {
          btn.innerHTML = originalText;
          btn.style.background = '#0ea5e9';
        }, 2000);
      });
    }
  </script>
</body>
</html>

