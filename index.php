<?php
/**
 * BELLA — костюм "Горошок" · Лендінг
 * Легкий PHP-обробник форми замовлення (без БД: пише в orders.csv)
 */

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $name  = trim(strip_tags($_POST['name']  ?? ''));
    $phone = trim(strip_tags($_POST['phone'] ?? ''));
    $size  = trim(strip_tags($_POST['size']  ?? ''));

    if ($name === '' || $phone === '') {
        $error = 'Будь ласка, заповніть імʼя та телефон.';
    } else {
        $row = [
            date('Y-m-d H:i:s'),
            $name,
            $phone,
            $size ?: '—',
        ];
        $file = __DIR__ . '/orders.csv';
        $fh = @fopen($file, 'a');
        if ($fh) {
            fputcsv($fh, $row);
            fclose($fh);
        }
        $success = true;
    }

    // AJAX-режим: повертаємо JSON, якщо запит без перезавантаження сторінки
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => $success, 'error' => $error], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>BELLA — Костюм «Горошок» · Колекція 2026</title>
<meta name="description" content="Костюм-двійка: широкі штани та блузка з зав'язками. М'який софт, посадка на резинці. Оплата при отриманні, Нова Пошта 1–3 дні.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  :root{
    --ink: #17140f;
    --ink-soft: #221e17;
    --bone: #f1ead9;
    --bone-dim: #cfc6b2;
    --brass: #b98d5d;
    --brass-light: #d9b48a;
    --rose: #c89c93;
    --line-on-ink: rgba(241,234,217,0.14);
    --line-on-bone: rgba(23,20,15,0.12);
    --radius-lg: 22px;
    --radius-md: 14px;
    --radius-sm: 8px;
    --shadow: 0 20px 40px -20px rgba(0,0,0,0.35);
    --container: 480px;
    --disp: 'Fraunces', serif;
    --sans: 'Work Sans', sans-serif;
  }

  *{ box-sizing:border-box; }
  html{ -webkit-text-size-adjust:100%; }
  body{
    margin:0;
    background:var(--ink);
    color:var(--bone);
    font-family:var(--sans);
    -webkit-font-smoothing:antialiased;
    overflow-x:hidden;
  }
  img{ max-width:100%; display:block; }
  a{ color:inherit; }
  h1,h2,h3{ font-family:var(--disp); margin:0; font-weight:600; letter-spacing:-0.01em; }
  p{ margin:0; }
  button{ font-family:inherit; cursor:pointer; }

  .wrap{ max-width:var(--container); margin:0 auto; position:relative; background:var(--ink); }

  /* Dot motif — signature element, echoes the fabric print */
  .dot-row{
    display:flex; gap:7px; align-items:center; justify-content:center;
  }
  .dot-row span{
    width:4px; height:4px; border-radius:50%; background:currentColor; opacity:.55;
  }
  .dot-divider{ padding:26px 0; }

  .dot-field{
    background-image: radial-gradient(currentColor 1px, transparent 1px);
    background-size: 14px 14px;
  }

  /* ---------- Header ---------- */
  .site-header{
    position:sticky; top:0; z-index:40;
    display:flex; align-items:center; justify-content:space-between;
    padding:16px 20px;
    background:rgba(23,20,15,0.86);
    backdrop-filter:blur(10px);
    border-bottom:1px solid var(--line-on-ink);
  }
  .logo{
    font-family:var(--disp); font-size:22px; letter-spacing:0.04em; font-weight:600;
    display:flex; flex-direction:column; line-height:1;
  }
  .logo small{ font-family:var(--sans); font-size:9px; letter-spacing:0.32em; color:var(--brass-light); font-weight:600; margin-top:3px; }
  .header-tag{
    font-size:11px; color:var(--bone-dim); border:1px solid var(--line-on-ink);
    padding:7px 12px; border-radius:100px; letter-spacing:0.02em;
  }

  /* ---------- Hero ---------- */
  .hero{ position:relative; }
  .hero-media{ position:relative; height:82vh; min-height:520px; max-height:720px; overflow:hidden; }
  .hero-media img{ width:100%; height:100%; object-fit:cover; object-position:50% 18%; }
  .hero-media::after{
    content:''; position:absolute; inset:0;
    background:linear-gradient(180deg, rgba(23,20,15,0) 35%, rgba(23,20,15,0.75) 78%, var(--ink) 100%);
  }
  .hero-copy{
    position:absolute; left:0; right:0; bottom:0; padding:0 22px 26px;
    z-index:2;
  }
  .eyebrow{
    display:inline-flex; align-items:center; gap:8px;
    font-size:11px; letter-spacing:0.18em; text-transform:uppercase;
    color:var(--brass-light); margin-bottom:14px; font-weight:600;
  }
  .eyebrow::before{ content:''; width:16px; height:1px; background:var(--brass-light); }
  .hero h1{
    font-size:38px; line-height:1.05; color:var(--bone); max-width:340px;
  }
  .hero-sub{
    margin-top:14px; font-size:15px; line-height:1.5; color:var(--bone-dim); max-width:320px;
  }
  .price-row{
    display:flex; align-items:baseline; gap:12px; margin-top:20px;
  }
  .price-new{ font-family:var(--disp); font-size:30px; color:var(--bone); font-weight:600; }
  .price-old{ font-size:15px; color:var(--bone-dim); text-decoration:line-through; opacity:.7; }
  .price-badge{
    font-size:11px; font-weight:700; color:var(--ink); background:var(--brass-light);
    padding:3px 9px; border-radius:100px; letter-spacing:0.02em;
  }
  .cta-primary{
    display:flex; align-items:center; justify-content:center; gap:10px;
    width:100%; margin-top:20px; padding:16px 20px;
    background:var(--bone); color:var(--ink); border:none; border-radius:100px;
    font-size:15px; font-weight:600; letter-spacing:0.01em;
    transition:transform .15s ease, background .15s ease;
  }
  .cta-primary:active{ transform:scale(0.97); }
  .cta-primary svg{ width:16px; height:16px; }

  /* ---------- Trust strip ---------- */
  .trust{
    background:var(--bone); color:var(--ink);
    display:grid; grid-template-columns:repeat(3,1fr);
    padding:22px 14px; gap:10px;
  }
  .trust-item{ display:flex; flex-direction:column; align-items:center; text-align:center; gap:8px; }
  .trust-item .ico{
    width:34px; height:34px; border-radius:50%; background:var(--ink);
    color:var(--brass-light); display:flex; align-items:center; justify-content:center;
  }
  .trust-item .ico svg{ width:16px; height:16px; }
  .trust-item span{ font-size:11.5px; line-height:1.35; color:var(--ink); font-weight:500; }

  /* ---------- Section shell ---------- */
  section{ position:relative; }
  .section-pad{ padding:56px 22px; }
  .kicker{
    display:flex; align-items:center; gap:10px;
    font-size:11px; letter-spacing:0.16em; text-transform:uppercase; font-weight:700;
    color:var(--brass-light); margin-bottom:12px;
  }
  .kicker::after{ content:''; flex:1; height:1px; background:var(--line-on-ink); }
  .on-bone .kicker{ color:var(--brass); }
  .on-bone .kicker::after{ background:var(--line-on-bone); }
  h2.section-title{ font-size:28px; line-height:1.15; }
  .section-lede{ margin-top:12px; font-size:14.5px; line-height:1.6; color:var(--bone-dim); max-width:340px; }
  .on-bone .section-lede{ color:#4a4436; }

  .on-bone{ background:var(--bone); color:var(--ink); }

  /* ---------- Lifestyle / editorial ---------- */
  .editorial-frame{
    margin-top:28px; border-radius:var(--radius-lg); overflow:hidden; position:relative;
    box-shadow:var(--shadow);
  }
  .editorial-frame img{ width:100%; height:440px; object-fit:cover; object-position:top; }
  .editorial-cap{
    position:absolute; left:16px; bottom:16px; right:16px;
    background:rgba(23,20,15,0.55); backdrop-filter:blur(6px);
    border:1px solid rgba(241,234,217,0.2);
    border-radius:var(--radius-md); padding:14px 16px;
  }
  .editorial-cap p{ font-size:13px; color:var(--bone); line-height:1.5; }
  .editorial-cap b{ color:var(--brass-light); font-weight:600; }

  .feature-list{ margin-top:26px; display:flex; flex-direction:column; gap:0; }
  .feature-list li{
    list-style:none; display:flex; gap:14px; padding:16px 0;
    border-bottom:1px solid var(--line-on-ink);
    align-items:flex-start;
  }
  .feature-list li:last-child{ border-bottom:none; }
  .feature-dot{
    flex:none; width:26px; height:26px; border-radius:50%;
    background:var(--dot-field, none);
    background-image: radial-gradient(var(--brass-light) 1.6px, transparent 1.6px);
    background-size:7px 7px; background-position:center;
    border:1px solid var(--line-on-ink);
    margin-top:2px;
  }
  .feature-list h3{ font-size:15px; color:var(--bone); font-weight:600; }
  .feature-list p{ font-size:13.5px; color:var(--bone-dim); margin-top:4px; line-height:1.5; }

  /* ---------- Detail with hanging photo ---------- */
  .detail-block{
    display:flex; flex-direction:column; gap:22px; margin-top:28px;
  }
  .detail-photo{
    border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow);
  }
  .detail-photo img{ width:100%; height:460px; object-fit:cover; object-position:top; }
  .spec-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
  .spec-card{
    background:var(--ink-soft); border:1px solid var(--line-on-ink); border-radius:var(--radius-md);
    padding:16px;
  }
  .spec-card span{ font-size:10.5px; text-transform:uppercase; letter-spacing:0.1em; color:var(--brass-light); font-weight:700; }
  .spec-card p{ font-size:14px; margin-top:6px; color:var(--bone); line-height:1.4; }

  /* ---------- Size / order ---------- */
  .order-card{
    margin-top:26px; background:var(--ink); border-radius:var(--radius-lg);
    padding:22px; box-shadow:var(--shadow); border:1px solid var(--line-on-bone);
  }
  .order-card .stock-line{
    display:flex; align-items:center; gap:8px; font-size:12px; color:var(--brass-light); margin-bottom:16px; font-weight:600;
  }
  .stock-bar{ flex:1; height:4px; border-radius:4px; background:var(--line-on-ink); overflow:hidden; }
  .stock-bar i{ display:block; height:100%; width:32%; background:var(--brass-light); border-radius:4px; }

  .field-label{
    font-size:11px; text-transform:uppercase; letter-spacing:0.12em; color:var(--bone-dim); font-weight:700; margin:18px 0 10px;
  }
  .size-grid{ display:grid; grid-template-columns:1fr 1fr; gap:10px; }
  .size-opt, .color-opt{
    border:1px solid var(--line-on-ink); border-radius:var(--radius-sm);
    padding:13px 10px; text-align:center; font-size:13.5px; color:var(--bone);
    background:transparent; font-weight:500;
  }
  .size-opt small{ display:block; font-size:10.5px; color:var(--bone-dim); margin-top:2px; }
  .size-opt.active, .color-opt.active{
    border-color:var(--brass-light); background:rgba(185,141,93,0.12); color:var(--bone);
  }
  .color-opt{ display:flex; align-items:center; gap:10px; justify-content:flex-start; }
  .swatch{ width:20px; height:20px; border-radius:50%; background:
      radial-gradient(circle at 30% 30%, #3a3630, #0c0a08);
      background-image: radial-gradient(#f1ead9 1px, transparent 1.4px), radial-gradient(circle at 30% 30%, #3a3630, #0c0a08);
      background-size:6px 6px, cover; flex:none; border:1px solid var(--line-on-ink);
  }

  .order-form{ margin-top:22px; display:flex; flex-direction:column; gap:10px; }
  .order-form input{
    width:100%; padding:14px 16px; border-radius:var(--radius-sm);
    border:1px solid var(--line-on-ink); background:var(--ink-soft); color:var(--bone);
    font-size:14.5px; font-family:var(--sans);
  }
  .order-form input::placeholder{ color:var(--bone-dim); }
  .order-form input:focus, .size-opt:focus-visible, .color-opt:focus-visible, .cta-primary:focus-visible, .submit-btn:focus-visible{
    outline:2px solid var(--brass-light); outline-offset:2px;
  }
  .submit-btn{
    margin-top:6px; width:100%; padding:16px; border:none; border-radius:100px;
    background:var(--brass-light); color:var(--ink); font-weight:700; font-size:15px;
  }
  .submit-btn:active{ transform:scale(0.98); }
  .form-note{ font-size:11.5px; color:var(--bone-dim); text-align:center; margin-top:12px; line-height:1.5; }
  .form-msg{ font-size:13px; text-align:center; padding:10px; border-radius:var(--radius-sm); margin-top:10px; }
  .form-msg.ok{ background:rgba(185,141,93,0.16); color:var(--brass-light); }
  .form-msg.err{ background:rgba(200,80,80,0.16); color:#e2a2a2; }

  /* ---------- Steps ---------- */
  .steps{ margin-top:26px; display:flex; flex-direction:column; gap:18px; }
  .step{ display:flex; gap:14px; align-items:flex-start; }
  .step-num{
    flex:none; width:30px; height:30px; border-radius:50%; border:1px solid var(--line-on-bone);
    display:flex; align-items:center; justify-content:center; font-family:var(--disp); font-size:14px; color:var(--brass); font-weight:600;
  }
  .step h3{ font-size:14.5px; color:var(--ink); font-weight:600; }
  .step p{ font-size:13px; color:#5a5342; margin-top:3px; line-height:1.45; }

  /* ---------- Reviews ---------- */
  .review-track{
    display:flex; gap:14px; margin-top:24px; overflow-x:auto; padding-bottom:6px;
    scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch;
  }
  .review-track::-webkit-scrollbar{ display:none; }
  .review-card{
    flex:none; width:78%; scroll-snap-align:start;
    background:var(--ink-soft); border:1px solid var(--line-on-ink); border-radius:var(--radius-lg);
    padding:20px;
  }
  .review-stars{ color:var(--brass-light); font-size:13px; letter-spacing:2px; }
  .review-card p{ font-size:13.5px; line-height:1.55; color:var(--bone); margin-top:12px; }
  .review-who{ display:flex; align-items:center; gap:10px; margin-top:16px; }
  .review-avatar{
    width:32px; height:32px; border-radius:50%; background:var(--brass-light); color:var(--ink);
    display:flex; align-items:center; justify-content:center; font-weight:700; font-size:13px; font-family:var(--disp);
  }
  .review-who span{ font-size:12.5px; color:var(--bone-dim); }

  /* ---------- Footer ---------- */
  footer{ padding:40px 22px 120px; }
  .footer-logo{ font-family:var(--disp); font-size:20px; }
  .footer-links{ display:flex; flex-direction:column; gap:12px; margin-top:22px; }
  .footer-links a{ font-size:13px; color:var(--bone-dim); text-decoration:none; }
  .footer-meta{ margin-top:26px; font-size:11.5px; color:var(--bone-dim); opacity:.65; line-height:1.7; }

  /* ---------- Sticky bar ---------- */
  .sticky-bar{
    position:fixed; left:50%; transform:translateX(-50%); bottom:0; z-index:50;
    width:100%; max-width:var(--container);
    background:rgba(23,20,15,0.92); backdrop-filter:blur(10px);
    border-top:1px solid var(--line-on-ink);
    padding:12px 16px; display:flex; align-items:center; gap:14px;
  }
  .sticky-bar .p-new{ font-family:var(--disp); font-size:18px; color:var(--bone); }
  .sticky-bar .p-old{ font-size:12px; color:var(--bone-dim); text-decoration:line-through; margin-left:6px; }
  .sticky-bar button{
    flex:1; padding:14px; border:none; border-radius:100px; background:var(--brass-light);
    color:var(--ink); font-weight:700; font-size:14px;
  }

  @media (min-width:481px){
    .wrap{ border-left:1px solid var(--line-on-ink); border-right:1px solid var(--line-on-ink); }
  }

  @media (prefers-reduced-motion: reduce){
    *{ animation:none !important; transition:none !important; }
  }
</style>
</head>
<body>

<div class="wrap">

  <header class="site-header">
    <div class="logo">BELLA<small>WOMEN · 2026</small></div>
    <div class="header-tag">−37% сьогодні</div>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-media">
      <img src="images/hero.jpg" alt="Костюм у горошок, широкі штани та блузка з зав'язками">
    </div>
    <div class="hero-copy">
      <div class="eyebrow">Колекція 2026 · Горошок</div>
      <h1>Костюм, у&nbsp;якому зручно бути собою</h1>
      <p class="hero-sub">Пояс на резинці, широкі штани з розрізами й блузка на зав'язках — посадку регулюєте самі.</p>
      <div class="price-row">
        <span class="price-new">994 грн</span>
        <span class="price-old">1578 грн</span>
        <span class="price-badge">−37%</span>
      </div>
      <button class="cta-primary" onclick="document.getElementById('order').scrollIntoView({behavior:'smooth'})">
        Обрати розмір і колір
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
    </div>
  </section>

  <!-- TRUST -->
  <div class="trust">
    <div class="trust-item">
      <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8M3 8l9-5 9 5M3 8l9 5 9-5"/></svg></div>
      <span>Оплата при отриманні</span>
    </div>
    <div class="trust-item">
      <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 2l4 4-4 4M21 6H9a4 4 0 0 0-4 4M7 22l-4-4 4-4M3 18h12a4 4 0 0 0 4-4"/></svg></div>
      <span>Обмін без зайвих питань</span>
    </div>
    <div class="trust-item">
      <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7z"/><circle cx="7.5" cy="18.5" r="1.5"/><circle cx="17.5" cy="18.5" r="1.5"/></svg></div>
      <span>Нова Пошта, 1–3 дні</span>
    </div>
  </div>

  <!-- EDITORIAL / LIFESTYLE -->
  <section class="section-pad">
    <div class="kicker">У русі</div>
    <h2 class="section-title">Один костюм — на каву, у справах, у вечір</h2>
    <p class="section-lede">Легкий софт не тисне і не м'ється: сідаєте, встаєте, прискорюєте крок — крій тримає форму.</p>
    <div class="editorial-frame">
      <img src="images/street.jpg" alt="Дівчина у костюмі-горошок на вулиці з кавою">
      <div class="editorial-cap">
        <p><b>Кишені є по-справжньому</b> — для телефону й рук, коли руки хочеться нікуди не класти.</p>
      </div>
    </div>

    <ul class="feature-list">
      <li>
        <span class="feature-dot"></span>
        <div>
          <h3>Пояс на резинці</h3>
          <p>Тягнеться під об'єм стегон — розмір «плаває» в межах сітки, не тисне після обіду.</p>
        </div>
      </li>
      <li>
        <span class="feature-dot"></span>
        <div>
          <h3>Розрізи знизу</h3>
          <p>Дають ширину кроку й легкість силуету, не оголюючи зайвого.</p>
        </div>
      </li>
      <li>
        <span class="feature-dot"></span>
        <div>
          <h3>Зав'язки на блузці</h3>
          <p>Самі регулюєте посадку в талії — під фігуру, під настрій, під сукню чи без.</p>
        </div>
      </li>
    </ul>
  </section>

  <!-- FABRIC DETAIL -->
  <section class="section-pad" style="padding-top:0;">
    <div class="kicker">Тканина</div>
    <h2 class="section-title">Софт, який дихає і не потребує прасування</h2>
    <div class="detail-block">
      <div class="detail-photo">
        <img src="images/detail.jpg" alt="Костюм на вішаку крупним планом, тканина софт у горошок">
      </div>
      <div class="spec-grid">
        <div class="spec-card">
          <span>Матеріал</span>
          <p>100% софт — матовий, легкий, приємний до тіла</p>
        </div>
        <div class="spec-card">
          <span>Посадка</span>
          <p>Середня, регулюється поясом і зав'язками</p>
        </div>
        <div class="spec-card">
          <span>Розміри</span>
          <p>42–46 та 48–52, детальна сітка нижче</p>
        </div>
        <div class="spec-card">
          <span>Догляд</span>
          <p>Машинне прання 30°, майже не м'ється</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ORDER -->
  <section id="order" class="section-pad on-bone">
    <div class="kicker">Замовлення</div>
    <h2 class="section-title">Оформити зараз</h2>
    <p class="section-lede">Оплачуєте лише після того, як перевірите річ на пошті.</p>

    <div class="order-card">
      <div class="stock-line">
        <span>Залишилось 8 од. за акцією</span>
        <div class="stock-bar"><i></i></div>
      </div>

      <div class="field-label">Розмір</div>
      <div class="size-grid" id="sizeGrid">
        <button type="button" class="size-opt active" data-size="42-46">42–46<small>S–M</small></button>
        <button type="button" class="size-opt" data-size="48-52">48–52<small>L–XL</small></button>
      </div>

      <div class="field-label">Колір</div>
      <div class="size-grid" id="colorGrid">
        <button type="button" class="color-opt active" data-color="Чорний в горошок">
          <span class="swatch"></span> Чорний в горошок
        </button>
      </div>

      <?php if ($success): ?>
        <div class="form-msg ok">Дякуємо! Ми зателефонуємо для підтвердження замовлення.</div>
      <?php elseif ($error): ?>
        <div class="form-msg err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form class="order-form" id="orderForm" method="post" action="index.php#order">
        <input type="hidden" name="order" value="1">
        <input type="hidden" name="size" id="sizeInput" value="42-46">
        <input type="hidden" name="color" id="colorInput" value="Чорний в горошок">
        <input type="text" name="name" placeholder="Ваше ім'я" required>
        <input type="tel" name="phone" placeholder="Номер телефону" required>
        <button type="submit" class="submit-btn">Замовити за 994 грн</button>
      </form>
      <p class="form-note">Натискаючи «Замовити», ви погоджуєтесь, що менеджер зв'яжеться для підтвердження. Оплата — на відділенні Нової Пошти.</p>
    </div>

    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <div><h3>Обираєте розмір і колір</h3><p>Сумніваєтесь — пишіть, менеджер підкаже за розмірною сіткою.</p></div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div><h3>Підтверджуємо замовлення</h3><p>Дзвонимо протягом дня, узгоджуємо відділення Нової Пошти.</p></div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div><h3>Оплачуєте на пошті</h3><p>Після того, як перевірите річ і переконаєтесь, що все підходить.</p></div>
      </div>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="section-pad">
    <div class="kicker">Відгуки</div>
    <h2 class="section-title">Що кажуть ті, хто вже носить</h2>
    <div class="review-track">
      <div class="review-card">
        <div class="review-stars">★★★★★</div>
        <p>Сіло ідеально по фігурі, а тканина не просвічує і не мнеться навіть після довгого дня.</p>
        <div class="review-who"><div class="review-avatar">М</div><span>Марина, Київ</span></div>
      </div>
      <div class="review-card">
        <div class="review-stars">★★★★★</div>
        <p>Виглядає значно дорожче за свою ціну, шви акуратні, а пояс на резинці — знахідка для будь-якої фігури.</p>
        <div class="review-who"><div class="review-avatar">С</div><span>Світлана, Львів</span></div>
      </div>
      <div class="review-card">
        <div class="review-stars">★★★★★</div>
        <p>Взяла на роботу і на вечір — просто змінюю взуття. Розмірна сітка повністю відповідає дійсності.</p>
        <div class="review-who"><div class="review-avatar">О</div><span>Ольга, Одеса</span></div>
      </div>
    </div>
  </section>

  <div class="dot-divider on-bone dot-field" style="color:var(--line-on-bone);">
    <div class="dot-row" style="color:var(--brass);">
      <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
    </div>
  </div>

  <footer>
    <div class="footer-logo">BELLA WOMEN</div>
    <div class="footer-links">
      <a href="http://sale.nash.cn.ua/1101/about.html">Про нас</a>
      <a href="http://sale.nash.cn.ua/1101/dostavka.html">Доставка та оплата</a>
      <a href="http://sale.nash.cn.ua/1101/warranty.html">Обмін товару</a>
      <a href="http://sale.nash.cn.ua/1101/politics.html">Політика конфіденційності</a>
      <a href="mailto:nash.cn.ua@gmail.com">nash.cn.ua@gmail.com</a>
    </div>
    <div class="footer-meta">
      ФОП Маркс К.Г. · ЄДРПОУ 56356187<br>
      Київ, вул. Арсенальна 26, офіс 173<br>
      © 2019–2026 BELLA WOMEN
    </div>
  </footer>

  <div class="sticky-bar">
    <div><span class="p-new">994 грн</span><span class="p-old">1578 грн</span></div>
    <button type="button" onclick="document.getElementById('order').scrollIntoView({behavior:'smooth'})">Замовити</button>
  </div>

</div>

<script>
  // Size selection
  document.querySelectorAll('#sizeGrid .size-opt').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#sizeGrid .size-opt').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('sizeInput').value = btn.dataset.size;
    });
  });
  document.querySelectorAll('#colorGrid .color-opt').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('#colorGrid .color-opt').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('colorInput').value = btn.dataset.color;
    });
  });

  // Optional: submit order without full page reload (progressive enhancement)
  const form = document.getElementById('orderForm');
  form.addEventListener('submit', function(e){
    e.preventDefault();
    const data = new FormData(form);
    fetch('index.php', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: data
    })
    .then(r => r.json())
    .then(res => {
      const old = document.querySelector('.form-msg');
      if (old) old.remove();
      const msg = document.createElement('div');
      msg.className = 'form-msg ' + (res.success ? 'ok' : 'err');
      msg.textContent = res.success
        ? 'Дякуємо! Ми зателефонуємо для підтвердження замовлення.'
        : (res.error || 'Щось пішло не так, спробуйте ще раз.');
      form.parentNode.insertBefore(msg, form);
      if (res.success) form.reset();
    })
    .catch(() => { form.submit(); }); // fallback: звичайна відправка форми
  });
</script>

</body>
</html>
