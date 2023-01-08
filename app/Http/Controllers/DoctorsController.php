<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class DoctorsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Doctors_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctors = Doctor::loggedClinic()
            ->with(['department', 'account'])
            ->orderBy('department_id')
            ->orderBy('name', 'asc')
            ->get();
        return view('pages.doctors.index', compact('doctors'));
    }

    public function create()
    {
        abort_if(Gate::denies('Doctors_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = Department::loggedClinic()->get();
        $accounts = Account::loggedAccountGroup()->doesnthave('childAccounts')->orderBy('name')->get();
        return view('pages.doctors.create', compact('departments', 'accounts'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Doctors_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name' => [

                'required',
                Rule::unique('doctors')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })
            ],
            'department_id' => 'required',
            'account_id' => 'required',
        ];

        $customMessages = [
            'name.unique' => 'Doctor Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = [
            'name' => $request->name,
            'department_id' => $request->department_id,
            'account_id' => $request->account_id,
        ];

        DB::beginTransaction();

        try {

            Doctor::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Doctor Added Successfully');
        return redirect()->route('doctors.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('Doctors_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctor = Doctor::loggedClinic()->findOrFail($id);
        $departments = Department::loggedClinic()->get();
        $accounts = Account::loggedAccountGroup()->doesnthave('childAccounts')->orderBy('name')->get();


        return view('pages.doctors.edit', compact('doctor', 'departments', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Doctors_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name' => [

                'required',
                Rule::unique('doctors')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })->ignore($id)
            ],
            'department_id' => 'required',
            'account_id' => 'required',
        ];

        $customMessages = [
            'name.unique' => 'Doctor Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);


        $doctor = Doctor::loggedClinic()->findOrFail($id);

        DB::beginTransaction();

        try {

            $doctor->update($request->all());
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Doctor Successfully');
        return redirect()->route('doctors.index');


    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Doctors_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $doctor = Doctor::loggedClinic()->findOrFail($id);
//
//        if (Helper::doctor_is_deletable($doctor->id)) {
//
//            $doctor->delete();
//
//            session()->flash('success', 'Doctor Deleted Successfully');
//            return redirect()->route('doctors.index');
//        } else {
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('doctors.index');
//        }
    }
}
