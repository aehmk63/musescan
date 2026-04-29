{{--
|--------------------------------------------------------------------------
| welcome.blade.php — Musée de l'Armée Centrale
| Placement : resources/views/welcome.blade.php
|
| ASSETS REQUIS :
|   resources/images/anp.png   → Logo Ministère de la Défense Nationale
|   resources/images/dic.png   → Logo Direction Information & Communication
|   resources/images/bg.jpg    → Photo intérieure du musée (hero background)
|
| DÉPENDANCE CDN :
|   jsQR 1.4.0 — décodage QR code réel via caméra + canvas
|--------------------------------------------------------------------------
--}}
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>المتحف المركزي للجيش — Musée de l'armée centrale</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />

    {{-- jsQR : décodage QR réel via canvas --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.4.0/jsQR.js"></script>

    <style>
        /* ============================================================
           RESET & VARIABLES
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        :root {
            --hdr-green:  #132016;
            --dark-green: #0e1b10;
            --gold:       #c8a84b;
            --gold-lt:    #dfc278;
            --gold-dim:   rgba(200,168,75,0.15);
            --ivory:      #f3ecd9;
            --ink:        #1c1406;
            --ink-mid:    #3a2e18;
            --muted-gold: rgba(200,168,75,0.6);
            --fa: 'Amiri', 'Traditional Arabic', serif;
            --ff: 'Cormorant Garamond', Georgia, serif;
            --fu: 'Jost', sans-serif;
        }

        body {
            font-family: var(--fu);
            background: var(--dark-green);
            color: #fff;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ============================================================
           HEADER
        ============================================================ */
        .hdr {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            height: 70px;
            background: var(--hdr-green);
            border-bottom: 1px solid rgba(200,168,75,0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 36px;
        }

        .hdr-block {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .hdr-block.r { flex-direction: row-reverse; }

        .emblem {
            width: 44px; height: 44px;
            border-radius: 50%;
            border: 1px solid var(--muted-gold);
            overflow: hidden;
            flex-shrink: 0;
            background: rgba(200,168,75,0.06);
            display: flex; align-items: center; justify-content: center;
        }

        .emblem img {
            width: 100%; height: 100%;
            object-fit: contain;
            padding: 4px;
        }

        .emblem-fallback {
            width: 22px; height: 22px;
            stroke: var(--muted-gold);
            fill: none;
            stroke-width: 0.9;
        }

        .hdr-txt { line-height: 1.35; }
        .hdr-t1 {
            font-family: var(--fu);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
        }
        .hdr-t2 {
            font-family: var(--fu);
            font-size: 10px;
            font-weight: 300;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: var(--muted-gold);
        }
        .hdr-block.r .hdr-txt { text-align: right; }

        /* ============================================================
           HERO
        ============================================================ */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 640px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            padding-top: 70px;
        }

        /*
        ┌──────────────────────────────────────────┐
        │  BG IMAGE : resources/images/bg.jpg      │
        └──────────────────────────────────────────┘
        */
        .hero-bg {
            position: absolute; inset: 0;
            background: url('{{ asset("images/bg.jpg") }}') center 35% / co no-repeat;
            filter: brightness(0.32) saturate(0.65);
        }

        .hero-veil {
            position: absolute; inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(13,27,14,0.6) 0%,
                rgba(13,27,14,0.25) 45%,
                rgba(13,27,14,0.72) 100%
            );
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 820px;
            padding: 0 28px;
            animation: up 1.1s ease both;
        }

        @keyframes up {
            from { opacity:0; transform: translateY(22px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .hero-republic-ar {
            font-family: var(--fa);
            font-size: clamp(14px,1.9vw,20px);
            color: rgba(255,255,255,0.72);
            direction: rtl;
            margin-bottom: 5px;
        }

        .hero-republic-fr {
            font-family: var(--fu);
            font-size: 9.5px;
            font-weight: 500;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--muted-gold);
            margin-bottom: 22px;
        }

        /* Séparateur doré ♦ */
        .div-gold {
            display: flex; align-items: center; justify-content: center;
            gap: 12px;
            margin: 0 auto 22px;
            width: fit-content;
        }

        .dg-line {
            width: 56px; height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold));
        }
        .dg-line.r { background: linear-gradient(90deg, var(--gold), transparent); }

        .dg-diamond {
            width: 7px; height: 7px;
            background: var(--gold);
            transform: rotate(45deg);
            flex-shrink: 0;
        }

        .hero-ar {
            font-family: var(--fa);
            font-size: clamp(44px,8vw,90px);
            font-weight: 700;
            color: #fff;
            direction: rtl;
            line-height: 1.1;
            margin-bottom: 10px;
            text-shadow: 0 2px 30px rgba(0,0,0,0.45);
        }

        .hero-fr {
            font-family: var(--ff);
            font-size: clamp(24px,4.5vw,58px);
            font-weight: 400;
            font-style: italic;
            color: var(--gold-lt);
            margin-bottom: 22px;
        }

        .hero-desc {
            font-family: var(--ff);
            font-size: clamp(13px,1.5vw,17px);
            font-style: italic;
            font-weight: 300;
            color: rgba(255,255,255,0.6);
            line-height: 1.8;
            max-width: 500px;
            margin: 0 auto 44px;
        }

        /* Boutons */
        .hero-btns {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .hbtn {
            width: 100%;
            max-width: 470px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-family: var(--fu);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            padding: 18px 32px;
            border: none;
            cursor: pointer;
            transition: all 0.22s;
            border-radius: 0;
        }

        .hbtn-gold {
            background: var(--gold);
            color: #1c1406;
        }
        .hbtn-gold:hover { background: var(--gold-lt); }

        .hbtn-gold svg {
            width: 18px; height: 18px;
            fill: currentColor;
        }

        .hbtn-outline {
            background: transparent;
            color: rgba(255,255,255,0.82);
            border: 1px solid rgba(255,255,255,0.28);
        }
        .hbtn-outline:hover {
            border-color: var(--gold);
            color: var(--gold-lt);
            background: var(--gold-dim);
        }

        /* Chevron bas */
        .hero-chevron {
            position: absolute;
            bottom: 26px; left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            background: none;
            border: none;
            color: var(--muted-gold);
            font-size: 24px;
            cursor: pointer;
            animation: bounce 2.2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%,100% { transform: translateX(-50%) translateY(0); }
            50%      { transform: translateX(-50%) translateY(8px); }
        }

        /* ============================================================
           SECTION ORGANIGRAMME (fond ivoire)
        ============================================================ */
        .sec-org {
            background: var(--ivory);
            /* texture légère */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='6' height='6'%3E%3Crect width='6' height='6' fill='%23f3ecd9'/%3E%3Ccircle cx='1' cy='1' r='0.6' fill='%23ddd0b8' opacity='0.35'/%3E%3C/svg%3E");
            padding: 104px clamp(24px,6vw,80px);
            text-align: center;
        }

        .org-ar {
            font-family: var(--fa);
            font-size: clamp(22px,3.5vw,40px);
            font-weight: 700;
            color: var(--ink);
            direction: rtl;
            line-height: 1.35;
            margin-bottom: 8px;
        }

        .org-fr {
            font-family: var(--fu);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--ink-mid);
        }

        .org-sep {
            display: flex; align-items: center; justify-content: center;
            gap: 16px;
            margin: 38px auto;
            width: fit-content;
        }

        .os-line {
            width: 80px; height: 1px;
            background: var(--gold);
            opacity: 0.55;
        }

        .os-diamond {
            width: 7px; height: 7px;
            background: var(--gold);
            transform: rotate(45deg);
            flex-shrink: 0;
        }

        /* ============================================================
           SECTION PLAN (placeholder)
        ============================================================ */
        .sec-plan {
            background: var(--ivory);
            border-top: 1px solid rgba(200,168,75,0.18);
            padding: 80px clamp(24px,6vw,80px);
        }

        .plan-wrap { max-width: 960px; margin: 0 auto; }

        .plan-hd {
            text-align: center;
            margin-bottom: 48px;
        }

        .plan-label {
            display: block;
            font-family: var(--fu);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 14px;
        }

        .plan-ar {
            font-family: var(--fa);
            font-size: clamp(18px,2.8vw,30px);
            color: var(--ink);
            direction: rtl;
            margin-bottom: 4px;
        }

        .plan-frf {
            font-family: var(--ff);
            font-size: clamp(14px,2vw,20px);
            font-style: italic;
            color: var(--ink-mid);
        }

        /*
        ╔══════════════════════════════════════════════════════════════╗
        ║  PLACEHOLDER — REMPLACER PAR LE PLAN DU MUSÉE               ║
        ╚══════════════════════════════════════════════════════════════╝
        */
        .plan-box {
            border: 1px dashed rgba(200,168,75,0.3);
            border-radius: 3px;
            height: 400px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 14px;
            background: rgba(200,168,75,0.025);
            position: relative;
        }

        .plan-box::before {
            content: '';
            position: absolute; inset: 10px;
            border: 1px dashed rgba(200,168,75,0.1);
            border-radius: 2px;
            pointer-events: none;
        }

        .plan-icon {
            width: 48px; height: 48px;
            border: 1px solid rgba(200,168,75,0.3);
            border-radius: 3px;
            display: flex; align-items: center; justify-content: center;
        }

        .plan-icon svg {
            width: 22px; height: 22px;
            stroke: var(--gold); fill: none; stroke-width: 1.3;
        }

        .plan-box h3 {
            font-family: var(--ff);
            font-size: 20px;
            font-weight: 300;
            font-style: italic;
            color: rgba(28,20,6,0.28);
        }

        .plan-box p {
            font-family: var(--fu);
            font-size: 11px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(28,20,6,0.18);
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        .ftr {
            background: var(--hdr-green);
            border-top: 1px solid rgba(200,168,75,0.28);
            padding: 56px 40px 48px;
            text-align: center;
        }

        .ftr-deco {
            display: flex; align-items: center; justify-content: center;
            gap: 14px;
            margin-bottom: 26px;
        }

        .fd-line { width: 70px; height: 1px; }
        .fd-line.l { background: linear-gradient(90deg, transparent, var(--gold)); }
        .fd-line.r { background: linear-gradient(90deg, var(--gold), transparent); }

        .fd-diamond {
            width: 7px; height: 7px;
            background: var(--gold);
            transform: rotate(45deg);
        }

        .ftr-ar {
            font-family: var(--fa);
            font-size: 18px;
            color: var(--gold-lt);
            direction: rtl;
            margin-bottom: 4px;
        }

        .ftr-fr {
            font-family: var(--ff);
            font-size: 15px;
            font-style: italic;
            color: var(--muted-gold);
            margin-bottom: 26px;
        }

        .ftr-copy {
            font-family: var(--fu);
            font-size: 10px;
            font-weight: 400;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgba(200,168,75,0.3);
        }

        /* ============================================================
           MODAL QR SCANNER
        ============================================================ */
        .qr-ov {
            position: fixed; inset: 0;
            z-index: 300;
            background: rgba(8,18,10,0.93);
            backdrop-filter: blur(10px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .qr-ov.open { display: flex; }

        .qr-box {
            background: var(--hdr-green);
            border: 1px solid rgba(200,168,75,0.22);
            border-radius: 4px;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            animation: pop 0.3s cubic-bezier(0.34,1.56,0.64,1) both;
        }

        @keyframes pop {
            from { opacity:0; transform: scale(0.91) translateY(14px); }
            to   { opacity:1; transform: scale(1) translateY(0); }
        }

        .qr-top {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(200,168,75,0.1);
        }

        .qr-top-title {
            font-family: var(--ff);
            font-size: 18px;
            font-style: italic;
            color: var(--gold-lt);
        }

        .qr-x {
            width: 30px; height: 30px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 3px;
            color: rgba(255,255,255,0.45);
            font-size: 16px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.18s;
        }

        .qr-x:hover { background: rgba(255,255,255,0.1); color: #fff; }

        .qr-body { padding: 20px; }

        /* Status */
        .qr-stat {
            display: flex; align-items: center;
            gap: 9px; margin-bottom: 14px;
        }

        .qr-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.18);
            flex-shrink: 0;
            transition: background 0.25s;
        }

        .qr-dot.scanning {
            background: #4ade80;
            box-shadow: 0 0 8px rgba(74,222,128,0.5);
            animation: blink 1.4s infinite;
        }

        .qr-dot.ok { background: var(--gold); box-shadow: 0 0 8px rgba(200,168,75,0.45); }
        .qr-dot.err { background: #f87171; }

        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.45} }

        .qr-stat-txt {
            font-family: var(--fu);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            transition: color 0.25s;
        }

        /* Vidéo zone */
        .qr-vid-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            background: #000;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 14px;
        }

        #qrVideo {
            width: 100%; height: 100%;
            object-fit: cover;
            display: none;
        }

        /* Canvas caché pour jsQR */
        #qrCanvas { display: none; }

        /* Idle placeholder */
        .qr-idle {
            width: 100%; height: 100%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 12px; padding: 20px;
        }

        .qr-idle svg {
            width: 54px; height: 54px;
            stroke: rgba(200,168,75,0.28);
            fill: none; stroke-width: 0.8;
        }

        .qr-idle p {
            font-family: var(--fu);
            font-size: 12px;
            color: rgba(255,255,255,0.18);
            text-align: center;
            line-height: 1.65;
        }

        /* Cadre de scan animé */
        .qr-frame {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            width: 60%; aspect-ratio: 1;
            display: none; pointer-events: none;
        }

        .qfc {
            position: absolute;
            width: 20px; height: 20px;
            border-color: var(--gold);
            border-style: solid;
        }

        .qfc.tl { top:0; left:0;  border-width: 2px 0 0 2px; }
        .qfc.tr { top:0; right:0; border-width: 2px 2px 0 0; }
        .qfc.bl { bottom:0; left:0; border-width: 0 0 2px 2px; }
        .qfc.br { bottom:0; right:0; border-width: 0 2px 2px 0; }

        .qr-beam {
            position: absolute;
            left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            top: 0;
            animation: beam 2s ease-in-out infinite;
        }

        @keyframes beam {
            0%  { top:4%;  opacity:0; }
            8%  { opacity:1; }
            92% { opacity:1; }
            100%{ top:96%; opacity:0; }
        }

        /* Résultat détecté */
        .qr-res {
            display: none;
            background: rgba(200,168,75,0.07);
            border: 1px solid rgba(200,168,75,0.22);
            border-radius: 3px;
            padding: 14px 16px;
            margin-bottom: 12px;
        }

        .qr-res-lbl {
            font-family: var(--fu);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 6px;
        }

        .qr-res-val {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            line-height: 1.5;
            word-break: break-all;
        }

        /* Bouton */
        #qrBtn {
            width: 100%;
            font-family: var(--fu);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--ink);
            background: var(--gold);
            padding: 14px;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 4px;
        }

        #qrBtn:hover { background: var(--gold-lt); }
        #qrBtn:disabled { opacity: 0.35; cursor: not-allowed; }

        .qr-note {
            font-family: var(--fu);
            font-size: 11px;
            color: rgba(255,255,255,0.2);
            text-align: center;
            line-height: 1.7;
            margin-top: 10px;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 600px) {
            .hdr { padding: 0 16px; }
            .hdr-t1, .hdr-t2 { font-size: 8.5px; }
            .emblem { width: 36px; height: 36px; }
            .hbtn { max-width: 100%; font-size: 11px; }
        }
    </style>
