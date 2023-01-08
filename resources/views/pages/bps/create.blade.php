@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Journal Vouchers')

@section('content')

    @livewire('vouchers',[
    'voucher_type' => 'bp',
    'action' => 'create',
    'accounts' => $accounts
    ])





@endsection

@section('scripts')
@endsection
