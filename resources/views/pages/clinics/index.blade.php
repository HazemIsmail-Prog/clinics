@extends('layouts.master')

@section('links')
@endsection

@section('title','Clinics')

@section('styles')
@endsection

@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Clinics</h6>
            @if (auth()->id() == 1)
                <div class="dropdown no-arrow">
                    <a class="btn btn-sm btn-outline-primary" href="{{route('clinics.create')}}">Add New</a>
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
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clinics as $clinic)
                        <tr style="color: {{$clinic->color}};">
                            <td>{{$clinic->name}}</td>
                            <td>{{$clinic->address}}</td>
                            <td>
                                <a href="{{route('clinics.edit',$clinic->id)}}"
                                   class="btn btn-outline-info btn-sm"><i class="fa fa-edit"></i></a>
                                @if($clinic->app_departments_count == 0)
                                    <a
                                        class="btn btn-outline-danger btn-sm"
                                        href="{{route('clinics.destroy',$clinic->id)}}"
                                        onclick="event.preventDefault();confirm('You\'r About to Delete This Clinic\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $clinic->id }}').submit() : false;"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <form
                                        action="{{route('clinics.destroy',$clinic->id)}}"
                                        id="delete-form-{{$clinic->id}}"
                                        method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @else
                                    <button
                                        disabled="true"
                                        title="There are related records for this Clinic and Cannot be Deleted"
                                        class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
@endsection
