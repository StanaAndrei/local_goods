<!-- resources/views/layouts/app.blade.php -->
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
</head>
<body>
    @include('partials.navbar')
    <div class="container">
        @yield('content')
    </div>
</body>
</html>