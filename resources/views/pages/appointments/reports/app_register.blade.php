@extends('layouts.print_p')

@section('title','Appointments | Appointments Register')

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
            <tr><td colspan="11" class="clinic_name">{{auth()->user()->clinic->name}}</td></tr>
            <tr><td colspan="11" class="page-title">Appointments Register</td></tr>
            <tr><td colspan="11" class="page-info">Report from {{date('d-m-Y', strtotime($start))}} to {{date('d-m-Y', strtotime($end))}} </td></tr>
            <tr>
                <th class="text-center" nowrap>Date</th>
                <th class="text-center" nowrap>Start</th>
                <th class="text-center" nowrap>End</th>
                <th class="text-center" nowrap>File No.</th>
                <th class="text-left" nowrap>Patient Name</th>
                <th class="text-center" nowrap>Mobile</th>
                <th class="text-center" nowrap>Status</th>
                <th class="text-center" nowrap>Device</th>
                <th class="text-center" nowrap>Created By</th>
                <th class="text-center" nowrap>Nurse</th>
                <th class="text-left">Remarks</th>
            </tr>
            </thead>
            <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td class="text-center" nowrap>{{date('d-m-Y', strtotime($appointment->date))}}</td>
                    <td class="text-center" nowrap>{{$appointment->start ? date('H:i', strtotime($appointment->start)) : '-' }}</td>
                    <td class="text-center" nowrap>{{$appointment->end ? date('H:i', strtotime($appointment->end)) : '-' }}</td>
                    <td class="text-center" nowrap>{{$appointment->patient_file_no == 0 ? 'New' : $appointment->patient_file_no }}</td>
                    <td class="text-left" nowrap>{{$appointment->name}}</td>
                    <td class="text-center" nowrap>{{$appointment->mobile}}</td>
                    <td class="text-center" nowrap>{{$appointment->status->name}}</td>
                    <td class="text-center" nowrap>{{$appointment->app_device->name}}</td>
                    <td class="text-center" nowrap>{{$appointment->creator->name}}</td>
                    <td class="text-center" nowrap>{{$appointment->nurse_id ? $appointment->nurse->name : '-'}}</td>
                    <td class="text-left">{{$appointment->notes}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
