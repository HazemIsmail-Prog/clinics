@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Doctors')

@section('content')
    <form action="{{route('doctors.update',$doctor->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Doctor - {{$doctor->name}}</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="row">

                    {{--Name--}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="name">Name</label>
                            <input class="form-control {{$errors->has('name')?'border-danger':''}}" type="text"
                                   id="name"
                                   name="name" value="{{old('name',$doctor->name)}}">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="department_id">Department</label>
                            <select
                                class="custom-select form-control  {{$errors->has('department_id')?'border-danger':''}}"
                                name="department_id" id="department_id">
                                <option value="">---</option>
                                @foreach($departments as $department)
                                    <option
                                        value="{{$department->id}}" {{old('department_id',$doctor->department_id) == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('department_id'))
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="account_id">Account</label>
                            <select
                                class="custom-select form-control  {{$errors->has('account_id')?'border-danger':''}}"
                                name="account_id" id="account_id">
                                <option value="">---</option>
                                @foreach($accounts as $account)
                                    <option
                                        value="{{$account->id}}" {{old('account_id',$doctor->account_id) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('account_id'))
                                <span class="text-danger">{{ $errors->first('account_id') }}</span>
                            @endif

                        </div>
                    </div>

                </div>


            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm text-primary">Update</button>
                    <a href="{{route('doctors.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>

        </div>

    </form>


@endsection
