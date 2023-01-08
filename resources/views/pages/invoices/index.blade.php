@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Invoices')

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
            <h6 class="m-0 font-weight-bold text-primary">
                Invoices {{request('patient_id') ? 'for '.\App\Models\Patient::whereId(request('patient_id'))->first()->file_no .' - '. \App\Models\Patient::whereId(request('patient_id'))->first()->name : ''}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">


            <form action="{{route('invoices.index')}}" id="search_form" class="my-2 form-inline" method="get">
                @method("GET")
                <div class="table-responsive">
                    <table class="table table-sm table-borderless table-hover table-striped">
                        <thead>
                        <tr class="noprint">
                            <td>
                                <input autocomplete="off" type="text" id="invoice_no"
                                       class="form-control bg-light border-0 w-100" placeholder="Inv # ..."
                                       name="invoice_no"
                                       value="{{request()->input('invoice_no')}}">
                            </td>


                            <td>
                                <input autocomplete="off" type="date" id="created_at"
                                       class="form-control bg-light border-0 w-100" placeholder="Search ..."
                                       name="created_at"
                                       value="{{request()->input('created_at')}}">
                            </td>
                            <td colspan="2">
                                <input autocomplete="off" type="text" id="patient_file_no"
                                       class="form-control bg-light border-0 w-100" placeholder="File # ..."
                                       name="patient_file_no"
                                       value="{{request()->input('patient_file_no')}}">
                            </td>
                            <td colspan="3">
                                <select class="form-control bg-light border-0 w-100" name="nurse_id" id="nurse_id">
                                    <option value="">--nurse--</option>
                                    @foreach($nurses as $nurse)
                                        <option
                                            {{request()->input('nurse_id') == $nurse->id ? 'selected' : ''}} value="{{$nurse->id}}">{{$nurse->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td colspan="3">
                                <select class="form-control bg-light border-0 w-100" name="user_id" id="user_id">
                                    <option value="">--user--</option>
                                    @foreach($users as $user)
                                        <option
                                            {{request()->input('user_id') == $user->id ? 'selected' : ''}} value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                                <a href="{{route('invoices.index')}}" class="btn btn-primary btn-sm">Clear Filter</a>
                            </td>
                        </tr>
                        </thead>
                    </table>
                    <table class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Patient Name</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Cash</th>
                            <th class="text-right">K-Net</th>
                            <th class="text-right">VISA</th>
                            <th class="text-right">Master</th>
                            <th class="text-right">Link</th>
                            <th class="text-right">Balance</th>
                            <th>Nurse</th>
{{--                            <th>Notes</th>--}}
                            <th class="text-center noprint">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>
                                    <div>
                                        {{$invoice->invoice_no}}
                                        @if($invoice->type == 'Balance Invoice')
                                            <a
                                                style="font-size: 0.6rem"
                                                class="badge badge-pill badge-success"
                                                href="{{route('invoices.index',['invoice_no' => \App\Models\Invoice::loggedClinic()->findOrFail($invoice->ref)->invoice_no])}}"
                                            >
                                                B-Inv
                                            </a>
                                        @endif
                                        @if($invoice->type == 'Insurance Invoice')
                                            <span style="font-size: 0.6rem" class="badge badge-pill badge-danger">Insurance</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.6rem" class="badge badge-pill badge-dark">{{$invoice->user->name}}</div>
                                </td>
                                <td>
                                    <div>{{date('d-m-Y', strtotime($invoice->created_at))}}</div>
                                    <div style="font-size: 0.6rem" class="text-muted">{{date('h:i a', strtotime($invoice->created_at))}}</div>
                                </td>
                                <td>
                                    <div style="font-size: 0.8rem;font-weight: bold">{{$invoice->patient->file_no}}</div>
                                    <div>{{$invoice->patient->name}}</div>
                                    <div style="font-size: 0.6rem" class="text-danger">{{$invoice->notes}}</div>
                                </td>
                                <td class="text-right">{{$invoice->total > 0 ? number_format($invoice->total, 3) : '-'}}</td>
                                <td class="text-right">{{$invoice->cash > 0 ? number_format($invoice->cash, 3) : '-'}}</td>
                                <td class="text-right">{{$invoice->knet > 0 ? number_format($invoice->knet, 3) : '-'}}</td>
                                <td class="text-right">{{$invoice->visa > 0 ? number_format($invoice->visa, 3) : '-'}}</td>
                                <td class="text-right">{{$invoice->master > 0 ? number_format($invoice->master, 3) : '-'}}</td>
                                <td class="text-right">
                                    <div>
                                        {{$invoice->knet_link + $invoice->credit_link > 0 ? number_format($invoice->knet_link + $invoice->credit_link, 3) : '-'}}
                                    </div>
                                    <div>
                                        @if ($invoice->knet_link > 0)
                                            <span style="font-size: 0.6rem" class="badge badge-pill badge-success">Knet</span>
                                        @endif
                                        @if ($invoice->credit_link > 0)
                                            <span style="font-size: 0.6rem" class="badge badge-pill badge-warning">Credit Card</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-right text-danger">{{$invoice->balance > 0 ? number_format($invoice->balance, 3) : '-'}}</td>
                                <td>{{$invoice->nurse_id ? $invoice->nurse->name : '-'}}</td>
{{--                                <td class="text-wrap small">{{$invoice->notes}}</td>--}}

                                <td class="text-center noprint">
                                    @can('Invoices_Read')
                                        <a
                                            href="{{route('invoices.show',$invoice->id)}}"
                                            class="btn btn-outline-secondary btn-sm"
                                            target="_blank"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endcan
                                    @if(\App\Models\Invoice::where('ref',$invoice->id)->count() == 0)
                                        @if($invoice->type == 'Balance Invoice')
                                            @can('Balances_Update')
                                                @if($invoice->created_at->year > auth()->user()->clinic->account_group->last_closed_year)
                                                    <a
                                                        href="{{route('balances.edit',$invoice->id)}}"
                                                        class="btn btn-outline-info btn-sm" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        @else
                                            @can('Invoices_Update')
                                                @if($invoice->created_at->year > auth()->user()->clinic->account_group->last_closed_year)
                                                    <a
                                                        href="{{route('invoices.edit',$invoice->id)}}"
                                                        class="btn btn-outline-info btn-sm" title="Edit"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        @endif
                                    @endif
                                    {{--                                    <a--}}
                                    {{--                                        class="btn btn-outline-danger btn-sm"--}}
                                    {{--                                        href="{{route('invoices.destroy',$invoice->id)}}"--}}
                                    {{--                                        onclick="event.preventDefault();confirm('You\'r About to Delete This Invoice\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $invoice->id }}').submit() : false;"--}}
                                    {{--                                    >--}}
                                    {{--                                        <i class="fa fa-trash"></i>--}}
                                    {{--                                    </a>--}}
                                    {{--                                    <form--}}
                                    {{--                                        action="{{route('invoices.destroy',$invoice->id)}}"--}}
                                    {{--                                        id="delete-form-{{$invoice->id}}"--}}
                                    {{--                                        method="POST"--}}
                                    {{--                                        style="display: none;">--}}
                                    {{--                                        @csrf--}}
                                    {{--                                        @method('DELETE')--}}
                                    {{--                                    </form>--}}

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="noprint">
                        <tr class="bg-primary text-white">
                            <td class="text-center" colspan="14">
                                Showing {{($invoices->currentPage()-1)* $invoices->perPage()+($invoices->total() ? 1:0)}}
                                to {{($invoices->currentPage()-1)*$invoices->perPage()+count($invoices)}}
                                of {{$invoices->total()}} Results
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row overflow-auto noprint">
                    <div class="col-12">
                        {{$invoices->appends(request()->input())->onEachSide(1)->links()}}
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
