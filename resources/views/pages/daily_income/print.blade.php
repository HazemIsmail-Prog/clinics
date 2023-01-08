@extends('layouts.print_p')

@section('title','Daily Income')

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
                <td colspan="8" class="page-title">Daily Income</td>
            </tr>
            <tr>
                <td colspan="8" class="page-info">Report For {{date('d-m-Y', strtotime($date))}}</td>
            </tr>
            <tr>
                <th class="text-center">SN.</th>
                <th class="text-center">Invoice No.</th>
                <th class="text-center">File No.</th>
                <th class="text-left">Patient Name</th>
                <th class="text-center">Status</th>
                <th class="text-center">Mobile</th>
                <th class="text-left">Department</th>
                <th class="text-left">Doctor</th>
                <th class="text-left">Nurse</th>
                <th class="text-right">Paid</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{$invoice->invoice_no}}</td>
                    <td class="text-center">{{$invoice->patient->file_no}}</td>
                    <td class="text-left">{{$invoice->patient->name}}</td>
                    <td class="text-center">{{date('d-m-Y',strtotime($invoice->patient->created_at)) == date('d-m-Y',strtotime($date)) ? 'New' : 'Old'}}</td>
                    <td class="text-center">{{$invoice->patient->mobile}}</td>
                    <td class="text-left">{{$invoice->doctor->department->name}}</td>
                    <td class="text-left">{{$invoice->doctor->name}}</td>
                    <td class="text-left">{{$invoice->nurse_id ? $invoice->nurse->name : '-'}}</td>
                    <td class="text-right">{{number_format($invoice->cash + $invoice->knet + $invoice->visa + $invoice->master + $invoice->knet_link + $invoice->credit_link ,3)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="page-break-after: always"></div>


        <table>
            <thead>
            <tr>
                <td colspan="8" class="clinic_name">{{auth()->user()->clinic->name}}</td>
            </tr>
            <tr>
                <td colspan="8" class="page-title">Daily Income</td>
            </tr>
            <tr>
                <td colspan="8" class="page-info">Report For {{date('d-m-Y', strtotime($date))}}</td>
            </tr>
            <tr>
                <th class="text-left">Department</th>
                <th class="text-left">Doctor</th>
                <th class="text-right">Paid Amount</th>
                <th class="text-center">New Patients</th>
                <th class="text-center">Old Patients</th>
                <th class="text-center">No. of Invoices</th>
            </tr>
            </thead>
            <tbody>
            @foreach($doctors as $doctor)
                <tr>
                    <td class="text-left">{{$doctor->department->name}}</td>
                    <td class="text-left">{{$doctor->name}}</td>
                    <td class="text-right">{{number_format($doctor->paid_amount,3)}}</td>
                    <td class="text-center">{{$doctor->new_patients == 0 ? '-' : $doctor->new_patients}}</td>
                    <td class="text-center">{{$doctor->invoices_count - $doctor->new_patients == 0 ? '-' : $doctor->invoices_count - $doctor->new_patients}}</td>
                    <td class="text-center">{{$doctor->invoices_count == 0 ? '-' : $doctor->invoices_count}}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" class="text-right">Totals : </th>
                <th class="text-right">{{number_format($doctors->sum('paid_amount'),3)}}</th>
                <th class="text-center">{{$doctors->sum('new_patients') == 0 ? '-' : $doctors->sum('new_patients')}}</th>
                <th class="text-center">{{$doctors->sum('invoices_count') - $doctors->sum('new_patients') == 0 ? '-' : $doctors->sum('invoices_count') - $doctors->sum('new_patients')}}</th>
                <th class="text-center">{{$doctors->sum('invoices_count') == 0 ? '-' : $doctors->sum('invoices_count')}}</th>
            </tr>
            </tbody>
        </table>
    </div>
@endsection