</head>
<body>

{{-- ────────────────────────────────────────────
     HEADER
──────────────────────────────────────────── --}}
<header class="hdr">

    {{-- Gauche --}}
    <div class="hdr-block l">
        <div class="emblem">
            {{-- resources/images/anp.png --}}
            <img src="{{ asset('images/anp.png') }}" alt="ANP"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
            <svg class="emblem-fallback" style="display:none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9"/><path d="M12 3v18M3 12h18" stroke-width="0.5"/>
            </svg>
        </div>
        <div class="hdr-txt">
            <div class="hdr-t1">Ministère de la</div>
            <div class="hdr-t2">Défense Nationale</div>
        </div>
    </div>

    {{-- Droite --}}
    <div class="hdr-block r">
        <div class="emblem">
            {{-- resources/images/dic.png --}}
            <img src="{{ asset('images/dic.png') }}" alt="DIC"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
            <svg class="emblem-fallback" style="display:none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9"/><path d="M12 3v18M3 12h18" stroke-width="0.5"/>
            </svg>
        </div>
        <div class="hdr-txt">
            <div class="hdr-t1">Direction de l'Information</div>
            <div class="hdr-t2">et de la Communication</div>
        </div>
    </div>

</header>


{{-- ────────────────────────────────────────────
     HERO
──────────────────────────────────────────── --}}
<section class="hero" id="top">

    {{-- resources/images/bg.jpg --}}
    <div class="hero-bg"></div>
    <div class="hero-veil"></div>

    <div class="hero-content">

        <p class="hero-republic-ar">الجمهورية الجزائرية الديمقراطية الشعبية</p>
        <p class="hero-republic-fr">République Algérienne Démocratique et Populaire</p>

        <div class="div-gold" aria-hidden="true">
            <div class="dg-line"></div>
            <div class="dg-diamond"></div>
            <div class="dg-line r"></div>
        </div>

        <h1 class="hero-ar">المتحف المركزي للجيش</h1>
        <p class="hero-fr">Musée de l'armée centrale</p>

        <p class="hero-desc">
            Mémoire vivante de l'Armée Nationale Populaire —<br>
            un patrimoine d'honneur, de courage et d'histoire.
        </p>

        <div class="hero-btns">

            <button class="hbtn hbtn-gold" onclick="openQr()" type="button">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3h7v7H3zM5 5v3h3V5zM14 3h7v7h-7zM16 5v3h3V5zM3 14h7v7H3zM5 16v3h3v-3zM14 14h2v2h-2zM18 14h2v2h-2zM14 18h2v2h-2zM18 18h2v2h-2z"/>
                </svg>
                Scanner le QR
            </button>

            <button class="hbtn hbtn-outline" onclick="goTo('sec-org')" type="button">
                Commencer la visite &nbsp;→
            </button>

        </div>
    </div>

    <button class="hero-chevron" onclick="goTo('sec-org')" type="button" aria-label="Défiler">&#8964;</button>

