@extends('layouts.print_p')

@section('title','Balance Sheet')

@section('styles')
    <style>
        @page {
            size: letter portrait;
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
                <td colspan="5" class="clinic_name">{{auth()->user()->clinic->name}}</td>
            </tr>
            <tr>
                <td colspan="5" class="page-title">Balance Sheet</td>
            </tr>
            <tr>
                <td colspan="5" class="page-info">Report From {{date('d-m-Y',strtotime(request('start')))}}
                    to {{date('d-m-Y',strtotime(request('end')))}}</td>
            </tr>

            </thead>
            <tbody>
            <div style="display: none">
                {{$total_assets = 0}}
                {{$total_liabilities = 0}}
                {{$total_equity = 0}}
            </div>

            <tr class="group-header"><td colspan="5" style="font-size: 20px;text-decoration: none">Assets</td></tr>
            @foreach($assets_groups as $group)
                <tr class="group-header">
                    <td></td>
                    <td colspan="4" class="text-left">{{$group->name}}</td>
                </tr>
                @foreach($group->childAccounts as $account)
                    @if($account->total != 0)
                    <tr class="group-details">
                        <td></td>
                        <td></td>
                        <td colspan="2">{{$account->name}}</td>
                        <td class="text-right">{{$account->total < 0 ? '('. number_format(abs($account->total),3).")" : number_format($account->total,3) }}</td>
                    </tr>
                    @endif
                @endforeach
                <tr class="group-total">
                    <td colspan="4" class="text-right">Group Total</td>
                    <td class="text-right">{{$group->childAccounts->sum('total') < 0 ? '(' .number_format(abs($group->childAccounts->sum('total')),3).')': number_format($group->childAccounts->sum('total'),3) }}</td>
                </tr>
                <div style="display: none">
                    {{$total_assets +=  $group->childAccounts->sum('total')}}
                </div>
            @endforeach
            <tr class="group-total">
                <td colspan="4" class="text-right">Assets Total</td>
                <td class="text-right">{{number_format($total_assets,3)}}</td>
            </tr>


            <tr class="group-header"><td colspan="5" style="font-size: 20px;text-decoration: none">Liabilities</td></tr>
            @foreach($liabilities_groups as $group)
                <tr class="group-header">
                    <td></td>
                    <td colspan="4" class="text-left">{{$group->name}}</td>
                </tr>
                @foreach($group->childAccounts as $account)
                    @if($account->total != 0)
                        <tr class="group-details">
                            <td></td>
                            <td></td>
                            <td colspan="2">{{$account->name}}</td>
                            <td class="text-right">{{$account->total < 0 ? '('. number_format(abs($account->total),3).")" : number_format($account->total,3) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr class="group-total">
                    <td colspan="4" class="text-right">Group Total</td>
                    <td class="text-right">{{$group->childAccounts->sum('total') < 0 ? '(' .number_format(abs($group->childAccounts->sum('total')),3).')': number_format($group->childAccounts->sum('total'),3) }}</td>
                </tr>
                <div style="display: none">
                    {{$total_liabilities +=  $group->childAccounts->sum('total')}}
                </div>
            @endforeach
            <tr class="group-total">
                <td colspan="4" class="text-right">Liabilities Total</td>
                <td class="text-right">{{number_format($total_liabilities,3)}}</td>
            </tr>


            <tr class="group-header"><td colspan="5" style="font-size: 20px;text-decoration: none">Equity</td></tr>
            @foreach($equity_group as $group)
                <tr class="group-header">
                    <td></td>
                    <td colspan="4" class="text-left">{{$group->name}}</td>
                </tr>
                @foreach($group->childAccounts as $account)
                    @if($account->total != 0)
                        <tr class="group-details">
                            <td></td>
                            <td></td>
                            <td colspan="2">{{$account->name}}</td>
                            <td class="text-right">{{$account->total < 0 ? '('. number_format(abs($account->total),3).")" : number_format($account->total,3) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr class="group-total">
                    <td colspan="4" class="text-right">Group Total</td>
                    <td class="text-right">{{$group->childAccounts->sum('total') < 0 ? '(' .number_format(abs($group->childAccounts->sum('total')),3).')': number_format($group->childAccounts->sum('total'),3) }}</td>
                </tr>
                <div style="display: none">
                    {{$total_equity +=  $group->childAccounts->sum('total')}}
                </div>
            @endforeach
            <tr class="group-total">
                <td colspan="4" class="text-right">Equity Total</td>
                <td class="text-right">{{$total_equity < 0 ? '('. number_format(abs($total_equity),3).")" : number_format($total_equity,3) }}</td>
            </tr>

            <tr class="group-header">
                <td></td>
                <td colspan="4" class="text-left">Profit & Loss</td>
            </tr>

            <tr class="group-details">
                <td></td>
                <td></td>
                <td colspan="2">Net Profit</td>
                <td class="text-right">{{ number_format($profit,3) }}</td>
            </tr>

            <tr class="group-total">
                <td colspan="4" class="text-right">Group Total</td>
                <td class="text-right">{{number_format($profit,3)}}</td>
            </tr>

            <tr class="group-total">
                <td colspan="4" class="text-right">Net Account Group Total</td>
                <td class="text-right">{{$total_equity + $profit < 0 ? '('. number_format(abs($total_equity + $profit),3).")" : number_format($total_equity + $profit,3) }}</td>
            </tr>

            <tr class="group-total">
                <td colspan="4" class="text-right">Net Total</td>
                <td class="text-right">{{number_format($total_equity + $profit + $total_liabilities,3)}}</td>
            </tr>


            </tbody>
        </table>
    </div>

@endsection
