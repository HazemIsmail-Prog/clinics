@extends('layouts.print_p')

@section('title','Profit & Loss')

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
                <td colspan="5" class="page-title">Profit & Loss</td>
            </tr>
            <tr>
                <td colspan="5" class="page-info">Report From {{date('d-m-Y',strtotime(request('start')))}}
                    to {{date('d-m-Y',strtotime(request('end')))}}</td>
            </tr>

            </thead>
            <tbody>
            <div style="display: none">
                {{$total_income = 0}}
                {{$total_expenses = 0}}
            </div>

            <tr class="group-header"><td colspan="5" style="font-size: 20px;text-decoration: none">Income</td></tr>
            @foreach($income_groups as $group)
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
                        <td class="text-right">{{ number_format($account->total,3) }}</td>
                    </tr>
                    @endif
                @endforeach
                <tr class="group-total">
                    <td colspan="4" class="text-right">Group Total</td>
                    <td class="text-right">{{number_format($group->childAccounts->sum('total'),3)}}</td>
                </tr>
                <div style="display: none">
                    {{$total_income +=  $group->childAccounts->sum('total')}}
                </div>
            @endforeach
            <tr class="group-total">
                <td colspan="4" class="text-right">Income Total</td>
                <td class="text-right">{{number_format($total_income,3)}}</td>
            </tr>


            <tr class="group-header"><td colspan="5" style="font-size: 20px;text-decoration: none">Expenses</td></tr>
            @foreach($expenses_groups as $group)
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
                            <td class="text-right">{{ number_format($account->total,3) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr class="group-total">
                    <td colspan="4" class="text-right">Group Total</td>
                    <td class="text-right">{{number_format($group->childAccounts->sum('total'),3)}}</td>
                </tr>
                <div style="display: none">
                    {{$total_expenses +=  $group->childAccounts->sum('total')}}
                </div>
            @endforeach
            <tr class="group-total">
                <td colspan="4" class="text-right">Expenses Total</td>
                <td class="text-right">{{number_format($total_expenses,3)}}</td>
            </tr>

            <tr class="group-total">
                <td colspan="4" class="text-right">Net Profit for the Year</td>
                <td class="text-right">{{number_format($total_income - $total_expenses,3)}}</td>
            </tr>

            </tbody>
        </table>
    </div>




@endsection
