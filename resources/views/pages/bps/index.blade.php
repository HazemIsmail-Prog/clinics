@extends('layouts.master')

@section('title','Bank Payments')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Bank Payments</h6>
            @can('BankPayments_Create')
                <div class="dropdown no-arrow">
                    <a class="btn btn-sm btn-outline-primary" href="{{route('bps.create')}}">New Bank Payment</a>
                </div>
            @endcan
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="row">
                <form action="{{route('bps.index')}}" id="search_form" class="my-2 form-inline w-100" method="get">
                    @method("GET")

                    <div class="col-md-6">
                        <input autocomplete="off" autofocus type="text" id="voucher_no"
                               class="form-control bg-light border-0 w-100" placeholder="Search ..."
                               name="voucher_no"
                               value="{{request()->input('voucher_no')}}">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
                        <a href="{{route('bps.index')}}" class="btn btn-primary btn-sm">Clear Filter</a>
                    </div>

                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th class="text-center">Voucher</th>
                        <th>Account - Narration</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Credit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vouchers as $voucher)
                        @foreach($voucher->voucher_details as $row)
                            <tr>
                                @if($loop->index ==0)
                                    <td rowspan="{{$voucher->voucher_details->count()}}" class="text-center">
                                        <div style="font-weight: bold">{{$voucher->voucher_no}}</div>
                                        <div style="font-size: 0.8rem">
                                            {{date('d-m-Y', strtotime($voucher->voucher_date))}}
                                            <span style="font-size: 0.6rem" class="badge badge-pill badge-dark">{{$voucher->creator->name ?? ''}}</span>
                                        </div>
                                        <div class="mt-1">
                                            @can('BankPayments_Read')

                                                <a href="{{route('bps.show',$voucher->id)}}"
                                                   class="btn btn-outline-success btn-sm" title="Print" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @endcan
                                            @can('BankPayments_Update')
                                                @if(date('Y', strtotime($voucher->voucher_date)) > auth()->user()->clinic->account_group->last_closed_year)
                                                    <a href="{{route('bps.edit',$voucher->id)}}"
                                                       class="btn btn-outline-info btn-sm" title="Edit"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                            @can('BankPayments_Delete')
                                                @if(date('Y', strtotime($voucher->voucher_date)) > auth()->user()->clinic->account_group->last_closed_year)
                                                    <a
                                                        class="btn btn-outline-danger btn-sm"
                                                        href="{{route('bps.destroy',$voucher->id)}}"
                                                        onclick="event.preventDefault();confirm('You\'r About to Delete This Voucher\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $voucher->id }}').submit() : false;"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </a>

                                                    <form
                                                        action="{{route('bps.destroy',$voucher->id)}}"
                                                        id="delete-form-{{$voucher->id}}"
                                                        method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                @endif
                                <td>
                                    <div style="font-weight: bold">{{$row->account->name}}</div>
                                    <div class="text-wrap small">{{ucwords(strtolower($row->narration))}}</div>
                                </td>
                                <td class="text-right">{{$row->debit == 0 ? '-' : number_format($row->debit,3)}}</td>
                                <td class="text-right">{{$row->credit == 0 ? '-' : number_format($row->credit,3)}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="7" class="bg-gray-300" style="height: 2px;"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$vouchers->links()}}
            </div>
        </div>
    </div>
@endsection
