<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Nurse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class NursesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Nurses_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nurses = Nurse::with('department')->loggedClinic()->orderBy('name', 'asc')->get();

        return view('pages.nurses.index', compact('nurses'));
    }

    public function create()
    {
        abort_if(Gate::denies('Nurses_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = Department::loggedClinic()->get();
        return view('pages.nurses.create', compact('departments'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Nurses_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name' => [

                'required',
                Rule::unique('nurses')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })
            ],
            'department_id' => 'required',
        ];

        $customMessages = [
            'name.unique' => 'Nurse Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = [
            'name' => $request->name,
            'department_id' => $request->department_id,
        ];

        DB::beginTransaction();

        try {

            Nurse::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Nurse Added Successfully');
        return redirect()->route('nurses.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('Nurses_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nurse = Nurse::with('department')->loggedClinic()->findOrFail($id);
        $departments = Department::loggedClinic()->get();

        return view('pages.nurses.edit', compact('nurse', 'departments'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Nurses_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name' => [

                'required',
                Rule::unique('nurses')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })->ignore($id)
            ],
            'department_id' => 'required',
        ];

        $customMessages = [
            'name.unique' => 'Nurse Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);


        $nurse = Nurse::loggedClinic()->findOrFail($id);

        DB::beginTransaction();

        try {

            $nurse->update($request->all());
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Nurse Successfully');
        return redirect()->route('nurses.index');


    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Nurses_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $nurse = Nurse::loggedClinic()->findOrFail($id);
//
//        if (Helper::nurse_is_deletable($nurse->id)) {
//
//            $nurse->delete();
//
//            session()->flash('success', 'Nurse Deleted Successfully');
//            return redirect()->route('nurses.index');
//        } else {
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('nurses.index');
//        }
    }
}
