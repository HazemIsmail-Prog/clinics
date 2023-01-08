@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Balance Payment')

@section('content')

    @livewire('balance-invoice',[
    'ref_invoice' => $ref_invoice,
    'action' => 'create',
    ])

@endsection

