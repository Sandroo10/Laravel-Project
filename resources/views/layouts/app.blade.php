<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Discover your next opportunity with RoleBoard. Explore full-time, part-time and remote vacancies.">
    <title>@yield('title', 'Vacancies') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    @include('partials.header')

    <main id="main-content" class="page-container" tabindex="-1">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="site-footer page-container">
        <span class="footer-brand">RoleBoard<span aria-hidden="true">.</span></span>
        <p>A new chapter starts with the right role.</p>
    </footer>
</body>
</html>
