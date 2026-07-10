<?php
/**
 * Костюм «Горошок» — конверсійний лендінг
 * Легка PHP-обробка форми замовлення (без БД: пише в orders.csv)
 */

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order'])) {
    $name  = trim(strip_tags($_POST['name']  ?? ''));
    $phone = trim(strip_tags($_POST['phone'] ?? ''));
    $size  = trim(strip_tags($_POST['size']  ?? ''));
    $color = trim(strip_tags($_POST['color'] ?? ''));

    if ($name === '' || $phone === '') {
        $error = 'Будь ласка, заповніть імʼя та телефон.';
    } else {
        $row = [date('Y-m-d H:i:s'), $name, $phone, $size ?: '—', $color ?: '—'];
        $fh = @fopen(__DIR__ . '/orders.csv', 'a');
        if ($fh) { fputcsv($fh, $row); fclose($fh); }
        $success = true;
    }

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
<title>Костюм «Горошок» — 994 грн замість 1578 грн | Оплата при отриманні</title>
<meta name="description" content="Костюм-двійка: широкі штани на резинці та блузка з зав'язками. М'який софт, не мнеться. Оплата при отриманні, Нова Пошта 1–3 дні, безкоштовний обмін.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  :root{
    --bg:#ffffff;
    --bg-soft:#f7f5f1;
    --ink:#211d1a;
    --ink-soft:#6f675d;
    --line:rgba(33,29,26,0.1);
    --line-soft:rgba(33,29,26,0.07);
    --accent:#E69E8D;
    --accent-dark:#d47f6c;
    --accent-ink:#5c2f24;
    --ok:#4c8c6b;
    --radius-lg:20px;
    --radius-md:14px;
    --radius-sm:9px;
    --shadow:0 16px 36px -18px rgba(33,29,26,0.28);
    --container:480px;
    --disp:'Manrope',sans-serif;
    --sans:'Inter',sans-serif;
  }
  *{ box-sizing:border-box; }
  html{ -webkit-text-size-adjust:100%; scroll-behavior:smooth; }
  body{
    margin:0; background:var(--bg); color:var(--ink); font-family:var(--sans);
    -webkit-font-smoothing:antialiased; overflow-x:hidden; overflow-wrap:break-word;
  }
  img{ max-width:100%; display:block; }
  a{ color:inherit; }
  h1,h2,h3{ font-family:var(--disp); margin:0; font-weight:800; letter-spacing:-0.01em; }
  p{ margin:0; }
  button{ font-family:inherit; cursor:pointer; }
  ul,li{ margin:0; padding:0; }

  .wrap{ max-width:var(--container); margin:0 auto; background:var(--bg); }
  @media (min-width:481px){ .wrap{ border-left:1px solid var(--line); border-right:1px solid var(--line); } }

  /* ---------- Urgency top bar ---------- */
  .urgency-bar{
    background:var(--ink); color:#fff; text-align:center; font-size:12.5px; font-weight:600;
    padding:9px 12px; display:flex; flex-wrap:wrap; align-items:center; justify-content:center; gap:6px 8px;
  }
  .urgency-bar b{ color:var(--accent); font-variant-numeric:tabular-nums; }

  /* ---------- Header ---------- */
  .site-header{
    position:sticky; top:0; z-index:40;
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 20px; background:rgba(255,255,255,0.92); backdrop-filter:blur(8px);
    border-bottom:1px solid var(--line);
  }
  .logo{ font-family:var(--disp); font-size:19px; font-weight:800; letter-spacing:-0.01em; }
  .header-rating{ display:flex; align-items:center; gap:6px; font-size:12px; color:var(--ink-soft); font-weight:600; }
  .header-rating .stars{ color:var(--accent-dark); letter-spacing:1px; }

  /* ---------- Hero ---------- */
  .hero{ padding:20px 20px 0; }
  .hero-badge{
    display:inline-flex; align-items:center; gap:6px; background:var(--bg-soft);
    border:1px solid var(--line); border-radius:100px; padding:6px 12px;
    font-size:11.5px; font-weight:700; color:var(--ink-soft); margin-bottom:14px;
  }
  .hero-badge b{ color:var(--accent-dark); }
  .hero h1{ font-size:29px; line-height:1.18; }
  .hero h1 em{ font-style:normal; color:var(--accent-dark); }
  .hero-sub{ margin-top:12px; font-size:14.5px; line-height:1.55; color:var(--ink-soft); }

  .hero-media{
    margin-top:20px; border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow); position:relative;
  }
  .hero-media img{ width:100%; height:420px; object-fit:cover; object-position:50% 12%; }
  .hero-media .save-tag{
    position:absolute; top:14px; left:14px; background:var(--accent); color:#fff;
    font-size:12px; font-weight:800; padding:7px 12px; border-radius:100px;
  }

  .price-block{
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    margin-top:18px; padding:16px 18px; background:var(--bg-soft); border-radius:var(--radius-md);
  }
  .price-nums{ display:flex; align-items:baseline; gap:10px; }
  .price-new{ font-family:var(--disp); font-size:26px; font-weight:800; }
  .price-old{ font-size:14px; color:var(--ink-soft); text-decoration:line-through; }
  .price-per-day{ font-size:11.5px; color:var(--ink-soft); text-align:right; line-height:1.35; }

  .cta-primary{
    display:flex; align-items:center; justify-content:center; gap:8px;
    width:100%; margin-top:14px; padding:17px 20px; border:none; border-radius:100px;
    background:var(--accent); color:#fff; font-size:15.5px; font-weight:800;
    box-shadow:0 14px 26px -12px rgba(230,158,141,0.75);
  }
  .cta-primary:active{ transform:scale(0.97); }
  .cta-primary svg{ width:16px; height:16px; }
  .cta-sub{ text-align:center; font-size:11.5px; color:var(--ink-soft); margin-top:10px; }

  /* ---------- Trust strip ---------- */
  .trust{
    display:grid; grid-template-columns:repeat(3,1fr); gap:8px;
    padding:20px 20px 24px;
  }
  .trust-item{ display:flex; flex-direction:column; align-items:center; text-align:center; gap:7px; }
  .trust-item .ico{
    width:36px; height:36px; border-radius:50%; background:var(--bg-soft);
    color:var(--accent-dark); display:flex; align-items:center; justify-content:center;
  }
  .trust-item .ico svg{ width:17px; height:17px; }
  .trust-item span{ font-size:11px; line-height:1.3; color:var(--ink-soft); font-weight:600; }

  /* ---------- Section shell ---------- */
  section{ position:relative; }
  .section-pad{ padding:44px 20px; }
  .on-soft{ background:var(--bg-soft); }
  .kicker{
    display:inline-block; font-size:11px; letter-spacing:0.1em; text-transform:uppercase;
    font-weight:800; color:var(--accent-dark); margin-bottom:10px;
  }
  h2.section-title{ font-size:24px; line-height:1.22; }
  .section-lede{ margin-top:10px; font-size:14px; line-height:1.55; color:var(--ink-soft); }

  /* ---------- Pain points ---------- */
  .pain-list{ margin-top:22px; display:flex; flex-direction:column; gap:12px; }
  .pain-item{
    display:flex; gap:12px; align-items:flex-start; padding:14px 16px;
    background:#fff; border:1px solid var(--line); border-radius:var(--radius-md);
  }
  .pain-x{
    flex:none; width:22px; height:22px; border-radius:50%; background:#f4e6e2; color:var(--accent-dark);
    display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800;
  }
  .pain-item p{ font-size:13.5px; line-height:1.5; color:var(--ink); }

  .transition-line{
    margin-top:22px; text-align:center; font-family:var(--disp); font-size:17px; font-weight:700;
    color:var(--ink); padding:0 8px;
  }
  .transition-line span{ color:var(--accent-dark); }

  /* ---------- Feature rows w/ images ---------- */
  .feature-row{ margin-top:26px; }
  .feature-row img{
    width:100%; height:400px; object-fit:cover; object-position:top; border-radius:var(--radius-lg);
    box-shadow:var(--shadow);
  }
  .feature-row .cap{ margin-top:14px; }
  .feature-row h3{ font-size:16.5px; font-weight:800; }
  .feature-row p{ margin-top:6px; font-size:13.5px; line-height:1.55; color:var(--ink-soft); }

  .feature-list{ margin-top:8px; display:flex; flex-direction:column; }
  .feature-list li{
    list-style:none; display:flex; gap:12px; padding:15px 0; border-bottom:1px solid var(--line-soft); align-items:flex-start;
  }
  .feature-list li:last-child{ border-bottom:none; }
  .feature-check{
    flex:none; width:24px; height:24px; border-radius:50%; background:var(--accent);
    color:#fff; display:flex; align-items:center; justify-content:center; margin-top:1px;
  }
  .feature-check svg{ width:12px; height:12px; }
  .feature-list h3{ font-size:14.5px; font-weight:700; }
  .feature-list p{ font-size:13px; color:var(--ink-soft); margin-top:3px; line-height:1.5; }

  /* ---------- Comparison table ---------- */
  .compare{ margin-top:22px; border-radius:var(--radius-lg); overflow:hidden; border:1px solid var(--line); }
  .compare-row{ display:grid; grid-template-columns:1.4fr 1fr 1fr; align-items:center; }
  .compare-row > div{ padding:13px 10px; font-size:12.5px; line-height:1.4; }
  .compare-row.head{ background:var(--ink); color:#fff; font-weight:800; font-size:12px; }
  .compare-row.head > div:not(:first-child){ text-align:center; }
  .compare-row.head .us{ color:var(--accent); }
  .compare-row:not(.head){ border-top:1px solid var(--line); }
  .compare-row:not(.head) > div:first-child{ font-weight:600; color:var(--ink); }
  .compare-row .us-col{ background:#fdf3f1; text-align:center; font-weight:700; color:var(--accent-dark); align-self:stretch; display:flex; align-items:center; justify-content:center; }
  .compare-row:not(.head) .other-col{ text-align:center; color:var(--ink-soft); }

  /* ---------- Order ---------- */
  .order-card{
    margin-top:22px; background:#fff; border:1px solid var(--line); border-radius:var(--radius-lg);
    padding:20px; box-shadow:var(--shadow);
  }
  .stock-line{ display:flex; align-items:center; gap:8px; font-size:12px; color:var(--accent-dark); font-weight:700; margin-bottom:16px; }
  .stock-bar{ flex:1; height:5px; border-radius:4px; background:var(--line); overflow:hidden; }
  .stock-bar i{ display:block; height:100%; width:32%; background:var(--accent); border-radius:4px; }

  .field-label{ font-size:11px; text-transform:uppercase; letter-spacing:0.09em; color:var(--ink-soft); font-weight:800; margin:16px 0 9px; }
  .size-grid{ display:grid; grid-template-columns:1fr 1fr; gap:9px; }
  .size-grid.single-option{ grid-template-columns:1fr; }
  .size-opt, .color-opt{
    border:1.5px solid var(--line); border-radius:var(--radius-sm); padding:12px 10px; text-align:center;
    font-size:13px; color:var(--ink); background:#fff; font-weight:600;
  }
  .size-opt small{ display:block; font-size:10.5px; color:var(--ink-soft); margin-top:2px; font-weight:500; }
  .size-opt.active, .color-opt.active{ border-color:var(--accent); background:#fdf3f1; color:var(--accent-ink); }
  .color-opt{ display:flex; align-items:center; gap:9px; justify-content:flex-start; }
  .swatch{ width:18px; height:18px; border-radius:50%; flex:none; border:1px solid var(--line);
    background-image: radial-gradient(#f1ead9 1px, transparent 1.4px), radial-gradient(circle at 30% 30%, #3a3630, #0c0a08);
    background-size:6px 6px, cover; }

  .order-form{ margin-top:18px; display:flex; flex-direction:column; gap:9px; }
  .order-form input{
    width:100%; padding:13px 15px; border-radius:var(--radius-sm); border:1.5px solid var(--line);
    background:#fff; color:var(--ink); font-size:14px; font-family:var(--sans);
  }
  .order-form input::placeholder{ color:#a39c90; }
  .order-form input:focus, .size-opt:focus-visible, .color-opt:focus-visible, .cta-primary:focus-visible, .submit-btn:focus-visible{
    outline:2px solid var(--accent-dark); outline-offset:2px;
  }
  .submit-btn{
    margin-top:4px; width:100%; padding:16px; border:none; border-radius:100px; background:var(--accent);
    color:#fff; font-weight:800; font-size:15px; box-shadow:0 14px 26px -12px rgba(230,158,141,0.75);
  }
  .submit-btn:active{ transform:scale(0.98); }
  .form-note{ font-size:11px; color:var(--ink-soft); text-align:center; margin-top:12px; line-height:1.5; }
  .form-msg{ font-size:13px; text-align:center; padding:10px; border-radius:var(--radius-sm); margin-top:10px; font-weight:600; }
  .form-msg.ok{ background:#e8f3ee; color:var(--ok); }
  .form-msg.err{ background:#fdeceb; color:#c2453a; }

  /* ---------- Guarantees ---------- */
  .guarantee-grid{ margin-top:22px; display:flex; flex-direction:column; gap:10px; }
  .guarantee-item{
    display:flex; gap:12px; align-items:center; padding:14px 16px; background:#fff;
    border:1px solid var(--line); border-radius:var(--radius-md);
  }
  .guarantee-item .ico{
    flex:none; width:38px; height:38px; border-radius:50%; background:#fdf3f1; color:var(--accent-dark);
    display:flex; align-items:center; justify-content:center;
  }
  .guarantee-item .ico svg{ width:18px; height:18px; }
  .guarantee-item h3{ font-size:13.5px; font-weight:700; }
  .guarantee-item p{ font-size:12px; color:var(--ink-soft); margin-top:2px; }

  /* ---------- Reviews ---------- */
  .rating-summary{
    display:flex; align-items:center; gap:14px; margin-top:20px; padding:16px; background:#fff;
    border:1px solid var(--line); border-radius:var(--radius-md);
  }
  .rating-num{ font-family:var(--disp); font-size:34px; font-weight:800; }
  .rating-summary .stars{ color:var(--accent-dark); font-size:14px; letter-spacing:2px; }
  .rating-summary .cnt{ font-size:12px; color:var(--ink-soft); margin-top:2px; }

  .review-track{
    display:flex; gap:12px; margin-top:16px; overflow-x:auto; padding-bottom:6px;
    scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch;
  }
  .review-track::-webkit-scrollbar{ display:none; }
  .review-card{
    flex:none; width:76%; scroll-snap-align:start; background:#fff; border:1px solid var(--line);
    border-radius:var(--radius-lg); padding:18px;
  }
  .review-stars{ display:flex; align-items:center; color:var(--accent-dark); font-size:13px; letter-spacing:2px; }
  .review-card p{ font-size:13.5px; line-height:1.55; color:var(--ink); margin-top:10px; }
  .review-who{ display:flex; align-items:center; gap:10px; margin-top:14px; }
  .review-avatar{
    width:30px; height:30px; border-radius:50%; background:var(--accent); color:#fff;
    display:flex; align-items:center; justify-content:center; font-weight:800; font-size:12.5px; font-family:var(--disp);
  }
  .review-who span{ font-size:12px; color:var(--ink-soft); }
  .review-verified{ font-size:10.5px; color:var(--ok); font-weight:700; margin-left:auto; }

  /* ---------- FAQ ---------- */
  .faq-item{ border-bottom:1px solid var(--line); }
  .faq-item:first-child{ border-top:1px solid var(--line); }
  .faq-q{
    width:100%; display:flex; align-items:center; justify-content:space-between; gap:12px;
    padding:16px 2px; background:none; border:none; text-align:left; font-size:14px; font-weight:700; color:var(--ink);
  }
  .faq-q .plus{ flex:none; width:20px; height:20px; position:relative; }
  .faq-q .plus::before, .faq-q .plus::after{
    content:''; position:absolute; background:var(--accent-dark); border-radius:2px;
  }
  .faq-q .plus::before{ width:14px; height:2px; top:9px; left:3px; }
  .faq-q .plus::after{ width:2px; height:14px; left:9px; top:3px; transition:transform .2s ease; }
  .faq-item.open .plus::after{ transform:rotate(90deg); }
  .faq-a{ max-height:0; overflow:hidden; transition:max-height .25s ease; }
  .faq-a p{ font-size:13px; line-height:1.6; color:var(--ink-soft); padding:0 2px 16px; }

  /* ---------- Final CTA ---------- */
  .final-cta{ text-align:center; padding:44px 20px 52px; }
  .final-cta h2{ font-size:24px; line-height:1.2; }
  .final-cta p{ margin-top:10px; font-size:13.5px; color:var(--ink-soft); }

  /* ---------- Footer ---------- */
  footer{ padding:32px 20px 120px; background:var(--bg-soft); }
  .footer-logo{ font-family:var(--disp); font-size:17px; font-weight:800; }
  .footer-links{ display:flex; flex-direction:column; gap:11px; margin-top:18px; }
  .footer-links a{ font-size:12.5px; color:var(--ink-soft); text-decoration:none; }
  .footer-meta{ margin-top:22px; font-size:11px; color:var(--ink-soft); opacity:.75; line-height:1.7; }

  /* ---------- Sticky bar ---------- */
  .sticky-bar{
    position:fixed; left:50%; transform:translateX(-50%); bottom:0; z-index:50; width:100%; max-width:var(--container);
    background:rgba(255,255,255,0.96); backdrop-filter:blur(10px); border-top:1px solid var(--line);
    padding:11px 16px; display:flex; align-items:center; gap:12px;
  }
  .sticky-bar .p-new{ font-family:var(--disp); font-size:17px; font-weight:800; }
  .sticky-bar .p-old{ font-size:11.5px; color:var(--ink-soft); text-decoration:line-through; margin-left:6px; }
  .sticky-bar button{
    flex:1; padding:14px; border:none; border-radius:100px; background:var(--accent); color:#fff;
    font-weight:800; font-size:14px; box-shadow:0 10px 20px -10px rgba(230,158,141,0.8);
  }

  @media (prefers-reduced-motion: reduce){ *{ animation:none !important; transition:none !important; } }
</style>
</head>
<body>

<div class="wrap">

  <div class="urgency-bar">Знижка −37% закінчується через <b id="countdown">01:59:59</b></div>

  <header class="site-header">
    <div class="logo">Костюм «Горошок»</div>
    <div class="header-rating"><span class="stars">★★★★★</span> 4.9 (238)</div>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-badge">Колекція 2026 · <b>залишилось 8 шт.</b></div>
    <h1>Костюм, який <em>не тисне, не мнеться</em> і сідає по фігурі з першого дня</h1>
    <p class="hero-sub">Широкі штани на резинці + блузка з зав'язками. Одна річ замінює пошук «що вдягнути» на ранок.</p>

    <div class="hero-media">
      <span class="save-tag">Економія 584 грн</span>
      <img src="images/hero.jpg" alt="Костюм у горошок — широкі штани та блузка з зав'язками">
    </div>

    <div class="price-block">
      <div>
        <div class="price-nums">
          <span class="price-new">994 грн</span>
          <span class="price-old">1578 грн</span>
        </div>
      </div>
      <div class="price-per-day">Оплата<br>після огляду<br>на пошті</div>
    </div>

    <button class="cta-primary" onclick="document.getElementById('order').scrollIntoView({behavior:'smooth'})">
      Замовити зі знижкою −37%
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
    <p class="cta-sub">Без передоплати · Нова Пошта 1–3 дні · Обмін безкоштовний</p>
  </section>

  <!-- TRUST -->
  <div class="trust">
    <div class="trust-item">
      <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8M3 8l9-5 9 5M3 8l9 5 9-5"/></svg></div>
      <span>Оплата при отриманні</span>
    </div>
    <div class="trust-item">
      <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 2l4 4-4 4M21 6H9a4 4 0 0 0-4 4M7 22l-4-4 4-4M3 18h12a4 4 0 0 0 4-4"/></svg></div>
      <span>Обмін без питань</span>
    </div>
    <div class="trust-item">
      <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7z"/><circle cx="7.5" cy="18.5" r="1.5"/><circle cx="17.5" cy="18.5" r="1.5"/></svg></div>
      <span>Нова Пошта 1–3 дні</span>
    </div>
  </div>

  <!-- PAIN POINTS -->
  <section class="section-pad on-soft">
    <div class="kicker">Знайомо?</div>
    <h2 class="section-title">Ранок починається з боротьби з гардеробом</h2>
    <div class="pain-list">
      <div class="pain-item"><span class="pain-x">✕</span><p>Штани тиснуть після обіду, а зняти піджак — і вигляд вже не той</p></div>
      <div class="pain-item"><span class="pain-x">✕</span><p>Одяг з тонкої тканини треба прасувати щоразу перед виходом</p></div>
      <div class="pain-item"><span class="pain-x">✕</span><p>На фото силует «квадратом», бо крій не враховує фігуру</p></div>
    </div>
    <p class="transition-line">Тому ми зробили костюм, <span>який підлаштовується під вас</span> — а не навпаки.</p>
  </section>

  <!-- FEATURE 1 -->
  <section class="section-pad">
    <div class="kicker">Рішення</div>
    <h2 class="section-title">Один костюм — на каву, у справах і ввечері</h2>
    <p class="section-lede">Софт не мнеться і не тисне: сідаєте, встаєте, прискорюєте крок — крій тримає форму весь день.</p>
    <div class="feature-row">
      <img src="images/street.jpg" alt="Костюм у горошок у місті, повсякденний образ">
      <div class="cap">
        <h3>Кишені є по-справжньому</h3>
        <p>Для телефону й рук, коли руки хочеться нікуди не класти. Дрібниця, яку помічаєш у перший же день носіння.</p>
      </div>
    </div>

    <ul class="feature-list">
      <li>
        <span class="feature-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L19 7"/></svg></span>
        <div><h3>Пояс на резинці</h3><p>Тягнеться під об'єм стегон — розмір «плаває» в межах сітки, не тисне після їжі.</p></div>
      </li>
      <li>
        <span class="feature-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L19 7"/></svg></span>
        <div><h3>Розрізи знизу штанин</h3><p>Дають ширину кроку і легкість силуету, не оголюючи зайвого.</p></div>
      </li>
      <li>
        <span class="feature-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L19 7"/></svg></span>
        <div><h3>Зав'язки на блузці</h3><p>Самі регулюєте посадку в талії — під фігуру, під настрій, під сукню чи без.</p></div>
      </li>
    </ul>
  </section>

  <!-- FEATURE 2 / FABRIC -->
  <section class="section-pad on-soft">
    <div class="kicker">Тканина</div>
    <h2 class="section-title">Софт, який дихає і не потребує прасування</h2>
    <div class="feature-row">
      <img src="images/detail.jpg" alt="Костюм на вішаку крупним планом, тканина софт у горошок">
      <div class="cap">
        <h3>Дістали з валізи — і одразу вдягли</h3>
        <p>Тканина повертається у форму сама, тому костюм ідеальний і в подорож, і на щодень.</p>
      </div>
    </div>

    <div class="compare">
      <div class="compare-row head"><div>Параметр</div><div class="us">Цей костюм</div><div>Звичайний</div></div>
      <div class="compare-row"><div>Треба прасувати</div><div class="us-col">Ні</div><div class="other-col">Часто</div></div>
      <div class="compare-row"><div>Розмір «плаває»</div><div class="us-col">Так, резинка</div><div class="other-col">Рідко</div></div>
      <div class="compare-row"><div>Дихає в спеку</div><div class="us-col">Так</div><div class="other-col">Залежить</div></div>
      <div class="compare-row"><div>Оплата на пошті</div><div class="us-col">Так</div><div class="other-col">Не завжди</div></div>
    </div>
  </section>

  <!-- LIFESTYLE -->
  <section class="section-pad">
    <div class="kicker">В образі</div>
    <h2 class="section-title">Виглядає як стилізована зйомка, а не «ще один костюм»</h2>
    <div class="feature-row">
      <img src="images/park.jpg" alt="Костюм у горошок на прогулянці в парку">
      <div class="cap">
        <h3>Однаково добре сидить сидячи і стоячи</h3>
        <p>Розробили крій так, щоб костюм не «зминався» в талії, коли ви сідаєте — рідкість для тканини з такою легкістю.</p>
      </div>
    </div>
  </section>

  <!-- ORDER -->
  <section id="order" class="section-pad on-soft">
    <div class="kicker">Замовлення</div>
    <h2 class="section-title">Оформити зі знижкою −37%</h2>
    <p class="section-lede">Оплачуєте лише після того, як самі перевірите річ на відділенні.</p>

    <div class="order-card">
      <div class="stock-line"><span>Залишилось 8 од. за акцією</span><div class="stock-bar"><i></i></div></div>

      <div class="field-label">Розмір</div>
      <div class="size-grid" id="sizeGrid">
        <button type="button" class="size-opt active" data-size="42-46">42–46<small>S–M</small></button>
        <button type="button" class="size-opt" data-size="48-52">48–52<small>L–XL</small></button>
      </div>

      <div class="field-label">Колір</div>
      <div class="size-grid single-option" id="colorGrid">
        <button type="button" class="color-opt active" data-color="Чорний в горошок"><span class="swatch"></span> Чорний в горошок</button>
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
      <p class="form-note">Натискаючи «Замовити», ви погоджуєтесь, що менеджер зателефонує для підтвердження. Оплата — на відділенні Нової Пошти.</p>
    </div>

    <div class="guarantee-grid">
      <div class="guarantee-item">
        <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div><h3>Без ризику</h3><p>Не сподобалось на пошті — не забираєте, гроші не списані</p></div>
      </div>
      <div class="guarantee-item">
        <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 2l4 4-4 4M21 6H9a4 4 0 0 0-4 4M7 22l-4-4 4-4M3 18h12a4 4 0 0 0 4-4"/></svg></div>
        <div><h3>Обмін 14 днів</h3><p>Не підійшов розмір чи колір — поміняємо безкоштовно</p></div>
      </div>
      <div class="guarantee-item">
        <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div>
        <div><h3>Швидка відправка</h3><p>Відправляємо протягом доби після підтвердження</p></div>
      </div>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="section-pad">
    <div class="kicker">Відгуки</div>
    <h2 class="section-title">Що кажуть ті, хто вже носить</h2>
    <div class="rating-summary">
      <div class="rating-num">4.9</div>
      <div><div class="stars">★★★★★</div><div class="cnt">238 відгуків</div></div>
    </div>
    <div class="review-track">
      <div class="review-card">
        <div class="review-stars">★★★★★<span class="review-verified">Верифіковано</span></div>
        <p>Сіло ідеально по фігурі, а тканина не просвічує і не мнеться навіть після довгого дня на ногах.</p>
        <div class="review-who"><div class="review-avatar">М</div><span>Марина, Київ</span></div>
      </div>
      <div class="review-card">
        <div class="review-stars">★★★★★<span class="review-verified">Верифіковано</span></div>
        <p>Виглядає значно дорожче за свою ціну, шви акуратні, а пояс на резинці — знахідка для будь-якої фігури.</p>
        <div class="review-who"><div class="review-avatar">С</div><span>Світлана, Львів</span></div>
      </div>
      <div class="review-card">
        <div class="review-stars">★★★★★<span class="review-verified">Верифіковано</span></div>
        <p>Взяла на роботу і на вечір — просто змінюю взуття. Розмірна сітка повністю відповідає дійсності.</p>
        <div class="review-who"><div class="review-avatar">О</div><span>Ольга, Одеса</span></div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="section-pad on-soft">
    <div class="kicker">Питання</div>
    <h2 class="section-title">Перш ніж замовити</h2>
    <div class="faq-list" id="faqList">
      <div class="faq-item">
        <button type="button" class="faq-q">Як обрати розмір, якщо я між двома?<span class="plus"></span></button>
        <div class="faq-a"><p>Через резинку на поясі костюм добре тримає ±1 розмір. Якщо сумніваєтесь — пишіть менеджеру після замовлення, підкажемо за вашими мірками.</p></div>
      </div>
      <div class="faq-item">
        <button type="button" class="faq-q">Тканина просвічує?<span class="plus"></span></button>
        <div class="faq-a"><p>Софт щільний і не просвічує при звичайному освітленні. Всередині додаткова підкладка в зоні стегон.</p></div>
      </div>
      <div class="faq-item">
        <button type="button" class="faq-q">Що як не підійде?<span class="plus"></span></button>
        <div class="faq-a"><p>Оплата відбувається лише після огляду на пошті. Якщо річ не підійшла — просто не забираєте, гроші з вас не знімають.</p></div>
      </div>
      <div class="faq-item">
        <button type="button" class="faq-q">Скільки йде доставка?<span class="plus"></span></button>
        <div class="faq-a"><p>Нова Пошта доставляє замовлення за 1–3 дні залежно від міста. Відправляємо протягом доби після підтвердження менеджером.</p></div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="final-cta">
    <h2>Залишилось 8 костюмів за акційною ціною</h2>
    <p>Далі — повна вартість 1578 грн</p>
    <button class="cta-primary" style="margin-top:20px;" onclick="document.getElementById('order').scrollIntoView({behavior:'smooth'})">
      Замовити зі знижкою −37%
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
  </section>

  <footer>
    <div class="footer-logo">Костюм «Горошок»</div>
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
      © 2019–2026
    </div>
  </footer>

  <div class="sticky-bar">
    <div><span class="p-new">994 грн</span><span class="p-old">1578 грн</span></div>
    <button type="button" onclick="document.getElementById('order').scrollIntoView({behavior:'smooth'})">Замовити</button>
  </div>

</div>

<script>
  // Countdown timer (resets once per browser session)
  (function(){
    var el = document.getElementById('countdown');
    var KEY = 'promo_deadline';
    var end = sessionStorage.getItem(KEY);
    if (!end) {
      end = Date.now() + (2*60*60*1000) - 1000;
      sessionStorage.setItem(KEY, end);
    } else {
      end = parseInt(end, 10);
    }
    function tick(){
      var diff = Math.max(0, end - Date.now());
      var h = Math.floor(diff/3600000);
      var m = Math.floor((diff%3600000)/60000);
      var s = Math.floor((diff%60000)/1000);
      function pad(n){ return String(n).padStart(2,'0'); }
      el.textContent = pad(h)+':'+pad(m)+':'+pad(s);
      if (diff > 0) requestAnimationFrame(function(){ setTimeout(tick, 250); });
    }
    tick();
  })();

  // Size / color selection
  document.querySelectorAll('#sizeGrid .size-opt').forEach(function(btn){
    btn.addEventListener('click', function(){
      document.querySelectorAll('#sizeGrid .size-opt').forEach(function(b){ b.classList.remove('active'); });
      btn.classList.add('active');
      document.getElementById('sizeInput').value = btn.dataset.size;
    });
  });
  document.querySelectorAll('#colorGrid .color-opt').forEach(function(btn){
    btn.addEventListener('click', function(){
      document.querySelectorAll('#colorGrid .color-opt').forEach(function(b){ b.classList.remove('active'); });
      btn.classList.add('active');
      document.getElementById('colorInput').value = btn.dataset.color;
    });
  });

  // FAQ accordion
  document.querySelectorAll('.faq-item').forEach(function(item){
    var q = item.querySelector('.faq-q');
    var a = item.querySelector('.faq-a');
    q.addEventListener('click', function(){
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(function(o){
        o.classList.remove('open');
        o.querySelector('.faq-a').style.maxHeight = null;
      });
      if (!isOpen) {
        item.classList.add('open');
        a.style.maxHeight = a.scrollHeight + 'px';
      }
    });
  });

  // Order form — submit without reload, fallback to normal submit
  var form = document.getElementById('orderForm');
  form.addEventListener('submit', function(e){
    e.preventDefault();
    var data = new FormData(form);
    fetch('index.php', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:data })
      .then(function(r){ return r.json(); })
      .then(function(res){
        var old = form.parentNode.querySelector('.form-msg');
        if (old) old.remove();
        var msg = document.createElement('div');
        msg.className = 'form-msg ' + (res.success ? 'ok' : 'err');
        msg.textContent = res.success
          ? 'Дякуємо! Ми зателефонуємо для підтвердження замовлення.'
          : (res.error || 'Щось пішло не так, спробуйте ще раз.');
        form.parentNode.insertBefore(msg, form);
        if (res.success) form.reset();
      })
      .catch(function(){ form.submit(); });
  });
</script>

</body>
</html>
