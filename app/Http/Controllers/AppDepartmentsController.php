<?php

namespace App\Http\Controllers;

use App\Models\AppDepartment;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class AppDepartmentsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('AppDepartments_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $app_departments = AppDepartment::loggedClinic()->withCount('app_devices')->orderBy('id', 'asc')->paginate(10);
        return view('pages.app_departments.index', compact('app_departments'));
    }

    public function create()
    {
        abort_if(Gate::denies('AppDepartments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.app_departments.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('AppDepartments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [
            'name' => [
                'required',
                Rule::unique('app_departments')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('clinic_id', auth()->user()->clinic_id);
                })
            ],
        ];

        $customMessages = [
            'name.unique' => 'Department Name Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = [
            'name' => $request->name,
            'clinic_id' => auth()->user()->clinic_id,
            'active' => $request->active ? 1 : 0,

        ];

        DB::beginTransaction();

        try {

            AppDepartment::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Department Added Successfully');
        return redirect()->route('app_departments.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('AppDepartments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $app_department = AppDepartment::loggedClinic()->findOrFail($id);
        $clinics = Clinic::loggedClinic()->get();
        return view('pages.app_departments.edit', compact('app_department', 'clinics'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('AppDepartments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [
            'name' => [
                'required',
                Rule::unique('app_departments')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('clinic_id', auth()->user()->clinic_id);
                })->ignore($id)
            ],
        ];

        $customMessages = [
            'name.unique' => 'Department Name Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = [
            'name' => $request->name,
            'clinic_id' => auth()->user()->clinic_id,
            'active' => $request->active ? 1 : 0,

        ];


        $app_department = AppDepartment::loggedClinic()->findOrFail($id);

        DB::beginTransaction();

        try {

            $app_department->update($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Department Updated Successfully');
        return redirect()->route('app_departments.index');


    }

    public function destroy($id)
    {
        abort_if(Gate::denies('AppDepartments_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $app_department = AppDepartment::loggedClinic()->findOrFail($id);
//
//        if (Helper::app_department_is_deletable($app_department->id)) {
//
//            $app_department->delete();
//
//            session()->flash('success', 'Department Deleted Successfully');
//            return redirect()->route('app_departments.index');
//        } else {
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('app_departments.index');
//        }
    }
}
