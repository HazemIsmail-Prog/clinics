@extends('layouts.print_p')

@section('title','Account Statement')

@section('styles')
    <style>
        @page {
            size: letter portrait;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        @foreach($accounts as $account)
            <table>
                <thead>
                <tr>
                    <td colspan="7" class="clinic_name">{{auth()->user()->clinic->name}}</td>
                </tr>
                <tr>
                    <td colspan="7" class="page-title">Account Statment</td>
                </tr>
                <tr>
                    <td colspan="7" class="page-info">Report From {{date('d-m-Y',strtotime(request('start')))}}
                        to {{date('d-m-Y',strtotime(request('end')))}}</td>
                </tr>
                <tr>
                    <td colspan="7" class="account_name">
                        Account Name : {{$account->name}}
                    </td>
                </tr>
                <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Trn. No.</th>
                    <th class="text-center">Type</th>
                    <th class="text-left">Narration</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Credit</th>
                    <th class="text-right">Balance</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right bordered">Opening Balance :</th>
                    <th class="text-right bordered">
                        {{$account->type == 'Debit' ? number_format($account->opening_debit - $account->opening_credit,3) : '-'}}
                    </th>
                    <th class="text-right bordered">
                        {{$account->type == 'Credit' ? number_format($account->opening_credit - $account->opening_debit,3) : '-'}}
                    </th>
                    <th class="bordered"></th>
                </tr>
                </thead>
                <tbody>
                <div class="d-none">
                    {{$account->type == 'Debit' ? $balance = $account->opening_debit - $account->opening_credit : $balance = $account->opening_credit - $account->opening_debit}}
                </div>


                @foreach($account->voucher_details as $row)

                    <tr>
                        <td nowrap class="text-center">{{date('d-m-Y',strtotime($row->voucher->voucher_date))}}</td>
                        <td class="text-center">{{$row->voucher->voucher_no}}</td>
                        <td class="text-center">{{strtoupper($row->voucher->voucher_type)}}</td>
                        <td class="text-left">{{$row->narration}}</td>
                        <td class="text-right">{{$row->debit == 0 ? '-' : number_format($row->debit,3)}}</td>
                        <td class="text-right">{{$row->credit == 0 ? '-' : number_format($row->credit,3)}}</td>
                        <div
                            class="d-none">{{$account->type == 'Debit' ? $balance += $row->debit - $row->credit : $balance += $row->credit - $row->debit}}</div>
                        <td class="text-right {{$balance<0 ? 'text-danger' : ''}}">{{number_format(abs($balance),3)}}</td>

                    </tr>

                @endforeach


                </tbody>
            </table>
            <div style="page-break-after: always"></div>

        @endforeach

    </div>




@endsection