</section>


{{-- ────────────────────────────────────────────
     ORGANIGRAMME INSTITUTIONNEL (fond ivoire)
──────────────────────────────────────────── --}}
<section class="sec-org" id="sec-org">

    <div class="org-level">
        <p class="org-ar">وزارة الدفاع الوطني</p>
        <p class="org-fr">Ministère de la Défense Nationale</p>
    </div>

    <div class="org-sep" aria-hidden="true">
        <div class="os-line"></div>
        <div class="os-diamond"></div>
        <div class="os-line"></div>
    </div>

    <div class="org-level">
        <p class="org-ar">أركان الجيش الوطني الشعبي</p>
        <p class="org-fr">État-major de l'Armée Nationale Populaire</p>
    </div>

    <div class="org-sep" aria-hidden="true">
        <div class="os-line"></div>
        <div class="os-diamond"></div>
        <div class="os-line"></div>
    </div>

    <div class="org-level">
        <p class="org-ar">مديرية الإعلام والاتصال</p>
        <p class="org-fr">Direction de l'Information et de la Communication</p>
    </div>

</section>


{{-- ────────────────────────────────────────────
     PLAN DU MUSÉE (PLACEHOLDER)
     Entre organigramme et footer
──────────────────────────────────────────── --}}
<section class="sec-plan" id="sec-plan">
    <div class="plan-wrap">

        <div class="plan-hd">
            <div class="org-sep" aria-hidden="true" style="margin-bottom:22px;">
                <div class="os-line"></div>
                <div class="os-diamond"></div>
                <div class="os-line"></div>
            </div>
            <span class="plan-label">Navigation</span>
            <p class="plan-ar">خريطة المتحف</p>
            <p class="plan-frf">Plan &amp; Organisation du Musée</p>
        </div>

        {{--
        ╔══════════════════════════════════════════════════════════════╗
        ║  PLACEHOLDER — PLAN DU MUSÉE                                ║
        ║  Remplacer ce bloc par :                                    ║
        ║  · SVG interactif des 4 étages                              ║
        ║  · Composant Alpine / Vue / React                           ║
        ║  · Image du plan architectural                              ║
        ╚══════════════════════════════════════════════════════════════╝
        --}}
        <div class="plan-box" role="img" aria-label="Emplacement réservé au plan du musée">
            <div class="plan-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <path d="M3 21V7l9-4 9 4v14"/>
                    <path d="M9 21v-6h6v6"/><path d="M9 9h.01M15 9h.01"/>
                </svg>
            </div>
            <h3>Plan du Musée — 4 Étages</h3>
            <p>Ce bloc sera remplacé par le diagramme interactif</p>
        </div>

    </div>
