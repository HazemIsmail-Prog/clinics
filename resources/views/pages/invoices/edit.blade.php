@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Edit Invoice')

@section('content')

    @livewire('invoice-modal',[
    'invoice' => $invoice,
    'action' => 'edit',
    ])

@endsection

