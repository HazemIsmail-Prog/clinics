@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Dashboard')

@section('content')
    <!-- Page Heading -->
    {{--    <div class="d-sm-flex align-items-center justify-content-between mb-4">--}}
    {{--        <h1 class="h3 mb-0 text-gray-800">hhhhhh</h1>--}}
    {{--    </div>--}}


    {{--    <div class="row">--}}

    {{--        <!-- Earnings (Monthly) Card Example -->--}}
    {{--        <div class="col-xl-3 col-md-6 mb-4">--}}
    {{--            <div class="card border-left-primary shadow h-100 py-2">--}}
    {{--                <div class="card-body">--}}
    {{--                    <div class="row no-gutters align-items-center">--}}
    {{--                        <div class="col mr-2">--}}
    {{--                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">--}}
    {{--                                Earnings (Monthly)--}}
    {{--                            </div>--}}
    {{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>--}}
    {{--                        </div>--}}
    {{--                        <div class="col-auto">--}}
    {{--                            <i class="fas fa-calendar fa-2x text-gray-300"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <!-- Earnings (Monthly) Card Example -->--}}
    {{--        <div class="col-xl-3 col-md-6 mb-4">--}}
    {{--            <div class="card border-left-success shadow h-100 py-2">--}}
    {{--                <div class="card-body">--}}
    {{--                    <div class="row no-gutters align-items-center">--}}
    {{--                        <div class="col mr-2">--}}
    {{--                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">--}}
    {{--                                Earnings (Annual)--}}
    {{--                            </div>--}}
    {{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>--}}
    {{--                        </div>--}}
    {{--                        <div class="col-auto">--}}
    {{--                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <!-- Earnings (Monthly) Card Example -->--}}
    {{--        <div class="col-xl-3 col-md-6 mb-4">--}}
    {{--            <div class="card border-left-info shadow h-100 py-2">--}}
    {{--                <div class="card-body">--}}
    {{--                    <div class="row no-gutters align-items-center">--}}
    {{--                        <div class="col mr-2">--}}
    {{--                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks--}}
    {{--                            </div>--}}
    {{--                            <div class="row no-gutters align-items-center">--}}
    {{--                                <div class="col-auto">--}}
    {{--                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>--}}
    {{--                                </div>--}}
    {{--                                <div class="col">--}}
    {{--                                    <div class="progress progress-sm mr-2">--}}
    {{--                                        <div class="progress-bar bg-info" role="progressbar"--}}
    {{--                                             style="width: 50%" aria-valuenow="50" aria-valuemin="0"--}}
    {{--                                             aria-valuemax="100"></div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="col-auto">--}}
    {{--                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <!-- Pending Requests Card Example -->--}}
    {{--        <div class="col-xl-3 col-md-6 mb-4">--}}
    {{--            <div class="card border-left-warning shadow h-100 py-2">--}}
    {{--                <div class="card-body">--}}
    {{--                    <div class="row no-gutters align-items-center">--}}
    {{--                        <div class="col mr-2">--}}
    {{--                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">--}}
    {{--                                Pending Requests--}}
    {{--                            </div>--}}
    {{--                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>--}}
    {{--                        </div>--}}
    {{--                        <div class="col-auto">--}}
    {{--                            <i class="fas fa-comments fa-2x text-gray-300"></i>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="row">
        <div class="col-md-8">

            @can('Dashboard_ErrorVouchers')
                @if($error_vouchers->count() > 0)
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-danger">
                                <h6 class="m-0 font-weight-bold text-white">Error Vouchers</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th class="text-left">Voucher #</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Credit</th>
                                    </tr>
                                    </thead>
                                    @foreach($error_vouchers as $row)
                                        <tr>
                                            <td class="text-left">{{$row->voucher_no}}</td>
                                            <td class="text-center">{{strtoupper($row->voucher_type)}}</td>
                                            <td class="text-right">{{$row->voucher_details->sum('debit') > 0 ? number_format($row->voucher_details->sum('debit'),3) : '-'}}</td>
                                            <td class="text-right">{{$row->voucher_details->sum('credit') > 0 ? number_format($row->voucher_details->sum('credit'),3) : '-'}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            @can('Dashboard_YearlyRevenueChart')
            <!-- Area Chart -->
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Yearly Revenue Chart</h6>
                            {{--                            <div class="dropdown no-arrow">--}}
                            {{--                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"--}}
                            {{--                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                            {{--                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>--}}
                            {{--                                </a>--}}
                            {{--                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"--}}
                            {{--                                     aria-labelledby="dropdownMenuLink">--}}
                            {{--                                    <div class="dropdown-header">Dropdown Header:</div>--}}
                            {{--                                    <a class="dropdown-item" href="#">Action</a>--}}
                            {{--                                    <a class="dropdown-item" href="#">Another action</a>--}}
                            {{--                                    <div class="dropdown-divider"></div>--}}
                            {{--                                    <a class="dropdown-item" href="#">Something else here</a>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('Dashboard_UpcomingBirthdays')

                <div class="col-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-birthday-cake"
                                                                             aria-hidden="true"></i>
                                Upcoming Birthdays <span
                                    class="badge badge-pill badge-primary">{{$this_week_birthdays->count()}}</span></h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body" style="overflow-y: scroll;max-height: 360px">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>File #</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Birthday</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($this_week_birthdays as $row)
                                    <tr>
                                        <td>
                                            <a href="{{route('patients.index',['patient_file_no'=>$row->file_no])}}">
                                                {{$row->file_no}}
                                            </a>
                                        </td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->mobile}}</td>
                                        <td>{{date('d-m-Y',strtotime($row->birthday))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4">{{$this_week_birthdays->links()}}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endcan

        </div>

        <div class="col-md-4">
        @can('Dashboard_PieChart')
            <!-- Pie Chart -->
                <div class="col-12">
                    @livewire('pie-chart')
                </div>
        @endcan
        @can('Dashboard_Offers')
            {{-- Offers --}}
            <div class="col-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-percent"
                                                                         aria-hidden="true"></i>
                            Current Offers <span
                                class="badge badge-pill badge-primary">{{$offers->count()}}</span></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body" style="overflow-y: scroll;max-height: 360px">
                        <table class="table table-sm w-100">
                            <thead>
                            <tr>
                                <th>Offer</th>
                                {{-- <th>Start</th> --}}
                                <th class=" text-right">End</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($offers as $row)
                                <tr>
                                    <td class=" text-wrap w-50">{{$row->description}}</td>
                                    {{-- <td>{{date('d-m-Y',strtotime($row->start))}}</td> --}}
                                    <td class=" text-right">{{date('d-m-Y',strtotime($row->end))}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endcan
        @can('Dashboard_AppStatuses')
            <!-- Pie Chart -->
                <div class="col-12">
                    @livewire('daily-appointments-widget',['current_date'=>date('Y-m-d')])
                </div>
            @endcan
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Chart -->
    <script src="{{asset('assets\theme\vendor\chart.js\Chart.js')}}"></script>

    <script>


        var get_data = {!! json_encode($line_chart)  !!};
        var labels = [];
        var data = [];
        get_data.forEach(element => labels.push(element['labels']));
        get_data.forEach(element => data.push(element['data']));


        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }


        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                // labels: ["Jan 2020", "Feb 2020", "Mar 2020", "Apr 2020", "May 2020", "Jun 2020", "Jul 2020", "Aug 2020", "Sep 2020", "Oct 2020", "Nov 2020", "Dec 2020"],
                labels: labels,
                datasets: [{
                    label: "Revenue",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    // data: [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000],
                    data: data,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 12
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return number_format(value) + ' KWD';
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel) + ' KWD';
                        }
                    }
                }
            }
        });

    </script>

    @stack('scripts')

@endsection
