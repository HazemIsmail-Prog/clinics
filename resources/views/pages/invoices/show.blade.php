@extends('layouts.print_p')

<title>Invoice - {{$invoice->invoice_no}}</title>

@section('styles')
    <style>
        @page {
            size: letter portrait;
        }



        table tbody td,
        table tbody th,
        table thead th,
        table thead td {
            font-size: 1.2rem;
        }



        table th{
            background: white;
            font-weight: bold;
            color: #000;
        }
        th,
        td{
            border: 0 !important;
        }

        table thead th{
            color: white;
            background: #a0976b !important;
        }
    </style>
@endsection

@section('content')

    <div class="container">
            <div class="text-center">
                <img style="max-height: 60px;" src="{{asset('assets/clinics_logos/'.auth()->user()->clinic->logo)}}"
                     alt="{{auth()->user()->clinic->logo}}">
            </div>

        <div class="page-footer">
            <hr>
            <div>{{auth()->user()->clinic->address}}</div>
        </div>
        <div class="divider"></div>

        <hr>
        <div class="text-center" style="padding: 5px;color: #1478cb;font-weight: bold">

            {{$invoice->type == "Balance Invoice" ? 'Balance Payment Invoice' : 'Invoice'}}

        </div>
        <hr>
        <div class="divider"></div>


        <div style="float: left;width: 49%;">
            <table>
                <tr>
                    <th style="border: 0 !important;" class="text-right">Invoice No.</th>
                    <td style="border: 0 !important;">{{$invoice->invoice_no}}</td>
                </tr>

                <tr>
                    <th style="border: 0 !important;" class="text-right">File No.</th>
                    <td style="border: 0 !important;">{{$invoice->patient->file_no}}</td>
                </tr>

                <tr>
                    <th style="border: 0 !important;" class="text-right">Patient Name</th>
                    <td style="border: 0 !important;">{{$invoice->patient->name}}</td>
                </tr>
            </table>

        </div>

        <div style="float: right;width: 49%;">

            <table>
                <tr>
                    <th style="border: 0 !important;" class="text-right">Date</th>
                    <td style="border: 0 !important;">{{date('d-m-Y', strtotime($invoice->created_at))}}</td>
                </tr>

                <tr>
                    <th style="border: 0 !important;" class="text-right">Time</th>
                    <td style="border: 0 !important;">{{date('h:i a', strtotime($invoice->created_at))}}</td>
                </tr>


                <tr>
                    <th style="border: 0 !important;" class="text-right">Department</th>
                    <td style="border: 0 !important;">{{$invoice->doctor->department->name}}</td>
                </tr>

                <tr>
                    <th style="border: 0 !important;" class="text-right">Doctor</th>
                    <td style="border: 0 !important;">{{$invoice->doctor->name}}</td>
                </tr>

                @if($invoice->nurse_id)
                    <tr>
                        <th style="border: 0 !important;" class="text-right">Nurse</th>
                        <td style="border: 0 !important;">{{$invoice->nurse->name}}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="divider"></div>

        @if($invoice->type != "Balance Invoice")
            <table>
                <thead>
                <tr>
                    <th style="border-left: 0 !important;border-right: 0 !important;" class="text-left">Treatments</th>
                    <th style="border-left: 0 !important;border-right: 0 !important;" class="text-right">Unit Price</th>
                    <th style="border-left: 0 !important;border-right: 0 !important;" class="text-center">Qty</th>
                    <th style="border-left: 0 !important;border-right: 0 !important;" class="text-right">Discount</th>
                    <th style="border-left: 0 !important;border-right: 0 !important;" class="text-right">Treatment Total</th>

                </tr>
                </thead>
                <tbody>
                @foreach($invoice->invoice_details as $detail)
                    <tr>

                        <td style="border-left: 0 !important;border-right: 0 !important;" class="text-left">{{$detail['treatment_name']}}</td>
                        <td style="border-left: 0 !important;border-right: 0 !important;" class="text-right">{{$detail['treatment_unit_price'] > 0 ? number_format($detail['treatment_unit_price'], 3) : '-'}}</td>
                        <td style="border-left: 0 !important;border-right: 0 !important;" class="text-center">{{$detail['qty']}}</td>
                        <td style="border-left: 0 !important;border-right: 0 !important;" class="text-right text-danger">
                            @if($detail['discount_type'] == 'percentage')
                                {{$detail['discount'] > 0 ? $detail['discount'] : '-'}} %
                            @else
                                {{$detail['discount'] > 0 ? number_format($detail['discount'], 3) : '-'}}
                            @endif
                        </td>
                        <td style="border-left: 0 !important;border-right: 0 !important;" class="text-right">{{$detail['total'] > 0 ? number_format($detail['total'], 3) : '-'}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @else
            <table>
                <thead>
                <tr>
                    <th class="text-left">Balance Reference</th>
                    <th class="text-right">Balance Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: left">Balance for Invoice No.
                        : {{\App\Models\Invoice::findOrFail($invoice->ref)->invoice_no }}</td>
                    <td style="text-align: right">{{number_format($invoice->total, 3)}}</td>
                </tr>
                </tbody>
            </table>
        @endif

        <div class="divider"></div>

        <div style="float: right;width: 30%;">

            <table>
                <tr>
                    <th class="text-right">Net Amount</th>
                    <td class="text-right">{{number_format($invoice->total, 3)}}</td>
                </tr>

                @if($invoice->cash > 0)
                    <tr>
                        <th class="text-right">Cash</th>
                        <td class="text-right">{{number_format($invoice->cash, 3)}}</td>
                    </tr>
                @endif

                @if($invoice->knet > 0)
                    <tr>
                        <th class="text-right">K-Net</th>
                        <td class="text-right">{{number_format($invoice->knet, 3)}}</td>
                    </tr>
                @endif
                @if($invoice->visa > 0)

                    <tr>
                        <th class="text-right">Visa</th>
                        <td class="text-right">{{number_format($invoice->visa, 3)}}</td>
                    </tr>
                @endif
                @if($invoice->master > 0)

                    <tr>
                        <th class="text-right">Master Card</th>
                        <td class="text-right">{{number_format($invoice->master, 3)}}</td>
                    </tr>
                @endif
                @if($invoice->knet_link > 0)

                    <tr>
                        <th class="text-right">K-Net Link</th>
                        <td class="text-right">{{number_format($invoice->knet_link, 3)}}</td>
                    </tr>
                @endif
                @if($invoice->credit_link > 0)

                    <tr>
                        <th class="text-right">Credit Card Link</th>
                        <td class="text-right">{{number_format($invoice->credit_link, 3)}}</td>
                    </tr>
                @endif
                @if($invoice->balance > 0)

                    <tr>
                        <th class="text-right">Balance</th>
                        <td class="text-right">{{number_format($invoice->balance, 3)}}</td>
                    </tr>
                @endif

                @if(\App\Models\Patient::find($invoice->patient->id)->balances->sum('amount') > 0)

                    <tr>
                        <th class="text-right">Total Balance</th>
                        <td class="text-right">{{number_format(\App\Models\Patient::find($invoice->patient->id)->balances->sum('amount'), 3)}}</td>
                    </tr>
                @endif

            </table>

            @if($invoice->type == "Insurance Invoice")

                <div style="font-size: 1rem;text-align: right;color: red">{{$invoice->type}}</div>

            @endif

        </div>

        <div class="divider"></div>

        <hr>

        <div style="font-size: 1.2rem;font-weight: bold;">Created By</div>
        <div style="font-size: 1rem;">{{$invoice->user->name}}</div>


        <div class="text-right">
            <a class="btn noprint" href="#" onclick="event.preventDefault();window.print();">Print</a>
            <a style="background: #a0976b; color: white" class="btn noprint" href="{{route('patients.index')}}">Back to Patients List</a>
        </div>



    </div>




@endsection

@section('scripts')

    <script>
        window.print();
    </script>

@endsection




