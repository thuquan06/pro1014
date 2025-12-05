<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đăng nhập quản trị - StarVel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f5f2eb;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-wrapper { width: 100%; max-width: 420px; animation: fadeInUp 0.6s ease-out; }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-container {
      background: #fff8f0;
      border-radius: 24px;
      padding: 48px 40px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.12);
      border: 1px solid #e0d7c6;
    }

    .login-header { text-align: center; margin-bottom: 40px; }
    .login-icon-wrapper { width: 100px; height: 100px; margin: 0 auto 24px; }
    .login-icon-bg {
      width: 100%; height: 100%;
      background: linear-gradient(135deg,#d6c1a0,#f0e4d3);
      border-radius: 24px;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 12px 40px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
    }
    .login-icon-wrapper:hover .login-icon-bg { transform: scale(1.05); }
    .login-icon-bg i { font-size: 48px; color: #5b4b3a; }

    .login-header h1 { color: #5b4b3a; font-size: 32px; font-weight: 800; margin-bottom: 8px; }
    .login-header p { color: #8c7b6a; font-size: 15px; }

    .alert-error {
      background: #f8d7da; color: #842029;
      padding: 16px 20px; border-radius: 14px; margin-bottom: 24px;
      display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 500;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .alert-error i { font-size: 20px; }

    .form-group { margin-bottom: 24px; }
    .form-group label { display: flex; align-items: center; gap: 8px; color: #5b4b3a; font-size: 14px; font-weight: 600; margin-bottom: 10px; }
    .form-group label i { color: #a67c52; font-size: 16px; }

    .input-wrapper { position: relative; }
    .form-control {
      width: 100%; padding: 16px 20px 16px 50px;
      border: 2px solid #d8cbb7; border-radius: 14px;
      font-size: 15px; background: #fffaf5; color: #5b4b3a; font-weight: 500;
      transition: all 0.3s ease;
    }
    .form-control::placeholder { color: #a89c91; font-weight: 400; }
    .form-control:focus {
      outline: none; border-color: #a67c52;
      background: #fff; box-shadow: 0 0 0 4px rgba(166,124,82,0.15);
    }
    .input-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #a89c91; font-size: 18px; pointer-events: none; }

    .password-toggle { position: absolute; right: 18px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #a89c91; font-size: 18px; transition: color 0.3s; z-index: 10; }
    .password-toggle:hover { color: #a67c52; }

    .remember-forgot { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
    .checkbox-wrapper { display: flex; align-items: center; gap: 10px; }
    .checkbox-wrapper input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; accent-color: #a67c52; }
    .checkbox-wrapper label { color: #8c7b6a; font-size: 14px; cursor: pointer; margin: 0; font-weight: 500; }
    .forgot-link { color: #a67c52; font-size: 14px; text-decoration: none; font-weight: 600; }
    .forgot-link:hover { text-decoration: underline; }

    .btn-login {
      width: 100%; padding: 18px;
      background: linear-gradient(135deg,#a67c52,#d6c1a0);
      border: none; border-radius: 14px; color: #fff; font-size: 15px; font-weight: 700;
      cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 12px;
      letter-spacing: 0.3px;
    }
    .btn-login:hover { background: linear-gradient(135deg,#d6c1a0,#a67c52); box-shadow: 0 8px 28px rgba(166,124,82,0.3); transform: translateY(-2px); }

    .login-footer { text-align: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e0d7c6; }
    .login-footer p { color: #a89c91; font-size: 13px; margin: 0; font-weight: 500; }
    .login-footer a { color: #5b4b3a; text-decoration: none; font-weight: 700; }
    .login-footer a:hover { text-decoration: underline; }

    @media (max-width: 480px){
      .login-container{padding:40px 28px;}
      .login-header h1{font-size:28px;}
      .login-icon-wrapper{width:80px;height:80px;}
      .remember-forgot{flex-direction:column; gap:16px; align-items:flex-start;}
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-container">
      <div class="login-header">
        <div class="login-icon-wrapper">
          <div class="login-icon-bg">
            <i class="fas fa-shield-alt"></i>
          </div>
        </div>
        <h1>Đăng nhập</h1>
        <p>Hệ thống quản lý StarVel</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <span><?= htmlspecialchars($error) ?></span></div>
      <?php endif; ?>

      <form action="<?= BASE_URL ?>?act=login" method="post" id="loginForm">
        <div class="form-group">
          <label for="username"><i class="fas fa-user"></i> Tài khoản</label>
          <div class="input-wrapper">
            <input type="text" id="username" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required autocomplete="username">
            <i class="fas fa-user input-icon"></i>
          </div>
        </div>

        <div class="form-group">
          <label for="password"><i class="fas fa-lock"></i> Mật khẩu</label>
          <div class="input-wrapper">
            <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required autocomplete="current-password">
            <i class="fas fa-lock input-icon"></i>
            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
          </div>
        </div>

        <div class="remember-forgot">
          <div class="checkbox-wrapper">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Ghi nhớ đăng nhập</label>
          </div>
          <a href="<?= BASE_URL ?>?act=forgot-password" class="forgot-link">Quên mật khẩu?</a>
        </div>

        <button type="submit" class="btn-login" id="btnSubmit">
          <span>Đăng nhập</span>
          <i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <div class="login-footer">
        <p>© 2025 <a href="#">StarVel</a>. All rights reserved.</p>
      </div>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    const loginForm = document.getElementById('loginForm');
    const btnSubmit = document.getElementById('btnSubmit');
    loginForm.addEventListener('submit', function() {
      btnSubmit.classList.add('loading');
      btnSubmit.querySelector('span').textContent = 'Đang xử lý...';
    });

    window.addEventListener('load', function() {
      document.getElementById('username').focus();
    });
  </script>
</body>
</html>
