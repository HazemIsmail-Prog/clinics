@extends('layouts.master')

@section('links')
    {{--    <link rel="stylesheet" href="{{asset('assets\custom\css\responsive-table.css')}}">--}}
@endsection

@section('title','Users')

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
            <h6 class="m-0 font-weight-bold text-primary">Users</h6>
            @if (auth()->id() == 1)
                <div class="dropdown no-arrow">
                    <a class="btn btn-sm btn-outline-primary" href="{{route('users.create')}}">New User</a>
                </div>
            @endif
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>Name</th>
                        <th>Username</th>
                        <th class="text-center">Status</th>


                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->username}}</td>
                            <td class="text-center">
                                @if($user->active == 1)
                                    <span class="badge badge-success badge-pill">Active</span>
                                @else
                                    <span class="badge badge-danger badge-pill">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center">

                                <a href="{{route('users.edit',$user->id)}}"
                                   class="btn btn-outline-info btn-sm" title="Edit"><i
                                        class="fa fa-edit"></i></a>
                                <a
                                    class="btn btn-outline-danger btn-sm"
                                    href="{{route('users.destroy',$user->id)}}"
                                    onclick="event.preventDefault();confirm('You\'r About to Delete This User\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $user->id }}').submit() : false;"
                                >
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action="{{route('users.destroy',$user->id)}}"
                                    id="delete-form-{{$user->id}}"
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
