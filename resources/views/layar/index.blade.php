<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Create a stylish landing page for your business startup and get leads for the offered services with this HTML landing page template.">
    <meta name="author" content="Inovatik">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
	<meta property="og:site_name" content="" /> <!-- website name -->
	<meta property="og:site" content="" /> <!-- website link -->
	<meta property="og:title" content=""/> <!-- title shown in the actual shared post -->
	<meta property="og:description" content="" /> <!-- description shown in the actual shared post -->
	<meta property="og:image" content="" /> <!-- image link, make sure it's jpg -->
	<meta property="og:url" content="" /> <!-- where do you want your post to link to -->
	<meta property="og:type" content="article" />

    <!-- Website Title -->
    <title>Martabak bangka 86</title>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,600,700,700i&amp;subset=latin-ext" rel="stylesheet">
    <link  type="text/css" href="{{asset('/css/bootstrap.css')}}" rel="stylesheet">
    <link  type="text/css" href="{{asset('/css/fontawesome-all.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('/css/swiper.css')}}" rel="stylesheet">
	<link type="text/css" href="{{asset('/css/magnific-popup.css')}}" rel="stylesheet">
	<link type="text/css" href="{{asset('/css/styles.css')}}" rel="stylesheet">
	
	<!-- Favicon  -->
    <link rel="icon" href="{{asset('/images/favicon.png')}}">
