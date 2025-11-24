<?php
// views/admin/dashboard.php (BẢN SỬA GỌN – KHÔNG absolute, KHÔNG margin âm)

// Lấy số liệu an toàn (nếu controller truyền $stats dạng mảng)
$cnt1 = $stats['cnt1'] ?? 0;     // Hóa đơn
$ks   = $stats['ks']   ?? 0;     // Khách sạn
$cnt2 = $stats['cnt2'] ?? 0;     // Góp ý
$goi  = $stats['goi']  ?? 0;     // Tour
$cnt5 = $stats['cnt5'] ?? 0;     // Trợ giúp
$blog = $stats['blog'] ?? 0;     // Blog
?>

<style>
  /* KHỐI CHUNG */
  .dash-wrap{display:block}
  .dash-row{display:grid;grid-template-columns:repeat(12,1fr);gap:16px}
  @media (max-width:1200px){.dash-row{grid-template-columns:repeat(8,1fr)}}
  @media (max-width:768px){.dash-row{grid-template-columns:repeat(4,1fr)}}
  .card{background:#fff;border-radius:12px;box-shadow:0 6px 16px rgba(0,0,0,.06);padding:16px}

  /* HÀNH ĐỘNG NHANH */
  .quick .item{grid-column:span 4;display:flex;align-items:center;gap:12px;cursor:pointer;text-decoration:none;border:1px solid #eef0f4}
  .quick .icon{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:20px}
  .quick .txt{font-weight:600;color:#222}
  .quick .item:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(13,110,253,.12)}

  /* SỐ LIỆU */
  .kpi .box{grid-column:span 2;text-align:center;padding:18px}
  .kpi .num{font-size:26px;font-weight:700;color:#0d6efd;margin-bottom:6px}
  .kpi .lbl{color:#555}
  .kpi a{text-decoration:none;color:inherit}
  .kpi .box:hover{box-shadow:0 8px 20px rgba(0,0,0,.08)}

  /* TIÊU ĐỀ */
  .section-title{font-size:18px;font-weight:700;margin:0 0 12px}
</style>

<div class="dash-wrap">

  <!-- Hành động nhanh -->
  <div class="card" style="margin-bottom:16px">
    <h3 class="section-title">Hành động nhanh</h3>
    <div class="dash-row quick">
      <a class="item card" href="<?= BASE_URL ?>?act=admin-tour-create" aria-label="Thêm tour">
        <div class="icon"><i class="glyphicon glyphicon-road"></i></div>
        <div class="txt">Thêm tour</div>
      </a>
      <a class="item card" href="<?= BASE_URL ?>?act=blog-create" aria-label="Thêm blog">
        <div class="icon"><i class="glyphicon glyphicon-leaf"></i></div>
        <div class="txt">Thêm blog</div>
      </a>
      <a class="item card" href="#" aria-label="Thêm khách sạn">
        <div class="icon"><i class="glyphicon glyphicon-bed"></i></div>
        <div class="txt">Thêm khách sạn</div>
      </a>
    </div>
  </div>

  <!-- Số liệu -->
  <div class="card">
    <h3 class="section-title">Tổng quan</h3>
    <div class="dash-row kpi">
      <a class="box card" href="#">
        <div class="num"><?= htmlspecialchars((string)$cnt1) ?></div>
        <div class="lbl">Hóa đơn</div>
      </a>
      <a class="box card" href="#">
        <div class="num"><?= htmlspecialchars((string)$ks) ?></div>
        <div class="lbl">Khách sạn</div>
      </a>
      <a class="box card" href="#">
        <div class="num"><?= htmlspecialchars((string)$cnt2) ?></div>
        <div class="lbl">Góp ý</div>
      </a>
      <a class="box card" href="<?= BASE_URL ?>?act=admin-tours">
        <div class="num"><?= htmlspecialchars((string)$goi) ?></div>
        <div class="lbl">Tour</div>
      </a>
      <a class="box card" href="#">
        <div class="num"><?= htmlspecialchars((string)$cnt5) ?></div>
        <div class="lbl">Trợ giúp</div>
      </a>
      <a class="box card" href="#">
        <div class="num"><?= htmlspecialchars((string)$blog) ?></div>
        <div class="lbl">Blog</div>
      </a>
    </div>
  </div>

</div>