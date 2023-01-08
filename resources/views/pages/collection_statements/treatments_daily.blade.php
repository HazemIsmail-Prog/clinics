@extends('layouts.print_p')

@section('title','Collection Statement')

@section('styles')
    <style>
        @page {
            size: letter landscape
        }
    </style>
@endsection

@section('content')

    <div class="container">

            <table>
                <thead>

                <tr>
                    <td colspan="8" class="clinic_name">{{auth()->user()->clinic->name}}</td>
                </tr>
                <tr>
                    <td colspan="8" class="page-title">Treatment Wise Daily View</td>
                </tr>
                <tr>
                    <td colspan="8" class="page-info">Report From {{date('d-m-Y',strtotime($start_date))}}
                        to {{date('d-m-Y',strtotime($end_date))}}</td>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $row)

                    <tr>
                    <td colspan="8" class="account_name">
                        {{$row->name}}
                    </td>
                </tr>

                <tr class="bg-primary text-white">
                    <th>Day</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Cash</th>
                    <th class="text-right">K-Net</th>
                    <th class="text-right">VISA</th>
                    <th class="text-right">Master Card</th>
                    <th class="text-right">Link</th>
                    <th class="text-right">Balance</th>
                    <th class="text-right">Collection</th>
                </tr>


                @foreach($row->invoices as $invoice)

                    <tr>
                        <td nowrap>{{date('d-m-Y', strtotime( $invoice->date))}}</td>
                        <td class="text-right">{{$invoice->total <= 0 ? '-' : number_format($invoice->total,3)}}</td>
                        <td class="text-right">{{$invoice->cash <= 0 ? '-' : number_format($invoice->cash,3)}}</td>
                        <td class="text-right">{{$invoice->knet <= 0 ? '-' : number_format($invoice->knet,3)}}</td>
                        <td class="text-right">{{$invoice->visa <= 0 ? '-' : number_format($invoice->visa,3)}}</td>
                        <td class="text-right">{{$invoice->master <= 0 ? '-' : number_format($invoice->master,3)}}</td>
                        <td class="text-right">{{$invoice->knet_link + $invoice->credit_link <= 0 ? '-' : number_format($invoice->knet_link + $invoice->credit_link,3)}}</td>
                        <td class="text-right">{{$invoice->balance <= 0 ? '-' : number_format($invoice->balance,3)}}</td>
                        <td class="text-right">{{$invoice->cash + $invoice->knet + $invoice->visa + $invoice->master + $invoice->knet_link + $invoice->credit_link <= 0 ? '-' : number_format($invoice->cash + $invoice->knet + $invoice->visa + $invoice->master + $invoice->knet_link + $invoice->credit_link,3)}}</td>
                    </tr>

                @endforeach
                <tr>
                    <th>{{$row->name}}'s Total</th>
                    <th class="text-right">{{$row->invoices->sum('total') <= 0 ? '-' : number_format($row->invoices->sum('total'),3) }}</th>
                    <th class="text-right">{{$row->invoices->sum('cash') <= 0 ? '-' : number_format($row->invoices->sum('cash'),3) }}</th>
                    <th class="text-right">{{$row->invoices->sum('knet') <= 0 ? '-' : number_format($row->invoices->sum('knet'),3) }}</th>
                    <th class="text-right">{{$row->invoices->sum('visa') <= 0 ? '-' : number_format($row->invoices->sum('visa'),3) }}</th>
                    <th class="text-right">{{$row->invoices->sum('master') <= 0 ? '-' : number_format($row->invoices->sum('master'),3) }}</th>
                    <th class="text-right">{{$row->invoices->sum('knet_link') + $row->invoices->sum('credit_link') <= 0 ? '-' : number_format($row->invoices->sum('knet_link') + $row->invoices->sum('credit_link'),3) }}</th>
                    <th class="text-right">{{$row->invoices->sum('balance') <= 0 ? '-' : number_format($row->invoices->sum('balance'),3) }}</th>
                    <th class="text-right">{{$row->invoices->sum('cash') + $row->invoices->sum('knet') + $row->invoices->sum('visa') + $row->invoices->sum('master') + $row->invoices->sum('knet_link') + $row->invoices->sum('credit_link') <= 0 ? '-' : number_format($row->invoices->sum('cash') + $row->invoices->sum('knet') + $row->invoices->sum('visa') + $row->invoices->sum('master') + $row->invoices->sum('knet_link') + $row->invoices->sum('credit_link'),3) }}</th>
                </tr>

                @endforeach
                </tbody>

            </table>
{{--            <div style="page-break-after: always"></div>--}}

    </div>



@endsection
