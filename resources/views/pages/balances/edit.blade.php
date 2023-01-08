@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Edit Balance Payment')

@section('content')

    @livewire('balance-invoice',[
    'invoice_to_edit' => $invoice,
    'ref_invoice' => $ref_invoice,
    'action' => 'edit',
    ])

@endsection

