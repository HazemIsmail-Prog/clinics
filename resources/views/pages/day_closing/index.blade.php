@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Day Closing')

@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Day Closing</h6>
        </div>
        <form action="{{route('day_closing.print')}}" target="_blank">
        @csrf
        <!-- Card Body -->
            <div class="card-body">

                <div class="form-group">
                    <label class="text-primary" for="date">Date</label>
                    <input type="date" class="form-control" name="date" value="{{Carbon\Carbon::today()->format('Y-m-d')}}">
                    @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                </div>

            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-sm text-primary">View report</button>

            </div>
        </form>
    </div>
@endsection
