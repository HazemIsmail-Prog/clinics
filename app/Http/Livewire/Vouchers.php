<?php

namespace App\Http\Livewire;

use App\Models\Doctor;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Vouchers extends Component
{

    public $accounts;
    public $action;
    public $voucher_type;
    public $current_voucher;
    public $placeholder = '';
    public $date;
    public $copy_from;
    public $no_voucher_message = false;
    public $items = [];
    public $doctors = [];
    public $total_credit = 0;
    public $total_debit = 0;
    public $total_difference = 0;
    public $rows_number = 1;


    protected function rules()
    {
        return [
            'items.*.account_id' => 'required',
            'items.*.credit' => 'required_without:items.*.debit|numeric|min:0|not_in:0',
            'items.*.debit' => 'required_without:items.*.credit|numeric|min:0|not_in:0',
            'total_difference' => 'required|numeric|in:0',
        ];
    }

    protected function messages()
    {
        return [
            'items.*.debit.required_without' => "please fill 1 at least",
            'items.*.credit.required_without' => "please fill 1 at least",
            'items.*.account_id.required' => "choose account",
            'total_difference.in' => 'Difference must be 0'
        ];
    }

    public function render()
    {
        return view('livewire.vouchers');
    }

    public function mount()
    {

        $this->doctors = Doctor::loggedClinic()->get();
        switch ($this->action) {
            case 'create' :

                switch ($this->voucher_type) {
                    case 'jv' :
                        $this->placeholder = 'Journal Voucher No.';
                        break;
                    case 'bp' :
                        $this->placeholder = 'Bank Payment No.';
                        break;
                    case 'br' :
                        $this->placeholder = 'Bank Receipt No.';
                        break;
                }


                $this->date = date('Y-m-d');
                $this->items = [
                    ['account_id' => '', 'doctor_id' => '', 'narration' => '', 'debit' => '', 'credit' => ''],
                    ['account_id' => '', 'doctor_id' => '', 'narration' => '', 'debit' => '', 'credit' => '']
                ];
                break;

            case 'edit' :

                $this->voucher_type = $this->current_voucher->voucher_type;
                $this->date = $this->current_voucher->voucher_date;
                foreach ($this->current_voucher->voucher_details as $row) {
                    $this->items [] = [
                        'account_id' => $row->account_id,
                        'doctor_id' => $row->doctor_id ?? '',
                        'narration' => $row->narration,
                        'debit' => $row->debit == 0 ? '' : $row->debit,
                        'credit' => $row->credit == 0 ? '' : $row->credit,
                    ];
                }
                $this->calculate_difference();
                break;
        }

    }

    public function copy_voucher()
    {
        $copied_voucher = Voucher::
        loggedAccountGroup()
            ->whereVoucherNo($this->copy_from)
            ->whereVoucherType($this->voucher_type)
            ->first();

        if ($copied_voucher) {
            $this->no_voucher_message = false;
            $this->items = [];
            foreach ($copied_voucher->voucher_details as $row) {
                $this->items [] = [

                    'account_id' => $row->account_id,
                    'doctor_id' => $row->doctor_id ?? '',
                    'narration' => $row->narration,
                    'debit' => $row->debit == 0 ? '' : $row->debit,
                    'credit' => $row->credit == 0 ? '' : $row->credit,
                ];

            }
        } else {
            $this->no_voucher_message = true;
        }
        $this->calculate_difference();
    }

    public function updatedCopyFrom()
    {
        $this->no_voucher_message = false;
    }

    public function add_row()
    {
        for ($i = 1; $i <= $this->rows_number; $i++) {
            $this->items [] = ['account_id' => '', 'doctor_id' => '', 'narration' => '', 'debit' => '', 'credit' => ''];
        }
        $this->rows_number = 1;
        $this->calculate_difference();
    }

    public function delete_row($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculate_difference();
    }

    public function updatedItems($value, $get_field)
    {
        $arr = explode('.', $get_field);
        $index = $arr[0];
        $changed_field = $arr[1];
        if ($changed_field == 'debit') {
            $this->items[$index]['credit'] = '';
        }

        if ($changed_field == 'credit') {
            $this->items[$index]['debit'] = '';
        }

        $this->calculate_difference();


        if ($this->errorBag->count() != 0) {
            $this->validate($this->rules(), $this->messages());
        }

    }

    public function calculate_difference()
    {
        $this->total_debit = 0;
        $this->total_credit = 0;
        $this->total_difference = 0;
        foreach ($this->items as $key => $val) {
            $this->total_debit = $this->total_debit + floatval($val['debit']);
            $this->total_credit = $this->total_credit + floatval($val['credit']);
        }
        $this->total_difference = round($this->total_debit, 3) - round($this->total_credit, 3);
    }

    public function save_voucher()
    {
        $this->calculate_difference();
        $this->validate($this->rules(), $this->messages());

        $voucher_data = [
            'account_group_id' => auth()->user()->clinic->account_group_id,
            'voucher_no' => Voucher::loggedAccountGroup()->where('voucher_type', $this->voucher_type)->max('voucher_no') + 1,
            'voucher_type' => $this->voucher_type,
            'voucher_date' => $this->date,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $voucher_details_data = [];
        foreach ($this->items as $item) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $item['account_id'],
                'doctor_id' => $item['doctor_id'] == '' ? null : $item['doctor_id'],
                'narration' => $item['narration'],
                'debit' => $item['debit'] == '' ? 0 : $item['debit'],
                'credit' => $item['credit'] == '' ? 0 : $item['credit'],
            ];
        }

        DB::beginTransaction();
        try {
            $voucher = Voucher::create($voucher_data);
            $voucher->voucher_details()->createMany($voucher_details_data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->withInput();
        }
        session()->flash('success', 'Voucher Added Successfully');
        switch ($this->voucher_type) {
            case 'jv' :
                return redirect()->route('jvs.index');
                break;
            case 'bp' :
                return redirect()->route('bps.index');
                break;
            case 'br' :
                return redirect()->route('brs.index');
                break;
        }
    }

    public function update_voucher()
    {

        $this->calculate_difference();
        $this->validate($this->rules(), $this->messages());

        $voucher_data = [
            'voucher_date' => $this->date,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ];

        $voucher_details_data = [];
        foreach ($this->items as $item) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $item['account_id'],
                'doctor_id' => $item['doctor_id'] == '' ? null : $item['doctor_id'],
                'narration' => $item['narration'],
                'debit' => $item['debit'] == '' ? 0 : $item['debit'],
                'credit' => $item['credit'] == '' ? 0 : $item['credit'],
            ];
        }

        DB::beginTransaction();
        try {
            $this->current_voucher->update($voucher_data);
            $this->current_voucher->voucher_details()->delete();
            $this->current_voucher->voucher_details()->createMany($voucher_details_data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->withInput();
        }
        session()->flash('success', 'Voucher Updated Successfully');
        switch ($this->voucher_type) {
            case 'jv' :
                return redirect()->route('jvs.index');
                break;
            case 'bp' :
                return redirect()->route('bps.index');
                break;
            case 'br' :
                return redirect()->route('brs.index');
                break;
        }
    }
}
