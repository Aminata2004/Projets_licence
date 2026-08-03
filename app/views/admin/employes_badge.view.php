<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Badge - <?= htmlspecialchars($employe['nom']) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    /* Styling for the page and preview */
    body {
      margin: 0;
      padding: 0;
      background-color: #f1f5f9;
      font-family: 'Inter', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }
    
    .no-print {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      font-family: 'Outfit', sans-serif;
      font-size: 14px;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
      transition: all 0.2s ease;
    }
    
    .btn-print {
      background-color: #10b981;
      color: #ffffff;
    }
    
    .btn-print:hover {
      background-color: #059669;
      transform: translateY(-1px);
    }
    
    .btn-close {
      background-color: #64748b;
      color: #ffffff;
    }
    
    .btn-close:hover {
      background-color: #475569;
      transform: translateY(-1px);
    }

    /* Badge container */
    .badge-card {
      width: 750px;
      height: 450px;
      background-color: #ffffff;
      border-radius: 24px;
      box-shadow: 0 20px 40px rgba(0, 32, 96, 0.12);
      position: relative;
      overflow: hidden;
      display: flex;
      box-sizing: border-box;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    /* Background SVG styling */
    .badge-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
      pointer-events: none;
    }

    /* Sidebar Content */
    .sidebar {
      position: absolute;
      top: 0;
      left: 0;
      width: 210px;
      height: 100%;
      z-index: 10;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      padding: 25px 15px 35px 15px;
      box-sizing: border-box;
      color: #ffffff;
      text-align: center;
    }

    .sidebar-top {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .sidebar-logo-text {
      font-family: 'Outfit', sans-serif;
      font-weight: 900;
      font-size: 18px;
      color: #ffffff;
      text-shadow: 1px 1px 0 #d1001c, -1px 1px 0 #d1001c, 1px -1px 0 #d1001c, -1px -1px 0 #d1001c, 0 2px 4px rgba(0,0,0,0.3);
      margin-top: 5px;
      letter-spacing: -0.5px;
    }

    .sidebar-middle {
      margin: 10px 0;
    }

    .sidebar-bottom {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    .sidebar-brand {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .brand-ann {
      font-family: 'Outfit', sans-serif;
      font-weight: 900;
      font-size: 26px;
      color: #ffffff;
      line-height: 1.1;
      letter-spacing: 1px;
      margin: 0;
    }

    .brand-express-pill {
      background-color: #d1001c;
      color: #ffffff;
      font-family: 'Outfit', sans-serif;
      font-size: 13px;
      font-weight: 900;
      letter-spacing: 2px;
      padding: 4px 18px;
      border-radius: 6px;
      margin-top: 5px;
      text-transform: uppercase;
      box-shadow: 0 2px 4px rgba(0,0,0,0.15);
      display: inline-block;
    }

    .yellow-divider {
      width: 35px;
      height: 4px;
      background-color: #ffaa00;
      margin: 15px auto;
      border-radius: 2px;
    }

    .slogan-text {
      font-family: 'Outfit', sans-serif;
      font-size: 9px;
      font-weight: 700;
      letter-spacing: 1.5px;
      color: #ffffff;
      opacity: 0.95;
      margin: 0;
      text-transform: uppercase;
    }

    /* Main Content Area */
    .main-content {
      position: absolute;
      top: 0;
      right: 0;
      width: 500px;
      height: 410px;
      z-index: 10;
      padding: 25px 35px 25px 25px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    .profile-container {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 3px solid #001e42;
      background-color: #ffffff;
      box-shadow: 0 0 0 3px #d1001c;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      box-sizing: border-box;
    }

    .profile-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-placeholder-icon {
      width: 60px;
      height: 60px;
      fill: #cbd5e1;
    }

    .middle-info {
      margin-top: -10px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .employee-name {
      font-family: 'Outfit', sans-serif;
      font-size: 26px;
      font-weight: 900;
      color: #001e42;
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .employee-role-badge {
      background-color: #d1001c;
      color: #ffffff;
      font-family: 'Outfit', sans-serif;
      padding: 4px 14px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      display: inline-block;
      margin-top: 6px;
      letter-spacing: 1px;
    }

    /* Grid layout */
    .details-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      grid-template-rows: auto auto;
      grid-row-gap: 20px;
      grid-column-gap: 40px;
      position: relative;
      margin-top: 15px;
    }

    /* Red cross dividers */
    .grid-divider-h {
      position: absolute;
      left: 0;
      right: 0;
      top: 50%;
      height: 1.5px;
      background-color: #d1001c;
      opacity: 0.35;
      transform: translateY(-50%);
    }

    .grid-divider-v {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 50%;
      width: 1.5px;
      background-color: #d1001c;
      opacity: 0.35;
      transform: translateX(-50%);
    }

    .grid-item {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .icon-wrapper {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: #d1001c;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      box-shadow: 0 2px 4px rgba(209, 0, 28, 0.2);
    }

    .icon-wrapper svg {
      width: 16px;
      height: 16px;
      fill: #ffffff;
    }

    .item-text {
      display: flex;
      flex-direction: column;
    }

    .info-label {
      font-size: 8px;
      font-weight: 700;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 2px;
    }

    .info-val {
      font-size: 13px;
      font-weight: 700;
      color: #0f172a;
    }

    /* Print styles */
    @media print {
      body {
        background-color: #ffffff;
        min-height: auto;
        padding: 0;
        margin: 0;
      }
      .no-print {
        display: none !important;
      }
      .badge-card {
        box-shadow: none;
        border: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 750px;
        height: 450px;
        page-break-inside: avoid;
      }
      @page {
        size: landscape;
        margin: 0;
      }
    }
  </style>
</head>
<body>

  <div class="no-print">
    <button class="btn btn-print" onclick="window.print()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
        <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
      </svg>
      Imprimer le badge
    </button>
    <button class="btn btn-close" onclick="window.close()">
      Fermer
    </button>
  </div>

  <div class="badge-card">
    <!-- Background Design Elements SVG -->
    <svg class="badge-bg" viewBox="0 0 750 450" fill="none" xmlns="http://www.w3.org/2000/svg">
      <!-- 1. Left Sidebar Curves (Red -> White -> Blue) sweeping to the bottom edge and right corner -->
      <!-- Red curve background layer -->
      <path d="M 0,0 L 245,0 C 200,80 265,180 265,225 C 265,270 195,330 185,370 C 175,410 220,418 250,418 L 605,418 C 675,418 730,395 750,330 L 750,450 L 0,450 Z" fill="#d1001c" />
      <!-- White spacer curve layer -->
      <path d="M 0,0 L 233,0 C 188,80 253,180 253,225 C 253,270 183,330 173,370 C 163,410 208,427 238,427 L 617,427 C 685,427 736,403 750,342 L 750,450 L 0,450 Z" fill="#ffffff" />
      <!-- Dark blue main layer -->
      <path d="M 0,0 L 225,0 C 180,80 245,180 245,225 C 245,270 175,330 165,370 C 155,410 200,435 230,435 L 627,435 C 693,435 741,410 750,352 L 750,450 L 0,450 Z" fill="#001e42" />

      <!-- 2. Bottom Right Faint Watermark (Crescent & Star) -->
      <g opacity="0.04">
        <!-- Crescent -->
        <path d="M 610,240 A 55,55 0 1,0 660,335 A 48,48 0 1,1 610,240 Z" fill="#001e42" />
        <!-- Star -->
        <polygon points="670,260 673,267 680,268 675,273 676,280 670,276 664,280 665,273 660,268 667,267" fill="#001e42" />
      </g>
    </svg>

    <!-- Sidebar HTML Content -->
    <div class="sidebar">
      <div class="sidebar-top">
        <!-- Sun/Crescent white/red gear logos -->
        <svg width="130" height="60" viewBox="0 0 130 60" fill="none">
          <!-- Left blue circle/gear -->
          <circle cx="45" cy="20" r="14" stroke="#ffffff" stroke-width="2" stroke-dasharray="4,2"/>
          <circle cx="45" cy="20" r="11" fill="#001e42"/>
          <path d="M 42,15 A 5,5 0 1,0 47,23.5 A 4,4 0 1,1 42,15 Z" fill="#ffffff" />
          <polygon points="48,16 49,18 51,18 49,19 50,21 48,20 46,21 47,19 45,18 47,18" fill="#ffffff" />
          
          <!-- Right red circle/gear -->
          <circle cx="85" cy="22" r="14" stroke="#d1001c" stroke-width="2" stroke-dasharray="4,2"/>
          <circle cx="85" cy="22" r="11" fill="#d1001c"/>
          <path d="M 82,17 A 5,5 0 1,0 87,25.5 A 4,4 0 1,1 82,17 Z" fill="#ffffff" />
          <polygon points="88,18 89,20 91,20 89,21 90,23 88,22 86,23 87,21 85,20 87,20" fill="#ffffff" />
        </svg>
        <span class="sidebar-logo-text">Ann express</span>
      </div>

      <div class="sidebar-middle">
        <!-- SVG Bus Graphic (High-Fidelity Perspective) -->
        <svg width="150" height="90" viewBox="0 0 150 90" fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- Drop Shadow -->
          <ellipse cx="75" cy="81" rx="68" ry="5" fill="#000000" opacity="0.3" />
          
          <!-- Wheels -->
          <circle cx="34" cy="74" r="9" fill="#1e293b" />
          <circle cx="34" cy="74" r="5" fill="#cbd5e1" />
          <circle cx="34" cy="74" r="2" fill="#475569" />
          
          <!-- Wheels -->
          <circle cx="112" cy="74" r="9" fill="#1e293b" />
          <circle cx="112" cy="74" r="5" fill="#cbd5e1" />
          <circle cx="112" cy="74" r="2" fill="#475569" />
          
          <!-- Main Body Underlayer (White) -->
          <path d="M 12,38 C 12,32 18,30 24,30 L 126,30 C 130,30 134,32 135,36 L 138,55 L 138,71 C 138,73 136,75 134,75 L 14,75 C 12,75 11,73 11,71 L 11,55 Z" fill="#ffffff" />
          
          <!-- Main Body Blue Upper Layer -->
          <path d="M 28,30 L 126,30 C 130,30 134,32 135,36 L 138,55 L 138,74 L 28,74 Z" fill="#001e42" />
          
          <!-- Red Sweeping Ribbon on Side -->
          <path d="M 28,60 C 50,60 70,50 90,42 C 110,34 135,34 135,34 L 137,42 C 137,42 110,42 90,50 C 70,58 50,68 28,68 Z" fill="#d1001c" />
          <path d="M 28,68 C 50,68 70,61 90,53 C 110,45 135,45 135,45 L 136,51 C 136,51 110,51 90,59 C 70,67 50,75 28,75 Z" fill="#d1001c" />
          
          <!-- Front Cab (Windshield, Grille, Headlights) in perspective -->
          <path d="M 11,55 L 28,55 L 28,30 L 24,30 C 18,30 13,32 11,36 Z" fill="#ffffff" stroke="#cbd5e1" stroke-width="0.5" />
          <!-- Front Windshield -->
          <path d="M 12,50 L 27,47 L 27,32 L 22,32 C 18,32 14,34 12,38 Z" fill="#1e293b" />
          <!-- Windshield reflection highlight -->
          <path d="M 14,37 L 20,34 L 18,48 L 13,49 Z" fill="#ffffff" opacity="0.15" />
          
          <!-- Side Windows -->
          <path d="M 32,32 L 48,32 L 48,48 L 32,48 Z" fill="#0f172a" />
          <path d="M 52,32 L 68,32 L 68,48 L 52,48 Z" fill="#0f172a" />
          <path d="M 72,32 L 88,32 L 88,48 L 72,48 Z" fill="#0f172a" />
          <path d="M 92,32 L 108,32 L 108,48 L 92,48 Z" fill="#0f172a" />
          <path d="M 112,32 L 128,32 L 128,48 L 112,48 Z" fill="#0f172a" />
          <!-- Window reflection highlights -->
          <path d="M 34,32 L 40,32 L 36,48 L 32,48 Z M 54,32 L 60,32 L 56,48 L 52,48 Z M 74,32 L 80,32 L 76,48 L 72,48 Z M 94,32 L 100,32 L 96,48 L 92,48 Z M 114,32 L 120,32 L 116,48 L 112,48 Z" fill="#ffffff" opacity="0.1" />
          
          <!-- Front Grille & Headlights -->
          <rect x="11" y="60" width="16" height="6" rx="2" fill="#475569" />
          <line x1="13" y1="62" x2="25" y2="62" stroke="#94a3b8" stroke-width="0.8" />
          <line x1="13" y1="64" x2="25" y2="64" stroke="#94a3b8" stroke-width="0.8" />
          
          <!-- Headlights -->
          <circle cx="13" cy="58" r="2.5" fill="#fef08a" />
          <circle cx="13" cy="58" r="1.5" fill="#ffffff" />
          <circle cx="25" cy="58" r="2.5" fill="#fef08a" />
          <circle cx="25" cy="58" r="1.5" fill="#ffffff" />
          
          <!-- Front bumper -->
          <path d="M 10,68 C 10,68 11,74 15,74 L 28,74 L 28,68 Z" fill="#94a3b8" />
        </svg>
      </div>

      <div class="sidebar-bottom">
        <div class="sidebar-brand">
          <h3 class="brand-ann">ANN</h3>
          <span class="brand-express-pill">Express</span>
        </div>
        <div class="yellow-divider"></div>
        <p class="slogan-text">Voyagez en toute confiance</p>
      </div>
    </div>

    <!-- Main Content HTML Area -->
    <div class="main-content">
      <div class="header-row">
        <!-- Logo Ann express Yelimane center top -->
        <svg width="250" height="70" viewBox="0 0 250 70" fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- 1. Scorpion Silhouette (Left) -->
          <path d="M 12,25 C 10,20 18,15 22,12 C 24,15 20,20 18,22 C 22,20 28,18 30,22 C 28,24 22,24 18,24 C 22,25 26,28 24,32 C 22,30 18,28 15,27" stroke="#1e293b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M 15,27 C 12,32 10,38 12,43 C 14,48 20,48 22,43 C 20,40 18,35 17,32" stroke="#1e293b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
          <circle cx="22" cy="12" r="1.2" fill="#1e293b" />
          
          <!-- 2. The Two Sun/Gear Logos (Center-Top, above Ann Express text) -->
          <!-- Left White/Blue Sun/Gear -->
          <circle cx="102" cy="14" r="8" stroke="#001e42" stroke-width="1" stroke-dasharray="2,1"/>
          <circle cx="102" cy="14" r="6" fill="#001e42"/>
          <path d="M 100.5,11 A 3,3 0 1,0 103.5,16 A 2.4,2.4 0 1,1 100.5,11 Z" fill="#ffffff" />
          
          <!-- Right White/Red Sun/Gear -->
          <circle cx="120" cy="15" r="8" stroke="#d1001c" stroke-width="1" stroke-dasharray="2,1"/>
          <circle cx="120" cy="15" r="6" fill="#d1001c"/>
          <path d="M 118.5,12 A 3,3 0 1,0 121.5,17 A 2.4,2.4 0 1,1 118.5,12 Z" fill="#ffffff" />
          
          <!-- 3. Text "Ann express" (Center, below gears) -->
          <text x="111" y="32" font-family="'Outfit', sans-serif" font-weight="900" font-size="15" fill="#d1001c" text-anchor="middle">Ann express</text>
          
          <!-- 4. Text "Yelimane" (Center, below Ann Express) -->
          <text x="114" y="49" font-family="'Georgia', serif" font-style="italic" font-weight="bold" font-size="18" fill="#001e42" text-anchor="middle">Yelimane</text>
          
          <!-- 5. Red Bust Silhouette (Right) -->
          <path d="M 158,18 C 158,13 162,11 165,11 C 168,11 170,13 170,16 C 170,19 167,21 165,23 C 167,25 169,27 170,30 L 158,30 C 159,27 161,25 162,23 C 160,21 158,19 158,18 Z" fill="#d1001c" />
          
          <!-- 6. Crescent Moon & House Silhouette (Far Right) -->
          <path d="M 185,17 A 11,11 0 1,0 196,32 A 9,9 0 1,1 185,17 Z" fill="#001e42" />
          <polygon points="186,24 188.5,21 191,24 191,27 186,27" fill="#001e42" />
          
          <!-- 7. Slogan (Bottom, centered) -->
          <text x="111" y="60" font-family="'Outfit', sans-serif" font-size="5.5" font-weight="900" fill="#d1001c" text-anchor="middle" letter-spacing="1.2">• VOYAGEZ EN TOUTE CONFIANCE •</text>
        </svg>

        <!-- Circular Photo with Red/Blue Border -->
        <div class="profile-container">
          <img src="<?= BASE_URL ?>/public/assets/images/default_agent_profile.png" alt="Photo de profil" onerror="this.style.display='none'; document.getElementById('profile-svg').style.display='block';">
          <!-- Fallback icon in case image loads fails -->
          <svg id="profile-svg" style="display:none;" class="profile-placeholder-icon" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
        </div>
      </div>

      <!-- Middle Info (Name and capsule) -->
      <div class="middle-info">
        <h2 class="employee-name"><?= htmlspecialchars($employe['nom']) ?></h2>
        <?php
          $roleDisplay = $employe['role'] ?? $employe['type'];
          $roleLabels = [
              'super_admin'   => 'Super admin',
              'Admin'         => 'Administrateur',
              'PDG'           => 'PDG',
              'chef_d_escale' => "Chef d'escale",
              'Utilisateur'   => 'Utilisateur',
              'Chauffeur'     => 'Chauffeur',
          ];
          $badgeText = $roleLabels[$roleDisplay] ?? $roleDisplay;
        ?>
        <span class="employee-role-badge"><?= htmlspecialchars(strtoupper($badgeText)) ?></span>
      </div>

      <!-- Details Grid -->
      <div class="details-grid">
        <!-- Dividers forming the red cross -->
        <div class="grid-divider-h"></div>
        <div class="grid-divider-v"></div>

        <!-- Type de compte -->
        <div class="grid-item">
          <div class="icon-wrapper">
            <svg viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </div>
          <div class="item-text">
            <span class="info-label">Type de compte</span>
            <span class="info-val"><?= htmlspecialchars($employe['type']) ?></span>
          </div>
        </div>

        <!-- Téléphone -->
        <div class="grid-item">
          <div class="icon-wrapper">
            <svg viewBox="0 0 24 24">
              <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
          </div>
          <div class="item-text">
            <span class="info-label">Téléphone</span>
            <span class="info-val"><?= htmlspecialchars($employe['telephone']) ?></span>
          </div>
        </div>

        <!-- Email -->
        <div class="grid-item">
          <div class="icon-wrapper">
            <svg viewBox="0 0 24 24">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
          </div>
          <div class="item-text">
            <span class="info-label">Email</span>
            <span class="info-val"><?= htmlspecialchars($employe['contact']) ?></span>
          </div>
        </div>

        <!-- Affectation -->
        <div class="grid-item">
          <div class="icon-wrapper">
            <svg viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
          </div>
          <div class="item-text">
            <span class="info-label">Affectation</span>
            <span class="info-val"><?= htmlspecialchars($employe['affectation']) ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Trigger print layout automatically when loaded
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        window.print();
      }, 600);
    });
  </script>
</body>
</html>
