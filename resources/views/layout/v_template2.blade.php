<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ashion Template">
    <meta name="keywords" content="Ashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title_page')</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('template2')}}/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="{{asset('template2')}}/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="{{asset('template2')}}/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="{{asset('template2')}}/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="{{asset('template2')}}/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="{{asset('template2')}}/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="{{asset('template2')}}/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="{{asset('template2')}}/css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
            <li><a href="#"><span class="icon_heart_alt"></span>
                <div class="tip">2</div>
            </a></li>
            <li><a href="#"><span class="icon_bag_alt"></span>
                <div class="tip">2</div>
            </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="{{asset('template2')}}/index.html"><img src="{{asset('template2')}}/img/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
          <a href="/login">Login</a>
            <a href="/register">Register</a>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('layout.v_nav2')
    <!-- Header Section End -->
    @yield('content')
    <!-- Categories Section Begin -->

<!-- Search End -->

<!-- Js Plugins -->
<script src="{{asset('template2')}}/js/jquery-3.3.1.min.js"></script>
<script src="{{asset('template2')}}/js/bootstrap.min.js"></script>
<script src="{{asset('template2')}}/js/jquery.magnific-popup.min.js"></script>
<script src="{{asset('template2')}}/js/jquery-ui.min.js"></script>
<script src="{{asset('template2')}}/js/mixitup.min.js"></script>
<script src="{{asset('template2')}}/js/jquery.countdown.min.js"></script>
<script src="{{asset('template2')}}/js/jquery.slicknav.js"></script>
<script src="{{asset('template2')}}/js/owl.carousel.min.js"></script>
<script src="{{asset('template2')}}/js/jquery.nicescroll.min.js"></script>
<script src="{{asset('template2')}}/js/main.js"></script>
</body>

</html>
