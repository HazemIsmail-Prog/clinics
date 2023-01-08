@extends('layouts.master')

@section('title','Offers')

@section('content')

    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Offers</h6>
            <div class="dropdown no-arrow">
                <a class="btn btn-sm btn-outline-primary" href="{{route('offers.create')}}">New Offer</a>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>Start</th>
                        <th>End</th>
                        <th>Description</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($offers as $offer)



                        <tr class="{{$offer->end >= date('Y-m-d', strtotime(\Carbon\Carbon::now())) && $offer->start <= date('Y-m-d',strtotime(\Carbon\Carbon::now())) ? 'text-success' : 'text-danger'}}">
                            <td>{{date('d-m-Y', strtotime($offer->start))}}</td>
                            <td>{{date('d-m-Y', strtotime($offer->end))}}</td>
                            <td>{{$offer->description}}</td>
                            <td class="text-center">

                                <a href="{{route('offers.edit',$offer->id)}}"
                                   class="btn btn-outline-info btn-sm" title="Edit"><i
                                        class="fa fa-edit"></i></a>
                                <a
                                    class="btn btn-outline-danger btn-sm"
                                    href="{{route('offers.destroy',$offer->id)}}"
                                    onclick="event.preventDefault();confirm('You\'r About to Delete This Offer\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $offer->id }}').submit() : false;"
                                >
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form
                                    action="{{route('offers.destroy',$offer->id)}}"
                                    id="delete-form-{{$offer->id}}"
                                    method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