</section>


{{-- ────────────────────────────────────────────
     FOOTER
──────────────────────────────────────────── --}}
<footer class="ftr">

    <div class="ftr-deco" aria-hidden="true">
        <div class="fd-line l"></div>
        <div class="fd-diamond"></div>
        <div class="fd-line r"></div>
    </div>

    <p class="ftr-ar">المتحف المركزي للجيش</p>
    <p class="ftr-fr">Musée de l'armée centrale</p>

    <p class="ftr-copy">© {{ date('Y') }} · ANP — Direction de l'Information et de la Communication</p>

</footer>


{{-- ────────────────────────────────────────────
     MODAL QR SCANNER
──────────────────────────────────────────── --}}
<div class="qr-ov" id="qrOverlay" onclick="bgClose(event)"
     role="dialog" aria-modal="true" aria-labelledby="qrTitle">

    <div class="qr-box">

        <div class="qr-top">
            <span class="qr-top-title" id="qrTitle">Scanner un code QR</span>
            <button class="qr-x" onclick="closeQr()" type="button" aria-label="Fermer">✕</button>
        </div>

        <div class="qr-body">

            <div class="qr-stat">
                <div class="qr-dot" id="qrDot"></div>
                <span class="qr-stat-txt" id="qrMsg">En attente</span>
            </div>

            <div class="qr-vid-wrap">

                <div class="qr-idle" id="qrIdle">
                    <svg viewBox="0 0 64 64">
                        <rect x="4"  y="4"  width="24" height="24" rx="2" stroke-width="1.5"/>
                        <rect x="36" y="4"  width="24" height="24" rx="2" stroke-width="1.5"/>
                        <rect x="4"  y="36" width="24" height="24" rx="2" stroke-width="1.5"/>
                        <rect x="10" y="10" width="12" height="12" rx="1" stroke-width="1"/>
                        <rect x="42" y="10" width="12" height="12" rx="1" stroke-width="1"/>
                        <rect x="10" y="42" width="12" height="12" rx="1" stroke-width="1"/>
                        <path d="M40 40h6v6h-6zm8 0h6v6h-6zm-8 8h6v6h-6zm8 8h6v6h-6z" stroke-width="0.8"/>
                    </svg>
                    <p>Appuyez sur "Activer la caméra"<br>pour scanner un objet du musée</p>
                </div>

                <video id="qrVideo" autoplay playsinline muted></video>
                <canvas id="qrCanvas"></canvas>

                <div class="qr-frame" id="qrFrame">
                    <div class="qfc tl"></div>
                    <div class="qfc tr"></div>
                    <div class="qfc bl"></div>
                    <div class="qfc br"></div>
                    <div class="qr-beam"></div>
                </div>

            </div>

            <div class="qr-res" id="qrRes">
                <div class="qr-res-lbl">Code détecté</div>
                <div class="qr-res-val" id="qrResVal">—</div>
            </div>

            <button id="qrBtn" onclick="startCam()" type="button">Activer la caméra</button>

            <p class="qr-note">Aucune donnée enregistrée · Accès local uniquement</p>

        </div>
    </div>
