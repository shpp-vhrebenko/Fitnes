<!doctype html>
<html lang="ru">
    @include('admin/partials/header')
    <body>
        @include('admin/partials/navbar')
        <div class="camotek-admin-content">
            @yield('content')
        </div>
        @include('admin/partials/footer')              
    </body>
</html>