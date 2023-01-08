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
                <td colspan="8" class="page-title">Revenue Wise Detailed View</td>
            </tr>
            <tr>
                <td colspan="8" class="page-info">Report From {{date('d-m-Y',strtotime($start_date))}}
                    to {{date('d-m-Y',strtotime($end_date))}}</td>
            </tr>

            <tr class="bg-primary text-white">
                <th>Date / Time</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Cash</th>
                <th class="text-right">K-Net</th>
                <th class="text-right">VISA</th>
                <th class="text-right">Master Card</th>
                <th class="text-right">Link</th>
                <th class="text-right">Balance</th>
                <th class="text-right">Collection</th>
            </tr>
            </thead>
            <tbody>

            @foreach($data as $row)

                <tr>
                    <td nowrap>{{date('d-m-Y', strtotime( $row->date))}}</td>
                    <td class="text-right">{{$row->total <= 0 ? '-' : number_format($row->total,3)}}</td>
                    <td class="text-right">{{$row->cash <= 0 ? '-' : number_format($row->cash,3)}}</td>
                    <td class="text-right">{{$row->knet <= 0 ? '-' : number_format($row->knet,3)}}</td>
                    <td class="text-right">{{$row->visa <= 0 ? '-' : number_format($row->visa,3)}}</td>
                    <td class="text-right">{{$row->master <= 0 ? '-' : number_format($row->master,3)}}</td>
                    <td class="text-right">{{$row->knet_link + $row->credit_link <= 0 ? '-' : number_format($row->knet_link + $row->credit_link,3)}}</td>
                    <td class="text-right">{{$row->balance <= 0 ? '-' : number_format($row->balance,3)}}</td>
                    <td class="text-right">{{$row->cash + $row->knet + $row->visa + $row->master + $row->knet_link + $row->credit_link <= 0 ? '-' : number_format($row->cash + $row->knet + $row->visa + $row->master + $row->knet_link + $row->credit_link,3)}}</td>
                </tr>

            @endforeach
            <tr>
                <th>Total</th>
                <th class="text-right">{{$data->sum('total') <= 0 ? '-' : number_format($data->sum('total'),3) }}</th>
                <th class="text-right">{{$data->sum('cash') <= 0 ? '-' : number_format($data->sum('cash'),3) }}</th>
                <th class="text-right">{{$data->sum('knet') <= 0 ? '-' : number_format($data->sum('knet'),3) }}</th>
                <th class="text-right">{{$data->sum('visa') <= 0 ? '-' : number_format($data->sum('visa'),3) }}</th>
                <th class="text-right">{{$data->sum('master') <= 0 ? '-' : number_format($data->sum('master'),3) }}</th>
                <th class="text-right">{{$data->sum('knet_link') + $data->sum('credit_link') <= 0 ? '-' : number_format($data->sum('knet_link') + $data->sum('credit_link'),3) }}</th>
                <th class="text-right">{{$data->sum('balance') <= 0 ? '-' : number_format($data->sum('balance'),3) }}</th>
                <th class="text-right">{{$data->sum('cash') + $data->sum('knet') + $data->sum('visa') + $data->sum('master') + $data->sum('knet_link') + $data->sum('credit_link') <= 0 ? '-' : number_format($data->sum('cash') + $data->sum('knet') + $data->sum('visa') + $data->sum('master') + $data->sum('knet_link') + $data->sum('credit_link'),3) }}</th>
            </tr>

            </tbody>
        </table>
        <div style="page-break-after: always"></div>
    </div>
@endsection
