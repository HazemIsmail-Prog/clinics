@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Appointments')

@section('content')

    @livewire('appointment-create',[
    'current_appointment' => $current_appointment ,
    'app_department' => $app_department,
    'action' => 'edit' ,
    ])

@endsection

@section('scripts')
@endsection
