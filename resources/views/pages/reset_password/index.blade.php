@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Reset Password')

@section('content')

    <form action="{{route('reset_password.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Reset Password</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="form-group">
                    <label class="text-primary" for="old_password">Old Password</label>
                    <input class="form-control {{$errors->has('old_password')?'border-danger':''}}" type="password"
                           id="old_password"
                           name="old_password" value="{{old('old_password')}}">
                    @if($errors->has('old_password'))
                        <span class="text-danger">{{ $errors->first('old_password') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="text-primary" for="new_password">New Password</label>
                    <input class="form-control {{$errors->has('new_password')?'border-danger':''}}" type="password"
                           id="new_password"
                           name="new_password" value="{{old('new_password')}}">
                    @if($errors->has('new_password'))
                        <span class="text-danger">{{ $errors->first('new_password') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="text-primary" for="password_confirmation">Confirm Password</label>
                    <input class="form-control {{$errors->has('password_confirmation')?'border-danger':''}}" type="password"
                           id="password_confirmation"
                           name="password_confirmation" value="{{old('password_confirmation')}}">
                    @if($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

            </div>

            <div class="card-footer text-center">
                <button type="submit" class="btn btn-sm text-primary">Save</button>
            </div>

        </div>


    </form>



@endsection

@section('scripts')
@endsection
