@extends('layouts.print_p')

@section('title','المواعيد | الاحصائية الشهرية')

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
            <tr><td colspan="14" class="clinic_name text-right">{{auth()->user()->clinic->ar_name}}</td></tr>
            <tr><td colspan="14" class="page-title text-right">الاحصائية الشهرية</td></tr>
            <tr>
                <td colspan="7" class="text-left page-info"><a id="details_toggle" class="btn noprint" href="">اخفاء التفاصيل</a></td>
                <td colspan="7" class="page-info text-right">الاحصائية الشهرية لشهر {{$month}} سنة {{$year}} </td>
            </tr>
            <tr>
            <tr>
                <th rowspan="3">المجموع الكلي</th>
                <th colspan="4">المجموع</th>
                <th colspan="4">متردد</th>
                <th colspan="4">جديد</th>
                <th id="left_col_name" rowspan="3">الجهاز</th>
            </tr>
            <tr>
                <th colspan="2">أنثى</th>
                <th colspan="2">ذكر</th>
                <th colspan="2">أنثى</th>
                <th colspan="2">ذكر</th>
                <th colspan="2">أنثى</th>
                <th colspan="2">ذكر</th>
            </tr>
            <tr>
                <th>غ ك</th>
                <th>ك</th>
                <th>غ ك</th>
                <th>ك</th>
                <th>غ ك</th>
                <th>ك</th>
                <th>غ ك</th>
                <th>ك</th>
                <th>غ ك</th>
                <th>ك</th>
                <th>غ ك</th>
                <th>ك</th>
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
                        <td class="text-center">{{$device->total == 0 ? '-' : $device->total}}</td>
                        <td class="text-center">{{$device->FemaleNonKwt == 0 ? '-' : $device->FemaleNonKwt}}</td>
                        <td class="text-center">{{$device->FemaleKwt == 0 ? '-' : $device->FemaleKwt}}</td>
                        <td class="text-center">{{$device->MaleNonKwt == 0 ? '-' : $device->MaleNonKwt}}</td>
                        <td class="text-center">{{$device->MaleKwt == 0 ? '-' : $device->MaleKwt}}</td>
                        <td class="text-center">{{$device->FollowUpFemaleNonKwt == 0 ? '-' : $device->FollowUpFemaleNonKwt}}</td>
                        <td class="text-center">{{$device->FollowUpFemaleKwt == 0 ? '-' : $device->FollowUpFemaleKwt}}</td>
                        <td class="text-center">{{$device->FollowUpMaleNonKwt == 0 ? '-' : $device->FollowUpMaleNonKwt}}</td>
                        <td class="text-center">{{$device->FollowUpMaleKwt == 0 ? '-' : $device->FollowUpMaleKwt}}</td>
                        <td class="text-center">{{$device->NewFemaleNonKwt + $device->NewFemaleNonKwtNoFile == 0 ? '-' : $device->NewFemaleNonKwt + $device->NewFemaleNonKwtNoFile}}</td>
                        <td class="text-center">{{$device->NewFemaleKwt + $device->NewFemaleKwtNoFile == 0 ? '-' : $device->NewFemaleKwt + $device->NewFemaleKwtNoFile}}</td>
                        <td class="text-center">{{$device->NewMaleNonKwt + $device->NewMaleNonKwtNoFile == 0 ? '-' : $device->NewMaleNonKwt + $device->NewMaleNonKwtNoFile}}</td>
                        <td class="text-center">{{$device->NewMaleKwt + $device->NewMaleKwtNoFile == 0 ? '-' : $device->NewMaleKwt + $device->NewMaleKwtNoFile}}</td>
                        <td class="text-center">{{$device->name}}</td>
                    </tr>
                @endforeach
                <tr>
                    <th>{{$department->app_devices->sum('total') == 0 ? '-' : $department->app_devices->sum('total')}}</th>
                    <th>{{$department->app_devices->sum('FemaleNonKwt') == 0 ? '-' : $department->app_devices->sum('FemaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('FemaleKwt') == 0 ? '-' : $department->app_devices->sum('FemaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('MaleNonKwt') == 0 ? '-' : $department->app_devices->sum('MaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('MaleKwt') == 0 ? '-' : $department->app_devices->sum('MaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpFemaleNonKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpFemaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpFemaleKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpFemaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpMaleNonKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpMaleNonKwt')}}</th>
                    <th>{{$department->app_devices->sum('FollowUpMaleKwt') == 0 ? '-' : $department->app_devices->sum('FollowUpMaleKwt')}}</th>
                    <th>{{$department->app_devices->sum('NewFemaleNonKwt') + $department->app_devices->sum('NewFemaleNonKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewFemaleNonKwt') + $department->app_devices->sum('NewFemaleNonKwtNoFile')}}</th>
                    <th>{{$department->app_devices->sum('NewFemaleKwt') + $department->app_devices->sum('NewFemaleKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewFemaleKwt') + $department->app_devices->sum('NewFemaleKwtNoFile')}}</th>
                    <th>{{$department->app_devices->sum('NewMaleNonKwt') + $department->app_devices->sum('NewMaleNonKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewMaleNonKwt') + $department->app_devices->sum('NewMaleNonKwtNoFile')}}</th>
                    <th>{{$department->app_devices->sum('NewMaleKwt') + $department->app_devices->sum('NewMaleKwtNoFile') == 0 ? '-' : $department->app_devices->sum('NewMaleKwt') + $department->app_devices->sum('NewMaleKwtNoFile')}}</th>
                    <th>{{$department->name}}</th>
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
                <th>{{$total == 0 ? '-' : $total}}</th>
                <th>{{$FemaleNonKwt == 0 ? '-' : $FemaleNonKwt}}</th>
                <th>{{$FemaleKwt == 0 ? '-' : $FemaleKwt}}</th>
                <th>{{$MaleNonKwt == 0 ? '-' : $MaleNonKwt}}</th>
                <th>{{$MaleKwt == 0 ? '-' : $MaleKwt}}</th>
                <th>{{$FollowUpFemaleNonKwt == 0 ? '-' : $FollowUpFemaleNonKwt}}</th>
                <th>{{$FollowUpFemaleKwt == 0 ? '-' : $FollowUpFemaleKwt}}</th>
                <th>{{$FollowUpMaleNonKwt == 0 ? '-' : $FollowUpMaleNonKwt}}</th>
                <th>{{$FollowUpMaleKwt == 0 ? '-' : $FollowUpMaleKwt}}</th>
                <th>{{$NewFemaleNonKwt + $NewFemaleNonKwtNoFile == 0 ? '-' : $NewFemaleNonKwt + $NewFemaleNonKwtNoFile}}</th>
                <th>{{$NewFemaleKwt + $NewFemaleKwtNoFile == 0 ? '-' : $NewFemaleKwt + $NewFemaleKwtNoFile}}</th>
                <th>{{$NewMaleNonKwt + $NewMaleNonKwtNoFile == 0 ? '-' : $NewMaleNonKwt + $NewMaleNonKwtNoFile}}</th>
                <th>{{$NewMaleKwt + $NewMaleKwtNoFile == 0 ? '-' : $NewMaleKwt + $NewMaleKwtNoFile}}</th>
                <th>المجموع الكلي</th>
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
                $(this).html('اخفاء التفاصيل');
                $('#left_col_name').html('الجهاز');
                $('.details').removeClass('d-none');
            } else {
                $(this).html('عرض التفاصيل');
                $('#left_col_name').html('القسم');
                $('.details').addClass('d-none');
            }
        })
    </script>
@endsection






