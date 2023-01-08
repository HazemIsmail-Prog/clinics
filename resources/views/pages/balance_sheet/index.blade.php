@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
    <link rel="stylesheet" href="{{asset('assets\plugins\select2\select2.min.css')}}">
@endsection

@section('title','Balance Sheet')

@section('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
        }

        #clear_filter {
            position: absolute;
            right: 10px;
        }

    </style>
@endsection

@section('content')
    <form action="{{route('balance_sheet.show')}}" method="get" target="_blank">
        @csrf
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Balance Sheet</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="start">Start Date</label>
                            <input required type="date" value="{{old('start',Carbon\Carbon::today()->subMonth(1)->format('Y-m-d'))}}" name="start" id="start"
                                   class="form-control {{$errors->has('start')?'border-danger':''}}">
                            @if($errors->has('start'))
                                <span class="text-danger">{{ $errors->first('start') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="text-primary" for="end">End Date</label>
                            <input required type="date" value="{{old('end',Carbon\Carbon::today()->format('Y-m-d'))}}" name="end" id="end"
                                   class="form-control {{$errors->has('end')?'border-danger':''}}">
                            @if($errors->has('end'))
                                <span class="text-danger">{{ $errors->first('end') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button class="btn btn-sm text-primary" type="submit">View Report</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="{{asset('assets\plugins\select2\select2.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>

@endsection
