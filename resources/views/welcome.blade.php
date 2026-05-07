<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>BusTrak — Future of Smart Transit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      scroll-behavior: smooth
    }

    body {
      background: #0B0F1A;
      color: #fff;
      font-family: 'Space Grotesk', sans-serif;
      overflow-x: hidden
    }

    .navbar {
      background: rgba(11, 15, 26, 0.7);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(26, 77, 255, 0.2);
      padding: 16px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all .3s
    }

    .navbar-brand {
      font-family: 'Orbitron', monospace;
      font-weight: 900;
      font-size: 1.4rem;
      color: #fff !important;
      letter-spacing: 2px
    }

    .navbar-brand .accent {
      color: #1A4DFF
    }

    .navbar-brand .dot {
      color: #FF8C42
    }

    .nav-link {
      color: rgba(255, 255, 255, 0.75) !important;
      font-weight: 500;
      font-size: .9rem;
      letter-spacing: .5px;
      padding: 8px 16px !important;
      border-radius: 8px;
      transition: all .2s
    }

    .nav-link:hover {
      color: #fff !important;
      background: rgba(26, 77, 255, 0.15)
    }

    .btn-nav-book {
      background: linear-gradient(135deg, #1A4DFF, #0033cc);
      color: #fff !important;
      border-radius: 25px;
      padding: 8px 24px !important;
      font-weight: 600
    }

    .btn-nav-book:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(26, 77, 255, 0.5)
    }

    .hero {
      min-height: 100vh;
      position: relative;
      display: flex;
      align-items: center;
      overflow: hidden;
      background: #0B0F1A
    }

    .hero-bg {
      position: absolute;
      inset: 0;
      z-index: 0
    }

    .grid-lines {
      position: absolute;
      inset: 0;
      background-image: linear-gradient(rgba(26, 77, 255, 0.07) 1px, transparent 1px), linear-gradient(90deg, rgba(26, 77, 255, 0.07) 1px, transparent 1px);
      background-size: 60px 60px
    }

    .glow-orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: .35
    }

    .orb-1 {
      width: 600px;
      height: 600px;
      background: #1A4DFF;
      top: -200px;
      right: -100px
    }

    .orb-2 {
      width: 400px;
      height: 400px;
      background: #FF8C42;
      bottom: -100px;
      left: -100px;
      opacity: .2
    }

    .orb-3 {
      width: 300px;
      height: 300px;
      background: #1A4DFF;
      top: 50%;
      left: 30%;
      opacity: .15
    }

    .hero-tag {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(26, 77, 255, 0.15);
      border: 1px solid rgba(26, 77, 255, 0.4);
      border-radius: 999px;
      padding: 6px 18px;
      font-size: .8rem;
      color: #7eb3ff;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-weight: 600;
      margin-bottom: 24px
    }

    .hero-tag .pulse-dot {
      width: 8px;
      height: 8px;
      background: #1A4DFF;
      border-radius: 50%;
      animation: pulse 2s infinite
    }

    @keyframes pulse {

      0%,
      100% {
        box-shadow: 0 0 0 0 rgba(26, 77, 255, .7)
      }

      70% {
        box-shadow: 0 0 0 8px rgba(26, 77, 255, 0)
      }
    }

    .hero-title {
      font-family: 'Orbitron', monospace;
      font-size: clamp(2.5rem, 6vw, 4.5rem);
      font-weight: 900;
      line-height: 1.1;
      margin-bottom: 20px
    }

    .hero-title .line2 {
      color: #1A4DFF;
      display: block
    }

    .hero-title .line3 {
      color: #FF8C42;
      display: block;
      font-size: clamp(1.8rem, 4vw, 3rem)
    }

    .hero-sub {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, .65);
      line-height: 1.8;
      max-width: 520px;
      margin-bottom: 40px
    }

    .btn-hero-primary {
      background: linear-gradient(135deg, #1A4DFF, #0033cc);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 16px 40px;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: .5px;
      transition: all .3s;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none
    }

    .btn-hero-primary:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 50px rgba(26, 77, 255, .5);
      color: #fff
    }

    .btn-hero-secondary {
      background: transparent;
      color: #fff;
      border: 1px solid rgba(255, 255, 255, .3);
      border-radius: 50px;
      padding: 16px 40px;
      font-size: 1rem;
      font-weight: 600;
      transition: all .3s;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      backdrop-filter: blur(10px)
    }

    .btn-hero-secondary:hover {
      background: rgba(255, 255, 255, .1);
      border-color: rgba(255, 255, 255, .6);
      color: #fff;
      transform: translateY(-4px)
    }

    .hero-bus-wrap {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .bus-float {
      animation: busFloat 4s ease-in-out infinite
    }

    @keyframes busFloat {

      0%,
      100% {
        transform: translateY(0) rotate(-1deg)
      }

      50% {
        transform: translateY(-20px) rotate(1deg)
      }
    }

    .route-line {
      stroke-dasharray: 8 4;
      animation: dashMove 1.5s linear infinite
    }

    @keyframes dashMove {
      to {
        stroke-dashoffset: -24
      }
    }

    .glow-ring {
      animation: glowPulse 3s ease-in-out infinite
    }

    @keyframes glowPulse {

      0%,
      100% {
        opacity: .3
      }

      50% {
        opacity: .7
      }
    }

    .stats-section {
      background: rgba(26, 77, 255, 0.06);
      border-top: 1px solid rgba(26, 77, 255, .2);
      border-bottom: 1px solid rgba(26, 77, 255, .2);
      padding: 40px 0
    }

    .stat-block {
      text-align: center;
      padding: 0 20px
    }

    .stat-num {
      font-family: 'Orbitron', monospace;
      font-size: 2.5rem;
      font-weight: 900;
      color: #1A4DFF;
      display: block
    }

    .stat-label {
      font-size: .85rem;
      color: rgba(255, 255, 255, .55);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 4px;
      display: block
    }

    section {
      padding: 100px 0
    }

    .section-tag {
      display: inline-block;
      background: rgba(26, 77, 255, .15);
      border: 1px solid rgba(26, 77, 255, .3);
      color: #7eb3ff;
      border-radius: 999px;
      padding: 4px 16px;
      font-size: .78rem;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      font-weight: 600;
      margin-bottom: 16px
    }

    .section-title {
      font-family: 'Orbitron', monospace;
      font-size: clamp(1.8rem, 3.5vw, 2.8rem);
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 16px
    }

    .section-sub {
      color: rgba(255, 255, 255, .55);
      font-size: 1rem;
      line-height: 1.8;
      max-width: 500px
    }

    .glass-card {
      background: rgba(255, 255, 255, .04);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: 20px;
      padding: 32px;
      backdrop-filter: blur(20px);
      transition: all .3s;
      position: relative;
      overflow: hidden
    }

    .glass-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(26, 77, 255, .5), transparent)
    }

    .glass-card:hover {
      transform: translateY(-8px);
      border-color: rgba(26, 77, 255, .4);
      box-shadow: 0 20px 60px rgba(26, 77, 255, .15)
    }

    .card-icon {
      width: 56px;
      height: 56px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin-bottom: 20px
    }

    .icon-blue {
      background: rgba(26, 77, 255, .2);
      color: #1A4DFF
    }

    .icon-orange {
      background: rgba(255, 140, 66, .2);
      color: #FF8C42
    }

    .icon-green {
      background: rgba(0, 200, 100, .2);
      color: #00c864
    }

    .icon-purple {
      background: rgba(150, 100, 255, .2);
      color: #9664ff
    }

    .card-title-sm {
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 10px;
      color: #fff
    }

    .card-desc {
      color: rgba(255, 255, 255, .55);
      font-size: .9rem;
      line-height: 1.7
    }

    .tracking-screen {
      background: #060912;
      border: 1px solid rgba(26, 77, 255, .3);
      border-radius: 24px;
      overflow: hidden;
      position: relative
    }

    .tracking-header {
      background: rgba(26, 77, 255, .15);
      border-bottom: 1px solid rgba(26, 77, 255, .2);
      padding: 16px 24px;
      display: flex;
      align-items: center;
      gap: 12px
    }

    .live-badge {
      background: #FF8C42;
      color: #fff;
      border-radius: 999px;
      padding: 3px 12px;
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      animation: pulse 2s infinite
    }

    .map-area {
      height: 340px;
      position: relative;
      overflow: hidden
    }

    .bus-pin {
      position: absolute;
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #1A4DFF, #0033cc);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 3px solid rgba(26, 77, 255, .5);
      box-shadow: 0 0 20px rgba(26, 77, 255, .6);
      animation: busPinMove 6s linear infinite
    }

    @keyframes busPinMove {
      0% {
        left: 10%;
        top: 60%
      }

      25% {
        left: 35%;
        top: 35%
      }

      50% {
        left: 60%;
        top: 50%
      }

      75% {
        left: 75%;
        top: 25%
      }

      100% {
        left: 10%;
        top: 60%
      }
    }

    .bus-pin-2 {
      animation: busPinMove2 8s linear infinite
    }

    @keyframes busPinMove2 {
      0% {
        left: 70%;
        top: 70%
      }

      33% {
        left: 40%;
        top: 80%
      }

      66% {
        left: 20%;
        top: 50%
      }

      100% {
        left: 70%;
        top: 70%
      }
    }

    .info-chip {
      position: absolute;
      background: rgba(11, 15, 26, .9);
      border: 1px solid rgba(26, 77, 255, .3);
      border-radius: 12px;
      padding: 8px 14px;
      font-size: .78rem;
      backdrop-filter: blur(10px)
    }

    .info-chip-1 {
      top: 20px;
      left: 20px
    }

    .info-chip-2 {
      top: 20px;
      right: 20px
    }

    .info-chip-3 {
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%)
    }

    .chip-label {
      color: rgba(255, 255, 255, .5);
      font-size: .7rem;
      margin-bottom: 2px
    }

    .chip-val {
      font-weight: 700;
      color: #fff
    }

    .chip-val.blue {
      color: #1A4DFF
    }

    .chip-val.orange {
      color: #FF8C42
    }

    .chip-val.green {
      color: #00c864
    }

    .booking-form {
      background: rgba(26, 77, 255, .08);
      border: 1px solid rgba(26, 77, 255, .25);
      border-radius: 24px;
      padding: 40px
    }

    .form-field {
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .1);
      border-radius: 12px;
      padding: 14px 18px;
      color: #fff;
      font-family: 'Space Grotesk', sans-serif;
      font-size: .95rem;
      width: 100%;
      transition: all .3s
    }

    .form-field:focus {
      outline: none;
      border-color: #1A4DFF;
      background: rgba(26, 77, 255, .1);
      box-shadow: 0 0 0 3px rgba(26, 77, 255, .2)
    }

    .form-label-sm {
      font-size: .8rem;
      color: rgba(255, 255, 255, .5);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      display: block
    }

    .btn-book-now {
      background: linear-gradient(135deg, #FF8C42, #e06000);
      color: #fff;
      border: none;
      border-radius: 14px;
      padding: 18px;
      font-size: 1rem;
      font-weight: 700;
      width: 100%;
      letter-spacing: .5px;
      transition: all .3s;
      cursor: pointer
    }

    .btn-book-now:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(255, 140, 66, .4)
    }

    .route-option {
      background: rgba(255, 255, 255, .04);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: 14px;
      padding: 16px 20px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: all .3s;
      cursor: pointer
    }

    .route-option:hover,
    .route-option.active {
      border-color: rgba(26, 77, 255, .5);
      background: rgba(26, 77, 255, .1)
    }

    .route-price {
      font-family: 'Orbitron', monospace;
      font-weight: 700;
      color: #FF8C42;
      font-size: 1rem
    }

    .route-info {
      font-size: .85rem
    }

    .route-name {
      font-weight: 600;
      margin-bottom: 2px
    }

    .route-time {
      color: rgba(255, 255, 255, .5);
      font-size: .78rem
    }

    .seat-badge {
      background: rgba(0, 200, 100, .15);
      color: #00c864;
      border-radius: 999px;
      padding: 3px 10px;
      font-size: .72rem;
      font-weight: 600
    }

    .testi-card {
      background: rgba(255, 255, 255, .04);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: 20px;
      padding: 28px;
      transition: all .3s
    }

    .testi-card:hover {
      border-color: rgba(26, 77, 255, .3);
      transform: translateY(-4px)
    }

    .stars {
      color: #FF8C42;
      font-size: .9rem;
      margin-bottom: 12px
    }

    .testi-text {
      color: rgba(255, 255, 255, .7);
      font-size: .9rem;
      line-height: 1.8;
      margin-bottom: 16px;
      font-style: italic
    }

    .testi-author {
      display: flex;
      align-items: center;
      gap: 12px
    }

    .testi-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: .9rem;
      color: #fff
    }

    .testi-name {
      font-weight: 600;
      font-size: .9rem
    }

    .testi-role {
      color: rgba(255, 255, 255, .45);
      font-size: .78rem
    }

    .feature-row {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 20px;
      border-radius: 14px;
      transition: all .3s;
      cursor: default
    }

    .feature-row:hover {
      background: rgba(26, 77, 255, .08)
    }

    .feature-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: rgba(26, 77, 255, .2);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #1A4DFF;
      font-size: 1.2rem;
      flex-shrink: 0
    }

    .feature-text strong {
      display: block;
      font-weight: 600;
      margin-bottom: 3px;
      font-size: .95rem
    }

    .feature-text span {
      color: rgba(255, 255, 255, .5);
      font-size: .85rem
    }

    footer {
      background: #060912;
      border-top: 1px solid rgba(26, 77, 255, .2);
      padding: 60px 0 30px
    }

    .footer-brand {
      font-family: 'Orbitron', monospace;
      font-weight: 900;
      font-size: 1.3rem;
      margin-bottom: 12px
    }

    .footer-desc {
      color: rgba(255, 255, 255, .45);
      font-size: .88rem;
      line-height: 1.7;
      max-width: 280px
    }

    .footer-title {
      font-weight: 700;
      font-size: .85rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: rgba(255, 255, 255, .4);
      margin-bottom: 16px
    }

    .footer-link {
      display: block;
      color: rgba(255, 255, 255, .55);
      font-size: .88rem;
      text-decoration: none;
      margin-bottom: 8px;
      transition: color .2s
    }

    .footer-link:hover {
      color: #1A4DFF
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, .06);
      padding-top: 24px;
      margin-top: 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .82rem;
      color: rgba(255, 255, 255, .35)
    }

    .social-btn {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255, 255, 255, .5);
      transition: all .2s;
      text-decoration: none
    }

    .social-btn:hover {
      background: rgba(26, 77, 255, .2);
      border-color: rgba(26, 77, 255, .4);
      color: #1A4DFF
    }

    .orange {
      color: #FF8C42
    }

    .blue {
      color: #1A4DFF
    }

    .divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(26, 77, 255, .3), transparent);
      margin: 0
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    .animate-up {
      animation: slideUp .7s ease forwards
    }

    .modal-content {
      background: rgba(11, 15, 26, 0.97);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(26, 77, 255, 0.3);
      border-radius: 20px;
      color: #fff
    }

    .modal-header {
      border-bottom: 1px solid rgba(26, 77, 255, 0.2);
      padding: 24px 28px 16px
    }

    .modal-body {
      padding: 20px 28px 28px
    }

    .modal-title {
      font-family: 'Orbitron', monospace;
      font-weight: 700;
      font-size: 1.1rem
    }

    .modal .form-control {
      background: rgba(255, 255, 255, 0.07);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 10px;
      color: #fff;
      padding: 12px 16px;
      font-family: 'Space Grotesk', sans-serif
    }

    .modal .form-control:focus {
      background: rgba(26, 77, 255, 0.12);
      border-color: #1A4DFF;
      box-shadow: 0 0 0 3px rgba(26, 77, 255, 0.25);
      color: #fff
    }

    .modal .form-control::placeholder {
      color: rgba(255, 255, 255, 0.3)
    }

    .modal .form-label {
      color: rgba(255, 255, 255, 0.65);
      font-size: .85rem;
      margin-bottom: 6px
    }

    .btn-modal-primary {
      background: linear-gradient(135deg, #1A4DFF, #0033cc);
      border: none;
      border-radius: 10px;
      padding: 12px 20px;
      color: #fff;
      font-weight: 600;
      width: 100%;
      font-size: .95rem;
      transition: all .3s
    }

    .btn-modal-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(26, 77, 255, 0.4)
    }

    .demo-creds {
      background: rgba(26, 77, 255, 0.1);
      border: 1px solid rgba(26, 77, 255, 0.2);
      border-radius: 10px;
      padding: 12px 16px;
      margin-top: 16px
    }

    .demo-creds small {
      color: rgba(255, 255, 255, 0.5);
      font-size: .75rem
    }

    .modal-switch-link {
      color: #1A4DFF;
      text-decoration: none;
      cursor: pointer
    }

    .modal-switch-link:hover {
      color: #4d7aff;
      text-decoration: underline
    }

    .modal-alert {
      padding: 12px 16px;
      border-radius: 10px;
      margin-bottom: 16px;
      font-size: .85rem
    }

    .modal-alert.success {
      background: rgba(0, 200, 100, 0.12);
      border: 1px solid rgba(0, 200, 100, 0.3);
      color: #00c864
    }

    .modal-alert.error {
      background: rgba(220, 53, 69, 0.12);
      border: 1px solid rgba(220, 53, 69, 0.3);
      color: #ff6b6b
    }

    .modal-error {
      color: #ff6b6b;
      font-size: .75rem;
      margin-top: 5px
    }
  </style>
