<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Galeri Klien</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  
  <!-- AOS CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  
  <!-- Lightbox CSS -->
  <link href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f8f8;
    }

    .galeri-klien-container {
      max-width: 1200px;
      margin: auto;
      padding: 40px 20px;
    }

    .galeri-klien-title {
      text-align: center;
      font-size: 2.5em;
      margin-bottom: 10px;
    }

    .galeri-klien-subtitle {
      text-align: center;
      font-size: 1.1em;
      color: #666;
      margin-bottom: 40px;
    }

    .galeri-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .galeri-grid img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .galeri-grid a:hover img {
      transform: scale(1.05);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
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
  <div class="galeri-klien-container">
    <h1 class="galeri-klien-title">Galeri Klien</h1>
    <p class="galeri-klien-subtitle">Dokumentasi dari proyek dan layanan yang telah kami selesaikan</p>

    <div class="galeri-grid">
      <a href="images/foto1.jpeg" data-lightbox="galeri" data-title="Proyek A - Momen Akad" data-aos="zoom-in">
        <img src="images/foto1.jpeg" alt="Proyek A">
      </a>
      <a href="images/foto2.jpeg" data-lightbox="galeri" data-title="Proyek B - Persiapan Staff WO" data-aos="zoom-in">
        <img src="images/foto2.jpeg" alt="Proyek B">
      </a>
      <a href="images/foto3.jpeg" data-lightbox="galeri" data-title="Proyek C - Dekorasi Pelaminan" data-aos="zoom-in">
        <img src="images/foto3.jpeg" alt="Proyek C">
      </a>
      <a href="images/foto4.jpeg" data-lightbox="galeri" data-title="Proyek D - Resepsi & Foto Bersama" data-aos="zoom-in">
        <img src="images/foto4.jpeg" alt="Proyek D">
      </a>
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

  <!-- Lightbox JS -->
  <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/js/lightbox.min.js"></script>
</body>
</html>
