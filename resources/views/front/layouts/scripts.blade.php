<script src="{{ asset('dist-front/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist-front/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist-front/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('dist-front/js/glightbox.min.js') }}"></script>
<script src="{{ asset('dist-admin/js/iziToast.min.js') }}"></script>

<script type="text/javascript">
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });
</script>
<script src="{{ asset('dist-front/js/custom.js') }}?v={{ filemtime(public_path('dist-front/js/custom.js')) }}"></script>
@if(!is_rtl())
<script src="{{ asset('dist-front/js/ltr.js') }}"></script>
@endif
@if(is_rtl())
<script src="{{ asset('dist-front/js/rtl.js') }}"></script>
@endif

@if($errors->any())
    <script>
        iziToast.error({
            message: '{{ $errors->first() }}',
            position: 'topRight'
        });
    </script>
@endif

@if(session('success'))
    <script>
        iziToast.success({
            message: '{{ session('success') }}',
            position: 'topRight'
        });
    </script>
@endif

@if(session('error'))
    <script>
        iziToast.error({
            message: '{{ session('error') }}',
            position: 'topRight'
        });
    </script>
@endif