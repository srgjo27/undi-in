<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
@include('layouts.be.head')

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.be.header')
        @include('layouts.be.navbar')
        <div class="vertical-overlay"></div>
        <div class="main-content">
            @include('layouts.be.content')
            @include('layouts.be.footer')
        </div>
    </div>
    <!-- End layout-wrapper -->
    @include('layouts.be.user-profile-canvas')
    @include('layouts.be.theme-setting')
    @include('layouts.be.scripts')
</body>

</html>
