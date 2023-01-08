@extends('layouts.master')

@section('links')
    {{--    <link rel="stylesheet" href="{{asset('assets\custom\css\responsive-table.css')}}">--}}
@endsection

@section('title','Appointment Departments')

@section('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
        }

        #clear_filter{
            position: absolute;
            right: 10px;
        }

    </style>
@endsection

@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Appointment Departments
            </h6>
            <div class="dropdown no-arrow">
                <a class="btn btn-sm btn-outline-primary" href="{{route('app_departments.create')}}">New Appointment Department</a>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Devices</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($app_departments->count()>0)
                        @foreach($app_departments as $app_department)
                            <tr>
                                <td>{{$app_department->name}}</td>
                                <td class="text-center">
                                    @if($app_department->active == 1)
                                        <span class="badge badge-success badge-pill">Active</span>
                                    @else
                                        <span class="badge badge-danger badge-pill">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($app_department->app_devices_count>0)
                                        <a href="" class="btn btn-outline-info btn-sm py-0" title="Appointments Devices">
                                            {{$app_department->app_devices_count}}
                                        </a>
                                    @endif
                                </td>



                                <td class="text-center">

                                    <a href="{{route('app_departments.edit',$app_department->id)}}" class="btn btn-outline-info btn-sm" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                        @if($app_department->app_devices_count == 0)
                                            <a
                                                class="btn btn-outline-danger btn-sm"
                                                href="{{route('app_departments.destroy',$app_department->id)}}"
                                                onclick="event.preventDefault();confirm('You\'r About to Delete This Department\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $app_department->id }}').submit() : false;"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <form
                                                action="{{route('app_departments.destroy',$app_department->id)}}"
                                                id="delete-form-{{$app_department->id}}"
                                                method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <button
                                                disabled="true"
                                                title="There are related records for this Department and Cannot be Deleted"
                                                class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="{{auth()->user()->id ==1 ? '4' : '3' }}">
                                No Records Found
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr class="bg-primary text-white">
                        <td class="text-center" colspan="4">
                            Showing {{($app_departments->currentPage()-1)* $app_departments->perPage()+($app_departments->total() ? 1:0)}}
                            to {{($app_departments->currentPage()-1)*$app_departments->perPage()+count($app_departments)}}
                            of {{$app_departments->total()}} Results
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row overflow-auto">
                <div class="col-12">
                    {{$app_departments->appends(request()->input())->onEachSide(1)->links()}}
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            var input = $("#search");
            var len = input.val().length;
            input[0].focus();
            input[0].setSelectionRange(len, len);

            $('#search').on('change', function () {
                $('#search_form').submit();
            });

            $('#search').on('input', function () {
                $('#clear_filter').removeClass('d-none');
            });

        });
    </script>

@endsection
