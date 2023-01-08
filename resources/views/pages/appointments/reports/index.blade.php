@extends('layouts.master')


@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
    <link rel="stylesheet" href="{{asset('assets\plugins\select2\select2.min.css')}}">
@endsection

@section('styles')
    <style>
        #calendar {
            font-size: 12px;
        }

        .fc-event-time {
            width: 100%;
        }

        .fc-event-title.fc-sticky {
            white-space: normal;
        }


        .fc-event {
            /*height: 60px !important;*/
            font-size: 10px;
        }

        .fc-toolbar-chunk:last-child {
            display: none;

        }

        .fc-toolbar-title {
            font-size: 12px !important;
            margin-bottom: -14px !important;
            /*margin-left: 14px !important;*/
        }

        .select2-selection__choice__remove{
            font-size: 10px;
        }
        .select2-selection__choice__display{
            font-size: 10px;
        }


    </style>
@endsection

@section('content')



    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Appointments Reports</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="accordion" id="accordionExample">

                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn text-primary btn-sm btn-block" type="button"
                                    data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">
                                Monthly Statistics
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                         data-parent="#accordionExample">
                        <div class="card-body">
                            <form action="{{route('appointments.monthly_stats')}}" target="_blank">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-primary" for="month">Month</label>
                                            <select class="form-control custom-select text-center" name="month"
                                                    id="month">
                                                <option value="1">Jan</option>
                                                <option value="2">Feb</option>
                                                <option value="3">Mar</option>
                                                <option value="4">Apr</option>
                                                <option value="5">May</option>
                                                <option value="6">Jun</option>
                                                <option value="7">Jul</option>
                                                <option value="8">Aug</option>
                                                <option value="9">Sep</option>
                                                <option value="10">Oct</option>
                                                <option value="11">Nov</option>
                                                <option value="12">Dec</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-primary" for="year">Year</label>
                                            <select class="form-control custom-select text-center" name="year"
                                                    id="year">
                                                @foreach($years as $year)
                                                    <option value="{{$year}}">{{$year}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 d-flex align-self-end justify-content-center">
                                        <div class="form-group text-center">
                                            <button class="btn btn-sm text-primary" type="submit">Get Report</button>
                                        </div>
                                    </div>

                                </div>


                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn text-primary btn-block btn-sm collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                Appointments Register
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <form action="{{route('appointments.app_register')}}" target="_blank">

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="start">Start</label>
                                            <input required type="date" value="{{Carbon\Carbon::today()->format('Y-m-d')}}" class="form-control" name="start" id="start">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="end">End</label>
                                            <input required type="date" value="{{Carbon\Carbon::today()->format('Y-m-d')}}" class="form-control" name="end" id="end">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="devices">Devices</label>
                                            <select style="width: 100%" class="js-example-basic-multiple" name="devices[]" id="devices" multiple>
                                                @foreach($devices as $device)
                                                    <option value="{{$device->id}}">{{$device->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="statuses">Status</label>
                                            <select style="width: 100%" class="form-control js-example-basic-multiple" name="statuses[]" id="statuses" multiple>
                                                @foreach($statuses as $status)
                                                    <option value="{{$status->id}}">{{$status->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="users">Users</label>
                                            <select style="width: 100%" class="form-control js-example-basic-multiple" name="users[]" id="users" multiple>
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="nurses">Nurses</label>
                                            <select style="width: 100%" class="form-control js-example-basic-multiple" name="nurses[]" id="nurses" multiple>
                                                @foreach($nurses as $nurse)
                                                    <option value="{{$nurse->id}}">{{$nurse->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-flex align-self-end justify-content-center">
                                        <div class="form-group text-center">
                                            <button class="btn btn-sm text-primary" type="submit">Get Report</button>
                                        </div>
                                    </div>

                                </div>


                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn text-primary btn-block btn-sm collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                Patient Visit
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                         data-parent="#accordionExample">
                        <div class="card-body">


                            <form action="{{route('appointments.patients_visit')}}" target="_blank">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-primary" for="start">Start</label>
                                            <input required type="date" value="{{Carbon\Carbon::today()->format('Y-m-d')}}" class="form-control" name="start" id="start">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-primary" for="end">End</label>
                                            <input required type="date" value="{{Carbon\Carbon::today()->format('Y-m-d')}}" class="form-control" name="end" id="end">
                                        </div>
                                    </div>

                                    <div class="col-md-4 d-flex align-self-end justify-content-center">
                                        <div class="form-group text-center">
                                            <button class="btn btn-sm text-primary" type="submit">Get Report</button>
                                        </div>
                                    </div>

                                </div>


                            </form>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                            <button class="btn text-primary btn-block btn-sm collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                                    aria-controls="collapseFour">
                                الاحصائية الشهرية
                            </button>
                        </h2>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                         data-parent="#accordionExample">
                        <div class="card-body">
                            <form action="{{route('appointments.monthly_stats_ar')}}" target="_blank">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-primary" for="month">الشهر</label>
                                            <select class="form-control custom-select text-center" name="month"
                                                    id="month">
                                                <option value="1">يناير</option>
                                                <option value="2">فبراير</option>
                                                <option value="3">مارس</option>
                                                <option value="4">أبريل</option>
                                                <option value="5">مايو</option>
                                                <option value="6">يونيو</option>
                                                <option value="7">يوليو</option>
                                                <option value="8">أغسطس</option>
                                                <option value="9">سبتمبر</option>
                                                <option value="10">أكتوبر</option>
                                                <option value="11">نوفمبر</option>
                                                <option value="12">ديسمبر</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-primary" for="year">السنة</label>
                                            <select class="form-control custom-select text-center" name="year"
                                                    id="year">
                                                @foreach($years as $year)
                                                    <option value="{{$year}}">{{$year}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 d-flex align-self-end justify-content-center">
                                        <div class="form-group text-center">
                                            <button class="btn btn-sm text-primary" type="submit">عرض التقرير</button>
                                        </div>
                                    </div>









                                </div>


                            </form>

                        </div>
                    </div>
                </div>

            </div>


        </div>


    </div>

@endsection

@section('scripts')
    <script src="{{asset('assets\plugins\select2\select2.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>

@endsection


