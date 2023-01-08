@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Journal Vouchers')

@section('content')

    @livewire('vouchers',[
    'current_voucher' => $current_voucher,
    'action' => 'edit',
    'accounts' => $accounts
    ])





@endsection

@section('scripts')
@endsection
