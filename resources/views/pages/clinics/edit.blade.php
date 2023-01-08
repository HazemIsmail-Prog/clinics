@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Clinics')

@section('content')
    <form action="{{route('clinics.update',$clinic->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Clinic - {{$clinic->name}}</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="name">Name</label>
                            <input class="form-control {{$errors->has('name')?'border-danger':''}}" type="text"
                                   id="name"
                                   name="name" value="{{old('name',$clinic->name)}}">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="ar_name">Arabic Name</label>
                            <input class="form-control {{$errors->has('ar_name')?'border-danger':''}}" type="text"
                                   id="ar_name"
                                   name="ar_name"
                                   value="{{old('ar_name',$clinic->ar_name)}}" dir="rtl">
                            @if($errors->has('ar_name'))
                                <span class="text-danger">{{ $errors->first('ar_name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="text-primary" for="address">Address</label>
                            <input class="form-control {{$errors->has('address')?'border-danger':''}}" type="text"
                                   id="address"
                                   name="address"
                                   value="{{old('address',$clinic->address)}}">
                            @if($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="text-primary" for="logo">Logo</label>
                            <div class="text-center">
                                @if($clinic->logo)
                                    <img id="output" style="width: 100%;height: 145px;"
                                         src="{{asset('assets/clinics_logos/'.$clinic->logo)}}" alt="">
                                @else
                                    <img id="output" style="width: 100%;height: 145px;"
                                         src="{{asset('assets\img\No_Image_Available.jpg')}}" alt="">
                                @endif
                                <button id="change_logo" type="button"
                                        class="mx-auto mt-1 d-block btn btn-sm text-primary">Change Logo
                                </button>
                                <input accept="image/*"
                                       class="d-none form-control mt-1 {{$errors->has('logo')?'border-danger':''}}"
                                       type="file" id="logo"
                                       name="logo">
                                @if($errors->has('logo'))
                                    <span class="text-danger">{{ $errors->first('logo') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="text-primary" for="color">Color</label>
                            <input style="height: 145px;"
                                   class="form-control {{$errors->has('color')?'border-danger':''}}" type="color"
                                   id="color"
                                   name="color"
                                   value="{{old('color',$clinic->color)}}">
                            @if($errors->has('color'))
                                <span class="text-danger">{{ $errors->first('color') }}</span>
                            @endif
                        </div>
                    </div>
                </div>



            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm text-primary">Update</button>
                    <a href="{{route('clinics.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>

        </div>

    </form>


@endsection

@section('scripts')
    <script>

        // Input file button
        $('#change_logo').on('click', function () {
            $('#logo').click();
        });

        // Image Preview
        $('#logo').on('change', function () {
            $('#output').attr('src', URL.createObjectURL(event.target.files[0]));
        });

    </script>
@endsection
