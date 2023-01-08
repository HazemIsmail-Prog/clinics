@extends('layouts.print_p')

@section('title','Appointments | Monthly Statistics')

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
                <td colspan="14" class="clinic_name">{{auth()->user()->clinic->name}}</td>
            </tr>
            <tr>
                <td colspan="14" class="page-title">Monthly Statistics</td>
            </tr>
            <tr>
                <td colspan="7" class="page-info">Appointments Monthly Statistics
                    for {{date('F, Y', strtotime('01-'.$month.'-'.$year))}} </td>
                <td colspan="7" class="page-info text-right "><a id="details_toggle" class="btn noprint" href="">Hide
                        Details</a></td>
            </tr>
            <tr>
                <th id="left_col_name" rowspan="3">Device</th>
                <th colspan="4">New</th>
                <th colspan="4">FollowUp</th>
                <th colspan="4">Total</th>
                <th rowspan="3">Grand Total</th>
            </tr>
            <tr>
                <th colspan="2">Male</th>
                <th colspan="2">Female</th>
                <th colspan="2">Male</th>
                <th colspan="2">Female</th>
                <th colspan="2">Male</th>
                <th colspan="2">Female</th>
            </tr>
            <tr>
                <th>Kwt</th>
                <th>NonKwt</th>
                <th>Kwt</th>
                <th>NonKwt</th>
                <th>Kwt</th>
                <th>NonKwt</th>
                <th>Kwt</th>
                <th>NonKwt</th>
                <th>Kwt</th>
                <th>NonKwt</th>
                <th>Kwt</th>
                <th>NonKwt</th>
            </tr>
            </thead>
            <tbody>
            <div class="d-none">
                {{$NewMaleKwt = 0}}
                {{$NewMaleKwtNoFile = 0}}
                {{$NewMaleNonKwt = 0}}
                {{$NewMaleNonKwtNoFile = 0}}
                {{$NewFemaleKwt = 0}}
                {{$NewFemaleKwtNoFile = 0}}
                {{$NewFemaleNonKwt = 0}}
                {{$NewFemaleNonKwtNoFile = 0}}
                {{$FollowUpMaleKwt = 0}}
                {{$FollowUpMaleNonKwt = 0}}
                {{$FollowUpFemaleKwt = 0}}
                {{$FollowUpFemaleNonKwt = 0}}
                {{$MaleKwt = 0}}
                {{$MaleNonKwt = 0}}
                {{$FemaleKwt = 0}}
                {{$FemaleNonKwt = 0}}
                {{$total = 0}}
            </div>

            @foreach($departments as $department)
                @foreach($department->app_devices as $device)

                    <tr class="details">
                        <td>{{$device->name}}</td>
                            <td class="text-center">{{$device->NewMaleKwt + $device->NewMaleKwtNoFile == 0 ? '-' : $device->NewMaleKwt + $device->NewMaleKwtNoFile}}</td>
                            <td class="text-center">{{$device->NewMaleNonKwt + $device->NewMaleNonKwtNoFile == 0 ? '-' : $device->NewMaleNonKwt + $device->NewMaleNonKwtNoFile}}</td>
                            <td class="text-center">{{$device->NewFemaleKwt + $device->NewFemaleKwtNoFile == 0 ? '-' : $device->NewFemaleKwt + $device->NewFemaleKwtNoFile}}</td>
                            <td class="text-center">{{$device->NewFemaleNonKwt + $device->NewFemaleNonKwtNoFile == 0 ? '-' : $device->NewFemaleNonKwt + $device->NewFemaleNonKwtNoFile}}</td>
                            <td class="text-center">{{$device->FollowUpMaleKwt == 0 ? '-' : $device->FollowUpMaleKwt}}</td>
                            <td class="text-center">{{$device->FollowUpMaleNonKwt == 0 ? '-' : $device->FollowUpMaleNonKwt}}</td>
                            <td class="text-center">{{$device->FollowUpFemaleKwt == 0 ? '-' : $device->FollowUpFemaleKwt}}</td>
                            <td class="text-center">{{$device->FollowUpFemaleNonKwt == 0 ? '-' : $device->FollowUpFemaleNonKwt}}</td>
                            <td class="text-center">{{$device->MaleKwt == 0 ? '-' : $device->MaleKwt}}</td>
                            <td class="text-center">{{$device->MaleNonKwt == 0 ? '-' : $device->MaleNonKwt}}</td>
                            <td class="text-center">{{$device->FemaleKwt == 0 ? '-' : $device->FemaleKwt}}</td>
                            <td class="text-center">{{$device->FemaleNonKwt == 0 ? '-' : $device->FemaleNonKwt}}</td>
                            <td class="text-center">{{$device->total == 0 ? '-' : $device->total}}</td>
                    </tr>
                @endforeach

                <tr>
                    <th>{{$department->name}}</th>
                    <th>{{$department->app_devices->sum('NewMaleKwt') + $department->app_devices->sum('NewMaleKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewMaleKwt') + $department->app_devices->sum('NewMaleKwtNoFile')}}</th>
                    <th>{{$department->app_devices->sum('NewMaleNonKwt') + $department->app_devices->sum('NewMaleNonKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewMaleNonKwt') + $department->app_devices->sum('NewMaleNonKwtNoFile')}}</th>
                    <th>{{$department->app_devices->sum('NewFemaleKwt') + $department->app_devices->sum('NewFemaleKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewFemaleKwt') + $department->app_devices->sum('NewFemaleKwtNoFile')}}</th>
                    <th>{{$department->app_devices->sum('NewFemaleNonKwt') + $department->app_devices->sum('NewFemaleNonKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewFemaleNonKwt') + $department->app_devices->sum('NewFemaleNonKwtNoFile')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpMaleKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpMaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpMaleNonKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpMaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpFemaleKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpFemaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpFemaleNonKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpFemaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('MaleKwt') == 0 ? '-' : $department->app_devices->sum('MaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('MaleNonKwt') == 0 ? '-' : $department->app_devices->sum('MaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('FemaleKwt') == 0 ? '-' : $department->app_devices->sum('FemaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('FemaleNonKwt') == 0 ? '-' : $department->app_devices->sum('FemaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('total') == 0 ? '-' : $department->app_devices->sum('total')}}</th>
                </tr>
                <div class="d-none">
                    {{$NewMaleKwt += $department->app_devices->sum('NewMaleKwt')}}
                    {{$NewMaleKwtNoFile += $department->app_devices->sum('NewMaleKwtNoFile')}}
                    {{$NewMaleNonKwt += $department->app_devices->sum('NewMaleNonKwt')}}
                    {{$NewMaleNonKwtNoFile += $department->app_devices->sum('NewMaleNonKwtNoFile')}}
                    {{$NewFemaleKwt += $department->app_devices->sum('NewFemaleKwt')}}
                    {{$NewFemaleKwtNoFile += $department->app_devices->sum('NewFemaleKwtNoFile')}}
                    {{$NewFemaleNonKwt += $department->app_devices->sum('NewFemaleNonKwt')}}
                    {{$NewFemaleNonKwtNoFile += $department->app_devices->sum('NewFemaleNonKwtNoFile')}}
                    {{$FollowUpMaleKwt += $department->app_devices->sum('FollowUpMaleKwt')}}
                    {{$FollowUpMaleNonKwt += $department->app_devices->sum('FollowUpMaleNonKwt')}}
                    {{$FollowUpFemaleKwt += $department->app_devices->sum('FollowUpFemaleKwt')}}
                    {{$FollowUpFemaleNonKwt += $department->app_devices->sum('FollowUpFemaleNonKwt')}}
                    {{$MaleKwt += $department->app_devices->sum('MaleKwt')}}
                    {{$MaleNonKwt += $department->app_devices->sum('MaleNonKwt')}}
                    {{$FemaleKwt += $department->app_devices->sum('FemaleKwt')}}
                    {{$FemaleNonKwt += $department->app_devices->sum('FemaleNonKwt')}}
                    {{$total += $department->app_devices->sum('total')}}
                </div>
            @endforeach
            <tr>
                <th>Total</th>
                <th>{{$NewMaleKwt + $NewMaleKwtNoFile == 0 ? '-' : $NewMaleKwt + $NewMaleKwtNoFile}}</th>
                <th>{{$NewMaleNonKwt + $NewMaleNonKwtNoFile == 0 ? '-' : $NewMaleNonKwt + $NewMaleNonKwtNoFile}}</th>
                <th>{{$NewFemaleKwt + $NewFemaleKwtNoFile == 0 ? '-' : $NewFemaleKwt + $NewFemaleKwtNoFile}}</th>
                <th>{{$NewFemaleNonKwt + $NewFemaleNonKwtNoFile == 0 ? '-' : $NewFemaleNonKwt + $NewFemaleNonKwtNoFile}}</th>
                <th>{{$FollowUpMaleKwt == 0 ? '-' : $FollowUpMaleKwt}}</th>
                <th>{{$FollowUpMaleNonKwt == 0 ? '-' : $FollowUpMaleNonKwt}}</th>
                <th>{{$FollowUpFemaleKwt == 0 ? '-' : $FollowUpFemaleKwt}}</th>
                <th>{{$FollowUpFemaleNonKwt == 0 ? '-' : $FollowUpFemaleNonKwt}}</th>
                <th>{{$MaleKwt == 0 ? '-' : $MaleKwt}}</th>
                <th>{{$MaleNonKwt == 0 ? '-' : $MaleNonKwt}}</th>
                <th>{{$FemaleKwt == 0 ? '-' : $FemaleKwt}}</th>
                <th>{{$FemaleNonKwt == 0 ? '-' : $FemaleNonKwt}}</th>
                <th>{{$total == 0 ? '-' : $total}}</th>
            </tr>
            </tbody>
        </table>
    </div>
@endsection



@section('scripts')
    <script src="{{asset('assets\theme\vendor\jquery\jquery.js')}}"></script>


    <script>

        $('#details_toggle').on('click', function (e) {

            e.preventDefault();

            if ($('.details').hasClass('d-none')) {
                $(this).html('Hide Details');
                $('#left_col_name').html('Device');
                $('.details').removeClass('d-none');
            } else {
                $(this).html('Show Details');
                $('#left_col_name').html('Department');
                $('.details').addClass('d-none');
            }
        })
    </script>

@endsection





