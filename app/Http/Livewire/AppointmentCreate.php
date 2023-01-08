<?php

namespace App\Http\Livewire;

use App\Models\AppDevice;
use App\Models\Appointment;
use App\Models\AppStatus;
use App\Models\Nationality;
use App\Models\Nurse;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentCreate extends Component
{
    // Checking Variables --------------------------------------------
    public $action;
    public $start;
    public $end;
    public $date;
    public $device_id;
    public $app_department;
    public $app_devices;
    public $nurses;


    public $search;
    public $duration;
    public $source;
    public $show_source = false;
    public $patients;
    public $is_new;
    public $time_step = 15;
    public $patient_data_changed = false;
    //----------------------------------------------------------------


    // Current Appointment Variables ---------------------------------
    public $current_appointment;
    public $notes;
    public $status_id;
    public $nurse_id;
    public $patient_id;
    public $patient_file_no;
    public $name;
    public $gender;
    public $mobile;
    public $civil_id;
    public $nationality_id;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;
    //----------------------------------------------------------------


    // Dropdown Lists Variables --------------------------------------
    public $app_departments;
    public $statuses;
    public $nationalities;
    public $departments;
    //----------------------------------------------------------------


    //Rules & Messages for Validation --------------------------------
    protected function rules()
    {
        return [
            'patient_id' => 'required_if:is_new,false',
            'patient_file_no' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'mobile' => 'required | numeric | digits_between:8,12',
            'civil_id' => 'required_if:is_new,false|digits:12',
            'nationality_id' => 'required',
        ];
    }

    protected $messages = [
        'name.min' => 'Name Should not be less than 6 characters ',
        'civil_id.required_if' => 'Civil Id is Required',
        'patient_file_no.required' => "Choose Patient from the list",
        'mobile.required' => "Mobile is Required",
        'nationality_id.required' => "Nationality is Required",
        'name.required' => "Name is Required",
    ];
    //----------------------------------------------------------------


    //Render & Mount -------------------------------------------------
    public function render()
    {
        return view('livewire.appointment-create');
    }

    public function mount()
    {
        $this->app_devices = AppDevice::loggedClinic()->whereActive(1)->where('app_department_id', $this->app_department)->get();
        $this->statuses = AppStatus::all();
        $this->nationalities = Nationality::orderBy('name')->get();
        $this->nurses = Nurse::loggedClinic()->get();
        $this->search = '';
        $this->patients = [];


        switch ($this->action) {
            case 'create' :
                $this->is_new = true;
                $this->patient_id = 0;
                $this->gender = 0;
                $this->patient_file_no = 0;
                $this->civil_id = '';
                $this->mobile = '';
                $this->name = '';
                $this->nationality_id = '';
                $this->status_id = 1;
                $this->nurse_id = '';
                $this->notes = '';
                $this->get_duration();


                break;

            case 'edit' :
                $this->patient_id = $this->current_appointment->patient_id;
                $this->patient_file_no = $this->current_appointment->patient_file_no;
                $this->name = $this->current_appointment->name;
                $this->gender = $this->current_appointment->gender;
                $this->nationality_id = $this->current_appointment->nationality_id;
                $this->mobile = $this->current_appointment->mobile;
                $this->civil_id = $this->current_appointment->civil_id;
                $this->notes = $this->current_appointment->notes;
                $this->date = $this->current_appointment->date;
                $this->start = $this->current_appointment->start;
                $this->end = $this->current_appointment->end;
                $this->status_id = $this->current_appointment->status_id;
                $this->device_id = $this->current_appointment->device_id;
                $this->nurse_id = $this->current_appointment->nurse_id;
                $this->created_by = $this->current_appointment->creator->name;
                $this->created_at = $this->current_appointment->created_at;
                $this->updated_by = $this->current_appointment->updator->name;
                $this->updated_at = $this->current_appointment->updated_at;
                $this->patient_file_no > 0 ? $this->is_new = false : $this->is_new = true;
                $this->get_duration();
                break;
        }

    }

    public function get_duration()
    {
        $hours = gmdate('H', strtotime($this->end) - strtotime($this->start));
        $mins = gmdate('i', strtotime($this->end) - strtotime($this->start));
        $this->duration = ($hours * 60) + $mins;
    }

    //CRUD Operations ------------------------------------------------
    public function save_appointment()
    {
        $this->check_busy();
        $this->validate();
        switch ($this->action) {
            case 'create' :
                $appointment = [
                    'patient_id' => $this->patient_id,
                    'patient_file_no' => $this->patient_file_no,
                    'name' => $this->name,
                    'gender' => $this->gender,
                    'nationality_id' => $this->nationality_id,
                    'mobile' => $this->mobile,
                    'civil_id' => $this->civil_id,
                    'notes' => $this->notes,
                    'date' => $this->date,
                    'start' => $this->start,
                    'end' => $this->end,
                    'status_id' => $this->status_id,
                    'device_id' => $this->device_id,
                    'nurse_id' => $this->nurse_id != "" ? $this->nurse_id : null,
                    'clinic_id' => auth()->user()->clinic_id,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ];
                Appointment::create($appointment);
                break;

            case 'edit' :
                $appointment = [
                    'patient_id' => $this->patient_id,
                    'patient_file_no' => $this->patient_file_no,
                    'name' => $this->name,
                    'gender' => $this->gender,
                    'nationality_id' => $this->nationality_id,
                    'mobile' => $this->mobile,
                    'civil_id' => $this->civil_id,
                    'notes' => $this->notes,
                    'date' => $this->date,
                    'start' => $this->start,
                    'end' => $this->end,
                    'status_id' => $this->status_id,
                    'device_id' => $this->device_id,
                    'nurse_id' => $this->nurse_id != "" ? $this->nurse_id : null,
                    'updated_by' => auth()->user()->id,
                ];
                $this->current_appointment->update($appointment);
                break;
        }
        return redirect()->route('appointments.index', [
            'app_department' => $this->app_department,
            'date' => $this->date,
        ]);
    }

    public function delete_confirmation()
    {
        $this->dispatchBrowserEvent('delete');
    }

    public function delete_appointment()
    {
        Appointment::destroy($this->current_appointment->id);
        return redirect()->route('appointments.index', [
            'app_department' => $this->app_department,
            'date' => $this->date,
        ]);
    }
    //----------------------------------------------------------------


    //Patient New Or Old Selection -----------------------------------
    public function new_patient()
    {
        if ($this->is_new == false) {
            $this->clearValidation();
            $this->is_new = true;
            $this->patient_id = 0;
            $this->patient_file_no = 0;
            $this->name = '';
            $this->gender = '';
            $this->mobile = '';
            $this->civil_id = '';
            $this->nationality_id = '';
        }
    }

    public function old_patient()
    {
        if ($this->is_new == true) {
            $this->clearValidation();
            $this->is_new = false;
            $this->patient_id = '';
            $this->patient_file_no = '';
            $this->name = '';
            $this->gender = '';
            $this->mobile = '';
            $this->civil_id = '';
            $this->nationality_id = '';
        }
    }


    //When Updating Fields -------------------------------------------
    public function updatedSearch()
    {
        if ($this->search != '') {
            $this->patients = Patient::search($this->search)->orderBy('file_no', 'desc')->take(10)->get();
        }
    }

    public function updatedNurseId()
    {
        $this->check_busy();
    }

    public function updatedDeviceId()
    {
        $this->check_busy();
    }

    public function updatedDate()
    {
        $this->check_busy();
    }

    public function updatedStatusId()
    {
        $this->check_busy();
    }


    // Check if Patient Data Changed ---------------------------------
    public function updatedName()
    {
        $this->check_changed_data();
    }

    public function updatedGender()
    {
        $this->check_changed_data();
    }

    public function updatedNationalityId()
    {
        $this->check_changed_data();
    }

    public function updatedMobile()
    {
        $this->check_changed_data();
    }

    public function updatedCivilId()
    {
        $this->check_changed_data();
    }

    public function check_changed_data()
    {
        $this->clearValidation();
        if (!$this->is_new) {
            $patient = Patient::loggedClinic()->findOrFail($this->patient_id);
            if (
                $patient->name != $this->name ||
                $patient->gender != $this->gender ||
                $patient->nationality_id != $this->nationality_id ||
                $patient->mobile != $this->mobile ||
                $patient->civil_id != $this->civil_id
            ) {
                $this->patient_data_changed = true;
            } else {
                $this->patient_data_changed = false;
            }
        }
    }


    // Save New Patient Data To Patient Table ------------------------
    public function change_patient_data()
    {
        $this->validate([
            'name' => 'required',
            'gender' => 'required',
            'mobile' => 'required | numeric | digits_between:8,12',
            'civil_id' => [
                'required', 'digits:12',
                Rule::unique('patients')->where(function ($query) {
                    return $query
                        ->where('civil_id', $this->civil_id)
                        ->where('id', '!=', $this->patient_id)
                        ->where('clinic_id', auth()->user()->clinic_id);
                })
            ],
            'nationality_id' => 'required',
        ]);
        $patient = Patient::loggedClinic()->findOrFail($this->patient_id);
        $data = [
            'name' => $this->name,
            'gender' => $this->gender,
            'nationality_id' => $this->nationality_id,
            'mobile' => $this->mobile,
            'civil_id' => $this->civil_id
        ];
        $patient->update($data);
        $this->patient_data_changed = false;
        $this->dispatchBrowserEvent('patient_data_updated');
    }


    //Time Values + Or - ---------------------------------------------
    public function start_dec()
    {
        $working_start = auth()->user()->clinic->working_start;
        if ($this->start > $working_start) {
            $time = Carbon::parse($this->start)
                ->subMinutes($this->time_step)
                ->format('H:i:s');
            $this->start = $time;
            $this->get_duration();
        }
    }

    public function start_inc()
    {
        if ($this->start != Carbon::parse($this->end)->subMinutes($this->time_step)->format('H:i:s')) {
            $time = Carbon::parse($this->start)
                ->addMinutes($this->time_step)
                ->format('H:i:s');
            $this->start = $time;
            $this->get_duration();
        }
    }

    public function end_dec()
    {
        if ($this->end != Carbon::parse($this->start)->addMinutes($this->time_step)->format('H:i:s')) {
            $time = Carbon::parse($this->end)
                ->subMinutes($this->time_step)
                ->format('H:i:s');
            $this->end = $time;
            $this->get_duration();
        }
    }

    public function end_inc()
    {
        $working_end = auth()->user()->clinic->working_end;
        if ($this->end < $working_end) {
            $time = Carbon::parse($this->end)
                ->addMinutes($this->time_step)
                ->format('H:i:s');
            $this->end = $time;
            $this->get_duration();
        }
    }

    // Select Old Patient From The List ------------------------------
    public function select_patient($id)
    {
        $patient = Patient::loggedClinic()->findOrFail($id);
        $this->patient_id = $patient->id;
        $this->patient_file_no = $patient->file_no;
        $this->name = $patient->name;
        $this->gender = $patient->gender;
        $this->mobile = $patient->mobile;
        $this->civil_id = $patient->civil_id;
        $this->nationality_id = $patient->nationality_id;
        $this->search = '';
        $this->validate();
    }

    // Check Time Intersection for Nurses & Devices ------------------
    public function check_busy()
    {
        $this->clearValidation();
        if ($this->status_id != 6) {
            $busy_nurse = Appointment::loggedClinic()
                ->where('date', $this->date)
                ->when($this->action == 'edit', function ($q) {
                    $q->where('id', '!=', $this->current_appointment->id);     // to ignore current record
                })
                ->where('status_id', '!=', 6)   // to ignore Cancelled
                ->whereNotNull('nurse_id')
                ->where('nurse_id', $this->nurse_id)
                ->where(function ($q) {
                    $q->where('start', '>=', $this->start)
                        ->where('start', '<', $this->end)
                        ->orWhere(function ($q) {
                            $q->where('end', '>', $this->start)
                                ->where('end', '<=', $this->end)
                                ->orWhere(function ($q) {
                                    $q->where('start', '<=', $this->start)
                                        ->where('end', '>=', $this->end);
                                });
                        });
                })
                ->first();
            if ($busy_nurse) {
                throw ValidationException::withMessages(['nurse_is_busy' => 'Busy in this Period']);
            }

            if ($this->device_id != "") {
                $busy_device = Appointment::loggedClinic()
                    ->where('date', $this->date)
                    ->when($this->action == 'edit', function ($q) {
                        $q->where('id', '!=', $this->current_appointment->id);     // to ignore current record
                    })->where('status_id', '!=', 6)   // to ignore Cancelled
                    ->whereNotNull('device_id')
                    ->where('device_id', $this->device_id)
                    ->where(function ($q) {
                        $q->where('start', '>=', $this->start)
                            ->where('start', '<', $this->end)
                            ->orWhere(function ($q) {
                                $q->where('end', '>', $this->start)
                                    ->where('end', '<=', $this->end)
                                    ->orWhere(function ($q) {
                                        $q->where('start', '<=', $this->start)
                                            ->where('end', '>=', $this->end);
                                    });
                            });
                    })
                    ->first();
                if ($busy_device) {
                    throw ValidationException::withMessages(['device_is_busy' => 'Busy in this Period']);
                }
            }
        }
    }


    // Create File for New Patient -----------------------------------
    public function save_new_to_patients_list()
    {
        $this->show_source = true;
        $this->validate(
            [
                'civil_id' => [
                    'required', 'digits:12',
                    Rule::unique('patients')->where(function ($query) {
                        return $query->where('civil_id', $this->civil_id)->where('clinic_id', auth()->user()->clinic_id);
                    })
                ],
                'name' => 'required',
                'gender' => 'required',
                'mobile' => 'required | numeric | digits_between:8,12',
                'nationality_id' => 'required',
                'source' => 'required',
            ],
            [
                'civil_id.unique' => 'Civil ID Already Exists',
            ]
        );
        $file_no = Patient::loggedClinic()->max('file_no') + 1;
        $data = [
            'file_no' => $file_no,
            'clinic_id' => auth()->user()->clinic_id,
            'user_id' => auth()->user()->id,
            'civil_id' => $this->civil_id,
            'name' => $this->name,
            'gender' => $this->gender,
            'mobile' => $this->mobile,
            'nationality_id' => $this->nationality_id,
            'source' => $this->source,
        ];
        DB::beginTransaction();
        try {
            $patient = Patient::create($data);
            $this->select_patient($patient->id);
            $this->is_new = false;
            $this->save_appointment();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
}
