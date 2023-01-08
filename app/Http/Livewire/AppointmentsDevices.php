<?php

namespace App\Http\Livewire;

use App\Models\AppDepartment;
use App\Models\AppDevice;
use Livewire\Component;
use function MongoDB\BSON\toJSON;

class AppointmentsDevices extends Component
{

    public $app_departments;

    public function render()
    {
        return view('livewire.appointments-devices');
    }

    public function mount()
    {
        $this->app_departments = AppDepartment::loggedClinic()
            ->with(['app_devices' => function ($q){
                $q->orderBy('sorting');
            }])
            ->orderBy('id','asc')->get();
    }

    public function sort($positions)
    {
        foreach ($positions as $position)
        {
            $current = AppDevice::loggedClinic()->findOrFail($position[1]) ;
            $current->update(['sorting'=>$position[0]]);
        }
        $this->mount();
    }
}
