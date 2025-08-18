<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Choisissez une compagnie</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body, html {
      height: 100%;
      margin: 0;
      background-color: #f8f9fa;
    }
    .alert-page {
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 20px;
      background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1470&q=80') no-repeat center center/cover;
      color: #fff;
      position: relative;
    }
    .alert-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(0,0,0,0.6);
      z-index: 1;
    }
    .alert-content {
      position: relative;
      z-index: 2;
      max-width: 600px;
      background: rgba(255, 255, 255, 0.1);
      padding: 40px 30px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }
    .alert-content h1 {
      font-weight: 700;
      font-size: 2.5rem;
      margin-bottom: 20px;
      text-shadow: 1px 1px 6px rgba(0,0,0,0.7);
    }
    .alert-content p {
      font-size: 1.2rem;
      margin-bottom: 30px;
      text-shadow: 1px 1px 6px rgba(0,0,0,0.6);
    }
    .btn-choose {
      font-size: 1.2rem;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      box-shadow: 0 6px 15px rgba(0,123,255,0.5);
    }
    @media (max-width: 576px) {
      .alert-content {
        padding: 30px 20px;
      }
      .alert-content h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <section class="alert-page">
    <div class="alert-overlay"></div>
    <div class="alert-content">
      <h1>🚍 Veuillez choisir une compagnie</h1>
      <p>Pour voir les programmes de voyage disponibles, sélectionnez d'abord une compagnie.</p>
      <a href="choisir_compagnie.php" class="btn btn-primary btn-choose">Choisir une compagnie</a>
    </div>
  </section>
</body>
</html>
