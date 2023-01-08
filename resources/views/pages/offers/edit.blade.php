@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Offers')

@section('content')
    <form action="{{route('offers.update',$offer->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Offer</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="row">

                    {{--strat--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="start">Start</label>
                            <input class="form-control {{$errors->has('start')?'border-danger':''}}" type="date"
                                   id="start"
                                   name="start" value="{{old('start',$offer->start)}}">
                            @if($errors->has('start'))
                                <span class="text-danger">{{ $errors->first('start') }}</span>
                            @endif
                        </div>
                    </div>

                    {{--end--}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="end">End</label>
                            <input class="form-control {{$errors->has('end')?'border-danger':''}}" type="date"
                                   id="end"
                                   name="end" value="{{old('end',$offer->end)}}">
                            @if($errors->has('end'))
                                <span class="text-danger">{{ $errors->first('end') }}</span>
                            @endif
                        </div>
                    </div>

                    {{--description--}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="text-primary" for="description">Description</label>
                            <textarea
                                class="form-control {{$errors->has('description')?'border-danger':''}}"
                                name="description"
                                id="description"
                                cols="30"
                                rows="10"
                            >
                                        {{old('description',$offer->description)}}
                                    </textarea>
                            @if($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                </div>


            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm text-primary">Save</button>
                    <a href="{{route('offers.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>
        </div>

    </form>


@endsection