</div>


{{-- ────────────────────────────────────────────
     JAVASCRIPT
──────────────────────────────────────────── --}}
<script>

/* ── Scroll ────────────────────────────── */
function goTo(id) {
    var el = document.getElementById(id);
    if (el) window.scrollTo({ top: el.getBoundingClientRect().top + scrollY - 68, behavior:'smooth' });
}

/* ── Modal ─────────────────────────────── */
function openQr() {
    document.getElementById('qrOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeQr() {
    document.getElementById('qrOverlay').classList.remove('open');
    document.body.style.overflow = '';
    stopCam();
    resetUi();
}

function bgClose(e) {
    if (e.target === document.getElementById('qrOverlay')) closeQr();
}

document.addEventListener('keydown', function(e){ if (e.key==='Escape') closeQr(); });

/* ── Camera & jsQR ─────────────────────── */
var CAM     = null;
var RAF     = null;
var ACTIVE  = false;
var LAST    = null;

function startCam() {
    var btn = document.getElementById('qrBtn');
    btn.disabled = true;
    setStatus('wait', 'Demande d\'accès en cours…');

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        setStatus('err', 'Caméra non supportée');
        btn.disabled = false;
        return;
    }

    navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: { ideal: 'environment' },
            width: { ideal: 1280 },
            height: { ideal: 720 }
        },
        audio: false
    })
    .then(function(s) {
        CAM    = s;
        ACTIVE = true;

        var v = document.getElementById('qrVideo');
        v.srcObject = s;
        v.style.display = 'block';

        document.getElementById('qrIdle').style.display  = 'none';
        document.getElementById('qrFrame').style.display = 'block';
        document.getElementById('qrBtn').style.display   = 'none';

        setStatus('scanning', 'Caméra active — Cherche un QR code…');

        v.play().catch(function(){});
        v.addEventListener('loadedmetadata', function(){ RAF = requestAnimationFrame(tick); }, { once: true });
        /* Sécurité : lancer même si l'événement ne se déclenche pas */
        setTimeout(function(){ if (ACTIVE && !RAF) RAF = requestAnimationFrame(tick); }, 600);
    })
    .catch(function(err) {
        btn.disabled = false;
        var m = {
            'NotAllowedError' : 'Permission refusée — autorisez la caméra dans les paramètres',
            'NotFoundError'   : 'Aucune caméra détectée',
            'NotReadableError': 'Caméra occupée par une autre application'
        };
        setStatus('err', m[err.name] || 'Erreur caméra : ' + err.name);
    });
}

