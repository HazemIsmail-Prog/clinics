<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Nurse;
use App\Models\Treatment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CollectionStatements extends Component
{

    public $data = [];
    public $start_date;
    public $end_date;
    public $users;
    public $departments;
    public $doctors;
    public $treatments;
    public $nurses;
    public $main_filter;
    public $view_filter;
    public $user_filter;
    public $department_filter;
    public $doctor_filter;
    public $treatment_filter;
    public $nurse_filter;
    public $filter;


    public function render()
    {
        return view('livewire.collection-statements');
    }

    public function mount()
    {

        $this->start_date = date('Y-m-d', strtotime(Carbon::now()->subMonth(1)));
        $this->end_date = date('Y-m-d', strtotime(Carbon::now()));
        $this->users = User::loggedClinic()->whereHas('invoices')->get();
        $this->departments = Department::loggedClinic()->get();
        $this->doctors = Doctor::loggedClinic()->whereHas('invoices')->get();
//        $this->treatments = Treatment::whereHas('invoices')->get();
        $this->nurses = Nurse::loggedClinic()->whereHas('invoices')->get();
        $this->main_filter = 'users';
        $this->view_filter = 'daily';
    }

    public function updated()
    {
        switch ($this->main_filter) {
            case 'users' :
                $this->filter = $this->user_filter;
                break;
            case 'users_doctors' :
                $this->filter = [
                    'user_filter' => $this->user_filter,
                    'doctor_filter' => $this->doctor_filter,
                ];
                break;
            case 'departments' :
                $this->filter = $this->department_filter;
                break;
            case 'doctors' :
                $this->filter = $this->doctor_filter;
                break;
            case 'treatments' :
                $this->filter = $this->treatment_filter;
                break;
            case 'nurses' :
                $this->filter = $this->nurse_filter;
                break;
            case 'times' :
                $this->filter = null;
                break;
            case 'revenues' :
                $this->filter = null;
                break;
        }
    }
}
