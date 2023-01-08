@extends('layouts.master')

@section('links')
    {{--    <link rel="stylesheet" href="{{asset('assets\custom\css\responsive-table.css')}}">--}}
@endsection

@section('title','Departments')

@section('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
        }

        #clear_filter {
            position: absolute;
            right: 10px;
        }

    </style>
@endsection

@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Departments</h6>
            <div class="dropdown no-arrow">
                <a class="btn btn-sm btn-outline-primary" href="{{route('departments.create')}}">New Department</a>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($departments as $department)
                        <tr>
                            <td>{{$department->name}}</td>
                            <td class="text-center">

                                <a href="{{route('departments.edit',$department->id)}}"
                                   class="btn btn-outline-info btn-sm" title="Edit"><i
                                        class="fa fa-edit"></i></a>
                                <a
                                    class="btn btn-outline-danger btn-sm"
                                    href="{{route('departments.destroy',$department->id)}}"
                                    onclick="event.preventDefault();confirm('You\'r About to Delete This Department\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $department->id }}').submit() : false;"
                                >
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action="{{route('departments.destroy',$department->id)}}"
                                    id="delete-form-{{$department->id}}"
                                    method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