/* Boucle de lecture frame par frame */
function tick() {
    if (!ACTIVE) return;

    var v  = document.getElementById('qrVideo');
    var cv = document.getElementById('qrCanvas');

    if (v.readyState < 2) { RAF = requestAnimationFrame(tick); return; }

    cv.width  = v.videoWidth  || 640;
    cv.height = v.videoHeight || 480;

    var ctx = cv.getContext('2d');
    ctx.drawImage(v, 0, 0, cv.width, cv.height);

    if (typeof jsQR !== 'undefined') {
        try {
            var data = ctx.getImageData(0, 0, cv.width, cv.height);
            var code = jsQR(data.data, data.width, data.height, {
                inversionAttempts: 'dontInvert'
            });

            if (code && code.data && code.data !== LAST) {
                LAST = code.data;
                onDetected(code.data);
                return; // arrêter la boucle
            }
        } catch(e) { /* ignore */ }
    }

    RAF = requestAnimationFrame(tick);
}

/* Code détecté */
function onDetected(val) {
    ACTIVE = false;
    setStatus('ok', 'Code QR détecté !');

    document.getElementById('qrFrame').style.display = 'none';
    document.getElementById('qrRes').style.display   = 'block';
    document.getElementById('qrResVal').textContent  = val;

    /* URL → redirection automatique après 1.8 s */
    if (/^https?:\/\//i.test(val)) {
        setStatus('ok', 'Redirection dans 2 secondes…');
        setTimeout(function(){
            stopCam();
            window.location.href = val;
        }, 1800);
    } else {
        /* Autre contenu → proposer de rescanner */
        var btn = document.getElementById('qrBtn');
        btn.style.display = 'block';
        btn.disabled      = false;
        btn.textContent   = '↺  Scanner à nouveau';
        btn.onclick       = rescan;
    }
}