</head>
<body data-spy="scroll" data-target=".fixed-top">
    
    <!-- Preloader -->
	<div class="spinner-wrapper">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <!-- end of preloader -->
    

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <!-- Text Logo - Use this if you don't have a graphic logo -->
        <!-- <a class="navbar-brand logo-text page-scroll" href="index.html">Evolo</a> -->

        <!-- Image Logo -->
        <!-- <a class="navbar-brand logo-image" href="index.html"><img src="{{asset('/images/favicon_1.png')}}" alt="alternative"></a> -->
        
        <!-- Mobile Menu Toggle Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-awesome fas fa-bars"></span>
            <span class="navbar-toggler-awesome fas fa-times"></span>
        </button>
        <!-- end of mobile menu toggle button -->

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#header">Home<span class="sr-only">(current)</span></a>
                </li>                
                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#lokasi">Lokasi Toko</a>
                </li>              
                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#product">Product</a>
                </li>
              

                <!-- Dropdown Menu -->          
               <!--  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle page-scroll" href="#about" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">About</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="terms-conditions.html"><span class="item-text">Terms Conditions</span></a>
                        <div class="dropdown-items-divide-hr"></div>
                        <a class="dropdown-item" href="privacy-policy.html"><span class="item-text">Privacy Policy</span></a>
                    </div>
                </li> -->
                <!-- end of dropdown menu -->

                <li class="nav-item">
                    <a class="nav-link page-scroll" href="{{ url('/masuk') }}"> Login </a>
                </li>

               
            </ul>             
         
        </div>
    </nav> <!-- end of navbar -->
    <!-- end of navigation -->


    <!-- Header -->
    <header id="header" class="header">
        <div class="header-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="text-container">
                            <h1>MARTABAK BANGKA 86
                            <p class="p-large">Website ini memberikan informasi seputar martabak bangka 86.</p>
                            <!-- <a class="btn-solid-lg page-scroll" href="#services">DISCOVER</a> -->
                        </div> <!-- end of text-container -->
                    </div> <!-- end of col -->
                    <div class="col-lg-5">
                        <div class="image-container">
                            <!-- <img class="img-fluid" src="{{asset('/images/favicon_1.png')}}" alt="alternative"> -->
                        </div> <!-- end of image-container -->
                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </div> <!-- end of header-content -->
    </header> <!-- end of header -->
    <!-- end of header -->



    

    <div id="lokasi" class="cards-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Lokasi Toko</h2>
                    <p class="p-heading p-large">Toko memiliki tempat penjualan terdiri dari</p>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-12">

                    <!-- Card -->
                     @foreach ($cabang as $k_cabang)
                    <div class="card">
                        <img class="card-image" src="{{ asset('/images/maps.png') }}" alt="alternative">
                        <div class="card-body">
                            <h4 class="card-title">{{$k_cabang->cabang_name}}</h4>
                            <p>Buka jam 17.00 s/d 23.00</p>
                            <p>{{$k_cabang->address}}</p>
                            @if($k_cabang->flag_active == 1)
                            <p class="btn btn-primary">Buka</p>
                            @else
                            <p class="btn btn-danger">Tutup</p>
                            @endif
                            <br>
                            <a target="_blank" class="btn-solid-lg page-scroll" href="{{$k_cabang->lokasi}}">LOKASI</a>
                        </div>
                    </div>
                    @endforeach
                    <!-- end of card -->

                 
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of cards-1 -->
    <!-- end of services -->


    <!-- Details Lightboxes -->
    <!-- Details Lightbox 1 -->
	<div id="details-lightbox-1" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="container">
            <div class="row">
                <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
                <div class="col-lg-8">
                    <div class="image-container">
                        <img class="img-fluid" src="{{ asset('/images/details-lightbox-1.svg')}}" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
                <div class="col-lg-4">
                    <h3>Design And Plan</h3>
                    <hr>
                    <h5>Core feature</h5>
                    <p>The emailing module basically will speed up your email marketing operations while offering more subscriber control.</p>
                    <p>Do you need to build lists for your email campaigns? It just got easier with Evolo.</p>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">List building framework</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Easy database browsing</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">User administration</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Automate user signup</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Quick formatting tools</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Fast email checking</div>
                        </li>
                    </ul>
                    <a class="btn-solid-reg mfp-close page-scroll" href="#request">REQUEST</a> <a class="btn-outline-reg mfp-close as-button" href="#screenshots">BACK</a>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of details lightbox 1 -->

    <!-- Details Lightbox 2 -->
	<div id="details-lightbox-2" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="container">
            <div class="row">
                <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
                <div class="col-lg-8">
                    <div class="image-container">
                        <img class="img-fluid" src="{{ asset('/images/details-lightbox-2.svg') }}" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
                <div class="col-lg-4">
                    <h3>Search To Optimize</h3>
                    <hr>
                    <h5>Core feature</h5>
                    <p>The emailing module basically will speed up your email marketing operations while offering more subscriber control.</p>
                    <p>Do you need to build lists for your email campaigns? It just got easier with Evolo.</p>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">List building framework</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Easy database browsing</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">User administration</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Automate user signup</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Quick formatting tools</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i><div class="media-body">Fast email checking</div>
                        </li>
                    </ul>
                    <a class="btn-solid-reg mfp-close page-scroll" href="#request">REQUEST</a> <a class="btn-outline-reg mfp-close as-button" href="#screenshots">BACK</a>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of details lightbox 2 -->
    <!-- end of details lightboxes -->


    <!-- Pricing -->
    <div id="product" class="cards-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Martabak 86</h2>
                    <p class="p-heading p-large">Martabak 86 LEZAT!!!</p>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-12">

                    <!-- Card-->
                     @foreach ($product as $k_product)
                    <div class="card">
                        <div class="card-body">
                           <?php $nominal=number_format($k_product->nominal,0,",","."); ?>
                            <div class="card-title">{{$k_product->nama}}</div>
                            <!-- <div class="card-subtitle">{{$k_product->description}}</div> -->
                            <hr class="cell-divide-hr">
                            <div class="price">
                                <span class="currency">IDR</span><br><span class="value">{{$nominal}}</span>
                               
                            <hr class="cell-divide-hr">
                            
                            <img class="img-fluid" src="{{$k_product->path_foto}}" alt="alternative">                       
                        </div>
                                
                                
                            </div>
                    </div> <!-- end of card -->
                    @endforeach
                        <!-- end of card -->

                   

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of cards-2 -->
    <!-- end of pricing -->

  


    <!-- Video -->
    <div class="basic-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Check Out The Video Martabang Bangka 86</h2>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-12">
                    
                    <!-- Video Preview -->
                    <div class="image-container">
                        <div class="video-wrapper">
                            <a class="popup-youtube" href="https://www.youtube.com/watch?v=fLCjQJCekTs" data-effect="fadeIn">
                                <img class="img-fluid" src="images/video-frame.svg" alt="alternative">
                                <span class="video-play-button">
                                    <span></span>
                                </span>
                            </a>
                        </div> <!-- end of video-wrapper -->
                    </div> <!-- end of image-container -->
                    <!-- end of video preview -->

                    <p>This video will show you a case study for one of our <strong>Major Customers</strong> and will help you understand why your startup needs Evolo in this highly competitive market</p>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-3 -->
    <!-- end of video -->


    <!-- Testimonials -->
    <div class="slider-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="image-container">
                        <img class="img-fluid" src="{{ asset('/images/testimonials-2-men-talking.svg') }}" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6">
                    <h2>Testimonials</h2>

                    <!-- Card Slider -->
                    <div class="slider-container">
                        <div class="swiper-container card-slider">
                            <div class="swiper-wrapper">
                                
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="{{ asset('/images/testimonial-1.svg') }}" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Martabak Lezat enak joss! pokoknya</p>
                                            <p class="testimonial-author">Udin - Pembeli</p>
                                        </div>
                                    </div>
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
        
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="{{ asset('/images/testimonial-2.svg') }}" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Enak Betul ga nyesel beli disini</p>
                                            <p class="testimonial-author">Marsha Singer - Pembeli</p>
                                        </div>
                                    </div>        
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
        
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="card">
                                        <img class="card-image" src="{{ asset('/images/testimonial-3.svg') }}" alt="alternative">
                                        <div class="card-body">
                                            <p class="testimonial-text">Oke Martabak mantap betul.</p>
                                            <p class="testimonial-author">Roy Smith - Pembeli</p>
                                        </div>
                                    </div>        
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
                               
                            </div> <!-- end of swiper-wrapper -->
        
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- end of add arrows -->
        
                        </div> <!-- end of swiper-container -->
                    </div> <!-- end of slider-container -->
                    <!-- end of card slider -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of slider-2 -->
    <!-- end of testimonials -->


    



   


    <!-- Copyright -->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="p-small">Copyright © 2021 Martabak Bangka 86 
                </div> <!-- end of col -->
            </div> <!-- enf of row -->
        </div> <!-- end of container -->
    </div> <!-- end of copyright --> 
    <!-- end of copyright -->
    
    	
    <!-- Scripts -->
    <script src="{{ asset('/js/jquery.min.js') }}"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="{{ asset('/js/popper.min.js') }}"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script> <!-- Bootstrap framework -->
    <script src="{{ asset('/js/jquery.easing.min.js') }}"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="{{ asset('/js/swiper.min.js') }}"></script> <!-- Swiper for image and text sliders -->
    <script src="{{ asset('/js/jquery.magnific-popup.js') }}"></script> <!-- Magnific Popup for lightboxes -->
    <script src="{{ asset('/js/validator.min.js') }}"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="{{ asset('/js/scripts.js') }}"></script> <!-- Custom scripts -->
</body>
</html> 