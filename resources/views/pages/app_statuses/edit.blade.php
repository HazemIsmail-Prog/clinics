@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Statuses')

@section('content')
    <form action="{{route('app_statuses.update',$app_status->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Status - {{$app_status->name}}</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="row">

                    {{--Name--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="name">Name</label>
                            <input class="form-control {{$errors->has('name')?'border-danger':''}}" type="text"
                                   id="name"
                                   name="name" value="{{old('name',$app_status->name)}}">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    {{--Color--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="color">Color</label>
                            <input class="form-control {{$errors->has('color')?'border-danger':''}}" type="color"
                                   id="color"
                                   name="color" value="{{old('name',$app_status->color)}}">
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
                    <a href="{{route('app_statuses.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>
        </div>

    </form>


@endsection
