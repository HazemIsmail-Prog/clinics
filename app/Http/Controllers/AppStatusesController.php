<?php

namespace App\Http\Controllers;

use App\Models\AppStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class AppStatusesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('AppStatuses_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $app_statuses = AppStatus::orderBy('color','asc')->get();

        return view('pages.app_statuses.index',compact('app_statuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('AppStatuses_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.app_statuses.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('AppStatuses_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $rules = [

            'name'  => [

                'required',
                Rule::unique('app_statuses')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })
            ],
        ];

        $customMessages = [
            'name.unique' => 'Status Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $data=[
            'name' => $request->name,
            'color' => $request->color,
            ];

        DB::beginTransaction();

        try {

            AppStatus::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Status Added Successfully');
        return redirect()->route('app_statuses.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('AppStatuses_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $app_status = AppStatus::findOrFail($id);
        return view('pages.app_statuses.edit',compact('app_status'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('AppStatuses_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name'  => [

                'required',
                Rule::unique('app_statuses')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })->ignore($id)
            ],
        ];

        $customMessages = [
            'name.unique' => 'Status Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);



        $app_status = AppStatus::findOrFail($id);

        DB::beginTransaction();

        try {

            $app_status->update($request->all());
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Status Successfully');
        return redirect()->route('app_statuses.index');



    }

    public function destroy($id)
    {
        abort_if(Gate::denies('AppStatuses_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $app_status = AppStatus::findOrFail($id);
//
//        if (Helper::app_status_is_deletable($app_status->id))
//        {
//
//            $app_status->delete();
//
//            session()->flash('success', 'Status Deleted Successfully');
//            return redirect()->route('app_statuses.index');
//        }else{
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('app_statuses.index');
//        }
    }
}
