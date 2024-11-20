<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('sneat/assets/') }}" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>
        {{ ucwords(str_replace('_', ' ', $setup->app_name)) }} |
        {{ ucwords(str_replace(['.', '_', 'index'], [' ', ' ', ''], Route::currentRouteName())) }}</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('template.sneat.style')
    @php
        $permissions = collect($setup->permission)
            ->pluck('feature.route')
            ->toArray();
    @endphp
</head>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('template.sneat.sidebar')
            <!-- Layout container -->
            <div class="layout-page">
                @include('template.sneat.navbar')
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @yield('content')
                    {{-- @include("template.floating_button") --}}
                    @include('template.sneat.footer')
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    @include('template.sneat.script')
</body>

</html>
