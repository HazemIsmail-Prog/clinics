<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DailyAppointmentsWidget extends Component
{
    public $current_date;
    public $statuses = [];
    public $devices = [];


    public function render()
    {
        return view('livewire.daily-appointments-widget');
    }

    public function mount()
    {
        $this->get_date();
    }

    public function updatedCurrentDate()
    {
        $this->get_date();
    }

    public function get_date()
    {

        $this->statuses = Appointment::
        loggedClinic()
            ->where('date', $this->current_date)
            ->with('status')
            ->groupBy('status_id')
            ->select('status_id', DB::raw('count(*) as total'))
            ->get();


        $this->devices = Appointment::
        loggedClinic()
            ->where('date', $this->current_date)
            ->with('app_device')
            ->groupBy('device_id')
            ->select('device_id', DB::raw('count(*) as total'))
            ->get();
    }

}
