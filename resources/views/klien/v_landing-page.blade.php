@extends('layout.v_template2')

@section('page')
<!-- Kalau mau kasih judul halaman bisa ditulis di sini -->
@endsection

@section('content')

<section class="categories">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="categories__item categories__large__item set-bg"
                    data-setbg="{{ asset('template2/img/categories/category-1.jpg') }}">
                    <div class="categories__text">
                        <h1>Venue</h1>
                        <p>Tempat terbaik untuk momen spesial Anda.</p>
                        <a href="#">Explore Options</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="{{ asset('template2/img/categories/category-2.jpg') }}">
                            <div class="categories__text">
                                <h4>Decorations</h4>
                                <p>Suasana magis sesuai impian Anda.</p>
                                <a href="#">Discover More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="{{ asset('template2/img/categories/category-3.jpg') }}">
                            <div class="categories__text">
                                <h4>Bridal Dresses</h4>
                                <p>Tampil menawan di hari istimewa.</p>
                                <a href="#">See Collection</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="{{ asset('template2/img/categories/category-4.jpg') }}">
                            <div class="categories__text">
                                <h4>Makeup Artists</h4>
                                <p>Sentuhan profesional untuk Anda.</p>
                                <a href="#">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="{{ asset('template2/img/categories/category-5.jpg') }}">
                            <div class="categories__text">
                                <h4>Photography</h4>
                                <p>Abadikan detik penuh bahagia.</p>
                                <a href="#">View Portofolio</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>
<!-- Categories Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="section-title">
                    <h4>Our Recommendation</h4>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <ul class="filter__controls">
                    <li class="active" data-filter="*">All</li>
                    <li data-filter=".venue">Venue</li>
                    <li data-filter=".decorations">Decorations</li>
                    <li data-filter=".bridal">Bridal Dresses</li>
                    <li data-filter=".makeup">Makeup Artists</li>
                    <li data-filter=".photography">Photography</li>
                </ul>
            </div>
        </div>
        <div class="row product__filter">
            @foreach($packages as $package)
            <div class="col-lg-4 col-md-6 col-sm-6 mix {{ strtolower(str_replace(' ', '', $package->type)) }}">
                <div class="product__item">
                    <div class="product__item__pic set-bg" style="background-image: url('{{ asset('storage/' . $package->foto) }}');">
                    </div>
                    <div class="product__item__text">
                        <h6>{{ $package->nama }}</h6>
                        <p>{{ $package->deskripsi }}</p>
                        <h5>Rp {{ number_format($package->harga_total, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- Product Section End -->

<!-- Banner Section Begin -->
<section class="banner set-bg" data-setbg="{{ asset('template2/img/banner/banner-1.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8 m-auto">
                <div class="banner__slider owl-carousel">
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>The Chloe Collection</span>
                            <h1>The Project Jacket</h1>
                            <a href="#">Shop now</a>
                        </div>
                    </div>
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>The Chloe Collection</span>
                            <h1>The Project Jacket</h1>
                            <a href="#">Shop now</a>
                        </div>
                    </div>
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>Wedding Organizer</span>
                            <h1>All Package</h1>
                            <a href="#">Shop now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->

<section>
    <div>
        <h3>isi konten</h3>
    </div>
</section>

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Wedding Planner</h6>
                    <p>Professional and personalized planning services</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Flexible Packages</h6>
                    <p>Tailored to match your style and budget</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Support 24/7</h6>
                    <p>Always available for your big day needs</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <h6>Trusted Services</h6>
                    <p>Over 100+ happy couples</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Footer Section Begin -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-7">
                <div class="footer__about">
                    <div class="footer__logo">
                        <a href="./index.html"><img src="{{ asset('template2/img/logo.png') }}" alt=""></a>
                    </div>
                    <p>We create unforgettable wedding moments with elegance and charm. Let us help you plan your dream day.</p>
                    <div class="footer__payment">
                        <a href="#"><img src="{{ asset('template2/img/payment/payment-1.png') }}" alt=""></a>
                        <a href="#"><img src="{{ asset('template2/img/payment/payment-2.png') }}" alt=""></a>
                        <a href="#"><img src="{{ asset('template2/img/payment/payment-3.png') }}" alt=""></a>
                        <a href="#"><img src="{{ asset('template2/img/payment/payment-4.png') }}" alt=""></a>
                        <a href="#"><img src="{{ asset('template2/img/payment/payment-5.png') }}" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-5">
                <div class="footer__widget">
                    <h6>Quick links</h6>
                    <ul>
                        <li><a href="#">Our Services</a></li>
                        <li><a href="#">Gallery</a></li>
                        <li><a href="#">Testimonilas</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4">
                <div class="footer__widget">
                    <h6>Account</h6>
                    <ul>
                        <li><a href="#">Login</a></li>
                        <li><a href="#">My Bookings</a></li>
                        <li><a href="#">Schedule Meeting</a></li>
                        <li><a href="#">Payment Methods</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-8">
                <div class="footer__newslatter">
                    <h6>Newsletter</h6>
                    <form action="#">
                        <input type="text" placeholder="Your email">
                        <button type="submit" class="site-btn">Subscribe</button>
                    </form>
                    <div class="footer__social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__copyright__text">
            <p>Copyright &copy; 2025 Wedding Planner | All rights reserved.</p>
        </div>
    </div>
</footer>
<!-- Footer Section End -->

@endsection
