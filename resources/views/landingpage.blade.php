<!DOCTYPE HTML>
<html lang="id">
<head>
  <title>Infinity Wedding</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Gaya Utama -->
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">

  <!-- Slick Carousel -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

  <!-- AOS Animate on Scroll -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

  <style>
    .slider .slick-slide > div {
        display: flex;
        justify-content: center;
    }
    .package-card {
        width: 220px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        padding: 16px 12px 18px 12px;
        margin: 0 auto;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .package-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .package-card h3 {
        font-size: 1.05rem;
        margin: 0.5rem 0 0.2rem 0;
        font-weight: bold;
        color: #222;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .package-card p {
        font-size: 0.92rem;
        min-height: 36px;
        color: #555;
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .package-card h4 {
        color: #2ecc71;
        font-size: 1.08rem;
        margin: 0.5rem 0 0 0;
        font-weight: bold;
    }
    .testimoni-slider .slick-slide > div {
      display: flex;
      justify-content: center;
    }
    .testimoni-card {
      width: 320px;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      padding: 22px 18px 18px 18px;
      margin: 0 auto;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .testimoni-card h3 {
      font-size: 1.08rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
      color: #111;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .testimoni-card p {
      font-size: 1.01rem;
      color: #444;
      margin-bottom: 0;
      min-height: 48px;
      line-height: 1.4;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header id="header">
    <a href="{{ route('landing') }}" class="logo">Infinity Wedding</a>
    <nav class="right">
      <a href="{{ route('login') }}" class="button alt">Log in</a>
      <a href="{{ route('register') }}" class="button">Register</a>
    </nav>
    <nav class="left">
      <a href="{{ route('galeri') }}" class="button alt">Galeri</a>
      <a href="{{ route('testimoni') }}" class="button alt">Testimoni</a>
    </nav>
  </header>

  <!-- Banner -->
  <section id="banner">
    <div class="content">
      <h1 class="fade-in">Wujudkan Impian Anda</h1>
      <p class="fade-in">"Dreams will be come true"</p>
      <ul class="actions">
        <li><a href="#one" class="button scrolly fade-in">Get Started</a></li>
      </ul>
    </div>
  </section>

  <!-- One -->
  <section id="one" class="wrapper">
    <div class="inner flex flex-3">
      <div class="flex-item left">
        <div data-aos="fade-up">
          <h3>Makna Pernikahan</h3>
          <p>Pernikahan bukan sekadar ikatan legal, tetapi juga komitmen emosional dan spiritual antara dua insan.</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="100">
          <h3>Komitmen Seumur Hidup</h3>
          <p>Melalui suka dan duka, pasangan saling mendukung dan menjaga janji setia yang diikrarkan.</p>
        </div>
      </div>
      <div class="flex-item image fit round" data-aos="fade-up" data-aos-delay="200">
        <img src="{{ asset('images/foto1.jpeg') }}" alt="">
      </div>
      <div class="flex-item right">
        <div data-aos="fade-up">
          <h3>Kebersamaan dan Pertumbuhan</h3>
          <p>Pernikahan adalah ruang untuk tumbuh bersama, saling belajar, dan membangun masa depan yang lebih baik.</p>
        </div>
        <div data-aos="fade-up" data-aos-delay="100">
          <h3>Landasan Keluarga Bahagia</h3>
          <p>Dengan cinta, kepercayaan, dan komunikasi yang baik, pernikahan menjadi fondasi keluarga yang harmonis dan penuh makna.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Two -->
  <section id="two" class="wrapper style1 special">
    <div class="inner">
      <h2 data-aos="fade-up">Quote</h2>
      <figure data-aos="fade-up">
        <blockquote>
          "Pernikahan bukan tentang menemukan seseorang untuk hidup bersama,<br>
          tetapi tentang menemukan seseorang yang tak bisa kamu hidup tanpanya."
        </blockquote>
      </figure>
    </div>
  </section>

  <!-- Produk -->
  <section id="produk" class="wrapper">
    <div class="inner">
      <h1 class="headproduk">Paket Pernikahan Kami</h1>
      <div class="lineproduk"></div>
      <div class="slider">
        @foreach($packages as $package)
        <div>
          <div class="package-card" data-aos="fade-up">
            @php
              $firstPhoto = $package->photos->first();
              $photoPath = $firstPhoto ? asset('images/foto_paket/' . $firstPhoto->filename) : asset('images/default.jpg');
            @endphp
            <img src="{{ $photoPath }}" alt="{{ $package->nama }}">

            <h3 title="{{ $package->nama }}">{{ $package->nama }}</h3>
            <p title="{{ $package->deskripsi }}">{{ Str::limit($package->deskripsi, 40) }}</p>
            <h4>Rp {{ number_format($package->harga_total, 0, ',', '.') }}</h4>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Testimoni -->
  <section id="testimoni" class="wrapper">
    <div class="inner">
      <h1 class="headproduk">Testimoni</h1>
      <div class="lineproduk"></div>
      <div class="testimoni-slider">
        <div>
          <div class="testimoni-card" data-aos="fade-up">
            <h3>@alwi</h3>
            <p>Pernikahan saya jadi salah satu moment paling sempurna di hidup saya</p>
          </div>
        </div>
        <div>
          <div class="testimoni-card" data-aos="fade-up">
            <h3>@mikal</h3>
            <p>Pernikahan saya berjalan dengan sangat lancar.</p>
          </div>
        </div>
        <div>
          <div class="testimoni-card" data-aos="fade-up">
            <h3>@cherry</h3>
            <p>Pernikahan saya sangat meriah dan megah, saya sangat suka</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="footer">
    <div class="inner">
      <h2>Hubungi Kami</h2>
      <ul class="actions">
        <li><span class="icon fa-phone"></span> <a href="#">(087123094141)</a></li>
        <li><span class="icon fa-envelope"></span> <a href="#">infinitywedding@gmail.com</a></li>
        <li><span class="icon fa-map-marker"></span> Jl. Soekarno Hatta, NO. 9, Bandung </li>
      </ul>
    </div>
  </footer>

  <div class="copyright">
    Powered by: <a>Kelompok 3</a>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/jquery.scrolly.min.js') }}"></script>
  <script src="{{ asset('js/skel.min.js') }}"></script>
  <script src="{{ asset('js/util.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>

  <!-- Eksternal Script -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <script>
    AOS.init({ duration: 1000, once: true });
    $(document).ready(function(){
      $('.slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        arrows: true,
        dots: true,
        responsive: [
          { breakpoint: 992, settings: { slidesToShow: 2 } },
          { breakpoint: 600, settings: { slidesToShow: 1 } }
        ]
      });
      $('.testimoni-slider').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3500,
        arrows: false,
        dots: true,
        responsive: [
          { breakpoint: 900, settings: { slidesToShow: 1 } }
        ]
      });
    });
  </script>

</body>
</html>
