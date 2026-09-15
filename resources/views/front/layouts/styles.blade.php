@if(is_rtl())
<link rel="stylesheet" href="{{ asset('dist-front/css/bootstrap.rtl.min.css') }}">
@else
<link rel="stylesheet" href="{{ asset('dist-front/css/bootstrap.min.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('dist-front/css/bootstrap-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-front/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-front/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-front/css/glightbox.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-admin/css/iziToast.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-front/css/gwjn-theme.css') }}?v={{ filemtime(public_path('dist-front/css/gwjn-theme.css')) }}">