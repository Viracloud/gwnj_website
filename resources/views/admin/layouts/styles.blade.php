<link rel="stylesheet" href="{{ asset('dist-admin/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-admin/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-admin/css/dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-admin/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-admin/css/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-admin/css/iziToast.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist-admin/css/style.css') }}?v={{ filemtime(public_path('dist-admin/css/style.css')) }}">
{{-- The admin panel is an internal tool and is intentionally always English/LTR,
     independent of the front-end's language setting (Settings -> Language Direction
     controls the public site only). This avoids a mirrored layout with untranslated
     English text, which looked broken. --}}