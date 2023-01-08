<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PieChart extends Component
{

    public $pie_chart;
    public $filter;

    public function render()
    {
        return view('livewire.pie-chart');
    }

    public function mount()
    {
        $this->filter = 'users';
        $this->update_chart_source();

    }

    public function updatedFilter()
    {
        $this->update_chart_source();
    }

    public function update_chart_source()
    {

        switch ($this->filter)
        {
            case 'users' :

                $by_user = DB::table('invoices')
                    ->join('users', 'invoices.user_id', '=', 'users.id')
                    ->select(
                        DB::raw('SUM(cash) + SUM(knet) + SUM(visa) + SUM(master) as total'),
                        DB::raw('users.name as label'),
                        DB::raw('users.clinic_id')

                    )
                    ->where('users.clinic_id',auth()->user()->clinic_id)
                    ->groupBy('users.name','users.clinic_id')
                    ->orderBy('total','desc')
                    ->get();
                break;

            case 'doctors' :

                $by_user = DB::table('invoices')
                    ->join('doctors', 'invoices.doctor_id', '=', 'doctors.id')
                    ->select(
                        DB::raw('SUM(cash) + SUM(knet) + SUM(visa) + SUM(master) as total'),
                        DB::raw('doctors.name as label'),
                        DB::raw('doctors.clinic_id')
                    )
                    ->where('doctors.clinic_id',auth()->user()->clinic_id)
                    ->groupBy('doctors.name','doctors.clinic_id')
                    ->orderBy('total','desc')
                    ->get();
                break;

            case 'departments' :
                $by_user = DB::table('invoices')
                    ->join('doctors', 'invoices.doctor_id', '=', 'doctors.id')
                    ->join('departments', 'doctors.department_id', '=', 'departments.id')
                    ->select(
                        DB::raw('SUM(cash) + SUM(knet) + SUM(visa) + SUM(master) as total'),
                        DB::raw('departments.name as label'),
                        DB::raw('departments.clinic_id')
                    )
                    ->where('departments.clinic_id',auth()->user()->clinic_id)
                    ->groupBy('departments.name','departments.clinic_id')
                    ->orderBy('total','desc')
                    ->get();

                break;

        }


        $this->pie_chart = [];
        foreach ($by_user as $row) {
            $this->pie_chart [] = [
                'labels' => $row->label,
                'data' => $row->total,
                'color' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
            ];
        }

        $this->dispatchBrowserEvent('update_chart_source',['resources' => $this->pie_chart]);

    }
}
