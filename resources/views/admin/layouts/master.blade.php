<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Admin Panel</title>
    <meta name="description" content="">

    <link rel="icon" type="image/png" href="{{ asset('uploads/'.$global_setting->favicon) }}">

    @include('admin.layouts.styles')
    
</head>
<body class="{{ Request::is('admin/login') || Request::is('admin/forget-password') || Request::is('admin/reset-password*') ? 'login-page' : '' }}">


    @if(!Request::is('admin/login') && !Request::is('admin/forget-password') && !Request::is('admin/reset-password*'))
    <div class="admin-wrapper">
        
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Mobile overlay -->
        <div id="overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main -->
        <div class="main-wrapper">
            
            <!-- Top bar -->
            @include('admin.layouts.top')

            <!-- Content -->
            <main class="content-area">

                @yield('content')

            </main>
        </div>
    </div>
    @else
    <div class="login-wrapper">
        <div class="login-box">
            
            @yield('content')
            
        </div>
    </div>
    @endif

    @include('admin.layouts.scripts')
    @stack('scripts')

</body>
</html>