function rescan() {
    LAST   = null;
    ACTIVE = true;
    document.getElementById('qrRes').style.display   = 'none';
    document.getElementById('qrFrame').style.display = 'block';
    document.getElementById('qrBtn').style.display   = 'none';
    setStatus('scanning', 'Caméra active — Cherche un QR code…');
    RAF = requestAnimationFrame(tick);
}

function stopCam() {
    ACTIVE = false;
    if (RAF) { cancelAnimationFrame(RAF); RAF = null; }
    if (CAM) { CAM.getTracks().forEach(function(t){ t.stop(); }); CAM = null; }
    var v = document.getElementById('qrVideo');
    if (v) { v.srcObject = null; v.style.display = 'none'; }
}

function resetUi() {
    ACTIVE = false;
    LAST   = null;

    document.getElementById('qrIdle').style.display  = 'flex';
    document.getElementById('qrFrame').style.display = 'none';
    document.getElementById('qrRes').style.display   = 'none';

    var btn = document.getElementById('qrBtn');
    btn.style.display = 'block';
    btn.disabled      = false;
    btn.textContent   = 'Activer la caméra';
    btn.onclick       = startCam;

    setStatus('idle', 'En attente');
}

/* Helper status UI */
function setStatus(type, msg) {
    var dot  = document.getElementById('qrDot');
    var text = document.getElementById('qrMsg');
    dot.className  = 'qr-dot';
    text.textContent = msg;

    var col = {
        scanning : 'rgba(255,255,255,0.55)',
        ok       : '#c8a84b',
        err      : '#f87171',
        wait     : 'rgba(255,255,255,0.3)',
        idle     : 'rgba(255,255,255,0.25)'
    };

    text.style.color = col[type] || col.idle;

    if (type === 'scanning') dot.classList.add('scanning');
    if (type === 'ok')       dot.classList.add('ok');
    if (type === 'err')      dot.classList.add('err');
}

</script>

</body>
</html>
