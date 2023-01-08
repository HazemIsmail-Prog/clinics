<?php

namespace App\Http\Controllers;

use App\Models\Nationality;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Gate;


class PatientsController extends Controller
{

    public function index(Request $request)
    {
        abort_if(Gate::denies('Patients_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Patient::
        loggedClinic()
            ->withCount(['appointments', 'invoices', 'balances'])
            ->with('balances')
            ->when($request->patient_file_no, function ($q1) use ($request) {
                $q1->where('file_no', $request->patient_file_no);
            })
            ->when($request->key, function ($q2) use ($request) {
                $q2->where(function ($q) use ($request) {
                    $q->where('file_no', $request->key)
                        ->orWhere('name', 'like', $request->key . '%')
                        ->orWhere('mobile', 'like', $request->key . '%')
                        ->orWhere('civil_id', 'like', $request->key . '%');
                });

            })
            ->orderBy('file_no', 'desc')
            ->paginate(10);
        return view('pages.patients.index', compact('patients'));
    }

    public function create()
    {
        abort_if(Gate::denies('Patients_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $nationalities = Nationality::orderBy('name')->get();
        return view('pages.patients.create', compact('nationalities'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Patients_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $request->request->add(['birthday' => $this->get_birthday($request->civil_id)]);

        $rules = [

            'civil_id' => [
                'required', 'digits:12',
                Rule::unique('patients')
                    ->where(function ($q) use ($request) {
                        $q->where('civil_id', $request->civil_id);
                        $q->where('clinic_id', auth()->user()->clinic_id);
                    })
            ],

            'name' => 'required',
            'gender' => 'required',
            'mobile' => 'required | numeric | digits_between:8,12',
            'nationality_id' => 'required',
            'phone' => 'nullable | numeric | digits_between:8,12',
            'source' => 'required'

        ];

        $customMessages = [
            'civil_id.unique' => 'Civil ID Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);

        $file_no = Patient::loggedClinic()->max('file_no') + 1;

        $data = [

            'file_no' => $file_no,
            'clinic_id' => auth()->user()->clinic_id,
            'user_id' => auth()->user()->id,
            'civil_id' => $request->civil_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'blood_group' => $request->blood_group,
            'nationality_id' => $request->nationality_id,
            'notes' => $request->notes,
            'status' => $request->status,
            'source' => $request->source,
            'birthday' => $request->birthday
        ];


        DB::beginTransaction();

        try {

            Patient::create($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Patient Added Successfully');
        return redirect()->route('patients.index');

    }

    public function show($id)
    {
        abort_if(Gate::denies('Patients_Read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patient = Patient::loggedClinic()->findOrFail($id);
        return view('pages.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        abort_if(Gate::denies('Patients_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patient = Patient::loggedClinic()->findOrFail($id);
        $nationalities = Nationality::orderBy('name')->get();
        return view('pages.patients.edit', compact('patient', 'nationalities'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Patients_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->request->add(['birthday' => $this->get_birthday($request->civil_id)]);

        $rules = [

            'civil_id' => [

                'required', 'digits:12',
                Rule::unique('patients')
                    ->where(function ($q) use ($request,$id) {
                        $q->where('civil_id', $request->civil_id);
                        $q->where('clinic_id', auth()->user()->clinic_id);
                        $q->where('id', '!=' , $id);
                    })
            ],

            'name' => 'required',
            'gender' => 'required',
            'mobile' => 'required | numeric | digits_between:8,12',
            'nationality_id' => 'required',
            'phone' => 'nullable | numeric | digits_between:8,12',
            'source' => 'required',
        ];

        $customMessages = [
            'civil_id.unique' => 'Civil ID Already Exists',
        ];

        $this->validate($request, $rules, $customMessages);


        $patient = Patient::loggedClinic()->findOrFail($id);

        $data = [

            'civil_id' => $request->civil_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'blood_group' => $request->blood_group,
            'nationality_id' => $request->nationality_id,
            'notes' => $request->notes,
            'status' => $request->status,
            'source' => $request->source,
            'birthday' => $request->birthday,
        ];

        DB::beginTransaction();

        try {

            $patient->update($data);
            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            session()->flash('failed', 'Something went wrong ..\nTry again later');
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Patient Updated Successfully');
        return redirect()->route('patients.index');


    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Patients_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $patient = Patient::loggedClinic()->findOrFail($id);
//
//        if (Helper::patient_is_deletable($patient->id)) {
//
//            $patient->delete();
//
//            session()->flash('success', 'Patient Deleted Successfully');
//            return redirect()->route('patients.index');
//        } else {
//            session()->flash('failed', 'Some Thing Was Wrong');
//            return redirect()->route('patients.index');
//        }
    }

    private function get_birthday($civil_id)
    {
        $year_index = substr($civil_id, 0, 1);
        if ($year_index == 3) {
            $year = substr($civil_id, 1, 2) + 2000;
        } elseif ($year_index == 2) {
            $year = substr($civil_id, 1, 2) + 1900;
        } else {
            throw ValidationException::withMessages(['civil_id' => 'Incorrect Civil ID']);
        }

        if (substr($civil_id, 3, 2) > 12 || substr($civil_id, 3, 2) == "00") {
            throw ValidationException::withMessages(['civil_id' => 'Incorrect Civil ID']);
        }

        if (substr($civil_id, 5, 2) > 31 || substr($civil_id, 5, 2) == "00") {
            throw ValidationException::withMessages(['civil_id' => 'Incorrect Civil ID']);
        }

        $month = substr($civil_id, 3, 2);
        $day = substr($civil_id, 5, 2);
        $birthday = Carbon::createFromDate($year, $month, $day);

        return $birthday;

    }

}
