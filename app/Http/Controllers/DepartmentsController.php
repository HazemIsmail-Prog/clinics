<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class DepartmentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Departments_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = Department::loggedClinic()->with('clinic')->orderBy('name', 'asc')->get();
        return view('pages.departments.index', compact('departments'));
    }

    public function create()
    {
        abort_if(Gate::denies('Departments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.departments.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Departments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name' => [

                'required',
                Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('clinic_id', auth()->user()->clinic_id);
                })
            ],
        ];

        $customMessages = [
            'name.unique' => 'Department Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = [
            'name' => $request->name,
            'clinic_id' => auth()->user()->clinic_id,
        ];

        DB::beginTransaction();

        try {

            Department::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Department Added Successfully');
        return redirect()->route('departments.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('Departments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $department = Department::loggedClinic()->findOrFail($id);

        return view('pages.departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Departments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name' => [

                'required',
                Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('clinic_id', auth()->user()->clinic_id);
                })->ignore($id)
            ],
        ];

        $customMessages = [
            'name.unique' => 'Department Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);


        $department = Department::loggedClinic()->findOrFail($id);

        DB::beginTransaction();

        try {

            $department->update($request->all());
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Department Successfully');
        return redirect()->route('departments.index');


    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Departments_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $department = Department::loggedClinic()->findOrFail($id);
//
//        if (Helper::department_is_deletable($department->id)) {
//
//            $department->delete();
//
//            session()->flash('success', 'Department Deleted Successfully');
//            return redirect()->route('departments.index');
//        } else {
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('departments.index');
//        }
    }
}
