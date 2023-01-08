@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Appointments Search')

@section('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
        }

        #clear_filter {
            position: absolute;
            right: 10px;
        }

        @media print {

            @page {
                margin: 0mm;
                size: landscape
            }

            body {
                margin: 10mm 0mm;
            }
        }

    </style>
@endsection

@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Appointments Search</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <form action="{{route('appointments.search')}}" id="search_form" class="my-2 form-inline" method="get">
                @method("GET")
                <div class="table-responsive">
                    <table class="table table-sm table-borderless table-striped">
                        <thead>
                        <tr class="noprint">
                            <td colspan="2">
                                <input autocomplete="off" type="text" id="patient_file_no"
                                       class="form-control bg-light border-0 w-100" placeholder="File no..."
                                       name="patient_file_no"
                                       value="{{request()->input('patient_file_no')}}">
                            </td>
                            <td colspan="4">
                                <input autocomplete="off" type="text" id="key"
                                       class="form-control bg-light border-0 w-100" placeholder="Search ..."
                                       name="key"
                                       value="{{request()->input('key')}}">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                                <a href="{{route('appointments.search')}}" class="btn btn-primary btn-sm">Clear Filter</a>
                            </td>
                        </tr>
                        <tr class="bg-primary text-white">
                            <th class="text-left">Date</th>
                            <th class="text-left">Start</th>
                            <th class="text-left">End</th>
                            <th class="text-left">File No.</th>
                            <th class="text-left">Patient Name</th>
                            <th class="text-left noprint">Mobile</th>
                            <th class="text-left">Civil ID</th>
                            <th class="text-left">Device</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Nurse</th>
                            <th class="text-left">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>
                                    <a
                                        href="{{route('appointments.index',['date'=>$appointment->date,'app_department'=>$appointment->app_device->app_department])}}"
                                    >
                                        {{date('d-m-Y',strtotime($appointment->date))}}
                                    </a>
                                </td>
                                <td>{{date('H:i',strtotime($appointment->start))}}</td>
                                <td>{{date('H:i',strtotime($appointment->end))}}</td>
                                <td>{{$appointment->patient_file_no}}</td>
                                <td class="text-wrap">{{$appointment->name}}</td>
                                <td class="noprint">{{$appointment->mobile}}</td>
                                <td>{{$appointment->civil_id}}</td>
                                <td>{{$appointment->app_device->name}}</td>
                                <td>{{$appointment->status->name}}</td>
                                <td>{{$appointment->nurse_id ? $appointment->nurse->name:'-'}}</td>
                                <td class="text-wrap">{{$appointment->notes}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="bg-primary text-white">
                            <td class="text-center" colspan="11">
                                Showing {{($appointments->currentPage()-1)* $appointments->perPage()+($appointments->total() ? 1:0)}}
                                to {{($appointments->currentPage()-1)*$appointments->perPage()+count($appointments)}}
                                of {{$appointments->total()}} Results
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row overflow-auto noprint">
                    <div class="col-12">
                        {{$appointments->appends(request()->input())->onEachSide(1)->links()}}
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
