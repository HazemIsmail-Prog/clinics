{{--@if (session('success'))--}}
{{--    <div id="alert" class="alert alert-success" role="alert">--}}
{{--        {{session('success')}}--}}
{{--    </div>--}}
{{--@elseif(session('failed'))--}}
{{--    <div id="alert" class="alert alert-danger" role="alert">--}}
{{--        {{session('failed')}}--}}
{{--    </div>--}}
{{--@endif--}}






@if (session('success'))
    <script>
        Swal.fire({
            position: 'center-center',
            icon: 'success',
            title: '{{session('success')}}',
            showConfirmButton: false,
            timer: 1500
        })
    </script>


@elseif(session('failed'))
    <script>
        Swal.fire({
            position: 'center-center',
            icon: 'error',
            title: '{{session('failed')}}',
            showConfirmButton: true,
        })
    </script>
@endif




