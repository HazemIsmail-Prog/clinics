<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Nurse;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class CollectionStatementsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_CollectionStatements'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.collection_statements.index');
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('Reports_CollectionStatements'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $filter = $request->filter;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $main_filter = $request->main_filter;
        $view_filter = $request->view_filter;

        switch ($main_filter) {
            case 'users' :
                switch ($view_filter) {
                    case 'detailed' :
                        $data = User::
                        loggedClinic()
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }])
                            ->get();
                        return view('pages.collection_statements.users_detailed', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'daily' :
                        $data = User::
                        loggedClinic()
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("DATE(created_at) as date"),
                                    DB::raw("user_id"),
                                ]);
                                $q->groupBy('date', 'user_id');
                                $q->orderBy('date', 'asc');
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }])
                            ->get();
                        return view('pages.collection_statements.users_daily', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'monthly' :
                        $data = User::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("Year(created_at) as year"),
                                    DB::raw("month(created_at) as month"),
                                    DB::raw("user_id"),
                                ])
                                    ->groupBy('year', 'month', 'user_id')
                                    ->orderBy('created_at', 'asc')
                                    ->whereDate('created_at', '>=', $start_date)
                                    ->whereDate('created_at', '<=', $end_date);

                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.users_monthly', compact('data', 'start_date', 'end_date'));
                        break;
                }
                break;
            case 'users_doctors' :

                if ($filter && array_key_exists('user_filter', $filter)) {
                    $user_filter = $filter['user_filter'];
                } else {
                    $user_filter = null;
                }
                if ($filter && array_key_exists('doctor_filter', $filter)) {
                    $doctor_filter = $filter['doctor_filter'];
                } else {
                    $doctor_filter = null;
                }

                switch ($view_filter) {
                    case 'detailed' :
                        $data = User::
                        loggedClinic()
                            ->when($user_filter, function ($q) use ($user_filter) {
                                $q->whereIn('id', $user_filter);
                            })
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date, $doctor_filter) {
                                $q->with('doctor');
                                $q->orderBy('invoices.created_at', 'asc');
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                                $q->when($doctor_filter, function ($q) use ($doctor_filter) {
                                    $q->whereIn('doctor_id', $doctor_filter);
                                });
                            }])
                            ->get();
                        return view('pages.collection_statements.users_doctors_detailed', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'daily' :
                        $data = User::
                        loggedClinic()
                            ->when($user_filter, function ($q) use ($user_filter) {
                                $q->whereIn('id', $user_filter);
                            })
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date, $doctor_filter) {
                                $q->with('doctor');
                                $q->select([
                                    DB::raw("SUM(invoices.total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("DATE(invoices.created_at) as date"),
                                    DB::raw("doctor_id"),
                                    DB::raw("user_id"),
                                ]);
                                $q->groupBy('date', 'user_id');
                                $q->orderBy('date', 'asc');
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                                $q->when($doctor_filter, function ($q) use ($doctor_filter) {
                                    $q->whereIn('doctor_id', $doctor_filter);
                                });
                            }])
                            ->get();
                        return view('pages.collection_statements.users_doctors_daily', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'monthly' :
                        $data = User::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date, $doctor_filter) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("Year(created_at) as year"),
                                    DB::raw("month(created_at) as month"),
                                    DB::raw("doctor_id"),
                                    DB::raw("user_id"),
                                ]);
                                $q->groupBy('year', 'month', 'user_id');
                                $q->orderBy('created_at', 'asc');
                                $q->whereDate('created_at', '>=', $start_date);
                                $q->whereDate('created_at', '<=', $end_date);
                                $q->when($doctor_filter, function ($q) use ($doctor_filter) {
                                    $q->whereIn('doctor_id', $doctor_filter);
                                });

                            }])
                            ->when($user_filter, function ($q) use ($user_filter) {
                                $q->whereIn('id', $user_filter);
                            })
                            ->get();
                        return view('pages.collection_statements.users_doctors_monthly', compact('data', 'start_date', 'end_date'));
                        break;
                }
                break;
            case 'doctors' :
                switch ($view_filter) {
                    case 'detailed' :
                        $data = Doctor::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                                $q->orderBy('created_at', 'asc');
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.doctors_detailed', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'daily' :
                        $data = Doctor::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("DATE(created_at) as date"),
                                    DB::raw("doctor_id"),
                                ]);
                                $q->groupBy('date', 'doctor_id');
                                $q->orderBy('date', 'asc');
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.doctors_daily', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'monthly' :
                        $data = Doctor::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("Year(created_at) as year"),
                                    DB::raw("month(created_at) as month"),
                                    DB::raw("doctor_id"),
                                ]);
                                $q->groupBy('year', 'month', 'doctor_id');
                                $q->orderBy('created_at', 'asc');
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.doctors_monthly', compact('data', 'start_date', 'end_date'));
                        break;
                }
                break;
            case 'nurses' :
                switch ($view_filter) {
                    case 'detailed' :
                        $data = Nurse::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                                $q->orderBy('created_at', 'asc');
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.nurses_detailed', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'daily' :
                        $data = Nurse::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("DATE(created_at) as date"),
                                    DB::raw("nurse_id"),
                                ]);
                                $q->groupBy('date', 'nurse_id');
                                $q->orderBy('date', 'asc');
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.nurses_daily', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'monthly' :
                        $data = Nurse::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("Year(created_at) as year"),
                                    DB::raw("month(created_at) as month"),
                                    DB::raw("nurse_id"),
                                ]);
                                $q->groupBy('year', 'month', 'nurse_id');
                                $q->orderBy('created_at', 'asc');
                                $q->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.nurses_monthly', compact('data', 'start_date', 'end_date'));
                        break;
                }
                break;
            case 'departments' :
                switch ($view_filter) {
                    case 'detailed' :
                        $data = Department::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.departments_detailed', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'daily' :
                        $data = Department::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("DATE(invoices.created_at) as date"),
                                ]);
                                $q->groupBy('date', 'department_id');
                                $q->orderBy('date', 'asc');
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.departments_daily', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'monthly' :
                        $data = Department::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("Year(invoices.created_at) as year"),
                                    DB::raw("month(invoices.created_at) as month"),
                                ]);
                                $q->groupBy('year', 'month', 'department_id');
                                $q->orderBy('invoices.created_at', 'asc');
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.departments_monthly', compact('data', 'start_date', 'end_date'));
                        break;
                }
                break;
            case 'revenues' :
                switch ($view_filter) {
                    case 'detailed' :
                        $data = Invoice::
                        loggedClinic()
                            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
                            ->orderBy('created_at', 'asc')
                            ->get();
                        return view('pages.collection_statements.revenues_detailed', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'daily' :
                        $data = Invoice::
                        loggedClinic()
                            ->select([
                                DB::raw("SUM(total) as total"),
                                DB::raw("SUM(cash) as cash"),
                                DB::raw("SUM(knet) as knet"),
                                DB::raw("SUM(visa) as visa"),
                                DB::raw("SUM(master) as master"),
                                DB::raw("SUM(knet_link) as knet_link"),
                                DB::raw("SUM(credit_link) as credit_link"),
                                DB::raw("SUM(balance) as balance"),
                                DB::raw("DATE(created_at) as date"),
                            ])
                            ->groupBy('date')
                            ->orderBy('date', 'asc')
                            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
                            ->get();
                        return view('pages.collection_statements.revenues_daily', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'monthly' :
                        $data = Invoice::
                        loggedClinic()
                            ->select([
                                DB::raw("SUM(total) as total"),
                                DB::raw("SUM(cash) as cash"),
                                DB::raw("SUM(knet) as knet"),
                                DB::raw("SUM(visa) as visa"),
                                DB::raw("SUM(master) as master"),
                                DB::raw("SUM(knet_link) as knet_link"),
                                DB::raw("SUM(credit_link) as credit_link"),
                                DB::raw("SUM(balance) as balance"),
                                DB::raw("Year(created_at) as year"),
                                DB::raw("month(created_at) as month"),
                            ])
                            ->groupBy('year', 'month')
                            ->orderBy('created_at', 'asc')
                            ->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date])
                            ->get();
                        return view('pages.collection_statements.revenues_monthly', compact('data', 'start_date', 'end_date'));
                        break;
                }
                break;
            case 'treatments' :
                switch ($view_filter) {
                    case 'detailed' :
                        $data = Treatment::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                                $q->with('doctor');
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.treatments_detailed', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'daily' :
                        $data = Treatment::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(invoices.total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("DATE(invoices.created_at) as date"),
                                ]);
                                $q->groupBy('date', 'treatment_id');
                                $q->orderBy('date', 'asc');
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.treatments_daily', compact('data', 'start_date', 'end_date'));
                        break;
                    case 'monthly' :
                        $data = Treatment::
                        loggedClinic()
                            ->whereHas('invoices', function ($q) use ($start_date, $end_date) {
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            })
                            ->with(['invoices' => function ($q) use ($start_date, $end_date) {
                                $q->select([
                                    DB::raw("SUM(invoices.total) as total"),
                                    DB::raw("SUM(cash) as cash"),
                                    DB::raw("SUM(knet) as knet"),
                                    DB::raw("SUM(visa) as visa"),
                                    DB::raw("SUM(master) as master"),
                                    DB::raw("SUM(knet_link) as knet_link"),
                                    DB::raw("SUM(credit_link) as credit_link"),
                                    DB::raw("SUM(balance) as balance"),
                                    DB::raw("Year(invoices.created_at) as year"),
                                    DB::raw("month(invoices.created_at) as month"),
                                ]);
                                $q->groupBy('year', 'month', 'treatment_id');
                                $q->orderBy('invoices.created_at', 'asc');
                                $q->whereBetween(DB::raw('DATE(invoices.created_at)'), [$start_date, $end_date]);
                            }])
                            ->when($filter, function ($q) use ($filter) {
                                $q->whereIn('id', $filter);
                            })
                            ->get();
                        return view('pages.collection_statements.treatments_monthly', compact('data', 'start_date', 'end_date'));
                        break;
                }
                break;
        }
    }
}
