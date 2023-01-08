@extends('layouts.master')

@section('title','Balances')

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
                Balances {{request('patient_id') ? 'for '.\App\Models\Patient::whereId(request('patient_id'))->first()->file_no .' - '. \App\Models\Patient::whereId(request('patient_id'))->first()->name : ''}}</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>Invoice No.</th>
                        <th>Date</th>
                        <th>File No.</th>
                        <th>Patient Name</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center noprint">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($balances as $balance)
                        <tr>
                            <td>
                                @can('Invoices_Read')
                                    <a href="{{route('invoices.show',$balance->invoice_id)}}"
                                       target="_blank">{{\App\Models\Invoice::loggedClinic()->findOrFail($balance->invoice_id)->invoice_no }}</a>
                                @else
                                    {{\App\Models\Invoice::loggedClinic()->findOrFail($balance->invoice_id)->invoice_no }}
                                @endcan
                            </td>
                            <td>{{date('d-m-Y', strtotime(\App\Models\Invoice::loggedClinic()->findOrFail($balance->invoice_id)->created_at))}}</td>
                            <td>{{$balance->patient->file_no}}</td>
                            <td>{{$balance->patient->name}}</td>
                            <td class="text-right">{{$balance->amount > 0 ? number_format($balance->amount, 3) : '-'}}</td>
                            <td class="text-center noprint">
                                @can('Balances_Create')
                                    <a
                                        href="{{route('balances.create',$balance->invoice_id)}}"
                                        class="btn text-primary btn-sm"
                                    >
                                        Pay
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="noprint">
                    <tr class="bg-primary text-white">
                        <td class="text-center" colspan="14">
                            Showing {{($balances->currentPage()-1)* $balances->perPage()+($balances->total() ? 1:0)}}
                            to {{($balances->currentPage()-1)*$balances->perPage()+count($balances)}}
                            of {{$balances->total()}} Results
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row overflow-auto noprint">
                <div class="col-12">
                    {{$balances->appends(request()->input())->onEachSide(1)->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
