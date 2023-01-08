@extends('layouts.master')

@section('title','Year Close')

@section('content')
    <form action="{{route('year_close.store')}}" method="post">
        @csrf
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Year Close</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <h2>Click on Close Year Button to Close Year {{$year}}</h2>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button class="btn btn-primary" type="submit">Close Year</button>
                </div>
            </div>
        </div>
    </form>
@endsection
