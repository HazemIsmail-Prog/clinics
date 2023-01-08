<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClinicsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Clinics_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $clinics = Clinic::loggedClinic()->get();
        return view('pages.clinics.index',compact('clinics'));
    }

    public function create()
    {
        abort_if(Gate::denies('Clinics_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.clinics.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Clinics_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = \Validator::make(request()->all(), [

            'name' => 'required',
            'ar_name' => 'required',
            'address' => 'required',
            'color' => 'required',
            'logo' => 'required|image|mimes:jpg,png,jpeg,gif,svg',

        ]);
        $validator->validate();

        $clinic = Clinic::create($request->except('logo'));
        $filename = 'clinic (' . $clinic->id . ').' . $request->logo->getClientOriginalExtension();
        $request->logo->move(public_path('assets/clinics_logos'), $filename);

        $clinic->update(['logo'=>$filename]);

        session()->flash('success', 'Clinic Added Successfully');
        return redirect()->route('clinics.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('Clinics_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic = Clinic::loggedClinic()->findOrFail($id);
        return view('pages.clinics.edit',compact('clinic'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Clinics_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = \Validator::make(request()->all(), [

            'name' => 'required',
            'ar_name' => 'required',
            'address' => 'required',
            'color' => 'required',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',

        ]);
        $validator->validate();

        $clinic = Clinic::loggedClinic()->findOrFail($id);

        $clinic->update($request->except('logo'));

        if ($request->logo){
            if (file_exists(public_path('assets/clinics_logos/'.$clinic->logo)) && $clinic->logo != null){
                unlink(public_path('assets/clinics_logos/'.$clinic->logo));
            }
            $filename = 'clinic (' . $clinic->id . ').' . $request->logo->getClientOriginalExtension();
            $request->logo->move(public_path('assets/clinics_logos'), $filename);

            $clinic->update(['logo'=>$filename]);
        }

        session()->flash('success', 'Clinic Updated Successfully');
        return redirect()->route('clinics.index');
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Clinics_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $clinic = Clinic::loggedClinic()->findOrFail($id);
//
//        if (Helper::clinic_is_deletable($clinic->id))
//        {
//            if (file_exists(public_path('assets/clinics_logos/'.$clinic->logo)) && $clinic->logo != null){
//                unlink(public_path('assets/clinics_logos/'.$clinic->logo));
//            }
//
//            $clinic->delete();
//
//
//            session()->flash('success', 'Clinic Deleted Successfully');
//            return redirect()->route('clinics.index');
//        }else{
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('clinics.index');
//        }

    }
}
