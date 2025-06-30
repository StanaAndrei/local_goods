<!-- resources/views/layouts/app.blade.php -->
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
    @livewireStyles
</head>
<body>
    @include('partials.navbar')
    <div class="container">
        @yield('content')
        {{ $slot ?? '' }}
    </div>
    @livewireScripts
</body>
</html>