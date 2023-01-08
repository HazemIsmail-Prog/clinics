@extends('layouts.print_p')

@section('title','Appointments | Patients Visit')

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
                <td colspan="9" class="clinic_name">{{auth()->user()->clinic->name}}</td>
            </tr>
            <tr>
                <td colspan="9" class="page-title">Patients Visit</td>
            </tr>
            <tr>
                <td colspan="9" class="page-info">Report
                    from {{date('d-m-Y', strtotime($start))}} to {{date('d-m-Y', strtotime($end))}} </td>
            </tr>
            <tr>
                <th class="text-center">SN.</th>
                <th class="text-center">Date</th>
                <th class="text-center">File No.</th>
                <th class="text-left">Patient Name</th>
                <th class="text-center">Mobile</th>
                <th class="text-left">Device</th>
                <th class="text-center">Civil ID</th>
                <th class="text-center">Gender</th>
                <th class="text-left">Kwt/N-Kwt</th>
            </tr>
            </thead>
            <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{date('d-m-Y', strtotime($appointment->date))}}</td>
                    <td class="text-center">{{$appointment->patient_file_no == 0 ? 'New' : $appointment->patient_file_no }}</td>
                    <td class="text-left">{{$appointment->name}}</td>
                    <td class="text-center">{{$appointment->mobile}}</td>
                    <td class="text-left">{{$appointment->app_device->name}}</td>
                    <td class="text-center">{{$appointment->civil_id}}</td>
                    <td class="text-center">{{$appointment->gender == 0 ? 'Female' : 'Male'}}</td>
                    <td class="text-left">{{$appointment->nationality_id == 2 ? 'Kuwaiti' : 'Non-Kuwaiti'}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection





