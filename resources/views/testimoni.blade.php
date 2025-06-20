<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Galeri Testimoni</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <!-- AOS CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f2f5;
    }

    .galeri-container {
      max-width: 1200px;
      margin: auto;
      padding: 40px 20px;
    }

    .galeri-title {
      text-align: center;
      font-size: 2.5em;
      margin-bottom: 10px;
    }

    .galeri-subtitle {
      text-align: center;
      font-size: 1.2em;
      color: #666;
      margin-bottom: 40px;
    }

    .galeri-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .card {
      background: #fff;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
    }

    .card h3 {
      margin: 0;
      font-size: 1.2em;
    }

    .card small {
      color: #888;
      display: block;
      margin-bottom: 10px;
    }

    .card p {
      font-style: italic;
      color: #444;
    }

    .back-home {
      display: block;
      text-align: center;
      margin-top: 50px;
      font-size: 1em;
      color: #007BFF;
      text-decoration: none;
    }

    .back-home:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="galeri-container">
    <h1 class="galeri-title">Galeri Testimoni</h1>
    <p class="galeri-subtitle">Apa kata klien kami tentang layanan kami?</p>

    <div class="galeri-grid">
      <div class="card" data-aos="fade-up">
        <img src="images/foto1.jpeg" alt="Andi Pratama">
        <h3>Andi Pratama</h3>
        <small>CEO Startup XYZ</small>
        <p>“Pelayanan sangat profesional dan cepat! Sangat merekomendasikan kepada semua orang.”</p>
      </div>
      <div class="card" data-aos="zoom-in">
        <img src="images/foto2.jpeg" alt="Sinta Wijaya">
        <h3>Sinta Wijaya</h3>
        <small>Marketing Expert</small>
        <p>“Tim sangat responsif dan hasil akhirnya memuaskan.”</p>
      </div>
      <div class="card" data-aos="fade-right">
        <img src="images/foto3.jpeg" alt="Budi Hartono">
        <h3>Budi Hartono</h3>
        <small>Founder BudiTech</small>
        <p>“Web yang dibuat sangat bagus dan sesuai dengan ekspektasi saya.”</p>
      </div>
      <div class="card" data-aos="flip-left">
        <img src="images/foto4.jpeg" alt="Lina Marlina">
        <h3>Lina Marlina</h3>
        <small>UI/UX Designer</small>
        <p>“Designnya clean, loading cepat, dan mudah dinavigasi.”</p>
      </div>
    </div>

    <a href="{{ route('landing') }}" class="back-home">← Kembali ke Beranda</a>
  </div>

  <!-- AOS JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>
</body>
</html>
