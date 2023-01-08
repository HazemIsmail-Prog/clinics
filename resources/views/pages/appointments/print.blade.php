@extends('layouts.print_p')

@section('title','Appointments')

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
                <td colspan="8" class="page-title">Current Appointments Sheet</td>
            </tr>
            <tr>
                <td colspan="8" class="page-info">Report For Department {{$department_name}}
                    at {{date('d-m-Y', strtotime($date))}} </td>
            </tr>
            </thead>
            <tbody>
            @foreach($devices as $device)

                <tr>
                    <td class="account_name" colspan="8">{{$device->name}}</td>
                </tr>
                <tr>
                    <th nowrap>File No.</th>
                    <th class="text-left">Patient Name</th>
                    <th>Mobile</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Nurse</th>
                    <th class="text-left">Remarks</th>
                </tr>


            @foreach($device->appointments as $appointment)
                <tr class="{{$appointment->status->name == 'Cancelled' ? 'text-danger' : '' }}">
                    @if($loop->index == 0)
                    @endif
                    <td nowrap
                        class="text-center">{{$appointment->patient_file_no > 0 ? $appointment->patient_file_no : 'New'}}</td>
                    <td class="text-left">{{$appointment->name}}</td>
                    <td nowrap>{{$appointment->mobile}}</td>
                    <td nowrap>{{date('H:i', strtotime($appointment->start))}}</td>
                    <td nowrap>{{date('H:i', strtotime($appointment->end))}}</td>
                    <td nowrap>{{$appointment->status->name}}</td>
                    <td nowrap>{{$appointment->nurse_id ? $appointment->nurse->name : '-'}}</td>
                    <td class="text-left">{{$appointment->notes}}</td>
                </tr>
            @endforeach
            </tbody>
            @endforeach
        </table>
    </div>
@endsection
