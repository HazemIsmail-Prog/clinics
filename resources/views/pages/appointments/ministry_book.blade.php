@extends('layouts.print_p')

@section('title','سجل المراجعين')

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
                <td colspan="8" class="page-title text-center">سجل المراجعين</td>
            </tr>
            <tr>
                <td colspan="4" class="page-info text-left">التاريخ : {{date('d-m-Y', strtotime($date))}}</td>
                <td colspan="4" class="page-info text-right">
                    @switch(date('w', strtotime($date)))
                        @case(0)
                        اليوم : الأحد
                        @break
                        @case(1)
                        اليوم : الاثنين
                        @break
                        @case(2)
                        اليوم : الثلاثاء
                        @break
                        @case(3)
                        اليوم : الأربعاء
                        @break
                        @case(4)
                        اليوم : الخميس
                        @break
                        @case(5)
                        اليوم : الجمعة
                        @break
                        @case(6)
                        اليوم : السبت
                @break
                @endswitch
            </tr>
            <tr>
                <th>متردد</th>
                <th>جديد</th>
                <th>العمر</th>
                <th>الجنسية</th>
                <th>الرقم المدني</th>
                <th class="text-left">الاسم</th>
                <th>رقم الملف</th>
                <th>م</th>
            </tr>

            </thead>
            <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    @if($appointment->patient_id != null)
                        <td class="text-center">{{date('d-m-Y',strtotime($appointment->patient->created_at)) != date('d-m-Y', strtotime($date)) ? '✔' : ''}}</td>
                        <td class="text-center">{{date('d-m-Y',strtotime($appointment->patient->created_at)) == date('d-m-Y', strtotime($date)) ? '✔' : ''}}</td>
                    @else
                        <td class="text-center"></td>
                        <td class="text-center">✔</td>
                    @endif
                    @if (substr($appointment->civil_id, 0, 1) == 3)
                        <td class="text-center">{{intval(date('Y', strtotime($date))) - intval(substr($appointment->civil_id, 1, 2) + 2000)}}</td>
                    @elseif (substr($appointment->civil_id, 0, 1) == 2)
                        <td class="text-center">{{intval(date('Y', strtotime($date))) - intval(substr($appointment->civil_id, 1, 2) + 1900)}}</td>
                    @else
                        <td class="text-center">-</td>
                    @endif
                    <td class="text-center">{{$appointment->nationality->name}}</td>
                    <td class="text-center">{{$appointment->civil_id ? $appointment->civil_id : '-'}}</td>
                    <td>{{$appointment->name}}</td>
                    <td class="text-center">{{$appointment->patient_file_no == 0 ? 'New' : $appointment->patient_file_no}}</td>
                    <td class="text-center">{{$loop->iteration}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
