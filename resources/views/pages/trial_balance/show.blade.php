@extends('layouts.print_p')

@section('title','Trial Balance')

@section('styles')
    <style>
        @page {
            size: letter landscape
        }

        table thead td,
        table tbody td{
            border: 0 !important;
        }

        .table-header td {
            font-size: 13px;
            font-weight: bold;
            border-top: 1px solid #000 !important;
            border-bottom: 1px solid #000 !important;
            text-align: center;
        }

        .group-header td {
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
        }

        .group-details td {
            font-size: 13px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .group-total td {
            font-size: 13px;
            font-weight: bold;
            border-top: 1px solid #000 !important;
        }
    </style>
@endsection

@section('content')

    <div class="container">

        <table>
            <thead>
            <tr>
                <td colspan="7" class="clinic_name">{{auth()->user()->clinic->name}}</td>
            </tr>
            <tr>
                <td colspan="7" class="page-title">Trial Balance</td>
            </tr>
            <tr>
                <td colspan="7" class="page-info">Report From {{date('d-m-Y',strtotime(request('start')))}} to {{date('d-m-Y',strtotime(request('end')))}}</td>
            </tr>
            <tr class="table-header">
                <td rowspan="2">Account Ledger Name</td>
                <td colspan="2">Opening Amount</td>
                <td colspan="2">Transaction Amount</td>
                <td colspan="2">Closing Amount</td>
            </tr>
            <tr class="table-header">
                <td>Debit</td>
                <td>Credit</td>
                <td>Debit</td>
                <td>Credit</td>
                <td>Debit</td>
                <td>Credit</td>
            </tr>
            </thead>
            <tbody>

            <div style="display: none">
                {{$total_opening_debit = 0}}
                {{$total_opening_credit = 0}}
                {{$total_transaction_debit = 0}}
                {{$total_transaction_credit = 0}}
                {{$total_closing_debit = 0}}
                {{$total_closing_credit = 0}}
            </div>

            @foreach($groups as $group)
                <tr class="group-header">
                    <td colspan="7">{{$group->name}}</td>
                </tr>

                <div style="display: none">
                    {{$group_opening_debit = 0}}
                    {{$group_opening_credit = 0}}
                    {{$group_transaction_debit = 0}}
                    {{$group_transaction_credit = 0}}
                    {{$group_closing_debit = 0}}
                    {{$group_closing_credit = 0}}
                </div>
                @foreach($group->childAccounts as $account)
                    @if($account->opening_debit != $account->opening_credit || $account->transaction_debit > 0 || $account->transaction_credit > 0)
                        <tr class="group-details">
                            <td>{{$account->name}}</td>
                            <td class="text-right">{{$account->opening_debit > $account->opening_credit ? number_format($account->opening_debit - $account->opening_credit,3) : '-' }}</td>
                            <td class="text-right">{{$account->opening_credit > $account->opening_debit ? number_format($account->opening_credit - $account->opening_debit,3) : '-' }}</td>
                            <td class="text-right">{{$account->transaction_debit ? $account->transaction_debit ==0 ? '-' : number_format($account->transaction_debit,3) : '-'}}</td>
                            <td class="text-right">{{$account->transaction_credit ? $account->transaction_credit == 0 ? '-' : number_format($account->transaction_credit,3) : '-'}}</td>
                            <td class="text-right">{{$account->opening_debit + $account->transaction_debit > $account->opening_credit + $account->transaction_credit ? number_format($account->opening_debit + $account->transaction_debit - $account->opening_credit - $account->transaction_credit,3) : '-'}}</td>
                            <td class="text-right">{{$account->opening_credit + $account->transaction_credit > $account->opening_debit + $account->transaction_debit ? number_format($account->opening_credit + $account->transaction_credit - $account->opening_debit - $account->transaction_debit,3) : '-'}}</td>

                            <div style="display: none">
                                {{$account->opening_debit > $account->opening_credit ? $group_opening_debit += $account->opening_debit - $account->opening_credit : '' }}
                                {{$account->opening_credit > $account->opening_debit ? $group_opening_credit += $account->opening_credit - $account->opening_debit : '' }}
                                {{$group_transaction_debit += $account->transaction_debit}}
                                {{$group_transaction_credit += $account->transaction_credit}}
                                {{$account->opening_debit + $account->transaction_debit > $account->opening_credit + $account->transaction_credit ? $group_closing_debit += $account->opening_debit + $account->transaction_debit - $account->opening_credit - $account->transaction_credit    : ''}}
                                {{$account->opening_credit + $account->transaction_credit > $account->opening_debit + $account->transaction_debit ? $group_closing_credit += $account->opening_credit + $account->transaction_credit - $account->opening_debit - $account->transaction_debit : ''}}
                            </div>
                        </tr>
                    @endif
                @endforeach
                <tr class="group-total">
                    <td class="text-right">Group Total</td>
                    <td class="text-right">{{$group_opening_debit == 0 ? '-' : number_format($group_opening_debit,3)}}</td>
                    <td class="text-right">{{$group_opening_credit == 0 ? '-' : number_format($group_opening_credit,3)}}</td>
                    <td class="text-right">{{$group_transaction_debit == 0 ? '-' : number_format($group_transaction_debit,3)}}</td>
                    <td class="text-right">{{$group_transaction_credit == 0 ? '-' : number_format($group_transaction_credit,3)}}</td>
                    <td class="text-right">{{$group_closing_debit == 0 ? '-' : number_format($group_closing_debit,3)}}</td>
                    <td class="text-right">{{$group_closing_credit == 0 ? '-' : number_format($group_closing_credit,3)}}</td>
                    <div style="display: none">
                        {{$total_opening_debit += $group_opening_debit}}
                        {{$total_opening_credit += $group_opening_credit}}
                        {{$total_transaction_debit += $group_transaction_debit}}
                        {{$total_transaction_credit += $group_transaction_credit}}
                        {{$total_closing_debit += $group_closing_debit}}
                        {{$total_closing_credit += $group_closing_credit}}
                    </div>
                </tr>
            @endforeach
            <tr class="group-total">
                <td class="text-right">Grand Total</td>
                <td class="text-right">{{$total_opening_debit == 0 ? '-' : number_format($total_opening_debit,3)}}</td>
                <td class="text-right">{{$total_opening_credit == 0 ? '-' : number_format($total_opening_credit,3)}}</td>
                <td class="text-right">{{$total_transaction_debit == 0 ? '-' : number_format($total_transaction_debit,3)}}</td>
                <td class="text-right">{{$total_transaction_credit == 0 ? '-' : number_format($total_transaction_credit,3)}}</td>
                <td class="text-right">{{$total_closing_debit == 0 ? '-' : number_format($total_closing_debit,3)}}</td>
                <td class="text-right">{{$total_closing_credit == 0 ? '-' : number_format($total_closing_credit,3)}}</td>
            </tr>
            </tbody>
        </table>
    </div>




@endsection
