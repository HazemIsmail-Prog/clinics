@extends('layouts.master')
@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection
@section('title','Users')
@section('content')


    <form action="{{route('users.update',$user->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit User - {{$user->name}}</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="username">User Name</label>
                            <input class="form-control {{$errors->has('username')?'border-danger':''}}" type="text"
                                   id="username"
                                   name="username" value="{{old('username',$user->username)}}">
                            @if($errors->has('username'))
                                <span class="text-danger">{{ $errors->first('username') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="name">Display Name</label>
                            <input class="form-control {{$errors->has('name')?'border-danger':''}}" type="text"
                                   id="name"
                                   name="name" value="{{old('name',$user->name)}}">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="password">Password</label>
                            <input class="form-control {{$errors->has('password')?'border-danger':''}}" type="password"
                                   id="password"
                                   name="password">
                            @if($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="clinic_id">Clinic</label>
                            <select class="custom-select form-control  {{$errors->has('clinic_id')?'border-danger':''}}"
                                    name="clinic_id" id="clinic_id">
                                @foreach($clinics as $clinic)
                                    <option
                                        value="{{$clinic->id}}" {{old('clinic_id',$user->clinic_id) == $clinic->id ? 'selected' : ''}}>{{$clinic->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('clinic_id'))
                                <span class="text-danger">{{ $errors->first('clinic_id') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <div class="form-check">
                                <input class="form-check-input"
                                       {{old('active',$user->active) == 1 ? 'checked' : ''}} name="active"
                                       type="checkbox" value="1" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Active
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card my-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Permissions</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="form-group">
                                    @foreach($permissions as $permission)
                                        @if($loop->index == 0 )
                                            <div class="card mb-2">
                                                <div
                                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                    <h6 class="m-0 font-weight-bold text-primary">{{explode('_',trim($permission))[0]}}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @else
                                                        @if(explode('_',trim($permission))[0] != explode('_',trim($permissions[$loop->index - 1]))[0])
                                                </div>
                                            </div>
                                            <div class="card mb-2">

                                                <div
                                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                                    <h6 class="m-0 font-weight-bold text-primary">{{explode('_',trim($permission))[0]}}</h6>
                                                </div>
                                                <div class="card-body">
                                                    @endif
                                                    @endif
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]"
                                                               type="checkbox"
                                                               value="{{$permission}}"
                                                               id="flexCheckChecked{{$permission}}"
                                                            {{$user->hasPermission($permission) == $permission ? 'checked' : ''}}
                                                        >
                                                        <label class="form-check-label"
                                                               for="flexCheckChecked{{$permission}}">
                                                            {{explode('_',trim($permission))[1]}}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                    @if($errors->has('permissions'))
                                                        <span
                                                            class="text-danger">{{ $errors->first('permissions') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm text-primary">Save</button>
                    <a href="{{route('users.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </form>

@endsection
