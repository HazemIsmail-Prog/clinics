@extends('layouts.master')
@section('title','Accounts')
@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Accounts</h6>
            @can('Accounts_Create')
                <div class="dropdown no-arrow">
                    <a class="btn btn-sm btn-outline-primary" href="{{route('accounts.create')}}">Add New</a>
                </div>
            @endcan
        </div>
        <!-- Card Body -->
        <div class="card-body">

            {{--    <div id="accordion1">
        @foreach($parentAccounts as $parentAccount)
            <div class="card mb-1">
                <div class="card-header d-flex justify-content-between align-items-center"
                     id="heading{{$parentAccount->id}}">
                    <div class="btn" data-toggle="collapse" data-target="#collapse{{$parentAccount->id}}"
                         aria-expanded="false" aria-controls="collapse{{$parentAccount->id}}">
                        {{$parentAccount->id}} - {{$parentAccount->name}}
                    </div>
                    <div class="text-center">
                                            @can('accounts_update')
                        <a href="{{route('accounts.edit',$parentAccount->id)}}" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('accounts_delete')
                        <a
                            class="btn btn-outline-danger btn-sm"
                            href="{{route('accounts.destroy',$parentAccount->id)}}"
                            onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $parentAccount->id }}').submit() : false;"
                        >
                            <i class="fa fa-trash"></i>
                        </a>
                        <form
                            action="{{route('accounts.destroy',$parentAccount->id)}}"
                            id="delete-form-{{$parentAccount->id}}"
                            method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                                            @endcan
                    </div>
                </div>
                <div id="collapse{{$parentAccount->id}}" class="collapse"
                     aria-labelledby="heading{{$parentAccount->id}}" data-parent="#accordion1">
                    <div class="card-body">
                        <div id="accordion2">
                            @foreach($parentAccount->childAccounts as $child1)
                                <div class="card mb-1">
                                    <div class="card-header d-flex justify-content-between align-items-center"
                                         id="heading{{$child1->id}}">
                                        <div class="btn" data-toggle="collapse" data-target="#collapse{{$child1->id}}"
                                             aria-expanded="false" aria-controls="collapse{{$child1->id}}">
                                            {{$child1->id}} - {{$child1->name}}
                                        </div>
                                        <div class="text-center">
                                                                @can('accounts_update')
                                            <a href="{{route('accounts.edit',$child1->id)}}"
                                               class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                                                @endcan
                                                                @can('accounts_delete')
                                            <a
                                                class="btn btn-outline-danger btn-sm"
                                                href="{{route('accounts.destroy',$child1->id)}}"
                                                onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $child1->id }}').submit() : false;"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form
                                                action="{{route('accounts.destroy',$child1->id)}}"
                                                id="delete-form-{{$child1->id}}"
                                                method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                                                @endcan
                                        </div>
                                    </div>
                                    <div id="collapse{{$child1->id}}" class="collapse"
                                         aria-labelledby="heading{{$child1->id}}" data-parent="#accordion2">
                                        <div class="card-body">
                                            <div id="accordion3">
                                                @foreach($child1->childAccounts as $child2)
                                                    <div class="card mb-1">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center"
                                                            id="heading{{$child2->id}}">
                                                            <div class="btn" data-toggle="collapse"
                                                                 data-target="#collapse{{$child2->id}}"
                                                                 aria-expanded="false"
                                                                 aria-controls="collapse{{$child2->id}}">
                                                                {{$child2->id}} - {{$child2->name}}
                                                            </div>
                                                            <div class="text-center">
                                                                                    @can('accounts_update')
                                                                <a href="{{route('accounts.edit',$child2->id)}}"
                                                                   class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                                                                    @endcan
                                                                                    @can('accounts_delete')
                                                                <a
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    href="{{route('accounts.destroy',$child2->id)}}"
                                                                    onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $child2->id }}').submit() : false;"
                                                                >
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                                <form
                                                                    action="{{route('accounts.destroy',$child2->id)}}"
                                                                    id="delete-form-{{$child2->id}}"
                                                                    method="POST"
                                                                    style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                                    @endcan
                                                            </div>
                                                        </div>
                                                        <div id="collapse{{$child2->id}}" class="collapse"
                                                             aria-labelledby="heading{{$child2->id}}"
                                                             data-parent="#accordion3">
                                                            <div class="card-body">
                                                                <div id="accordion4">
                                                                    @foreach($child2->childAccounts as $child3)
                                                                        <div class="card mb-1">
                                                                            <div
                                                                                class="card-header d-flex justify-content-between align-items-center"
                                                                                id="heading{{$child3->id}}">
                                                                                <div class="btn" data-toggle="collapse"
                                                                                     data-target="#collapse{{$child3->id}}"
                                                                                     aria-expanded="false"
                                                                                     aria-controls="collapse{{$child3->id}}">
                                                                                    {{$child3->id}} - {{$child3->name}}
                                                                                </div>
                                                                                <div class="text-center">
                                                                                                        @can('accounts_update')
                                                                                    <a href="{{route('accounts.edit',$child3->id)}}"
                                                                                       class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                                                                                        @endcan
                                                                                                        @can('accounts_delete')
                                                                                    <a
                                                                                        class="btn btn-outline-danger btn-sm"
                                                                                        href="{{route('accounts.destroy',$child3->id)}}"
                                                                                        onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $child3->id }}').submit() : false;"
                                                                                    >
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </a>
                                                                                    <form
                                                                                        action="{{route('accounts.destroy',$child3->id)}}"
                                                                                        id="delete-form-{{$child3->id}}"
                                                                                        method="POST"
                                                                                        style="display: none;">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                    </form>
                                                                                                        @endcan
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>--}}


            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>ID</th>
                        <th>Account Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($parentAccounts as $parentAccount)
                        <tr>
                            <td class="text-left">{{$parentAccount->id}}</td>
                            <td class="parent">{{$parentAccount->name}} <span
                                    class="badge {{$parentAccount->type == 'Debit' ? 'badge-danger' : 'badge-success'}} badge-pill rounded-pill">{{$parentAccount->type}}</span>
                            </td>
                            <td class="text-center">
                                @can('Accounts_Update')
                                    <a href="{{route('accounts.edit',$parentAccount->id)}}"
                                       class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('Accounts_Delete')
                                    <a
                                        class="btn btn-outline-danger btn-sm"
                                        href="{{route('accounts.destroy',$parentAccount->id)}}"
                                        onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $parentAccount->id }}').submit() : false;"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <form
                                        action="{{route('accounts.destroy',$parentAccount->id)}}"
                                        id="delete-form-{{$parentAccount->id}}"
                                        method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endcan
                            </td>
                        </tr>
                        @foreach($parentAccount->childAccounts as $child1)
                            <tr>
                                <td class="text-left">{{$child1->id}}</td>
                                <td class="child1"><i class="fas fa-reply fa-lg"></i> {{$child1->name}} <span
                                        class="badge {{$child1->type == 'Debit' ? 'badge-danger' : 'badge-success'}} badge-pill rounded-pill">{{$child1->type}}</span>
                                </td>
                                <td class="text-center">

                                    @can('Accounts_Update')
                                        <a href="{{route('accounts.edit',$child1->id)}}"
                                           class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                    @endcan
                                    @can('Accounts_Delete')
                                        <a
                                            class="btn btn-outline-danger btn-sm"
                                            href="{{route('accounts.destroy',$child1->id)}}"
                                            onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $child1->id }}').submit() : false;"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form
                                            action="{{route('accounts.destroy',$child1->id)}}"
                                            id="delete-form-{{$child1->id}}"
                                            method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                            @foreach($child1->childAccounts as $child2)
                                <tr>
                                    <td class="text-left">{{$child2->id}}</td>
                                    <td class="child2"><i class="fas fa-reply fa-lg"></i> {{$child2->name}} <span
                                            class="badge {{$child2->type == 'Debit' ? 'badge-danger' : 'badge-success'}} badge-pill rounded-pill">{{$child2->type}}</span>
                                    </td>
                                    <td class="text-center">
                                        @can('Accounts_Update')
                                            <a href="{{route('accounts.edit',$child2->id)}}"
                                               class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                        @endcan
                                        @can('Accounts_Delete')
                                            <a
                                                class="btn btn-outline-danger btn-sm"
                                                href="{{route('accounts.destroy',$child2->id)}}"
                                                onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $child2->id }}').submit() : false;"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form
                                                action="{{route('accounts.destroy',$child2->id)}}"
                                                id="delete-form-{{$child2->id}}"
                                                method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                                @foreach($child2->childAccounts as $child3)
                                    <tr>
                                        <td class="text-left">{{$child3->id}}</td>
                                        <td class="child3"><i class="fas fa-reply fa-lg"></i> {{$child3->name}} <span
                                                class="badge {{$child3->type == 'Debit' ? 'badge-danger' : 'badge-success'}} badge-pill rounded-pill">{{$child3->type}}</span>
                                        </td>
                                        <td class="text-center">
                                            @can('Accounts_Update')
                                                <a href="{{route('accounts.edit',$child3->id)}}"
                                                   class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Accounts_Delete')
                                                <a
                                                    class="btn btn-outline-danger btn-sm"
                                                    href="{{route('accounts.destroy',$child3->id)}}"
                                                    onclick="event.preventDefault();confirm('You\'r About to Delete This Account\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $child3->id }}').submit() : false;"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <form
                                                    action="{{route('accounts.destroy',$child3->id)}}"
                                                    id="delete-form-{{$child3->id}}"
                                                    method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection

