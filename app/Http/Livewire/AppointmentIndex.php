<?php

namespace App\Http\Livewire;

use App\Models\AppDepartment;
use App\Models\AppDevice;
use App\Models\Appointment;
use Livewire\Component;

class AppointmentIndex extends Component
{

    public $resources;
    public $events;
    public $app_department;
    public $date;
    public $departments;
    public $show_cancelled;
    public $cancelled_appointments_count;

    //Render & Mount -------------------------------------------------
    public function render()
    {
        return view('livewire.appointment-index');

    }
    public function mount()
    {
        $this->departments = AppDepartment::loggedClinic()->whereActive(1)->whereHas('app_devices',function ($q){
            $q->whereActive(1);
        })->get();
        $this->show_cancelled = false;
        $this->cancelled_appointments_count = 0;

        $this->update_view_data();
    }
    //----------------------------------------------------------------

    // Filter Calendar After Update Data or Department ---------------
    public function updatedDate()
    {
        $this->update_view_data();
    }
    public function updatedAppDepartment()
    {
        $this->update_view_data();
    }
    public function show_cancelled()
    {
        $this->show_cancelled ? $this->show_cancelled = false : $this->show_cancelled = true;
        $this->update_view_data();
    }
    public function update_view_data()
    {



        $this->resources = [];
        $this->events = [];
        $devices = AppDevice::loggedClinic()->where('app_department_id', $this->app_department)->whereActive(1)->get();
        foreach ($devices as $device) {
            $this->resources[] = [
                'id' => $device->id,
                'title' => $device->name,
                'sort' => $device->sorting,
                'department' => $device->app_department_id,
            ];
        }
        if ($this->show_cancelled){
            $appointments = Appointment::loggedClinic()->with('status','nurse')->where('date', $this->date)->whereIn('device_id', $devices->pluck('id'))->get();
        }else{
            $appointments = Appointment::loggedClinic()->with('status','nurse')->where('date', $this->date)->whereIn('device_id', $devices->pluck('id'))->where('status_id','!=',6)->get();
        }



        $this->cancelled_appointments_count = Appointment::loggedClinic()->with('status')->where('date', $this->date)->whereIn('device_id', $devices->pluck('id'))->where('status_id',6)->count();



        foreach ($appointments as $appointment) {
            $this->events[] = [
                'id' => $appointment->id,
                'title' => ($appointment->patient_file_no == 0 ? 'New' : $appointment->patient_file_no) . " - " . $appointment->name . ($appointment->nurse_id ? " - " . $appointment->nurse->name : '' ),
                'start' => date('Y-m-d', strtotime($appointment->date)) . 'T' . $appointment->start,
                'end' => date('Y-m-d', strtotime($appointment->date)) . 'T' . $appointment->end,
                'resourceId' => $appointment->device_id,
                'color' => $appointment->status->color,
            ];
        }

        $this->dispatchBrowserEvent('data-updated', ['resources' => $this->resources, 'events' => $this->events, 'date' => $this->date]);
    }
    //----------------------------------------------------------------

    // Drag & Drop Edit for Appointment -----------------------------
    public function quick_edit($id, $start, $end, $device)
    {
        $appointment = Appointment::loggedClinic()->findOrFail($id);

        if ($appointment->status_id == 6)
        {
            $data = [
                'start' => $start,
                'end' => $end,
                'device_id' => $device,
                'updated_by' => auth()->user()->id,
            ];
            $appointment->update($data);
        } else {

            $busy_nurse = Appointment::loggedClinic()
                ->where('date', $this->date)
                ->where('id', '!=', $appointment->id)
                ->where('status_id','!=',6)   // to ignore Cancelled
                ->whereNotNull('nurse_id')
                ->where('nurse_id','!=',0)
                ->where('nurse_id', $appointment->nurse_id)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start', '>=', $start)
                        ->where('start', '<', $end)
                        ->orWhere(function ($q) use ($start, $end) {
                            $q->where('end', '>', $start)
                                ->where('end', '<=', $end)
                                ->orWhere(function ($q) use ($start, $end) {
                                    $q->where('start', '<=', $start)
                                        ->where('end', '>=', $end);
                                });
                        });


                })
                ->first();

            $busy_device = Appointment::loggedClinic()
                ->where('date', $this->date)
                ->where('id', '!=', $appointment->id)
                ->where('status_id','!=',6)   // to ignore Cancelled
                ->whereNotNull('device_id')
                ->where('device_id','!=',0)
                ->where('device_id', $device)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start', '>=', $start)
                        ->where('start', '<', $end)
                        ->orWhere(function ($q) use ($start, $end) {
                            $q->where('end', '>', $start)
                                ->where('end', '<=', $end)
                                ->orWhere(function ($q) use ($start, $end) {
                                    $q->where('start', '<=', $start)
                                        ->where('end', '>=', $end);
                                });
                        });


                })
                ->first();

            if ($busy_nurse == null && $busy_device == null) {
                $data = [
                    'start' => $start,
                    'end' => $end,
                    'device_id' => $device,
                    'updated_by' => auth()->user()->id,
                ];

                $appointment->update($data);
            } else {
                if ($busy_nurse){
                    $this->dispatchBrowserEvent('alert', [
                        'title' => '<strong  class="text-primary">Error</strong>',
                        'message' =>
                            $busy_nurse->nurse->name .
                            ' is busy on ' .
                            $busy_nurse->app_device->name .
                            ' from ' .
                            date('h:i a', strtotime($busy_nurse->start)) .
                            ' to ' .
                            date('h:i a', strtotime($busy_nurse->end))
                    ]);
                }
                if ($busy_device){
                    $this->dispatchBrowserEvent('alert', [
                        'title' => '<strong  class="text-primary">Error</strong>',
                        'message' =>
                            $busy_device->app_device->name .
                            ' is busy from ' .
                            date('h:i a', strtotime($busy_device->start)) .
                            ' to ' .
                            date('h:i a', strtotime($busy_device->end))
                    ]);
                }

            }

        }





        $this->update_view_data();

    }
    //----------------------------------------------------------------



    // Pass Data to Modal for Create Or Edit -------------------------
    public function create_appointment($device, $start, $end)
    {
        return redirect()->route('appointments.create',[
            'start' => $start,
            'end' => $end,
            'app_department' => $this->app_department,
            'device_id' => $device,
            'date' => $this->date,
        ]);
    }
    public function edit_appointment($id)
    {
        return redirect()->route('appointments.edit',$id);
    }
    //----------------------------------------------------------------

    public function print()
    {
        redirect()->route('appointments.print',['date' => $this->date,'department'=>$this->app_department]);
    }
}
