@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','New Invoice')

@section('content')

    @livewire('invoice-modal',[
    'patient' => $patient,
    'action' => 'create',
    ])

@endsection

