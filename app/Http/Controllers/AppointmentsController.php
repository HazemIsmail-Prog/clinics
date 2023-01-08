<?php

namespace App\Http\Controllers;

use App\Models\AppDepartment;
use App\Models\AppDevice;
use App\Models\Appointment;
use App\Models\AppStatus;
use App\Models\Nurse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class AppointmentsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('Appointments_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->app_department) {
            $app_department = AppDepartment::loggedClinic()->findOrFail($request->app_department)->id;
            $date = $request->date;
        } else {
            $app_department = AppDepartment::loggedClinic()->first()->id;
            $date = date('Y-m-d');
        }

        return view('pages.appointments.index', compact('app_department', 'date'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('Appointments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $start = $request->start;
        $end = $request->end;
        $app_department = AppDepartment::loggedClinic()->findOrFail($request->app_department)->id;
        $device_id = $request->device_id;
        $date = $request->date;

        return view('pages.appointments.create', compact('start', 'end', 'app_department', 'device_id', 'date'));
    }

    public function edit($id)
    {
        abort_if(Gate::denies('Appointments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $current_appointment = Appointment::loggedClinic()->findOrFail($id);
        $app_department = $current_appointment->app_device->app_department->id;
        return view('pages.appointments.edit', compact('current_appointment', 'app_department'));

    }

    public function print(Request $request)
    {
        abort_if(Gate::denies('Appointments_Read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $department_name = AppDepartment::loggedClinic()->findOrFail($request->department)->name;
        $date = $request->date;

        $devices = AppDevice::
        loggedClinic()
            ->where('app_department_id', $request->department)
            ->whereHas('appointments', function ($q) use ($request) {
                $q->where('date', $request->date);
            })
            ->with(['appointments' => function ($q) use ($request) {
                $q->where('date', $request->date);
                $q->orderBy('start', 'asc');
                $q->with('status', 'nurse');
            }])
            ->orderBy('sorting')
            ->get();

        return view('pages.appointments.print', compact('department_name', 'date', 'devices'));

    }

    public function search(Request $request)
    {
        abort_if(Gate::denies('Appointments_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(Gate::denies('Appointments_Read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointments = Appointment::
        loggedClinic()
            ->with(['app_device' => function ($q) {
                $q->with('app_department');
            }, 'nurse', 'status'])
            ->when($request->patient_file_no, function ($q1) use ($request) {
                $q1->where('patient_file_no', $request->patient_file_no);
            })
            ->when($request->key, function ($q2) use ($request) {
                $q2->where(function ($q) use ($request) {
                    $q->where('name', 'like', $request->key . '%')
                        ->orWhere('mobile', 'like', $request->key . '%')
                        ->orWhere('civil_id', 'like', $request->key . '%');
                });
            })
            ->orderBy('date', 'desc')
            ->orderBy('start')
            ->paginate(10);
        return view('pages.appointments.search', compact('appointments'));

    }

    public function ministry_book(Request $request)
    {
        abort_if(Gate::denies('Appointments_Read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $date = $request->date;
        $appointments = Appointment::
        loggedClinic()
            ->where('date', $date)
            ->where('status_id', 3)
            ->with(['nationality', 'patient'])
            ->get();
        return view('pages.appointments.ministry_book', compact('date', 'appointments'));
    }

    public function reports_index()
    {
        abort_if(Gate::denies('Reports_Appointments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $result = Appointment::loggedClinic()->select(DB::raw('YEAR(date) as year'))->distinct()->get();
        $years = $result->pluck('year');
        $devices = AppDevice::loggedClinic()->whereHas('appointments')->get();
        $statuses = AppStatus::whereHas('appointments')->get();
        $users = User::loggedClinic()->whereHas('created_appointments')->get();
        $nurses = Nurse::loggedClinic()->whereHas('appointments')->get();
        return view('pages.appointments.reports.index', compact('years', 'devices', 'statuses', 'users', 'nurses'));
    }

    public function monthly_stats(Request $request)
    {
        abort_if(Gate::denies('Reports_Appointments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $month = $request->month;
        $year = $request->year;
        $departments = $this->get_monthly_statistics($month, $year);
        return view('pages.appointments.reports.monthly_stats', compact('departments', 'month', 'year'));
    }

    public function monthly_stats_ar(Request $request)
    {
        abort_if(Gate::denies('Reports_Appointments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $month = $request->month;
        $year = $request->year;
        $departments = $this->get_monthly_statistics($month, $year);
        return view('pages.appointments.reports.monthly_stats_ar', compact('departments', 'month', 'year'));
    }

    private function get_monthly_statistics($month, $year)
    {
        return $departments = AppDepartment::
        loggedClinic()
            ->whereHas('app_devices')
            ->with(['app_devices' => function ($q) use ($month, $year) {
                $q->withCount(['appointments as NewMaleKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->select(DB::raw('COUNT(*)'))
                        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                        ->whereRaw('appointments.date = DATE(patients.created_at)');
                    //Male
                    $q2->where('appointments.gender', 1);
                    //Kwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '=', 2);
                }]);
                $q->withCount(['appointments as NewMaleKwtNoFile' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->where('appointments.patient_file_no',0);
                    //Male
                    $q2->where('appointments.gender', 1);
                    //Kwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '=', 2);
                }]);
                $q->withCount(['appointments as NewMaleNonKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->select(DB::raw('COUNT(*)'))
                        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                        ->whereRaw('appointments.date = DATE(patients.created_at)');
                    //Male
                    $q2->where('appointments.gender', 1);
                    //NonKwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as NewMaleNonKwtNoFile' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->where('appointments.patient_file_no',0);
                    //Male
                    $q2->where('appointments.gender', 1);
                    //NonKwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as NewFemaleKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->select(DB::raw('COUNT(*)'))
                        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                        ->whereRaw('appointments.date = DATE(patients.created_at)');
                    //Female
                    $q2->where('appointments.gender', 0);
                    //Kwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '=', 2);
                }]);
                $q->withCount(['appointments as NewFemaleKwtNoFile' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->where('appointments.patient_file_no',0);
                    //Female
                    $q2->where('appointments.gender', 0);
                    //Kwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '=', 2);
                }]);
                $q->withCount(['appointments as NewFemaleNonKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->select(DB::raw('COUNT(*)'));
                    $q2->join('patients', 'appointments.patient_id', '=', 'patients.id');
                    $q2->whereRaw('appointments.date = DATE(patients.created_at)');
                    //Female
                    $q2->where('appointments.gender', 0);
                    //NonKwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as NewFemaleNonKwtNoFile' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //New
                    $q2->where('appointments.patient_file_no',0);
                    //Female
                    $q2->where('appointments.gender', 0);
                    //NonKwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as FollowUpMaleKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //Old
                    $q2->select(DB::raw('COUNT(*)'))
                        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                        ->where(function ($q3) {
                            $q3->whereRaw('appointments.date != DATE(patients.created_at)');
                            $q3->orWhereNull('patients.created_at');
                        });
                    //Male
                    $q2->where('appointments.gender', 1);
                    //Kwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '=', 2);
                }]);
                $q->withCount(['appointments as FollowUpMaleNonKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //Old
                    $q2->select(DB::raw('COUNT(*)'))
                        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                        ->where(function ($q3) {
                            $q3->whereRaw('appointments.date != DATE(patients.created_at)');
                            $q3->orWhereNull('patients.created_at');
                        });
                    //Male
                    $q2->where('appointments.gender', 1);
                    //NonKwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as FollowUpFemaleKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //Old
                    $q2->select(DB::raw('COUNT(*)'))
                        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                        ->where(function ($q3) {
                            $q3->whereRaw('appointments.date != DATE(patients.created_at)');
                            $q3->orWhereNull('patients.created_at');
                        });
                    //Female
                    $q2->where('appointments.gender', 0);
                    //Kwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '=', 2);
                }]);
                $q->withCount(['appointments as FollowUpFemaleNonKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    //Old
                    $q2->select(DB::raw('COUNT(*)'))
                        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                        ->where(function ($q3) {
                            $q3->whereRaw('appointments.date != DATE(patients.created_at)');
                            $q3->orWhereNull('patients.created_at');
                        });
                    //Female
                    $q2->where('appointments.gender', 0);
                    //NonKwt
                    $q2->whereNotNull('appointments.nationality_id');
                    $q2->where('appointments.nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as MaleKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    $q2->where('gender', 1);
                    $q2->whereNotNull('nationality_id');
                    $q2->where('nationality_id', 2);
                }]);
                $q->withCount(['appointments as MaleNonKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    $q2->where('gender', 1);
                    $q2->whereNotNull('nationality_id');
                    $q2->where('nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as FemaleKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    $q2->where('gender', 0);
                    $q2->whereNotNull('nationality_id');
                    $q2->where('nationality_id', 2);
                }]);
                $q->withCount(['appointments as FemaleNonKwt' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    $q2->where('gender', 0);
                    $q2->whereNotNull('nationality_id');
                    $q2->where('nationality_id', '!=', 2);
                }]);
                $q->withCount(['appointments as total' => function ($q2) use ($month, $year) {
                    $q2->where('status_id', 3);
                    $q2->whereMonth('date', $month);
                    $q2->whereYear('date', $year);
                    $q2->whereNotNull('nationality_id');
                }]);
            }])
            ->get();
    }

    public function patients_visit(Request $request)
    {
        abort_if(Gate::denies('Reports_Appointments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $start = $request->start;
        $end = $request->end;

        $appointments = Appointment::
        loggedClinic()
            ->with(['app_device', 'nationality'])
            ->where('status_id', 3)
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->orderBy('device_id')
            ->orderBy('start')
            ->get();

        return view('pages.appointments.reports.patients_visit', compact('appointments', 'start', 'end'));
    }

    public function app_register(Request $request)
    {
        abort_if(Gate::denies('Reports_Appointments'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $start = $request->start;
        $end = $request->end;
        $device = $request->devices;
        $status = $request->statuses;
        $user = $request->users;
        $nurse = $request->nurses;

        $appointments = Appointment::
        loggedClinic()
            ->with(['status', 'app_device', 'creator', 'nurse'])
            ->whereBetween('date', [$start, $end])
            ->when($device, function ($q) use ($device) {
                return $q->whereIn('device_id', $device);
            })
            ->when($status, function ($q) use ($status) {
                return $q->whereIn('status_id', $status);
            })
            ->when($user, function ($q) use ($user) {
                return $q->whereIn('created_by', $user);
            })
            ->when($nurse, function ($q) use ($nurse) {
                return $q->whereIn('nurse_id', $nurse);
            })
            ->orderBy('date')
            ->orderBy('device_id')
            ->orderBy('start')
            ->get();


        return view('pages.appointments.reports.app_register', compact('appointments', 'start', 'end'));
    }


}
