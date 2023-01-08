@extends('layouts.print_p')

<title>Bank Receipt - {{$voucher->voucher_no}}</title>

@section('styles')
    <style>
        @page {
            size: letter portrait;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <table>
            <thead>
            <tr>
                <td colspan="2" class="clinic_name">{{auth()->user()->clinic->name}}</td>
                <td colspan="2" class="page-info text-right">{{date("d-m-Y h:i a",strtotime(now())) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="page-title">Bank Receipt</td>
            </tr>
            <tr>
                <td colspan="2" class="account_name" style="border-right: 0">
                    Voucher No : {{$voucher->voucher_no}}
                    <br>
                    Date : {{date('d-m-Y',strtotime($voucher->voucher_date)) }}
                </td>
                <td colspan="2" class="account_name" style="border-left: 0">
                    <div style="text-align: right">
                        <a class="btn noprint" href="#" onclick="event.preventDefault();window.print();">Print</a>
                        <a class="btn noprint" href="#" onclick="event.preventDefault();window.close();">Close</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th>S. No.</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
            </thead>
            <tbody>
            @foreach($voucher->voucher_details as $row)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-left">
                        <div style="font-weight: bold">{{$row->account->name}}</div>
                        <div>{{$row->narration}}</div>
                    </td>
                    <td class="text-right">{{$row->debit == 0 ? '-' : number_format($row->debit,3) }}</td>
                    <td class="text-right">{{$row->credit == 0 ? '-' : number_format($row->credit,3) }}</td>
                </tr>
            @endforeach
            <tr>
                <th class="text-right" colspan="2">Total[KD] :</th>
                <th class="text-right">{{number_format($voucher->voucher_details->sum('debit'),3) }}</th>
                <th class="text-right">{{number_format($voucher->voucher_details->sum('credit'),3) }}</th>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="font-weight: bold;padding-top: 50px">Remarks :</td>
            </tr>
            <tr>
                <td colspan="4" style="font-weight: bold;text-align: center;border: 0">
                    ..........................................................................................................................................................................................................................
                </td>
            </tr>
            <tr>
                <td width="50%" colspan="2" style="font-weight: bold;text-align: center;border: 0;padding-top: 50px">
                    <div>Prepared by :</div>
                    <br>
                    <br>
                    <div>................................................</div>
                </td>
                <td width="50%" colspan="2" style="font-weight: bold;text-align: center;border: 0;padding-top: 50px">

                    <div>Authorized by :</div>
                    <br>
                    <br>
                    <div>................................................</div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection
