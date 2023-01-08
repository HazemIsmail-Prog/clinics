<!DOCTYPE html>
<html lang="en">

<head>

    @include('includes.meta')

    @include('includes.links')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

    @yield('links')
    @livewireStyles

    <title>@yield('title')</title>

    @yield('styles')

    @if(auth()->user()->clinic)
        @include('includes.clinic_color')
    @endif

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    @include('includes.sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            @include('includes.topbar')

            <!-- Begin Page Content -->
            <div class="container-fluid">

            @yield('content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

{{--        @include('includes.footer')--}}

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded noprint" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

@include('includes.logout_modal')

@include('includes.scripts')
@include('includes.alerts')

@yield('scripts')



@livewireScripts

</body>

</html>
