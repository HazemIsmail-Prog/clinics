@extends('layouts.master')

@section('title','Patients')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
        }

        #clear_filter {
            position: absolute;
            right: 10px;
        }


        table .btn {
            /*padding: 0 5px;*/
        }

    </style>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Patients</h6>
            @can('Patients_Create')
                <div class="dropdown no-arrow">
                    <a class="btn btn-outline-primary" href="{{route('patients.create')}}">New Patient</a>
                </div>
            @endcan
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <form action="{{route('patients.index')}}" id="search_form" class="w-100" method="get">
                <div class="row mb-2">

                    @method("GET")
                    <div class="col-md-2">
                        <input autocomplete="off" type="text" id="patient_file_no"
                               class="form-control bg-light border-0 w-100" placeholder="File no..."
                               name="patient_file_no"
                               value="{{request()->input('patient_file_no')}}">
                    </div>
                    <div class="col-md-4">
                        <input autocomplete="off" type="text" id="key"
                               class="form-control bg-light border-0 w-100" placeholder="Search ..."
                               name="key"
                               value="{{request()->input('key')}}">
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                        <a href="{{route('patients.index')}}" class="btn btn-primary btn-sm">Clear Filter</a>
                    </div>
                </div>

            </form>


            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th class="text-center">File #</th>
                        <th>Name</th>
                        <th class="text-center">Mobile</th>
                        <th class="text-center">Civil ID</th>

                        @can('Appointments_Read')
                            <th class="text-center">Appointments</th>
                        @endcan

                        @can('Invoices_Read')
                            <th class="text-center">Bills</th>
                        @endcan

                        @can('Balances_Read')
                            <th class="text-center">Balance</th>
                        @endcan

                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($patients->count()>0)
                        @foreach($patients as $patient)
                            <tr>
                                <td class="text-center">{{$patient->file_no}}</td>
                                <td>
                                    <div>
                                        {{$patient->name}}
                                        <span class="badge badge-pill badge-danger">{{$patient->status}}</span>
                                    </div>
                                    @if($patient->created_at)
                                        <div style="font-size: 0.6rem;">{{date('d-m-Y',strtotime($patient->created_at))}}</div>
                                    @endif
                                </td>
                                <td class="text-center">{{$patient->mobile}}</td>
                                <td class="text-center">{{$patient->civil_id}}</td>

                                @can('Appointments_Read')
                                    <td class="text-center">
                                        @if($patient->appointments_count>0)
                                            <a href="{{route('appointments.search',['patient_file_no' => $patient->file_no])}}"
                                               class="btn btn-outline-info btn-sm py-0" title="Appointments History"
                                            >
                                                {{$patient->appointments_count}}
                                            </a>
                                        @endif
                                    </td>
                                @endcan
                                @can('Invoices_Read')
                                    <td class="text-center">
                                        @if($patient->invoices_count>0)
                                            <a href="{{route('invoices.index',['patient_file_no'=>$patient->file_no])}}"
                                               class="btn btn-outline-info btn-sm py-0" title="Billing History"
                                            >
                                                {{$patient->invoices_count}}
                                            </a>
                                        @endif
                                    </td>
                                @endcan
                                @can('Balances_Read')
                                    <td class="text-center">
                                        @if($patient->balances_count>0)
                                            <a href="{{route('balances.index',['patient_id'=>$patient->id])}}"
                                               class="btn btn-danger btn-sm blink_me py-0" title="Balance History"
                                            >
                                                {{$patient->balances->sum('amount')}} K.D
                                            </a>
                                        @endif
                                    </td>
                                @endcan
                                <td class="text-center">
                                    @can("Invoices_Create")
                                        <a href="{{route('invoices.create',$patient->id)}}"
                                           class="btn btn-outline-success btn-sm" title="New Invoice">
                                            <i class="fa fa-plus-circle"></i>
                                        </a>
                                    @endcan
                                    @can('Patients_Read')
                                        <a href="{{route('patients.show',$patient->id)}}"
                                           class="btn btn-outline-dark btn-sm" title="Patient Sheet" target="_blank">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('Patients_Update')
                                        <a href="{{route('patients.edit',$patient->id)}}"
                                           class="btn btn-outline-info btn-sm" title="Edit Patient Data">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('Patients_Delete')
                                        <a
                                            class="btn btn-outline-danger btn-sm"
                                            href="{{route('patients.destroy',$patient->id)}}"
                                            onclick="event.preventDefault();confirm('You\'r About to Delete This Patient\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $patient->id }}').submit() : false;"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form
                                            action="{{route('patients.destroy',$patient->id)}}"
                                            id="delete-form-{{$patient->id}}"
                                            method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="{{auth()->user()->id ==1 ? '9' : '8' }}">
                                No Records Found
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr class="bg-primary text-white">
                        <td class="text-center" colspan="{{auth()->user()->id ==1 ? '9' : '8' }}">
                            Showing {{($patients->currentPage()-1)* $patients->perPage()+($patients->total() ? 1:0)}}
                            to {{($patients->currentPage()-1)*$patients->perPage()+count($patients)}}
                            of {{$patients->total()}} Results
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
            {{$patients->appends(request()->input())->onEachSide(1)->links()}}


        </div>
    </div>
@endsection
