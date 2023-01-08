@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Appointment Devices')

@section('styles')
@endsection

@section('content')
    <form action="{{route('app_devices.update',$app_device->id)}}" method="post">
        @csrf
        @method('PUT')
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Edit Appointment Departments - {{$app_device->name}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">




                <div class="row">

                    {{--Name--}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="text-primary" for="name">Name</label>
                            <input class="form-control {{$errors->has('name')?'border-danger':''}}" type="text"
                                   id="name"
                                   name="name" value="{{old('name',$app_device->name)}}">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="text-primary" for="app_department_id">Department</label>
                            <select class="custom-select form-control  {{$errors->has('app_department_id')?'border-danger':''}}" name="app_department_id" id="app_department_id">
                                @foreach($app_departments as $department)
                                    <option value="{{$department->id}}" {{old('app_department_id',$app_device->app_department_id) == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('app_department_id'))
                                <span class="text-danger">{{ $errors->first('app_department_id') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <div class="form-check">
                                <input class="form-check-input"
                                       {{old('active',$app_device->active) == 1 ? 'checked' : ''}} name="active"
                                       type="checkbox" value="1" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Active
                                </label>
                            </div>

                        </div>
                    </div>

                </div>









        </div>
        <div class="card-footer">
            <div class="text-center">
                <button type="submit" class="btn btn-sm text-primary">Update</button>
                <a href="{{route('app_devices.index')}}" class="btn btn-sm">Cancel</a>
            </div>
        </div>
    </div>
    </form>


@endsection

@section('scripts')
@endsection
