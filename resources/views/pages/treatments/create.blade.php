@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Treatments')

@section('content')
    <form action="{{route('treatments.store')}}" method="post">
        @csrf
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Add New Treatment</h6>
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
                                   name="name" value="{{old('name')}}">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    {{--Price--}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="price">Price</label>
                            <input class="form-control {{$errors->has('price')?'border-danger':''}}" type="number"
                                   step="0.001"
                                   id="price"
                                   name="price" value="{{old('price')}}">
                            @if($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
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
                                        value="{{$department->id}}" {{old('department_id') == $department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('department_id'))
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <div class="form-check">
                                <input class="form-check-input" checked name="active" type="checkbox" value="1"
                                       id="defaultCheck1">
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
                    <button type="submit" class="btn btn-sm text-primary">Save</button>
                    <a href="{{route('treatments.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>
        </div>

    </form>


@endsection
