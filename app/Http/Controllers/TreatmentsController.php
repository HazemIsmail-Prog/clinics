<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class TreatmentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Treatments_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $treatments = Treatment::
        with('department')
            ->loggedClinic()
            ->orderBy('department_id')
            ->orderBy('name', 'asc')->get();

        return view('pages.treatments.index', compact('treatments'));
    }

    public function create()
    {
        abort_if(Gate::denies('Treatments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = Department::loggedClinic()->get();
        return view('pages.treatments.create', compact('departments'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Treatments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $rules = [

            'name' => [

                'required',
                Rule::unique('treatments')->where(function ($query) use ($request) {
                    return $query->where('department_id',$request->department_is)->where('name', $request->name);
                })
            ],
            'department_id' => 'required',
        ];

        $customMessages = [
            'name.unique' => 'Treatment Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'department_id' => $request->department_id,
            'active' => $request->active ? 1: 0,
        ];

        DB::beginTransaction();

        try {

            Treatment::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Treatment Added Successfully');
        return redirect()->route('treatments.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('Treatments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $treatment = Treatment::loggedClinic()->findOrFail($id);
        $departments = Department::loggedClinic()->get();

        return view('pages.treatments.edit', compact('treatment', 'departments'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Treatments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $rules = [


            'name' => [

                'required',
                Rule::unique('treatments')->where(function ($query) use ($request) {
                    return $query->where('department_id',$request->department_is)->where('name', $request->name);
                })->ignore($id)
            ],
            'department_id' => 'required',
        ];

        $customMessages = [
            'name.unique' => 'Treatment Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);


        $treatment = Treatment::loggedClinic()->findOrFail($id);

        $data = [
                'name' => $request->name,
                'price' => $request->price,
                'department_id' => $request->department_id,
                'active' => $request->active ? 1: 0,
        ];

        DB::beginTransaction();

        try {

            $treatment->update($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Treatment Successfully');
        return redirect()->route('treatments.index');


    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Treatments_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $treatment = Treatment::loggedClinic()->findOrFail($id);
//
//        if (Helper::treatment_is_deletable($treatment->id)) {
//
//            $treatment->delete();
//
//            session()->flash('success', 'Treatment Deleted Successfully');
//            return redirect()->route('treatments.index');
//        } else {
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('treatments.index');
//        }
    }
}
