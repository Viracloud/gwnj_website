<script src="{{ asset('dist-front/js/jquery.min.js') }}"></script>
<script src="{{ asset('dist-front/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist-front/js/glightbox.min.js') }}"></script>
<script src="{{ asset('dist-admin/js/iziToast.min.js') }}"></script>

<script type="text/javascript">
    const lightbox = GLightbox({
        selector: '.glightbox, [data-glightbox]',
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });
</script>
<script src="{{ asset('dist-front/js/custom.js') }}?v={{ filemtime(public_path('dist-front/js/custom.js')) }}"></script>

@if($errors->any())
    <script>
        iziToast.error({
            message: @json($errors->first()),
            position: 'topRight'
        });
    </script>
@endif

@if(session('success'))
    <script>
        iziToast.success({
            message: @json(session('success')),
            position: 'topRight'
        });
    </script>
@endif

@if(session('error'))
    <script>
        iziToast.error({
            message: @json(session('error')),
            position: 'topRight'
        });
    </script>
@endif