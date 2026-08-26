<?php
/**
 * DigiTrade Academy - Luxury Admin Login Portal
 */
declare(strict_types=1);

require_once __DIR__ . '/../api/config/auth.php';

// If already logged in, redirect to dashboard
if (isAuthenticatedAdmin()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
if (isset($_GET['error']) && $_GET['error'] === 'auth_required') {
    $error = 'Please log in to access the Admin Portal.';
}
if (isset($_GET['logged_out'])) {
    $logoutSuccess = 'You have been successfully logged out.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | DigiTrade Academy Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <style>
    :root {
      --maroon-dark: #4A0B11;
      --maroon-primary: #80141D;
      --maroon-light: #9E1B24;
      --maroon-glow: rgba(128, 20, 29, 0.4);
      --gold-primary: #D4AF37;
      --gold-light: #F2CA52;
      --gold-glow: rgba(212, 175, 55, 0.35);
      --bg-dark: #080A0F;
      --bg-card: #111522;
      --border-color: rgba(212, 175, 55, 0.22);
      --text-main: #FFFFFF;
      --text-muted: #94A3B8;
      --font-heading: 'Outfit', sans-serif;
      --font-body: 'Inter', sans-serif;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: var(--font-body);
      background-color: var(--bg-dark);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow-x: hidden;
      padding: 20px;
    }

    /* Ambient Lighting Background */
    .ambient-glow-1 {
      position: fixed;
      width: 500px;
      height: 500px;
      top: -150px;
      left: -150px;
      background: radial-gradient(circle, var(--maroon-primary) 0%, transparent 70%);
      opacity: 0.35;
      filter: blur(80px);
      pointer-events: none;
      z-index: 0;
    }

    .ambient-glow-2 {
      position: fixed;
      width: 450px;
      height: 450px;
      bottom: -150px;
      right: -150px;
      background: radial-gradient(circle, var(--gold-primary) 0%, transparent 70%);
      opacity: 0.18;
      filter: blur(90px);
      pointer-events: none;
      z-index: 0;
    }

    .login-wrapper {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 460px;
    }

    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(128, 20, 29, 0.3);
      border: 1px solid var(--border-color);
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 0.8rem;
      color: var(--gold-light);
      letter-spacing: 1px;
      text-transform: uppercase;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .brand-title {
      font-family: var(--font-heading);
      font-size: 1.9rem;
      font-weight: 800;
      letter-spacing: 0.5px;
      color: #fff;
    }

    .brand-title span {
      color: var(--gold-primary);
      text-shadow: 0 0 15px var(--gold-glow);
    }

    .brand-subtitle {
      font-size: 0.9rem;
      color: var(--text-muted);
      margin-top: 4px;
    }

    .login-card {
      background: rgba(17, 21, 34, 0.85);
      backdrop-filter: blur(16px);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 38px 32px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(128, 20, 29, 0.15);
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--maroon-primary), var(--gold-primary), var(--maroon-primary));
    }

    .card-title {
      font-family: var(--font-heading);
      font-size: 1.35rem;
      font-weight: 700;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .card-title i {
      color: var(--gold-primary);
    }

    .card-desc {
      font-size: 0.88rem;
      color: var(--text-muted);
      margin-bottom: 24px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: #E2E8F0;
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i.icon-lead {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 0.95rem;
      transition: color 0.3s;
    }

    .form-input {
      width: 100%;
      background: rgba(8, 10, 15, 0.7);
      border: 1px solid rgba(212, 175, 55, 0.2);
      border-radius: 10px;
      padding: 13px 42px 13px 42px;
      color: #fff;
      font-size: 0.95rem;
      font-family: var(--font-body);
      transition: all 0.3s ease;
      outline: none;
    }

    .form-input:focus {
      border-color: var(--gold-primary);
      box-shadow: 0 0 15px var(--gold-glow);
      background: rgba(8, 10, 15, 0.9);
    }

    .toggle-pass {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      cursor: pointer;
      font-size: 0.9rem;
      background: none;
      border: none;
      padding: 0;
    }

    .toggle-pass:hover {
      color: var(--gold-light);
    }

    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, #9E1B24 0%, #80141D 50%, #5E0C13 100%);
      color: #FFFFFF;
      border: 1px solid rgba(212, 175, 55, 0.4);
      padding: 14px;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 700;
      font-family: var(--font-heading);
      letter-spacing: 0.5px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 8px 20px rgba(128, 20, 29, 0.4);
      transition: all 0.3s ease;
      margin-top: 26px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 25px rgba(212, 175, 55, 0.3), 0 0 20px var(--maroon-glow);
      border-color: var(--gold-light);
      background: linear-gradient(135deg, #B5222D 0%, #9E1B24 50%, #701018 100%);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .credentials-hint {
      margin-top: 24px;
      background: rgba(212, 175, 55, 0.08);
      border: 1px dashed rgba(212, 175, 55, 0.3);
      border-radius: 10px;
      padding: 12px 14px;
      font-size: 0.8rem;
      color: #CBD5E1;
      line-height: 1.5;
    }

    .credentials-hint strong {
      color: var(--gold-light);
    }

    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-error {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.4);
      color: #FCA5A5;
    }

    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.4);
      color: #6EE7B7;
    }

    .back-home {
      text-align: center;
      margin-top: 22px;
    }

    .back-home a {
      color: var(--text-muted);
      text-decoration: none;
      font-size: 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: color 0.3s;
    }

    .back-home a:hover {
      color: var(--gold-primary);
    }

    .spinner {
      display: none;
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>

  <div class="ambient-glow-1"></div>
  <div class="ambient-glow-2"></div>

  <div class="login-wrapper">
    <!-- Brand Header -->
    <div class="brand-header">
      <div class="brand-badge">
        <i class="fas fa-shield-halved"></i> SECURE ADMIN PORTAL
      </div>
      <h1 class="brand-title">DIGI<span>TRADE</span> ACADEMY</h1>
      <p class="brand-subtitle">Forex &amp; Performance Marketing Management Suite</p>
    </div>

    <!-- Login Card -->
    <div class="login-card">
      <h2 class="card-title">
        <i class="fas fa-lock"></i> Mentor &amp; Admin Sign In
      </h2>
      <p class="card-desc">Enter your administrator credentials to access real-time leads.</p>

      <div id="alertBox">
        <?php if (!empty($error)): ?>
          <div class="alert alert-error"><i class="fas fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($logoutSuccess)): ?>
          <div class="alert alert-success"><i class="fas fa-circle-check"></i> <?= htmlspecialchars($logoutSuccess) ?></div>
        <?php endif; ?>
      </div>

      <form id="adminLoginForm">
        <div class="form-group">
          <label class="form-label" for="username">Admin Username</label>
          <div class="input-wrapper">
            <i class="fas fa-user-shield icon-lead"></i>
            <input type="text" id="username" name="username" class="form-input" placeholder="e.g. admin" required autocomplete="username">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrapper">
            <i class="fas fa-key icon-lead"></i>
            <input type="password" id="password" name="password" class="form-input" placeholder="••••••••••••"  required autocomplete="current-password">
            <button type="button" class="toggle-pass" id="togglePassBtn" aria-label="Toggle Password Visibility">
              <i class="fas fa-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login" id="submitBtn">
          <span class="spinner" id="loginSpinner"></span>
          <span id="btnText"><i class="fas fa-arrow-right-to-bracket"></i> Authenticate &amp; Enter</span>
        </button>
      </form>

    </div>

    <!-- Back Link -->
    <div class="back-home">
      <a href="../index.html"><i class="fas fa-arrow-left"></i> Return to Main Website</a>
    </div>
  </div>

  <script>
    // Password visibility toggle
    const togglePassBtn = document.getElementById('togglePassBtn');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassBtn.addEventListener('click', () => {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    });

    // AJAX Login Form Submit
    const loginForm = document.getElementById('adminLoginForm');
    const alertBox = document.getElementById('alertBox');
    const submitBtn = document.getElementById('submitBtn');
    const loginSpinner = document.getElementById('loginSpinner');
    const btnText = document.getElementById('btnText');

    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;

      if (!username || !password) return;

      submitBtn.disabled = true;
      loginSpinner.style.display = 'inline-block';
      btnText.textContent = 'Verifying...';
      alertBox.innerHTML = '';

      try {
        const response = await fetch('../api/admin-login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password })
        });

        const data = await response.json();

        if (data.success) {
          alertBox.innerHTML = `
            <div class="alert alert-success">
              <i class="fas fa-circle-check"></i> ${data.message || 'Access granted! Loading dashboard...'}
            </div>`;
          setTimeout(() => {
            window.location.href = data.data?.redirect || 'dashboard.php';
          }, 800);
        } else {
          alertBox.innerHTML = `
            <div class="alert alert-error">
              <i class="fas fa-circle-exclamation"></i> ${data.message || 'Login failed. Invalid credentials.'}
            </div>`;
          submitBtn.disabled = false;
          loginSpinner.style.display = 'none';
          btnText.innerHTML = '<i class="fas fa-arrow-right-to-bracket"></i> Authenticate &amp; Enter';
        }
      } catch (err) {
        alertBox.innerHTML = `
          <div class="alert alert-error">
            <i class="fas fa-circle-exclamation"></i> Network error connecting to API. Please ensure server is running.
          </div>`;
        submitBtn.disabled = false;
        loginSpinner.style.display = 'none';
        btnText.innerHTML = '<i class="fas fa-arrow-right-to-bracket"></i> Authenticate &amp; Enter';
      }
    });
  </script>
</body>
</html>
