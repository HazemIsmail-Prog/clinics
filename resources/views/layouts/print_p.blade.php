<!DOCTYPE html>
<html lang="en">

<head>

    @include('includes.meta')

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{asset('assets/custom/css/reports.css')}}">

    @yield('styles')


</head>

<body>


@yield('content')

@yield('scripts')


</body>

</html>
