<?php

namespace App\Http\Controllers;

use App\Models\Nationality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class NationalitiesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Nationalities_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nationalities = Nationality::orderBy('name', 'asc')->get();

        return view('pages.nationalities.index', compact('nationalities'));
    }

    public function create()
    {
        abort_if(Gate::denies('Nationalities_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.nationalities.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Nationalities_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $rules = [

            'name' => [

                'required',
                Rule::unique('nationalities')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })
            ],
        ];

        $customMessages = [
            'name.unique' => 'Nationality Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = [
            'name' => $request->name,
        ];

        DB::beginTransaction();

        try {

            Nationality::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Nationality Added Successfully');
        return redirect()->route('nationalities.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('Nationalities_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nationality = Nationality::findOrFail($id);
        return view('pages.nationalities.edit', compact('nationality'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Nationalities_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name' => [

                'required',
                Rule::unique('nationalities')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })->ignore($id)
            ],
        ];

        $customMessages = [
            'name.unique' => 'Nationality Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);


        $nationality = Nationality::findOrFail($id);

        DB::beginTransaction();

        try {

            $nationality->update($request->all());
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Nationality Successfully');
        return redirect()->route('nationalities.index');


    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Nationalities_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $nationality = Nationality::findOrFail($id);
//
//        if (Helper::nationality_is_deletable($nationality->id)) {
//
//            $nationality->delete();
//
//            session()->flash('success', 'Nationality Deleted Successfully');
//            return redirect()->route('nationalities.index');
//        } else {
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('nationalities.index');
//        }
    }
}
