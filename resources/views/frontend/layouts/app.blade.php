<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no"> -->
 	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> {{ $title ?? 'Home' }}</title>
    <meta name="language" content="English">
    <meta name="author" content="{{ config('app.name') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App URL -->
    <meta name="app-url" content="{{ url('/') }}">
    <meta name="robots" content="follow, index, max-snippet:-1, max-image-preview:large"/>
    <!--<link rel="canonical" href="{{ url('/') }}" />-->
    <link rel="icon" href="{{ uploadedFile(settings('site_icon')) }}" sizes="32x32" />
    <link rel="icon" href="{{ uploadedFile(settings('site_icon')) }}" sizes="192x192" />
    <link rel="apple-touch-icon" href="{{ uploadedFile(settings('site_icon')) }}" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    @yield('meta')
    <!-- line awesome -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/line-awesome.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/bootstrap.min.css">
    <!-- Font awesome -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/fontawesome-all.min.css">
    <!-- Slick -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/slick.css">
    <!-- Animate css -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/animate.min.css">
    <!-- splitting -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/splitting.css">
    <!-- zoom css -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/magiczoom.css">
    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('frontend/v2') }}/css/main.css?v=3.7">
    <!-- Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-REW8ZPB8LF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-REW8ZPB8LF');
</script>



    
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '2710548559245038');
  fbq('track', 'PageView');
</script>
<noscript>
  <img height="1" width="1" style="display:none" 
       src="https://www.facebook.com/tr?id=2710548559245038&ev=PageView&noscript=1"/>