</head>

<body>

  <!-- Auto-open login modal if redirected with success/status -->
  @if(session('status') || session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
      loginModal.show();
    });
  </script>
  @endif

  <!-- ── NAVBAR ── -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="bi bi-bus-front-fill me-2"></i>Bus<span class="accent">Trak</span><span class="dot">.</span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <i class="bi bi-list text-white fs-4"></i>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav mx-auto gap-1">
          <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#tracking">Live Tracking</a></li>
          <li class="nav-item"><a class="nav-link" href="#booking">Book Ticket</a></li>
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        </ul>
        <div class="d-flex gap-2 align-items-center">
          @auth
          <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('driver') ? route('driver.dashboard') : route('customer.dashboard')) }}"
            class="btn-nav-book nav-link">Dashboard</a>
          @else
          <button type="button" class="nav-link" style="background:none;border:none;cursor:pointer;" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In</button>
          <button type="button" class="btn-nav-book nav-link" style="border:none;cursor:pointer;" data-bs-toggle="modal" data-bs-target="#registerModal">Get Started</button>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  <!-- ── LOGIN MODAL (FIXED - Standard Form Submission) ── -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Welcome Back 👋</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if(session('status'))
          <div class="modal-alert success">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
          </div>
          @endif
          @if(session('success'))
          <div class="modal-alert success">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
          </div>
          @endif

          <!-- STANDARD FORM - NO AJAX -->
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="mb-4 d-flex justify-content-between align-items-center">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="color:rgba(255,255,255,.5);font-size:.85rem;">Remember me</label>
              </div>
              <div>
                <a href="#" class="modal-switch-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" data-bs-dismiss="modal" style="color:#FF8C42;">Forgot Password?</a>
              </div>
            </div>
            <button type="submit" class="btn-modal-primary">Sign In</button>
          </form>

          <div class="demo-creds">
            <small style="display:block;color:rgba(255,255,255,.4);font-weight:600;margin-bottom:4px;">Demo Credentials</small>
            <small style="display:block;">Admin: super@admin.com / password123</small>
            <small style="display:block;">Customer: customer@example.com / customer123</small>
          </div>
          <div class="text-center mt-4">
            <small style="color:rgba(255,255,255,.45);">Don't have an account?
              <a class="modal-switch-link" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Register here</a>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── FORGOT PASSWORD MODAL ── -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="forgotPasswordModalLabel"><i class="bi bi-key me-2" style="color:#FF8C42;"></i>Reset Password</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="forgotAlert"></div>
          <form id="forgotForm">
            @csrf
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
              <div class="modal-error" id="forgotEmailErr"></div>
            </div>
            <button type="submit" class="btn-modal-primary" id="forgotBtn">Send Reset Link</button>
          </form>
          <div class="text-center mt-4">
            <small style="color:rgba(255,255,255,.45);">Remember your password?
              <a class="modal-switch-link" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Back to Login</a>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── REGISTER MODAL ── -->
  <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Create Account ✨</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="registerAlert"></div>
          <form id="registerForm">
            @csrf
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control" placeholder="John Doe" required>
              <div class="modal-error" id="regNameErr"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Email Address</label>
              <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
              <div class="modal-error" id="regEmailErr"></div>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="••••••••" required>
              <div class="modal-error" id="regPassErr"></div>
            </div>
            <div class="mb-4">
              <label class="form-label">Confirm Password</label>
              <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
              <div class="modal-error" id="regPassConfErr"></div>
            </div>
            <button type="submit" class="btn-modal-primary" id="registerBtn">Create Account</button>
          </form>
          <div class="text-center mt-4">
            <small style="color:rgba(255,255,255,.45);">Already have an account?
              <a class="modal-switch-link" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Sign in</a>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── HERO ── -->
  <section class="hero" id="home">
    <div class="hero-bg">
      <div class="grid-lines"></div>
      <div class="glow-orb orb-1"></div>
      <div class="glow-orb orb-2"></div>
      <div class="glow-orb orb-3"></div>
    </div>

    <div class="container position-relative" style="z-index:2;">
      <div class="row align-items-center g-5">
        <div class="col-lg-6 animate-up">
          <div class="hero-tag">
            <span class="pulse-dot"></span>
            Kenya's Most Advanced Transit Platform
          </div>
          <h1 class="hero-title">
            The Future of
            <span class="line2">Smart Bus</span>
            <span class="line3">Travel.</span>
          </h1>
          <p class="hero-sub">
            Real-time GPS tracking, instant seat booking, and M-Pesa payments —
            all in one powerful platform built for Kenya's roads.
          </p>
          <div class="d-flex flex-wrap gap-3">
            @auth
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('driver') ? route('driver.dashboard') : route('customer.dashboard')) }}"
              class="btn-hero-primary">
              <i class="bi bi-speedometer2"></i> Go to Dashboard
            </a>
            @else
            <button type="button" class="btn-hero-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
              <i class="bi bi-ticket-perforated-fill"></i> Book Your Ride
            </button>
            @endauth
            <a href="{{ route('map') }}" class="btn-hero-secondary">
              <i class="bi bi-geo-alt-fill" style="color:#FF8C42;"></i> Track Live Bus
            </a>
          </div>
          <div class="d-flex gap-4 mt-5">
            <div>
              <div style="font-family:'Orbitron',monospace;font-size:1.5rem;font-weight:900;color:#1A4DFF;">50K+</div>
              <div style="color:rgba(255,255,255,.45);font-size:.8rem;">Monthly Riders</div>
            </div>
            <div style="width:1px;background:rgba(255,255,255,.1);"></div>
            <div>
              <div style="font-family:'Orbitron',monospace;font-size:1.5rem;font-weight:900;color:#FF8C42;">98%</div>
              <div style="color:rgba(255,255,255,.45);font-size:.8rem;">On-Time Rate</div>
            </div>
            <div style="width:1px;background:rgba(255,255,255,.1);"></div>
            <div>
              <div style="font-family:'Orbitron',monospace;font-size:1.5rem;font-weight:900;color:#00c864;">24/7</div>
              <div style="color:rgba(255,255,255,.45);font-size:.8rem;">Live Support</div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="hero-bus-wrap">
            <svg viewBox="0 0 500 380" width="100%" style="max-width:520px;filter:drop-shadow(0 30px 60px rgba(26,77,255,0.4))">
              <ellipse cx="250" cy="340" rx="200" ry="20" fill="rgba(26,77,255,0.15)" class="glow-ring" />
              <ellipse cx="250" cy="340" rx="160" ry="14" fill="rgba(26,77,255,0.1)" class="glow-ring" />
              <rect x="30" y="300" width="440" height="50" rx="8" fill="rgba(26,77,255,0.08)" stroke="rgba(26,77,255,0.2)" stroke-width="1" />
              <rect x="100" y="323" width="60" height="4" rx="2" fill="rgba(26,77,255,0.3)" />
              <rect x="220" y="323" width="60" height="4" rx="2" fill="rgba(26,77,255,0.3)" />
              <rect x="340" y="323" width="60" height="4" rx="2" fill="rgba(26,77,255,0.3)" />
              <polyline points="60,260 150,180 250,220 350,140 440,180" fill="none" stroke="#1A4DFF" stroke-width="2" stroke-opacity=".3" class="route-line" />
              <polyline points="60,280 150,240 250,260 350,200 440,240" fill="none" stroke="#FF8C42" stroke-width="1.5" stroke-opacity=".2" class="route-line" style="animation-duration:2s" />
              <g class="bus-float">
                <ellipse cx="250" cy="300" rx="165" ry="12" fill="rgba(26,77,255,0.2)" />
                <rect x="70" y="180" width="360" height="115" rx="22" fill="#0d1224" stroke="#1A4DFF" stroke-width="1.5" />
                <rect x="70" y="220" width="360" height="6" fill="rgba(26,77,255,0.4)" />
                <rect x="70" y="270" width="360" height="3" fill="rgba(255,140,66,0.5)" />
                <rect x="95" y="190" width="52" height="28" rx="8" fill="rgba(26,77,255,0.3)" stroke="rgba(26,77,255,0.6)" stroke-width="1" />
                <rect x="160" y="190" width="52" height="28" rx="8" fill="rgba(26,77,255,0.25)" stroke="rgba(26,77,255,0.5)" stroke-width="1" />
                <rect x="225" y="190" width="52" height="28" rx="8" fill="rgba(26,77,255,0.3)" stroke="rgba(26,77,255,0.6)" stroke-width="1" />
                <rect x="290" y="190" width="52" height="28" rx="8" fill="rgba(26,77,255,0.25)" stroke="rgba(26,77,255,0.5)" stroke-width="1" />
                <rect x="355" y="190" width="55" height="28" rx="8" fill="rgba(26,77,255,0.3)" stroke="rgba(26,77,255,0.6)" stroke-width="1" />
                <rect x="400" y="228" width="30" height="8" rx="4" fill="#FF8C42" style="filter:blur(1px)" />
                <rect x="400" y="240" width="30" height="5" rx="3" fill="rgba(255,140,66,0.6)" />
                <rect x="400" y="250" width="30" height="25" rx="4" fill="rgba(26,77,255,0.3)" stroke="rgba(26,77,255,0.5)" stroke-width="1" />
                <rect x="100" y="247" width="180" height="20" rx="4" fill="rgba(26,77,255,0.2)" stroke="rgba(26,77,255,0.4)" stroke-width="1" />
                <text x="190" y="261" text-anchor="middle" fill="#7eb3ff" font-family="'Orbitron',monospace" font-size="9" font-weight="700">NBI → MOMBASA</text>
                <circle cx="140" cy="295" r="28" fill="#0a0e1a" stroke="#1A4DFF" stroke-width="2" />
                <circle cx="140" cy="295" r="18" fill="rgba(26,77,255,0.2)" stroke="rgba(26,77,255,0.4)" stroke-width="1" />
                <circle cx="140" cy="295" r="6" fill="#1A4DFF" />
                <circle cx="360" cy="295" r="28" fill="#0a0e1a" stroke="#1A4DFF" stroke-width="2" />
                <circle cx="360" cy="295" r="18" fill="rgba(26,77,255,0.2)" stroke="rgba(26,77,255,0.4)" stroke-width="1" />
                <circle cx="360" cy="295" r="6" fill="#1A4DFF" />
                <text x="310" y="265" text-anchor="middle" fill="rgba(255,255,255,0.8)" font-family="'Orbitron',monospace" font-size="11" font-weight="900">BUSTRAK</text>
                <rect x="80" y="288" width="340" height="4" rx="2" fill="rgba(26,77,255,0.4)" style="filter:blur(3px)" />
              </g>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── STATS ── -->
  <div class="stats-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-6 col-md-3">
          <div class="stat-block"><span class="stat-num">120+</span><span class="stat-label">Daily Routes</span></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-block"><span class="stat-num">50K+</span><span class="stat-label">Monthly Passengers</span></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-block"><span class="stat-num">98%</span><span class="stat-label">On-Time Arrival</span></div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-block"><span class="stat-num">4.9★</span><span class="stat-label">Customer Rating</span></div>
        </div>
      </div>
    </div>
  </div>

  <div class="divider"></div>

  <!-- ── LIVE TRACKING ── -->
  <section id="tracking">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-5">
          <span class="section-tag">Live GPS Tracking</span>
          <h2 class="section-title">Know Exactly Where Your Bus Is — <span class="orange">Right Now</span></h2>
          <p class="section-sub">Our real-time GPS system updates every 5 seconds. See your bus moving on the map, get ETA alerts, and never wait at the wrong stop again.</p>
          <div class="mt-4">
            <div class="feature-row">
              <div class="feature-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div class="feature-text"><strong>Live GPS Updates</strong><span>Position updates every 5 seconds</span></div>
            </div>
            <div class="feature-row">
              <div class="feature-icon"><i class="bi bi-clock-fill"></i></div>
              <div class="feature-text"><strong>Smart ETA</strong><span>Accurate arrival predictions based on traffic</span></div>
            </div>
            <div class="feature-row">
              <div class="feature-icon"><i class="bi bi-bell-fill"></i></div>
              <div class="feature-text"><strong>Stop Alerts</strong><span>SMS notification when bus is 2 stops away</span></div>
            </div>
          </div>
          <a href="{{ route('map') }}" class="btn-hero-primary mt-4" style="display:inline-flex;">
            <i class="bi bi-map-fill"></i> Open Live Map
          </a>
        </div>
        <div class="col-lg-7">
          <div class="tracking-screen">
            <div class="tracking-header">
              <span class="live-badge"><i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>LIVE</span>
              <span style="font-size:.9rem;font-weight:600;">BusTrak Fleet Monitor</span>
              <span style="margin-left:auto;color:rgba(255,255,255,.4);font-size:.8rem;font-family:'Orbitron',monospace;">{{ now()->format('H:i:s') }}</span>
            </div>
            <div class="map-area" style="background:linear-gradient(135deg,#060912,#0d1a2e);">
              <svg width="100%" height="100%" viewBox="0 0 700 340" preserveAspectRatio="xMidYMid slice">
                <defs>
                  <pattern id="mapGrid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(26,77,255,0.12)" stroke-width="0.5" />
                  </pattern>
                </defs>
                <rect width="700" height="340" fill="url(#mapGrid)" />
                <path d="M 0 170 Q 175 140 350 170 Q 525 200 700 170" stroke="rgba(26,77,255,0.25)" stroke-width="8" fill="none" />
                <path d="M 0 170 Q 175 140 350 170 Q 525 200 700 170" stroke="rgba(26,77,255,0.08)" stroke-width="14" fill="none" />
                <path d="M 350 0 Q 320 85 350 170 Q 380 255 350 340" stroke="rgba(26,77,255,0.2)" stroke-width="8" fill="none" />
                <circle cx="100" cy="165" r="6" fill="rgba(26,77,255,0.6)" stroke="#1A4DFF" stroke-width="1" />
                <circle cx="250" cy="158" r="6" fill="rgba(26,77,255,0.6)" stroke="#1A4DFF" stroke-width="1" />
                <circle cx="450" cy="175" r="6" fill="rgba(26,77,255,0.6)" stroke="#1A4DFF" stroke-width="1" />
                <circle cx="600" cy="168" r="6" fill="rgba(26,77,255,0.6)" stroke="#1A4DFF" stroke-width="1" />
                <path d="M 0 170 Q 175 140 350 170 Q 525 200 700 170" stroke="#1A4DFF" stroke-width="2" fill="none" stroke-dasharray="12 6" class="route-line" />
                <g class="bus-pin" style="transform:translate(180px,155px)">
                  <circle r="16" fill="#1A4DFF" stroke="rgba(26,77,255,0.4)" stroke-width="8" />
                  <text x="0" y="5" text-anchor="middle" fill="#fff" font-size="12">🚌</text>
                </g>
                <g class="bus-pin bus-pin-2" style="transform:translate(480px,175px)">
                  <circle r="14" fill="#FF8C42" stroke="rgba(255,140,66,0.4)" stroke-width="8" />
                  <text x="0" y="5" text-anchor="middle" fill="#fff" font-size="11">🚌</text>
                </g>
                <text x="100" y="150" fill="rgba(255,255,255,0.4)" font-family="'Space Grotesk',sans-serif" font-size="10" text-anchor="middle">Nairobi CBD</text>
                <text x="350" y="145" fill="rgba(255,255,255,0.4)" font-family="'Space Grotesk',sans-serif" font-size="10" text-anchor="middle">Westlands</text>
                <text x="600" y="153" fill="rgba(255,255,255,0.4)" font-family="'Space Grotesk',sans-serif" font-size="10" text-anchor="middle">Kikuyu</text>
              </svg>
              <div class="info-chip info-chip-1">
                <div class="chip-label">BUS KAA 123A</div>
                <div class="chip-val blue">87 km/h</div>
              </div>
              <div class="info-chip info-chip-2">
                <div class="chip-label">NEXT STOP</div>
                <div class="chip-val orange">Westlands — 4 min</div>
              </div>
              <div class="info-chip info-chip-3">
                <div class="chip-label">ACTIVE BUSES</div>
                <div class="chip-val green">2 Online</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── BOOKING ── -->
  <section id="booking" style="background:rgba(26,77,255,0.03);">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-lg-5">
          <div class="booking-form">
            <h4 style="font-family:'Orbitron',monospace;font-weight:800;margin-bottom:8px;">Book a Seat</h4>
            <p style="color:rgba(255,255,255,.45);font-size:.88rem;margin-bottom:28px;">Select your route and travel date to see available buses</p>
            <div class="mb-3">
              <span class="form-label-sm">From</span>
              <select class="form-field">
                <option>Nairobi CBD</option>
                <option>Westlands</option>
              </select>
            </div>
            <div class="mb-3">
              <span class="form-label-sm">To</span>
              <select class="form-field">
                <option>Mombasa</option>
                <option>Westlands</option>
              </select>
            </div>
            <div class="mb-4">
              <span class="form-label-sm">Travel Date</span>
              <input type="date" class="form-field" value="{{ date('Y-m-d') }}">
            </div>
            <a href="{{ route('register') }}" class="btn-book-now" style="display:block;text-align:center;text-decoration:none;">
              <i class="bi bi-search me-2"></i>Search Available Buses
            </a>
            <div style="border-top:1px solid rgba(255,255,255,.06);margin-top:24px;padding-top:20px;">
              <p style="font-size:.8rem;color:rgba(255,255,255,.35);text-align:center;margin:0;">
                <i class="bi bi-shield-check me-1" style="color:#00c864;"></i>
                Secured by M-Pesa · Instant confirmation
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-7">
          <span class="section-tag">Smart Booking</span>
          <h2 class="section-title">Book in 60 Seconds. Pay with <span class="orange">M-Pesa.</span></h2>
          <p class="section-sub">No queues. No agents. Search routes, pick your seat, and pay directly from your phone. Your digital ticket arrives instantly.</p>
          <div class="mt-4">
            <div class="route-option active">
              <div class="route-info">
                <div class="route-name">Nairobi → Mombasa</div>
                <div class="route-time"><i class="bi bi-clock me-1"></i>7:00 AM &nbsp;·&nbsp; 480 km &nbsp;·&nbsp; ~6 hrs</div>
              </div>
              <div class="text-end">
                <div class="route-price">KES 1,200</div>
                <div class="seat-badge mt-1">32 seats left</div>
              </div>
            </div>
            <div class="route-option">
              <div class="route-info">
                <div class="route-name">Nairobi CBD → Westlands</div>
                <div class="route-time"><i class="bi bi-clock me-1"></i>8:00 AM &nbsp;·&nbsp; 5 km &nbsp;·&nbsp; ~20 min</div>
              </div>
              <div class="text-end">
                <div class="route-price">KES 50</div>
                <div class="seat-badge mt-1">18 seats left</div>
              </div>
            </div>
          </div>
          <div class="d-flex gap-3 mt-4">
            <div class="glass-card flex-fill text-center" style="padding:20px;">
              <i class="bi bi-phone-fill fs-3" style="color:#1A4DFF;"></i>
              <div style="font-weight:700;margin-top:8px;font-size:.9rem;">M-Pesa STK Push</div>
              <div style="color:rgba(255,255,255,.45);font-size:.78rem;margin-top:4px;">Pay from your phone</div>
            </div>
            <div class="glass-card flex-fill text-center" style="padding:20px;">
              <i class="bi bi-qr-code fs-3" style="color:#FF8C42;"></i>
              <div style="font-weight:700;margin-top:8px;font-size:.9rem;">Digital Ticket</div>
              <div style="color:rgba(255,255,255,.45);font-size:.78rem;margin-top:4px;">Instant via email/SMS</div>
            </div>
            <div class="glass-card flex-fill text-center" style="padding:20px;">
              <i class="bi bi-shield-check fs-3" style="color:#00c864;"></i>
              <div style="font-weight:700;margin-top:8px;font-size:.9rem;">Guaranteed Seat</div>
              <div style="color:rgba(255,255,255,.45);font-size:.78rem;margin-top:4px;">Your seat is reserved</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── FEATURES ── -->
  <section id="features">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-tag">Everything You Need</span>
        <h2 class="section-title">Why Kenya Chooses <span class="blue">BusTrak</span></h2>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="glass-card h-100">
            <div class="card-icon icon-blue"><i class="bi bi-geo-alt-fill"></i></div>
            <div class="card-title-sm">Real-Time GPS</div>
            <div class="card-desc">Track every bus live on an interactive map. Updates every 5 seconds so you always know where your bus is.</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="glass-card h-100">
            <div class="card-icon icon-orange"><i class="bi bi-phone-fill"></i></div>
            <div class="card-title-sm">M-Pesa Payments</div>
            <div class="card-desc">Pay for your ticket instantly using M-Pesa STK Push. No cash needed. Confirmation in seconds.</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="glass-card h-100">
            <div class="card-icon icon-green"><i class="bi bi-ticket-perforated-fill"></i></div>
            <div class="card-title-sm">Digital Tickets</div>
            <div class="card-desc">Receive your ticket instantly by email and SMS. Show it on your phone at the gate — no printing needed.</div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="glass-card h-100">
            <div class="card-icon icon-purple"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div class="card-title-sm">Seat Selection</div>
            <div class="card-desc">Choose your exact seat from a visual bus layout. See which seats are taken before you book.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── TESTIMONIALS ── -->
  <section id="about" style="background:rgba(26,77,255,0.03);">
    <div class="container">
      <div class="text-center mb-5">
        <span class="section-tag">Testimonials</span>
        <h2 class="section-title">What Our <span class="orange">Passengers</span> Say</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="testi-card">
            <div class="stars">★★★★★</div>
            <p class="testi-text">"I book my Nairobi-Mombasa ticket in under a minute and pay via M-Pesa. The seat map is brilliant — I always get my window seat!"</p>
            <div class="testi-author">
              <div class="testi-avatar" style="background:linear-gradient(135deg,#1A4DFF,#0033cc);">AM</div>
              <div>
                <div class="testi-name">Amina Mwangi</div>
                <div class="testi-role">Frequent Traveller · Nairobi</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testi-card">
            <div class="stars">★★★★★</div>
            <p class="testi-text">"As a bus operator, BusTrak transformed our business. We can see all our buses live, manage bookings, and receive M-Pesa payments automatically."</p>
            <div class="testi-author">
              <div class="testi-avatar" style="background:linear-gradient(135deg,#FF8C42,#e06000);">JO</div>
              <div>
                <div class="testi-name">James Omondi</div>
                <div class="testi-role">Bus Operator · Kisumu</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testi-card">
            <div class="stars">★★★★★</div>
            <p class="testi-text">"The live map is incredible. I can see exactly where the bus is and I get an SMS when it's 2 stops away. No more waiting in the rain guessing!"</p>
            <div class="testi-author">
              <div class="testi-avatar" style="background:linear-gradient(135deg,#00c864,#007a3d);">FK</div>
              <div>
                <div class="testi-name">Fatuma Kariuki</div>
                <div class="testi-role">Daily Commuter · Nairobi</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- ── CTA ── -->
  <section style="background:linear-gradient(135deg,#0d1433,#0B0F1A);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-50%;right:-10%;width:500px;height:500px;background:#1A4DFF;border-radius:50%;filter:blur(100px);opacity:.15;"></div>
    <div style="position:absolute;bottom:-30%;left:-5%;width:400px;height:400px;background:#FF8C42;border-radius:50%;filter:blur(100px);opacity:.1;"></div>
    <div class="container text-center position-relative" style="z-index:2;">
      <span class="section-tag">Get Started Today</span>
      <h2 class="section-title" style="max-width:600px;margin:0 auto 20px;">Ready to Experience the <span class="blue">Future</span> of Bus Travel?</h2>
      <p style="color:rgba(255,255,255,.5);max-width:500px;margin:0 auto 40px;font-size:1rem;line-height:1.8;">Join thousands of Kenyan travellers booking smarter. Create your free account and book your first ride in minutes.</p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        @auth
        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('driver') ? route('driver.dashboard') : route('customer.dashboard')) }}" class="btn-hero-primary">
          <i class="bi bi-speedometer2"></i> Go to Dashboard
        </a>
        @else
        <button type="button" class="btn-hero-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
          <i class="bi bi-person-plus-fill"></i> Create Free Account
        </button>
        @endauth
        <a href="{{ route('map') }}" class="btn-hero-secondary">
          <i class="bi bi-map-fill"></i> View Live Map
        </a>
      </div>
    </div>
  </section>

  <!-- ── FOOTER ── -->
  <footer>
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-4">
          <div class="footer-brand">Bus<span style="color:#1A4DFF;">Trak</span><span style="color:#FF8C42;">.</span></div>
          <p class="footer-desc">Kenya's most advanced bus management and ticketing platform. Real-time tracking, smart booking, and M-Pesa payments — all in one place.</p>
          <div class="d-flex gap-2 mt-4">
            <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-btn"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>
        <div class="col-6 col-lg-2">
          <div class="footer-title">Platform</div>
          @auth
          <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('driver') ? route('driver.dashboard') : route('customer.dashboard')) }}" class="footer-link">Dashboard</a>
          @else
          <a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#registerModal">Book a Ticket</a>
          <a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In</a>
          <a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
          @endauth
          <a href="{{ route('map') }}" class="footer-link">Live Tracking</a>
        </div>
        <div class="col-6 col-lg-2">
          <div class="footer-title">Routes</div>
          <a href="#" class="footer-link">Nairobi → Mombasa</a>
          <a href="#" class="footer-link">Nairobi → Kisumu</a>
          <a href="#" class="footer-link">Nairobi → Nakuru</a>
          <a href="#" class="footer-link">All City Routes</a>
        </div>
        <div class="col-6 col-lg-2">
          <div class="footer-title">Company</div>
          <a href="#" class="footer-link">About Us</a>
          <a href="#" class="footer-link">Careers</a>
          <a href="#" class="footer-link">Press</a>
          <a href="#" class="footer-link">Contact</a>
        </div>
        <div class="col-6 col-lg-2">
          <div class="footer-title">Support</div>
          <a href="#" class="footer-link">Help Center</a>
          <a href="#" class="footer-link">Privacy Policy</a>
          <a href="#" class="footer-link">Terms of Use</a>
          <a href="#" class="footer-link">Refund Policy</a>
        </div>
      </div>
      <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} BusTrak. All rights reserved. Built in Kenya 🇰🇪</span>
        <span style="font-family:'Orbitron',monospace;font-size:.75rem;color:rgba(26,77,255,.6);">v2.0 FUTURE TRANSIT</span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Route variables for AJAX
    const forgotPasswordRoute = '{{ route("custom.password.email") }}';
    const registerRoute = '{{ route("register") }}';
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // Route option selection
    document.querySelectorAll('.route-option').forEach(o => {
      o.addEventListener('click', function() {
        document.querySelectorAll('.route-option').forEach(x => x.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
      navbar.style.background = window.scrollY > 50
        ? 'rgba(11,15,26,0.95)'
        : 'rgba(11,15,26,0.7)';
    });

    // Helper functions
    function showAlert(id, msg, type) {
      const el = document.getElementById(id);
      if (el) el.innerHTML = `<div class="modal-alert ${type}">${msg}</div>`;
    }

    function clearErrors(formId) {
      document.querySelectorAll(`#${formId} .modal-error`).forEach(e => e.textContent = '');
      document.querySelectorAll(`#${formId} .form-control`).forEach(i => i.style.borderColor = '');
    }

    function showFieldErrors(errors) {
      const map = {
        email: ['regEmailErr', 'forgotEmailErr'],
        password: ['regPassErr'],
        name: ['regNameErr'],
        password_confirmation: ['regPassConfErr']
      };
      Object.keys(errors).forEach(field => {
        (map[field] || []).forEach(id => {
          const el = document.getElementById(id);
          if (el) el.textContent = errors[field][0];
        });
      });
    }

    function setBtn(id, loading, text) {
      const btn = document.getElementById(id);
      if (btn) {
        btn.disabled = loading;
        btn.textContent = loading ? 'Please wait...' : text;
      }
    }

    // ── FORGOT PASSWORD ──
    if (document.getElementById('forgotForm')) {
      document.getElementById('forgotForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        clearErrors('forgotForm');
        setBtn('forgotBtn', true, 'Sending...');

        const email = this.email.value;

        try {
          const formData = new URLSearchParams();
          formData.append('email', email);

          const res = await fetch(forgotPasswordRoute, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json',
            },
            body: formData.toString()
          });

          const data = await res.json();

          if (res.ok) {
            showAlert('forgotAlert', '✅ ' + (data.message || 'Reset link sent! Check your email.'), 'success');
            document.getElementById('forgotForm').reset();
            setTimeout(() => {
              const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
              if (modal) modal.hide();
              const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
              loginModal.show();
            }, 2000);
          } else {
            if (res.status === 422 && data.errors) {
              if (data.errors.email) {
                document.getElementById('forgotEmailErr').textContent = data.errors.email[0];
              } else if (data.message) {
                showAlert('forgotAlert', data.message, 'error');
              }
            } else {
              showAlert('forgotAlert', data.message || 'Failed to send reset link.', 'error');
            }
          }
        } catch (err) {
          console.error('Error:', err);
          showAlert('forgotAlert', 'Network error. Please try again.', 'error');
        }

        setBtn('forgotBtn', false, 'Send Reset Link');
      });
    }

    // ── REGISTER ──
    if (document.getElementById('registerForm')) {
      document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        clearErrors('registerForm');
        setBtn('registerBtn', true, 'Creating Account...');

        try {
          const res = await fetch(registerRoute, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json',
            },
            body: JSON.stringify({
              name: this.name.value,
              email: this.email.value,
              password: this.password.value,
              password_confirmation: this.password_confirmation.value,
            })
          });

          if (res.ok) {
            showAlert('registerAlert', '✅ Account created! Redirecting...', 'success');
            setTimeout(() => {
              window.location.href = '/customer/dashboard';
            }, 900);
            return;
          }

          const data = await res.json();
          if (res.status === 422 && data.errors) {
            showFieldErrors(data.errors);
          } else {
            showAlert('registerAlert', data.message || 'Registration failed. Please try again.', 'error');
          }
        } catch (err) {
          showAlert('registerAlert', 'Network error. Please try again.', 'error');
        }

        setBtn('registerBtn', false, 'Create Account');
      });
    }
  </script>
</body>

</html>