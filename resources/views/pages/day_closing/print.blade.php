@extends('layouts.print_p')

<title>Day Closing</title>

@section('styles')
    <style>
        @page {
            size: letter portrait;
        }

        table td,
        table th{
            font-size: 1rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <table>
            <thead>
            <tr>
                <td colspan="2" class="clinic_name">{{auth()->user()->clinic->name}}</td>
            </tr>
            <tr>
                <td colspan="2" class="page-title">Day Closing</td>
            </tr>

            <tr>
                <td colspan="2" class="divider"></td>
            </tr>

            <tr>
                <td class="account_name" style="border-right: 0">
                    User : {{auth()->user()->name}}
                    <br>
                    Date : {{date('d-m-Y',strtotime($date)) }}
                </td>
                <td class="account_name" style="border-left: 0">
                    <div style="text-align: right">
                        <a class="btn noprint" href="#" onclick="event.preventDefault();window.print();">Print</a>
                        <a class="btn noprint" href="#" onclick="event.preventDefault();window.close();">Close</a>
                    </div>
                </td>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td colspan="2" class="divider"></td>
            </tr>

            <tr>
                <th>Mode of Payment</th>
                <th class="text-right">Amount</th>
            </tr>
            <tr>
                <td class="text-center">Cash</td>
                <td class="text-right">{{number_format($invoices->sum('cash'),3)}}</td>
            </tr>
            <tr>
                <td class="text-center">K-Net</td>
                <td class="text-right">{{number_format($invoices->sum('knet'),3)}}</td>
            </tr>

            <tr>
                <td class="text-center">VISA</td>
                <td class="text-right">{{number_format($invoices->sum('visa'),3)}}</td>
            </tr>
            <tr>
                <td class="text-center">Master Card</td>
                <td class="text-right">{{number_format($invoices->sum('master'),3)}}</td>
            </tr>
            <tr>
                <td class="text-center">K-Net Link</td>
                <td class="text-right">{{number_format($invoices->sum('knet_link'),3)}}</td>
            </tr>
            <tr>
                <td class="text-center">Credit Card Link</td>
                <td class="text-right">{{number_format($invoices->sum('credit_link'),3)}}</td>
            </tr>
            <tr>
                <th class="text-center">Total</th>
                <th class="text-right">{{number_format($invoices->sum('cash') + $invoices->sum('knet') + $invoices->sum('visa') + $invoices->sum('master')+ $invoices->sum('knet_link')+ $invoices->sum('credit_link'),3)}}</th>
            </tr>

            @if($invoices->count()>0)

                <tr><td colspan="2" class="divider"></td></tr>
                <tr><th colspan="2" class="text-left">Invoices No.</th></tr>

                <tr>
                    <td colspan="2">
                        @foreach($invoices as $invoice)
                            <div
                                style="float: left; border: 1px solid #a4a7ab;border-radius: 5px; padding:1px 5px;margin: 2px;">{{$invoice->invoice_no}}</div>
                        @endforeach
                    </td>
                </tr>

            @endif

            </tbody>
        </table>

    </div>








@endsection