</noscript>
    
    
</head>
<body x-data="{
    cartSummaryOpened : false,
    checkoutModal : false
}">

    {{-- Start Header --}}
    @livewire('frontend.inc.header')
    {{-- End Header --}}

    {{-- Start Main Content --}}
    <div class="main-content">
        {{ $slot }}
    </div>
    {{-- End Main Content --}}

    @livewire('frontend.cart-summary')

	<!-- footer section -->
    <footer class="footer-area  overflow--hidden">

        <div class="thumb--wrap bg--img">
            <img src="{{ uploadedFile(settings('footer_logo')) }}">
        </div>

        <div class="footer-top">
            <div class="container-fluid">
                <div class="row gy-4 justify-content-center py-3">
                    @php
                        $widget_one = is_array(json_decode(settings('widget_one_title'))) ? json_decode(settings('widget_one_title')) : [];
                        $widget_one_links = @json_decode(settings('widget_one_link'));
                    @endphp
                    <div class="col-xl-2 col-sm-6">
                        <div class="footer-item">
                            <h5 class="footer-item--title">{{ settings('widget_title_one') }}</h5>
                            <ul class="footer--menu">
                                @foreach ($widget_one as $key => $one)
                                <li class="menu--item">
                                    <a href="{{ @$widget_one_links[$key] }}" class="menu--link"> {{ $one }} </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @php
                        $widget_two = is_array(json_decode(settings('widget_two_title'))) ? json_decode(settings('widget_two_title')) : [];
                        $widget_two_links = @json_decode(settings('widget_two_link'));
                    @endphp
                    <div class="col-xl-2 col-sm-6">
                        <div class="footer-item">
                            <h5 class="footer-item--title">{{ settings('widget_title_two') }}</h5>
                            <ul class="footer--menu">
                                @foreach ($widget_two as $key => $two)
                                <li class="menu--item">
                                    <a href="{{ @$widget_two_links[$key] }}" class="menu--link"> {{ $two }} </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @php
                        $footer_showrooms = is_array(json_decode(settings('footer_showroom_title'))) ? json_decode(settings('footer_showroom_title')) : [];
                        $footer_descriptions = @json_decode(settings('footer_showroom_description'));
                        $footer_one_phones = @json_decode(settings('footer_showroom_phone_one'));
                        $footer_two_phones = @json_decode(settings('footer_showroom_phone_two'));
                    @endphp
                    @foreach ($footer_showrooms as $key => $title)
                    <div class="col-xl-2 col-sm-6">
                        <div class="footer-item">
                            <h5 class="footer-item--title text-start text-sm-end">{{ $title }}</h5>
                            <div class="footer-contact-info mb-3 d-flex justify-content-end flex-column gap-1">
                                <div class="d-flex justify-content-end align-items-center">
                                    <p class="fw--400 text-start text-sm-end">{{ @$footer_descriptions[$key] }}</p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <p class="text-start text-sm-end"><a href="tel:{{ @$footer_one_phones[$key] }}">{{ @$footer_one_phones[$key] }}</a> </p>
                                </div>
                            </div>

                            @if($loop->last)
                            <div class="footer-contact-info mb-3 d-flex justify-content-end flex-column gap-1">
                                <div class="d-flex justify-content-end align-items-center">
                                    <p class="text--black fs--18 fw--600 text-start text-sm-end">Follow {{ config('app.name') }}</p>
                                </div>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <p class="text-start text-sm-end">Please give a feadback</p>
                                </div>
                            </div>

                            <ul class="social-list flex-wrap justify-content-start justify-content-sm-end">
                                <li class="social-list--item"><a href="{{ settings('fb_link') }}"
                                        class="social-list__link icon-wrapper">
                                        <div class="icon"><i class="fab fa-facebook-f"></i></div>
                                    </a> </li>
                                <li class="social-list--item"><a href="{{ settings('yt_link') }}"
                                        class="social-list__link icon-wrapper active">
                                        <div class="icon"><i class="fa-brands fa-youtube"></i></div>
                                    </a></li>
                                <li class="social-list--item"><a href="{{ settings('linkedin_link') }}"
                                        class="social-list__link icon-wrapper">
                                        <div class="icon"><i class="fab fa-linkedin-in"></i></div>
                                    </a></li>
                                <li class="social-list--item"><a href="{{ settings('insta_link') }}"
                                        class="social-list__link icon-wrapper">
                                        <div class="icon"><i class="fab fa-instagram"></i></div>
                                    </a></li>
                            </ul>
                            @endif

                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <!-- bottom Footer -->
            <div class="bottom-footer pt-4 pb-3">
                <div class="container">
                    <div class="row text-center gy-2">
                        <div class="col-lg-12">
                            <div class="bottom-footer-text">
                                {!! settings('copyright_text') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Top End-->

    </footer>
	<!-- footer section -->

    <!--<div class="scroll-top show">-->
    <!--    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">-->
    <!--        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"-->
    <!--            style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 197.514;">-->
    <!--        </path>-->
    <!--    </svg>-->
        
      
    <!--</div>-->
    
    <style>
    
    .whatsapp {
        font-size:40px;
        position: fixed;
        right: 30px;
        bottom: 42px;
        height: 50px;
        width: 50px;
        cursor: pointer;
        display: block;
        border-radius: 50px;
        
    }
    
    .la-whatsapp{
        color:green;
    }
 
    </style>
    
    <div class="whatsapp" >
           <a aria-label="Chat on WhatsApp" href="https://wa.link/8riuag"> <i class="lab la-whatsapp"></i></i><a />
        
    </div>

    
  

</body>

<!-- Jquery js -->
<script src="{{ asset('frontend/v2') }}/js/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Js -->
<script src="{{ asset('frontend/v2') }}/js/bootstrap.bundle.min.js"></script>
<!-- Slick js -->
<script src="{{ asset('frontend/v2') }}/js/slick.min.js"></script>
<!-- wow js -->
<script src="{{ asset('frontend/v2') }}/js/wow.min.js"></script>
<!-- splitting js -->
<script src="{{ asset('frontend/v2') }}/js/splitting.min.js"></script>
<!-- zoom js -->
<script src="{{ asset('frontend/v2') }}/js/magiczoom.js"></script>
<!-- main js -->
<script src="{{ asset('frontend/v2') }}/js/main.js?v=1.4"></script>

@stack('js')



</html>

