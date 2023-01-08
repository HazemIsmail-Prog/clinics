@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Collection Statements')

@section('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
        }

        #clear_filter {
            position: absolute;
            right: 10px;
        }

        @media print {

            @page {
                margin: 0mm;
                size: landscape
            }

            body {
                margin: 10mm 0mm;
            }
        }

    </style>
@endsection

@section('content')
    @livewire('collection-statements')
@endsection
