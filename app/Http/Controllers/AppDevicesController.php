<?php

namespace App\Http\Controllers;

use App\Models\AppDepartment;
use App\Models\AppDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class AppDevicesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('AppDevices_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.app_devices.index');
    }

    public function create()
    {
        abort_if(Gate::denies('AppDevices_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $app_departments = AppDepartment::loggedClinic()->get();
        return view('pages.app_devices.create',compact('app_departments'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('AppDevices_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $rules = [

            'name'  => [

                'required',
                Rule::unique('app_devices')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('app_department_id', $request->app_department_id);
                })
            ],
            'app_department_id'=>'required',
        ];

        $customMessages = [
            'name.unique' => 'Device Name Already Exists in Department '.$request->app_department_id,
        ];

        $this->validate($request, $rules, $customMessages);

        $sorting = AppDevice::where('app_department_id',$request->app_department_id)->max('sorting')+1;

        $data=[
            'name' => $request->name,
            'app_department_id' => $request->app_department_id,
            'sorting' => $sorting,
            'active' => $request->active ? 1 : 0,


        ];

        DB::beginTransaction();

        try {

            AppDevice::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Device Added Successfully');
        return redirect()->route('app_devices.index');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('AppDevices_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $app_departments = AppDepartment::loggedClinic()->get();
        $app_device = AppDevice::loggedClinic()->findOrFail($id);
        return view('pages.app_devices.edit',compact('app_departments','app_device'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('AppDevices_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rules = [

            'name'  => [

                'required',
                Rule::unique('app_devices')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('app_department_id', $request->app_department_id);
                })->ignore($id)
            ],
        ];

        $customMessages = [
            'name.unique' => 'Device Name Already Exists in Department '.$request->app_department_id,
        ];

        $this->validate($request, $rules, $customMessages);



        $app_device = AppDevice::loggedClinic()->findOrFail($id);

        $data=[
            'name' => $request->name,
            'app_department_id' => $request->app_department_id,
            'active' => $request->active ? 1 : 0,


        ];

        DB::beginTransaction();

        try {

            $app_device->update($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Device Updated Successfully');
        return redirect()->route('app_devices.index');



    }

    public function destroy($id)
    {
        abort_if(Gate::denies('AppDevices_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $app_device = AppDevice::loggedClinic()->findOrFail($id);
//
//        if (Helper::app_device_is_deletable($app_device->id))
//        {
//
//            $app_device->delete();
//
//            session()->flash('success', 'Device Deleted Successfully');
//            return redirect()->route('app_devices.index');
//        }else{
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('app_devices.index');
//        }
    }
}
