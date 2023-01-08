@extends('layouts.master')


@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
    <link rel='stylesheet' href='{{ asset('assets\plugins\fullcalendar\main.min.css') }}'/>

@endsection

@section('title','Appointments')


@section('styles')
    <style>
        #calendar {
            font-size: 12px;
        }

        .fc-event-time {
            width: 100%;
        }

        .fc-event-title.fc-sticky {
            white-space: normal;
        }


        .fc-event {
            /*height: 60px !important;*/
            font-size: 10px;
        }

        .fc-toolbar-chunk:last-child {
            display: none;

        }


        /*row height*/
        .fc .fc-timegrid-slot {
            height: 20px;
        }

        .fc-toolbar-title {
            font-size: 12px !important;
            margin-bottom: -14px !important;
            /*margin-left: 14px !important;*/
        }


        .fc .fc-timegrid-slot{
            border: 1px solid #000;
        }


    </style>
@endsection

@section('content')



{{--    <div class="card shadow mb-4">--}}
        <!-- Card Header - Dropdown -->
{{--        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
{{--            <h6 class="m-0 font-weight-bold text-primary">Appointments</h6>--}}
{{--        </div>--}}
        <!-- Card Body -->
{{--        <div class="card-body">--}}

            @livewire('appointment-index',[
            'date'=> $date,
            'app_department' => $app_department,
            ])

{{--        </div>--}}


{{--    </div>--}}

@endsection


