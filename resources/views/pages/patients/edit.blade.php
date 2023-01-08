@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Patients')

@section('styles')
@endsection

@section('content')

    <form action="{{route('patients.update',$patient->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Patient - {{$patient->name}}</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <div class="form-section bg-primary">

                    <div class="section-header">
                        <span>Basic Data</span>
                    </div>

                    <div class="section-body">

                        <div class="row">

                            {{--Name--}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-primary" for="name">Name</label>
                                    <input class="form-control {{$errors->has('name')?'border-danger':''}}" type="text"
                                           id="name"
                                           autocomplete="off"
                                           name="name" value="{{old('name',$patient->name)}}">
                                    @if($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{--Mobile--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-primary" for="mobile">Mobile</label>
                                    <input class="form-control {{$errors->has('mobile')?'border-danger':''}}"
                                           type="number" id="mobile"
                                           autocomplete="off"
                                           name="mobile" value="{{old('mobile',$patient->mobile)}}">
                                    @if($errors->has('mobile'))
                                        <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{--Civil ID--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="text-primary" for="civil_id">Civil ID</label>
                                    <input class="form-control {{$errors->has('civil_id')?'border-danger':''}}"
                                           type="number" id="civil_id"
                                           autocomplete="off"
                                           name="civil_id" value="{{old('civil_id',$patient->civil_id)}}">
                                    @if($errors->has('civil_id'))
                                        <span class="text-danger">{{ $errors->first('civil_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{--Nationality--}}
                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="text-primary" for="nationality_id">Nationality</label>
                                    <select
                                        class="custom-select form-control  {{$errors->has('nationality_id')?'border-danger':''}}"
                                        name="nationality_id" id="nationality_id">
                                        <option value="">---</option>
                                        @foreach($nationalities as $nationality)
                                            <option
                                                value="{{$nationality->id}}" {{old('nationality_id',$patient->nationality_id) == $nationality->id ? 'selected' : ''}}>{{$nationality->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('nationality_id'))
                                        <span class="text-danger">{{ $errors->first('nationality_id') }}</span>
                                    @endif

                                </div>
                            </div>

                            {{--Gender--}}
                            <div class="col-md-3">

                                <div class="form-group">

                                    <label class="d-block text-primary" for="gender">Gender</label>


                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male"
                                               value="0" {{old('gender',$patient->gender) == 0 ? 'checked' : ''}}>
                                        <label class="form-check-label" for="male">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female"
                                               value="1" {{old('gender',$patient->gender) == 1 ? 'checked' : ''}}>
                                        <label class="form-check-label" for="female">Male</label>
                                    </div>
                                    @if($errors->has('gender'))
                                        <span class="text-danger">{{ $errors->first('gender') }}</span>
                                    @endif
                                </div>

                            </div>


                            {{--source--}}
                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="text-primary" for="source">Source</label>
                                    <select
                                        class="custom-select form-control  {{$errors->has('source')?'border-danger':''}}"
                                        name="source" id="source">
                                        <option value="">---</option>
                                        @foreach(config('global.patient_sources') as $source)
                                            <option
                                                value="{{$source}}" {{old('source',$patient->source) == $source ? 'selected' : ''}}>{{$source}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('source'))
                                        <span class="text-danger">{{ $errors->first('source') }}</span>
                                    @endif

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="form-section bg-primary">

                    <div class="section-header">
                        <span>Optional Data</span>
                    </div>

                    <div class="section-body">

                        <div class="row">

                            {{--Address--}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-primary" for="address">Address</label>
                                    <input class="form-control {{$errors->has('address')?'border-danger':''}}"
                                           type="text" id="address"
                                           autocomplete="off"
                                           name="address" value="{{old('address',$patient->address)}}">
                                    @if($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{--Phone--}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-primary" for="phone">Phone</label>
                                    <input class="form-control {{$errors->has('phone')?'border-danger':''}}"
                                           type="number" id="phone"
                                           autocomplete="off"
                                           name="phone" value="{{old('phone',$patient->phone)}}">
                                    @if($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{--Blood Group--}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-primary" for="blood_group">Blood Group</label>
                                    <select
                                        class="custom-select form-control {{$errors->has('blood_group')?'border-danger':''}}"
                                        id="blood_group" name="blood_group">
                                        <option value="">---</option>
                                        <option
                                            value="o+" {{old('blood_group',$patient->blood_group) == 'o+' ? 'selected' : ''}}>
                                            O+
                                        </option>
                                        <option
                                            value="o-" {{old('blood_group',$patient->blood_group) == 'o-' ? 'selected' : ''}}>
                                            O-
                                        </option>
                                        <option
                                            value="a+" {{old('blood_group',$patient->blood_group) == 'a+' ? 'selected' : ''}}>
                                            A+
                                        </option>
                                        <option
                                            value="a-" {{old('blood_group',$patient->blood_group) == 'a-' ? 'selected' : ''}}>
                                            A-
                                        </option>
                                        <option
                                            value="b+" {{old('blood_group',$patient->blood_group) == 'b+' ? 'selected' : ''}}>
                                            B+
                                        </option>
                                        <option
                                            value="b-" {{old('blood_group',$patient->blood_group) == 'b-' ? 'selected' : ''}}>
                                            B-
                                        </option>
                                        <option
                                            value="ab+" {{old('blood_group',$patient->blood_group) == 'ab+' ? 'selected' : ''}}>
                                            AB+
                                        </option>
                                        <option
                                            value="ab-" {{old('blood_group',$patient->blood_group) == 'ab-' ? 'selected' : ''}}>
                                            AB-
                                        </option>
                                    </select>
                                    @if($errors->has('blood_group'))
                                        <span class="text-danger">{{ $errors->first('blood_group') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{--Status--}}
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label class="text-primary" for="status">Status</label>
                                    <select
                                        class="custom-select form-control {{$errors->has('status')?'border-danger':''}}"
                                        id="status" name="status">
                                        <option value="">---</option>
                                        <option
                                            value="blocked" {{old('status',$patient->status) == 'blocked' ? 'selected' : ''}}>
                                            Blocked
                                        </option>
                                        <option
                                            value="free" {{old('status',$patient->status) == 'free' ? 'selected' : ''}}>
                                            Free
                                        </option>
                                        <option
                                            value="vip" {{old('status',$patient->status) == 'vip' ? 'selected' : ''}}>
                                            VIP
                                        </option>
                                    </select>
                                    @if($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>

                            </div>

                            {{--Notes--}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-primary" for="notes">Notes</label>
                                    <input class="form-control {{$errors->has('notes')?'border-danger':''}}" type="text"
                                           id="notes"
                                           name="notes" value="{{old('notes',$patient->notes)}}">
                                    @if($errors->has('notes'))
                                        <span class="text-danger">{{ $errors->first('notes') }}</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>


                </div>

            </div>

            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn text-primary">Update</button>
                    <a href="{{route('patients.index')}}" class="btn">Cancel</a>
                </div>
            </div>

        </div>

    </form>


@endsection

@section('scripts')
@endsection
