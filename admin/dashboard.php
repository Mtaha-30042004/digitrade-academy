<?php
/**
 * DigiTrade Academy - Luxury Maroon & Gold Admin Portal
 * Live Admissions & Leads Management Suite
 */
declare(strict_types=1);

require_once __DIR__ . '/../api/config/auth.php';

// Auth Protection
requireAdminPageAuth();

$adminName = $_SESSION['admin_name'] ?? 'Admin';
$adminUser = $_SESSION['admin_username'] ?? 'admin';
$adminRole = $_SESSION['admin_role'] ?? 'Master Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | DigiTrade Academy Admissions Portal</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    :root {
      --maroon-dark: #38070C;
      --maroon-primary: #80141D;
      --maroon-light: #9E1B24;
      --maroon-glow: rgba(128, 20, 29, 0.4);
      --gold-primary: #D4AF37;
      --gold-light: #F2CA52;
      --gold-dark: #AA8520;
      --gold-glow: rgba(212, 175, 55, 0.3);
      --bg-dark: #080A0F;
      --bg-surface: #0E121B;
      --bg-card: #131826;
      --bg-card-hover: #181F30;
      --border-gold: rgba(212, 175, 55, 0.25);
      --border-subtle: rgba(255, 255, 255, 0.08);
      --text-main: #FFFFFF;
      --text-muted: #94A3B8;
      --status-pending: #F59E0B;
      --status-contacted: #3B82F6;
      --status-enrolled: #10B981;
      --status-cancelled: #EF4444;
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
      flex-direction: column;
    }

    /* Ambient Background Glows */
    .bg-glow-top {
      position: fixed;
      top: -200px;
      right: 10%;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, var(--maroon-primary) 0%, transparent 70%);
      opacity: 0.22;
      filter: blur(100px);
      pointer-events: none;
      z-index: 0;
    }

    .bg-glow-bottom {
      position: fixed;
      bottom: -200px;
      left: 5%;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, var(--gold-primary) 0%, transparent 70%);
      opacity: 0.12;
      filter: blur(100px);
      pointer-events: none;
      z-index: 0;
    }

    /* Top Navigation Bar */
    .top-nav {
      background: rgba(14, 18, 27, 0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--border-gold);
      padding: 14px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .brand-section {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .brand-logo-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--maroon-primary), var(--gold-primary));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: #fff;
      box-shadow: 0 0 15px var(--gold-glow);
    }

    .brand-titles h1 {
      font-family: var(--font-heading);
      font-size: 1.25rem;
      font-weight: 800;
      letter-spacing: 0.5px;
      line-height: 1.2;
    }

    .brand-titles h1 span {
      color: var(--gold-primary);
    }

    .brand-titles p {
      font-size: 0.75rem;
      color: var(--text-muted);
      letter-spacing: 0.5px;
    }

    .live-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(16, 185, 129, 0.12);
      border: 1px solid rgba(16, 185, 129, 0.3);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.75rem;
      color: #34D399;
      font-weight: 600;
      margin-left: 15px;
    }

    .pulse-dot {
      width: 8px;
      height: 8px;
      background: #10B981;
      border-radius: 50%;
      box-shadow: 0 0 8px #10B981;
      animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(0.95); opacity: 0.8; }
      50% { transform: scale(1.3); opacity: 1; }
      100% { transform: scale(0.95); opacity: 0.8; }
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .admin-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid var(--border-subtle);
      padding: 6px 14px;
      border-radius: 10px;
    }

    .admin-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--maroon-primary), #4A0B11);
      border: 1px solid var(--gold-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 700;
      color: var(--gold-light);
    }

    .admin-info {
      line-height: 1.2;
    }

    .admin-name {
      font-size: 0.85rem;
      font-weight: 700;
      color: #fff;
    }

    .admin-role {
      font-size: 0.7rem;
      color: var(--gold-light);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      font-family: var(--font-heading);
      cursor: pointer;
      text-decoration: none;
      transition: all 0.25s ease;
      border: 1px solid transparent;
    }

    .btn-gold {
      background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark));
      color: #000;
      border-color: var(--gold-light);
      font-weight: 700;
    }

    .btn-gold:hover {
      box-shadow: 0 0 15px var(--gold-glow);
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: rgba(255, 255, 255, 0.06);
      color: #E2E8F0;
      border-color: var(--border-subtle);
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.12);
      color: #fff;
    }

    .btn-maroon {
      background: linear-gradient(135deg, var(--maroon-light), var(--maroon-primary));
      color: #fff;
      border-color: rgba(212, 175, 55, 0.3);
    }

    .btn-maroon:hover {
      box-shadow: 0 0 15px var(--maroon-glow);
      transform: translateY(-1px);
    }

    /* Main Container */
    .dashboard-container {
      max-width: 1440px;
      width: 100%;
      margin: 0 auto;
      padding: 28px 24px;
      position: relative;
      z-index: 1;
      flex: 1;
    }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 20px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--bg-card);
      border: 1px solid var(--border-gold);
      border-radius: 16px;
      padding: 22px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
      transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-3px);
      border-color: var(--gold-light);
    }

    .stat-card::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--maroon-primary), var(--gold-primary));
    }

    .stat-info h3 {
      font-size: 0.82rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--text-muted);
      margin-bottom: 6px;
    }

    .stat-value {
      font-family: var(--font-heading);
      font-size: 2.1rem;
      font-weight: 800;
      color: #FFFFFF;
      line-height: 1;
    }

    .stat-sub {
      font-size: 0.75rem;
      color: var(--gold-light);
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .stat-icon-wrapper {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
    }

    .icon-total {
      background: rgba(212, 175, 55, 0.15);
      color: var(--gold-primary);
      border: 1px solid rgba(212, 175, 55, 0.3);
    }

    .icon-today {
      background: rgba(59, 130, 246, 0.15);
      color: #60A5FA;
      border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .icon-enrolled {
      background: rgba(16, 185, 129, 0.15);
      color: #34D399;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .icon-pending {
      background: rgba(245, 158, 11, 0.15);
      color: #FBBF24;
      border: 1px solid rgba(245, 158, 11, 0.3);
    }

    /* Filters & Controls Bar */
    .controls-card {
      background: var(--bg-surface);
      border: 1px solid var(--border-gold);
      border-radius: 16px;
      padding: 18px 20px;
      margin-bottom: 24px;
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
      align-items: center;
      justify-content: space-between;
    }

    .search-box {
      flex: 1;
      min-width: 260px;
      position: relative;
    }

    .search-box i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 0.9rem;
    }

    .search-input {
      width: 100%;
      background: rgba(8, 10, 15, 0.8);
      border: 1px solid rgba(212, 175, 55, 0.2);
      border-radius: 8px;
      padding: 10px 14px 10px 38px;
      color: #fff;
      font-size: 0.88rem;
      outline: none;
      transition: all 0.25s ease;
    }

    .search-input:focus {
      border-color: var(--gold-primary);
      box-shadow: 0 0 12px var(--gold-glow);
    }

    .filter-group {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }

    .select-control {
      background: rgba(8, 10, 15, 0.8);
      border: 1px solid rgba(212, 175, 55, 0.2);
      color: #E2E8F0;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 0.85rem;
      outline: none;
      cursor: pointer;
    }

    .select-control:focus {
      border-color: var(--gold-primary);
    }

    /* Tabs */
    .tabs-nav {
      display: flex;
      gap: 10px;
      margin-bottom: 18px;
      border-bottom: 1px solid var(--border-subtle);
      padding-bottom: 12px;
    }

    .tab-btn {
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-family: var(--font-heading);
      font-size: 0.95rem;
      font-weight: 700;
      padding: 8px 16px;
      border-radius: 8px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.25s ease;
    }

    .tab-btn.active {
      background: rgba(128, 20, 29, 0.35);
      color: var(--gold-light);
      border: 1px solid var(--border-gold);
    }

    .tab-badge {
      background: rgba(212, 175, 55, 0.2);
      padding: 2px 8px;
      border-radius: 20px;
      font-size: 0.75rem;
      color: var(--gold-primary);
    }

    /* Leads Table */
    .table-card {
      background: var(--bg-card);
      border: 1px solid var(--border-gold);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .table-responsive {
      overflow-x: auto;
      width: 100%;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.88rem;
    }

    thead {
      background: rgba(8, 10, 15, 0.9);
      border-bottom: 1px solid var(--border-gold);
    }

    th {
      padding: 14px 18px;
      font-family: var(--font-heading);
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--gold-light);
      font-weight: 700;
      white-space: nowrap;
    }

    tbody tr {
      border-bottom: 1px solid var(--border-subtle);
      transition: background-color 0.2s ease;
    }

    tbody tr:hover {
      background-color: var(--bg-card-hover);
    }

    td {
      padding: 14px 18px;
      vertical-align: middle;
      color: #E2E8F0;
    }

    .lead-name {
      font-weight: 700;
      color: #FFFFFF;
      font-size: 0.92rem;
      margin-bottom: 3px;
    }

    .lead-meta {
      font-size: 0.75rem;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .city-tag {
      background: rgba(255, 255, 255, 0.06);
      padding: 2px 6px;
      border-radius: 4px;
      font-size: 0.7rem;
      color: var(--gold-light);
    }

    /* WhatsApp Button in Table */
    .btn-whatsapp-chat {
      background: rgba(37, 211, 102, 0.15);
      border: 1px solid rgba(37, 211, 102, 0.4);
      color: #25D366;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
    }

    .btn-whatsapp-chat:hover {
      background: #25D366;
      color: #000;
      box-shadow: 0 0 10px rgba(37, 211, 102, 0.5);
    }

    .course-badge {
      display: inline-block;
      max-width: 220px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-weight: 600;
      font-size: 0.82rem;
      color: #FFFFFF;
    }

    .exp-badge {
      display: block;
      font-size: 0.72rem;
      color: var(--gold-light);
      margin-top: 2px;
    }

    .mentor-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(128, 20, 29, 0.25);
      border: 1px solid rgba(212, 175, 55, 0.2);
      padding: 4px 10px;
      border-radius: 6px;
      font-size: 0.78rem;
      color: #F8FAFC;
      white-space: nowrap;
    }

    /* Status Select Dropdown in Table */
    .status-select {
      background: #0B0E16;
      border: 1px solid;
      border-radius: 6px;
      padding: 5px 8px;
      font-size: 0.78rem;
      font-weight: 700;
      cursor: pointer;
      outline: none;
      transition: all 0.2s;
    }

    .status-select.pending {
      color: var(--status-pending);
      border-color: rgba(245, 158, 11, 0.5);
      background: rgba(245, 158, 11, 0.1);
    }

    .status-select.contacted {
      color: var(--status-contacted);
      border-color: rgba(59, 130, 246, 0.5);
      background: rgba(59, 130, 246, 0.1);
    }

    .status-select.enrolled {
      color: var(--status-enrolled);
      border-color: rgba(16, 185, 129, 0.5);
      background: rgba(16, 185, 129, 0.1);
    }

    .status-select.cancelled {
      color: var(--status-cancelled);
      border-color: rgba(239, 68, 68, 0.5);
      background: rgba(239, 68, 68, 0.1);
    }

    .btn-details {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-subtle);
      color: var(--text-muted);
      width: 32px;
      height: 32px;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-details:hover {
      background: rgba(212, 175, 55, 0.2);
      color: var(--gold-light);
      border-color: var(--gold-primary);
    }

    /* Empty State */
    .empty-state {
      padding: 50px 20px;
      text-align: center;
      color: var(--text-muted);
    }

    .empty-state i {
      font-size: 2.5rem;
      color: var(--gold-primary);
      margin-bottom: 12px;
      opacity: 0.6;
    }

    /* Modal */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(8px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
      padding: 20px;
    }

    .modal-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }

    .modal-box {
      background: var(--bg-card);
      border: 1px solid var(--border-gold);
      border-radius: 18px;
      width: 100%;
      max-width: 580px;
      box-shadow: 0 25px 60px rgba(0,0,0,0.8), 0 0 30px var(--maroon-glow);
      overflow: hidden;
      transform: translateY(20px);
      transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal-box {
      transform: translateY(0);
    }

    .modal-header {
      padding: 18px 24px;
      background: rgba(128, 20, 29, 0.3);
      border-bottom: 1px solid var(--border-gold);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .modal-header h3 {
      font-family: var(--font-heading);
      font-size: 1.15rem;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .modal-header h3 i {
      color: var(--gold-primary);
    }

    .modal-close {
      background: none;
      border: none;
      color: var(--text-muted);
      font-size: 1.2rem;
      cursor: pointer;
    }

    .modal-close:hover {
      color: #fff;
    }

    .modal-body {
      padding: 22px 24px;
      max-height: 70vh;
      overflow-y: auto;
    }

    .lead-detail-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 14px;
      margin-bottom: 18px;
    }

    .lead-field label {
      display: block;
      font-size: 0.72rem;
      text-transform: uppercase;
      color: var(--text-muted);
      letter-spacing: 0.5px;
      margin-bottom: 4px;
    }

    .lead-field p {
      font-size: 0.9rem;
      font-weight: 600;
      color: #FFFFFF;
    }

    .message-box {
      background: rgba(8, 10, 15, 0.8);
      border: 1px solid var(--border-subtle);
      border-radius: 8px;
      padding: 12px 14px;
      font-size: 0.85rem;
      color: #CBD5E1;
      margin-bottom: 18px;
      line-height: 1.5;
    }

    .notes-textarea {
      width: 100%;
      background: rgba(8, 10, 15, 0.9);
      border: 1px solid var(--border-gold);
      border-radius: 8px;
      padding: 10px 12px;
      color: #fff;
      font-size: 0.85rem;
      resize: vertical;
      min-height: 80px;
      outline: none;
    }

    .modal-footer {
      padding: 14px 24px;
      background: rgba(8, 10, 15, 0.6);
      border-top: 1px solid var(--border-subtle);
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 10px;
    }

    /* Toast */
    .toast-popup {
      position: fixed;
      bottom: 24px;
      right: 24px;
      background: rgba(19, 24, 38, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid var(--gold-primary);
      padding: 14px 20px;
      border-radius: 10px;
      color: #fff;
      font-size: 0.88rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.6), 0 0 20px var(--gold-glow);
      z-index: 2000;
      transform: translateY(100px);
      opacity: 0;
      transition: all 0.3s ease;
    }

    .toast-popup.show {
      transform: translateY(0);
      opacity: 1;
    }

    @media (max-width: 900px) {
      .top-nav {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
      }
      .nav-actions {
        width: 100%;
        justify-content: space-between;
      }
      .lead-detail-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <div class="bg-glow-top"></div>
  <div class="bg-glow-bottom"></div>

  <!-- Top Header Navigation -->
  <header class="top-nav">
    <div class="brand-section">
      <div class="brand-logo-icon">
        <i class="fas fa-chart-line"></i>
      </div>
      <div class="brand-titles">
        <h1>DIGI<span>TRADE</span> ACADEMY</h1>
        <p>Official Admissions &amp; Lead Management Portal</p>
      </div>
      <div class="live-status">
        <span class="pulse-dot"></span> LIVE LEADS FEED
      </div>
    </div>

    <div class="nav-actions">
      <button class="btn btn-secondary" id="btnRefresh" title="Refresh Leads Feed">
        <i class="fas fa-rotate" id="refreshIcon"></i> Refresh
      </button>

      <a href="../api/export-csv.php" class="btn btn-gold" id="btnExport" title="Download Excel CSV">
        <i class="fas fa-file-excel"></i> Export CSV
      </a>

      <div class="admin-profile">
        <div class="admin-avatar"><?= strtoupper(substr($adminName, 0, 1)) ?></div>
        <div class="admin-info">
          <div class="admin-name"><?= htmlspecialchars($adminName) ?></div>
          <div class="admin-role"><?= htmlspecialchars($adminRole) ?></div>
        </div>
      </div>

      <a href="../api/admin-logout.php?redirect=1" class="btn btn-maroon" title="Logout">
        <i class="fas fa-power-off"></i>
      </a>
    </div>
  </header>

  <!-- Main Content Dashboard -->
  <main class="dashboard-container">
    
    <!-- 1. Real-time KPI Metric Cards -->
    <section class="stats-grid">
      <div class="stat-card">
        <div class="stat-info">
          <h3>Total Applications</h3>
          <div class="stat-value" id="statTotal">--</div>
          <div class="stat-sub"><i class="fas fa-arrow-trend-up"></i> Lifetime Leads</div>
        </div>
        <div class="stat-icon-wrapper icon-total">
          <i class="fas fa-users-viewfinder"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <h3>Today's Inquiries</h3>
          <div class="stat-value" id="statToday" style="color:#60A5FA;">--</div>
          <div class="stat-sub"><i class="fas fa-bolt"></i> Received in 24 Hours</div>
        </div>
        <div class="stat-icon-wrapper icon-today">
          <i class="fas fa-calendar-day"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <h3>Enrolled Students</h3>
          <div class="stat-value" id="statEnrolled" style="color:#34D399;">--</div>
          <div class="stat-sub"><i class="fas fa-circle-check"></i> Active Batch Admissions</div>
        </div>
        <div class="stat-icon-wrapper icon-enrolled">
          <i class="fas fa-user-graduate"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <h3>Pending Follow-ups</h3>
          <div class="stat-value" id="statPending" style="color:#FBBF24;">--</div>
          <div class="stat-sub"><i class="fas fa-clock"></i> Action Required</div>
        </div>
        <div class="stat-icon-wrapper icon-pending">
          <i class="fas fa-hourglass-half"></i>
        </div>
      </div>
    </section>

    <!-- 2. Controls & Search Filter Bar -->
    <section class="controls-card">
      <div class="search-box">
        <i class="fas fa-magnifying-glass"></i>
        <input type="text" id="searchInput" class="search-input" placeholder="Search by student name, WhatsApp number, city, or email...">
      </div>

      <div class="filter-group">
        <select id="courseFilter" class="select-control">
          <option value="">All Courses</option>
          <option value="Forex Trading Mastery">Forex Trading Mastery</option>
          <option value="Meta Ads">Meta Ads &amp; Marketing</option>
          <option value="Facebook Marketplace">Facebook Marketplace</option>
          <option value="Master Bundle">All-in-One Master Bundle</option>
        </select>

        <select id="statusFilter" class="select-control">
          <option value="">All Statuses</option>
          <option value="pending">Pending</option>
          <option value="contacted">Contacted</option>
          <option value="enrolled">Enrolled</option>
          <option value="cancelled">Cancelled</option>
        </select>

        <select id="mentorFilter" class="select-control">
          <option value="">All Mentors</option>
          <option value="Taha">Muhammad Taha</option>
          <option value="Safiullah">Muhammad Safiullah</option>
        </select>
      </div>
    </section>

    <!-- 3. Navigation Tabs -->
    <div class="tabs-nav">
      <button class="tab-btn active" id="tabAdmissions">
        <i class="fas fa-user-plus"></i> Admissions Leads <span class="tab-badge" id="admissionsBadge">0</span>
      </button>
      <button class="tab-btn" id="tabContacts">
        <i class="fas fa-envelope-open-text"></i> Contact Inquiries <span class="tab-badge" id="contactsBadge">0</span>
      </button>
    </div>

    <!-- 4. Leads Data Table Card -->
    <section class="table-card">
      <div class="table-responsive">
        <table id="leadsTable">
          <thead>
            <tr>
              <th>ID &amp; Date</th>
              <th>Student Details</th>
              <th>WhatsApp Contact</th>
              <th>Selected Course</th>
              <th>Assigned Mentor</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="leadsTableBody">
            <tr>
              <td colspan="7" class="empty-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading real-time leads...</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <!-- Lead Detail & Notes Modal -->
  <div class="modal-overlay" id="leadModal">
    <div class="modal-box">
      <div class="modal-header">
        <h3><i class="fas fa-id-card-clip"></i> Application Overview</h3>
        <button class="modal-close" id="modalCloseBtn">&times;</button>
      </div>
      <div class="modal-body">
        <div class="lead-detail-grid">
          <div class="lead-field">
            <label>Student Full Name</label>
            <p id="modalName">--</p>
          </div>
          <div class="lead-field">
            <label>WhatsApp Number</label>
            <p id="modalPhone">--</p>
          </div>
          <div class="lead-field">
            <label>Email Address</label>
            <p id="modalEmail">--</p>
          </div>
          <div class="lead-field">
            <label>City / Country</label>
            <p id="modalCity">--</p>
          </div>
          <div class="lead-field">
            <label>Selected Course</label>
            <p id="modalCourse">--</p>
          </div>
          <div class="lead-field">
            <label>Experience Level</label>
            <p id="modalExp">--</p>
          </div>
          <div class="lead-field">
            <label>Assigned Mentor</label>
            <p id="modalMentor">--</p>
          </div>
          <div class="lead-field">
            <label>Submission Date</label>
            <p id="modalDate">--</p>
          </div>
        </div>

        <div class="lead-field">
          <label>Student Goal / Inquiry Message</label>
          <div class="message-box" id="modalMessage">No message provided.</div>
        </div>

        <div class="lead-field">
          <label>Internal Mentor / Admin Notes</label>
          <textarea id="modalNotes" class="notes-textarea" placeholder="Add follow-up notes, fee confirmation, batch details..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" id="modalCancelBtn">Close</button>
        <button class="btn btn-gold" id="modalSaveBtn">
          <i class="fas fa-floppy-disk"></i> Save Notes
        </button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="toast-popup" id="toastPopup">
    <i class="fas fa-circle-check" style="color:var(--gold-primary);"></i>
    <span id="toastMessage">Action completed successfully.</span>
  </div>

  <script>
    // Global Dashboard State
    let currentLeads = [];
    let currentTab = 'admissions'; // 'admissions' or 'contacts'
    let selectedLeadId = null;

    // DOM Elements
    const tableBody = document.getElementById('leadsTableBody');
    const searchInput = document.getElementById('searchInput');
    const courseFilter = document.getElementById('courseFilter');
    const statusFilter = document.getElementById('statusFilter');
    const mentorFilter = document.getElementById('mentorFilter');
    const btnRefresh = document.getElementById('btnRefresh');
    const refreshIcon = document.getElementById('refreshIcon');

    // KPI Stat elements
    const statTotal = document.getElementById('statTotal');
    const statToday = document.getElementById('statToday');
    const statEnrolled = document.getElementById('statEnrolled');
    const statPending = document.getElementById('statPending');
    const admissionsBadge = document.getElementById('admissionsBadge');

    // Modal elements
    const leadModal = document.getElementById('leadModal');
    const modalCloseBtn = document.getElementById('modalCloseBtn');
    const modalCancelBtn = document.getElementById('modalCancelBtn');
    const modalSaveBtn = document.getElementById('modalSaveBtn');
    const modalNotes = document.getElementById('modalNotes');

    // Tabs
    const tabAdmissions = document.getElementById('tabAdmissions');
    const tabContacts = document.getElementById('tabContacts');

    // Init on load
    document.addEventListener('DOMContentLoaded', () => {
      fetchLeads();
      setupEventListeners();

      // Auto-refresh leads feed every 30 seconds
      setInterval(() => {
        if (!leadModal.classList.contains('active')) {
          fetchLeads(false);
        }
      }, 30000);
    });

    // Fetch Admissions & KPIs
    async function fetchLeads(showSpin = true) {
      if (showSpin) {
        refreshIcon.classList.add('fa-spin');
      }

      const params = new URLSearchParams({
        search: searchInput.value.trim(),
        course: courseFilter.value,
        status: statusFilter.value,
        mentor: mentorFilter.value
      });

      try {
        const response = await fetch(`../api/get-admissions.php?${params.toString()}`);
        const data = await response.json();

        if (data.success && data.data) {
          const stats = data.data.stats || {};
          statTotal.textContent = stats.total || 0;
          statToday.textContent = stats.today || 0;
          statEnrolled.textContent = stats.enrolled || 0;
          statPending.textContent = stats.pending || 0;
          admissionsBadge.textContent = stats.total || 0;

          currentLeads = data.data.leads || [];
          renderLeadsTable(currentLeads);
        } else {
          tableBody.innerHTML = `
            <tr>
              <td colspan="7" class="empty-state">
                <i class="fas fa-circle-exclamation" style="color:#EF4444;"></i>
                <p>${data.message || 'Error fetching records'}</p>
              </td>
            </tr>`;
        }
      } catch (err) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fas fa-triangle-exclamation" style="color:#F59E0B;"></i>
              <p>Unable to connect to database API. Check server status.</p>
            </td>
          </tr>`;
      } finally {
        refreshIcon.classList.remove('fa-spin');
      }
    }

    // Render Table Rows
    function renderLeadsTable(leads) {
      if (!leads || leads.length === 0) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="7" class="empty-state">
              <i class="fas fa-inbox"></i>
              <p>No leads found matching your search filters.</p>
            </td>
          </tr>`;
        return;
      }

      let html = '';
      leads.forEach(lead => {
        // Clean phone for WhatsApp URL
        const rawPhone = (lead.whatsapp || '').replace(/[^0-9]/g, '');
        const studentGreeting = encodeURIComponent(`Assalam o Alaikum ${lead.full_name}! This is DigiTrade Academy regarding your enrollment inquiry for ${lead.course}.`);
        const waLink = `https://wa.me/${rawPhone}?text=${studentGreeting}`;
        
        // Status class
        const statusClass = (lead.status || 'pending').toLowerCase();
        
        const dateFormatted = new Date(lead.created_at).toLocaleDateString('en-US', {
          month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });

        html += `
          <tr>
            <td>
              <strong style="color:var(--gold-primary);">#${lead.id}</strong><br>
              <span style="font-size:0.75rem; color:var(--text-muted);">${dateFormatted}</span>
            </td>
            <td>
              <div class="lead-name">${escapeHtml(lead.full_name)}</div>
              <div class="lead-meta">
                ${lead.city ? `<span class="city-tag"><i class="fas fa-location-dot"></i> ${escapeHtml(lead.city)}</span>` : ''}
                ${lead.email ? `<span><i class="fas fa-envelope"></i> ${escapeHtml(lead.email)}</span>` : ''}
              </div>
            </td>
            <td>
              <div style="margin-bottom:4px; font-weight:600;">${escapeHtml(lead.whatsapp)}</div>
              <a href="${waLink}" target="_blank" class="btn-whatsapp-chat" title="Open direct WhatsApp chat">
                <i class="fab fa-whatsapp"></i> Chat Now
              </a>
            </td>
            <td>
              <div class="course-badge" title="${escapeHtml(lead.course)}">${escapeHtml(lead.course)}</div>
              <span class="exp-badge"><i class="fas fa-layer-group"></i> ${escapeHtml(lead.experience_level || 'Beginner')}</span>
            </td>
            <td>
              <span class="mentor-badge">
                <i class="fas fa-chalkboard-user" style="color:var(--gold-light);"></i>
                ${escapeHtml(lead.mentor_choice || 'Muhammad Taha')}
              </span>
            </td>
            <td>
              <select class="status-select ${statusClass}" onchange="updateLeadStatus(${lead.id}, this.value, this)">
                <option value="pending" ${lead.status === 'pending' ? 'selected' : ''}>Pending</option>
                <option value="contacted" ${lead.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                <option value="enrolled" ${lead.status === 'enrolled' ? 'selected' : ''}>Enrolled</option>
                <option value="cancelled" ${lead.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
              </select>
            </td>
            <td>
              <button class="btn-details" onclick="openLeadModal(${lead.id})" title="View Details &amp; Notes">
                <i class="fas fa-eye"></i>
              </button>
            </td>
          </tr>
        `;
      });

      tableBody.innerHTML = html;
    }

    // Update Status via AJAX
    async function updateLeadStatus(id, newStatus, selectElement) {
      // Update visual class
      selectElement.className = `status-select ${newStatus}`;

      try {
        const response = await fetch('../api/update-status.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ type: 'admission', id: id, status: newStatus })
        });
        const data = await response.json();
        if (data.success) {
          showToast(`Lead #${id} status updated to ${newStatus.toUpperCase()}`);
          fetchLeads(false);
        } else {
          showToast(`Failed: ${data.message}`, '#EF4444');
        }
      } catch (err) {
        showToast('Error connecting to status update API', '#EF4444');
      }
    }

    // Open Lead Details Modal
    function openLeadModal(id) {
      const lead = currentLeads.find(l => Number(l.id) === Number(id));
      if (!lead) return;

      selectedLeadId = id;
      document.getElementById('modalName').textContent = lead.full_name || '--';
      document.getElementById('modalPhone').textContent = lead.whatsapp || '--';
      document.getElementById('modalEmail').textContent = lead.email || 'Not Provided';
      document.getElementById('modalCity').textContent = lead.city || 'Not Provided';
      document.getElementById('modalCourse').textContent = lead.course || '--';
      document.getElementById('modalExp').textContent = lead.experience_level || 'Beginner';
      document.getElementById('modalMentor').textContent = lead.mentor_choice || '--';
      document.getElementById('modalDate').textContent = lead.created_at || '--';
      document.getElementById('modalMessage').textContent = lead.message || 'No specific goals or message included.';
      modalNotes.value = lead.notes || '';

      leadModal.classList.add('active');
    }

    function closeLeadModal() {
      leadModal.classList.remove('active');
      selectedLeadId = null;
    }

    // Save Internal Notes
    modalSaveBtn.addEventListener('click', async () => {
      if (!selectedLeadId) return;
      const notes = modalNotes.value.trim();
      const lead = currentLeads.find(l => Number(l.id) === Number(selectedLeadId));
      const status = lead ? lead.status : 'pending';

      try {
        const response = await fetch('../api/update-status.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ type: 'admission', id: selectedLeadId, status: status, notes: notes })
        });
        const data = await response.json();
        if (data.success) {
          showToast('Notes saved successfully!');
          closeLeadModal();
          fetchLeads(false);
        }
      } catch (err) {
        showToast('Failed to save notes', '#EF4444');
      }
    });

    // Event Listeners
    function setupEventListeners() {
      // Search with debounce
      let debounceTimer;
      searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchLeads(true), 350);
      });

      courseFilter.addEventListener('change', () => fetchLeads(true));
      statusFilter.addEventListener('change', () => fetchLeads(true));
      mentorFilter.addEventListener('change', () => fetchLeads(true));
      btnRefresh.addEventListener('click', () => fetchLeads(true));

      modalCloseBtn.addEventListener('click', closeLeadModal);
      modalCancelBtn.addEventListener('click', closeLeadModal);
      leadModal.addEventListener('click', (e) => {
        if (e.target === leadModal) closeLeadModal();
      });

      // Tabs click
      tabAdmissions.addEventListener('click', () => {
        tabAdmissions.classList.add('active');
        tabContacts.classList.remove('active');
        fetchLeads(true);
      });

      tabContacts.addEventListener('click', async () => {
        tabContacts.classList.add('active');
        tabAdmissions.classList.remove('active');
        fetchContacts();
      });
    }

    // Fetch Contacts tab
    async function fetchContacts() {
      tableBody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fas fa-spinner fa-spin"></i><p>Loading contact messages...</p></td></tr>`;
      try {
        const response = await fetch('../api/get-contacts.php');
        const data = await response.json();
        if (data.success && data.data && data.data.messages) {
          renderContactsTable(data.data.messages);
        }
      } catch (e) {
        tableBody.innerHTML = `<tr><td colspan="7" class="empty-state"><p>Error fetching contact messages.</p></td></tr>`;
      }
    }

    function renderContactsTable(messages) {
      if (!messages.length) {
        tableBody.innerHTML = `<tr><td colspan="7" class="empty-state"><i class="fas fa-inbox"></i><p>No contact messages yet.</p></td></tr>`;
        return;
      }

      let html = '';
      messages.forEach(msg => {
        const dateFormatted = new Date(msg.created_at).toLocaleDateString('en-US', {
          month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });
        html += `
          <tr>
            <td><strong>#${msg.id}</strong><br><span style="font-size:0.75rem; color:var(--text-muted);">${dateFormatted}</span></td>
            <td><div class="lead-name">${escapeHtml(msg.name)}</div><div class="lead-meta">${escapeHtml(msg.email || '')}</div></td>
            <td>${escapeHtml(msg.phone || 'N/A')}</td>
            <td colspan="2"><strong>${escapeHtml(msg.subject || 'Inquiry')}:</strong><br><span style="font-size:0.8rem; color:#CBD5E1;">${escapeHtml(msg.message)}</span></td>
            <td><span class="status-select ${msg.status}">${escapeHtml(msg.status)}</span></td>
            <td>
              ${msg.phone ? `<a href="https://wa.me/${(msg.phone).replace(/[^0-9]/g, '')}" target="_blank" class="btn-whatsapp-chat"><i class="fab fa-whatsapp"></i> Chat</a>` : '-'}
            </td>
          </tr>`;
      });
      tableBody.innerHTML = html;
    }

    // Toast helper
    function showToast(msg, color = '#D4AF37') {
      const toast = document.getElementById('toastPopup');
      const toastMsg = document.getElementById('toastMessage');
      toastMsg.textContent = msg;
      toast.style.borderColor = color;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 3500);
    }

    // Sanitize helper
    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }
  </script>
</body>
</html>
