@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Appointments')

@section('content')

    @livewire('appointment-create',[
    'start' => $start ,
    'end' => $end ,
    'app_department' => $app_department,
    'device_id' => $device_id ,
    'date' => $date ,
    'action' => 'create' ,
    ])

@endsection

@section('scripts')
@endsection
