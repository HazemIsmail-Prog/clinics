<?php

namespace App\Http\Livewire;

use App\Models\AccountGroup;
use App\Models\Balance;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class InvoiceModal extends Component
{

    public $is_insurance = false;
    public $action;
    public $invoice;
    public $departments;
    public $department_id;
    public $doctors;
    public $doctor_id;
    public $nurses;
    public $nurse_id;
    public $treatments = [];
    public $treatment_id;
    public $patient;
    public $patient_id;
    public $changed_file_no;
    public $file_no_not_found = false;
    public $items = [];
    public $total_amount = 0;
    public $difference = 0;
    public $total_paid = 0;
    public $cash;
    public $knet;
    public $visa;
    public $master;
    public $knet_link;
    public $credit_link;
    public $balance;
    public $notes;
    public $rows_number = 1;

    protected function rules()
    {

        if ($this->is_insurance) {
            return [
                'department_id' => 'required',
                'doctor_id' => 'required',
                'items.*.treatment_id' => 'required',
                'items.*.qty' => 'required | numeric | min:1',
                'items.*.discount' => 'nullable | numeric | min:0 ',
                'items.*.total' => 'nullable | numeric | min:0',
            ];
        } else {
            return [
                'department_id' => 'required',
                'doctor_id' => 'required',
                'items.*.treatment_id' => 'required',
                'items.*.qty' => 'required | numeric | min:1',
                'items.*.discount' => 'nullable | numeric | min:0 ',
                'items.*.total' => 'nullable | numeric | min:0',
                'difference' => 'required|numeric|min:0',
                'cash' => 'nullable | numeric | min:0',
                'knet' => 'nullable | numeric | min:0',
                'visa' => 'nullable | numeric | min:0',
                'master' => 'nullable | numeric | min:0',
                'knet_link' => 'nullable | numeric | min:0',
                'credit_link' => 'nullable | numeric | min:0',
                'balance' => 'numeric | min:0',
            ];
        }

    }

    protected function messages()
    {
        return [
            'difference.min' => 'Cannot pay more than amount',
        ];

    }

    public function render()
    {
        return view('livewire.invoice-modal');
    }

    public function mount()
    {
        $this->departments = Department::loggedClinic()->get();

        if ($this->action == 'edit') {
            $this->patient = $this->invoice->patient;
            $this->patient_id = $this->patient->id;
            $this->department_id = $this->invoice->doctor->department_id;
            $this->doctors = Doctor::loggedClinic()->where('department_id', $this->department_id)->orderBy('name')->get();
            $this->doctor_id = $this->invoice->doctor_id;
            $this->nurses = Nurse::loggedClinic()->where('department_id', $this->department_id)->orderBy('name')->get();
            $this->nurse_id = $this->invoice->nurse_id;
            $this->treatments = Treatment::loggedClinic()->whereActive(1)->where('department_id', $this->department_id)->orderBy('name')->get();
            $this->items = [];
            foreach ($this->invoice->invoice_details as $item) {
                $this->items [] = [
                    'treatment_id' => $item->treatment_id,
                    'price' => $item->treatment->price,
                    'qty' => $item->qty,
                    'discount' => $item->discount == 0 ? '' : $item->discount,
                    'discount_type' => $item->discount_type,
                    'total' => $item->total,
                ];
            }
            $this->cash = $this->invoice->cash == 0 ? '' : $this->invoice->cash;
            $this->knet = $this->invoice->knet == 0 ? '' : $this->invoice->knet;
            $this->visa = $this->invoice->visa == 0 ? '' : $this->invoice->visa;
            $this->master = $this->invoice->master == 0 ? '' : $this->invoice->master;
            $this->knet_link = $this->invoice->knet_link == 0 ? '' : $this->invoice->knet_link;
            $this->credit_link = $this->invoice->credit_link == 0 ? '' : $this->invoice->credit_link;
            $this->notes = $this->invoice->notes;
            $this->patient_id = $this->invoice->patient_id;
            $this->is_insurance = $this->invoice->type == 'Insurance Invoice';
            $this->updated('is_insurance');
        }
    }

    public function updated($key)
    {
        switch ($key){

            case 'department_id':
                $this->resetValidation();
                $this->items = [
                    ['treatment_id' => '', 'price' => '', 'qty' => 1, 'discount' => '', 'discount_type' => 'fixed', 'total' => '']
                ];
        
                $this->total_amount = 0;
                $this->balance = 0;
                $this->difference = 0;
        
                $this->cash = '';
                $this->knet = '';
                $this->visa = '';
                $this->master = '';
                $this->knet_link = '';
                $this->credit_link = '';
        
                $this->notes = '';
        
                $this->doctors = [];
                $this->doctor_id = null;
                $this->nurses = [];
                $this->nurse_id = null;
                $this->treatments = [];
                $this->treatment_id = null;
        
                $this->doctors = Doctor::loggedClinic()->where('department_id', $this->department_id)->orderBy('name')->get();
                $this->nurses = Nurse::loggedClinic()->where('department_id', $this->department_id)->orderBy('name')->get();
                $this->treatments = Treatment::loggedClinic()->whereActive(1)->where('department_id', $this->department_id)->orderBy('name')->get();
                break;

            case 'changed_file_no':
                if ($this->changed_file_no != '') {
                    $new_patient = Patient::loggedClinic()->where('file_no', $this->changed_file_no)->first();
                    if ($new_patient) {
                        $this->patient = $new_patient;
                        $this->patient_id = $new_patient->id;
                        $this->file_no_not_found = false;
                    } else {
                        $this->file_no_not_found = true;
                    }
                } else {
                    $this->patient = $this->invoice->patient;
                    $this->patient_id = $this->patient->id;
                }
                break;

            case 'doctor_id':
                $this->validateOnly('doctor_id');
                break;

            case 'cash':
            case 'knet':
            case 'visa':
            case 'master':
            case 'knet_link':
            case 'credit_link':
            case 'is_insurance':
                $this->get_total_and_balance();
                break;
        }
    }

    public function pay_all($key)
    {
        $this->clear_payments();
        switch ($key){
            case 'cash':
                $this->cash = $this->total_amount;
                break;
            case 'knet':
                $this->knet = $this->total_amount;
                break;
            case 'visa':
                $this->visa = $this->total_amount;
                break;
            case 'master':
                $this->master = $this->total_amount;
                break;
            case 'knet_link':
                $this->knet_link = $this->total_amount;
                break;
            case 'credit_link':
                $this->credit_link = $this->total_amount;
                break;
        }
        $this->get_total_and_balance();
    }

    public function add_row()
    {
        for ($i = 1; $i <= $this->rows_number; $i++) {
            $this->items [] = ['treatment_id' => '', 'price' => '', 'qty' => 1, 'discount' => '', 'discount_type' => 'fixed', 'total' => ''];
        }
        $this->rows_number = 1;
    }

    public function delete_row($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->get_total_and_balance();
    }

    public function updatedItems($value, $get_field)
    {
        $arr = explode('.', $get_field);
        $index = $arr[0];
        $changed_field = $arr[1];

        if ($this->items[$index]['treatment_id'] == '') {
            return;
        } else {
            $this->items[$index]['price'] = Treatment::loggedClinic()->whereActive(1)->findOrFail($this->items[$index]['treatment_id'])->price;

            $price = $this->items[$index]['price'];
            $qty = $this->items[$index]['qty'] == '' ? 0 : $this->items[$index]['qty'];
            $discount = $this->items[$index]['discount'] == '' ? 0 : $this->items[$index]['discount'];

            if ($this->items[$index]['discount_type'] == 'fixed') {
                $this->items[$index]['total'] = ($price * $qty) - $discount;
            } else {
                $this->items[$index]['total'] = ($price * $qty) - ($price * $qty * $discount / 100);
            }
            $this->get_total_and_balance();
        }
    }

    public function clear_payments()
    {
        $this->cash = '';
        $this->knet = '';
        $this->visa = '';
        $this->master = '';
        $this->knet_link = '';
        $this->credit_link = '';
    }

    public function get_total_and_balance()
    {
        $this->balance = 0;
        $this->total_amount = 0;
        $this->difference = 0;
        foreach ($this->items as $key => $val) {
            $this->total_amount = $this->total_amount + floatval($val['total']);
        }


        if ($this->is_insurance) {
            $this->cash = '';
            $this->knet = '';
            $this->visa = '';
            $this->master = '';
            $this->knet_link = '';
            $this->credit_link = '';
            $this->total_paid = 0;
            $this->balance = 0;
            $this->difference = 0;

        } else {
            $total_paid =
                ($this->cash !== "" ? $this->cash : 0) +
                ($this->knet !== "" ? $this->knet : 0) +
                ($this->visa !== "" ? $this->visa : 0) +
                ($this->master !== "" ? $this->master : 0) +
                ($this->knet_link !== "" ? $this->knet_link : 0) +
                ($this->credit_link !== "" ? $this->credit_link : 0);
            $this->balance = $this->total_amount - $total_paid;
            $this->difference = $this->total_amount - $total_paid;
        }

        $this->validate();
    }

    public function balance_confirmation()
    {
        $this->get_total_and_balance();

        if ($this->balance > 0) {

            $this->dispatchBrowserEvent('balance');
        } else {
            switch ($this->action) {
                case 'create' :
                    $this->save_invoice();
                    break;
                case 'edit' :
                    $this->update_invoice();
                    break;
            }
        }
    }

    public function balance_confirmed()
    {
        switch ($this->action) {
            case 'create' :
                $this->save_invoice();
                break;
            case 'edit' :
                $this->update_invoice();
                break;
        }
    }

    public function save_invoice()
    {
        $this->get_total_and_balance();

        $invoice_data = [
            'invoice_no' => Invoice::loggedClinic()->max('invoice_no') + 1,
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor_id,
            'nurse_id' => $this->nurse_id,
            'user_id' => auth()->id(),
            'ref' => null,
            'clinic_id' => auth()->user()->clinic_id,
            'total' => $this->total_amount,
            'cash' => $this->cash == "" ? 0 : $this->cash,
            'knet' => $this->knet == "" ? 0 : $this->knet,
            'visa' => $this->visa == "" ? 0 : $this->visa,
            'master' => $this->master == "" ? 0 : $this->master,
            'knet_link' => $this->knet_link == "" ? 0 : $this->knet_link,
            'credit_link' => $this->credit_link == "" ? 0 : $this->credit_link,
            'balance' => $this->balance,
            'type' => $this->is_insurance ? 'Insurance Invoice' : 'Billing Invoice',
            'notes' => $this->notes,
        ];


        DB::beginTransaction();

        try {

            $invoice = Invoice::create($invoice_data);
            $invoice->invoice_details()->createMany($this->get_details_items());
            if ($this->balance > 0) {
                $this->create_balance_record($invoice->id);
            }
            $this->save_voucher($invoice->id);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
        return redirect()->route('invoices.show', $invoice->id);

    }

    public function update_invoice()
    {
        $this->get_total_and_balance();

        $invoice_data = [
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'nurse_id' => $this->nurse_id,
            'total' => $this->total_amount,
            'cash' => $this->cash == "" ? 0 : $this->cash,
            'knet' => $this->knet == "" ? 0 : $this->knet,
            'visa' => $this->visa == "" ? 0 : $this->visa,
            'master' => $this->master == "" ? 0 : $this->master,
            'knet_link' => $this->knet_link == "" ? 0 : $this->knet_link,
            'credit_link' => $this->credit_link == "" ? 0 : $this->credit_link,
            'balance' => $this->balance,
            'type' => $this->is_insurance ? 'Insurance Invoice' : 'Billing Invoice',
            'notes' => $this->notes,
        ];


        DB::beginTransaction();

        try {
            Balance::loggedClinic()->where('invoice_id', $this->invoice->id)->delete();
            $this->invoice->invoice_details()->delete();
            $voucher = Voucher::
            loggedAccountGroup()
                ->where('voucher_no', $this->invoice->id)
                ->where('voucher_type', 'inv')
                ->first();
            if ($voucher) {
                $voucher->voucher_details()->delete();
            }

            $this->invoice->update($invoice_data);
            $this->invoice->invoice_details()->createMany($this->get_details_items());

            if ($this->balance > 0) {
                $this->create_balance_record($this->invoice->id);
            }

            $this->save_voucher($this->invoice->id);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }


        return redirect()->route('invoices.show', $this->invoice->id);

    }

    public function get_details_items()
    {
        $items_data = [];
        foreach ($this->items as $key => $item) {

            $items_data[] = [
                'treatment_id' => $item['treatment_id'],
                'treatment_name' => Treatment::whereActive(1)->find($item['treatment_id'])->name,
                'treatment_unit_price' => Treatment::whereActive(1)->find($item['treatment_id'])->price,
                'qty' => $item['qty'],
                'discount' => $item['discount'] == '' ? 0 : $item['discount'],
                'discount_type' => $item['discount_type'],
                'total' => $item['total'],
            ];
        }
        return $items_data;
    }

    public function create_balance_record($invoice_id)
    {
        $balance_data = [
            'patient_id' => $this->patient->id,
            'invoice_id' => $invoice_id,
            'amount' => $this->balance,
            'clinic_id' => auth()->user()->clinic_id,
        ];
        Balance::create($balance_data);
    }

    public function save_voucher($invoice_id)
    {

        switch ($this->action) {
            case 'create' :
                $voucher_data = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'voucher_no' => $invoice_id,
                    'voucher_type' => 'inv',
                    'voucher_date' => today(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ];
                $voucher = Voucher::create($voucher_data);
                $voucher->voucher_details()->createMany($this->get_voucher_details($invoice_id));
                break;

            case 'edit' :
                $voucher_data = [
                    'updated_by' => auth()->id(),
                ];

                $voucher = Voucher::
                loggedAccountGroup()
                    ->where('voucher_no', $invoice_id)
                    ->where('voucher_type', 'inv')
                    ->first();
                $voucher->update($voucher_data);
                $voucher->voucher_details()->createMany($this->get_voucher_details($invoice_id));
                break;
        }

    }

    public function get_voucher_details($invoice_id)
    {

        $account_group = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id);

        $patients_receivable_account = $account_group->balance_account;
        $bank_charge_account = $account_group->bankcharge_account;
        $bank_account = $account_group->bank_account;
        $cash_receptionist_account = $account_group->cash_account;

        $knet_income = $account_group->knet_ratio;
        $knet_bank_commission = $account_group->knet_bankcharge_ratio;

        $visa_income = $account_group->visa_ratio;
        $visa_bank_commission = $account_group->visa_bankcharge_ratio;

        $master_income = $account_group->master_ratio;
        $master_bank_commission = $account_group->master_bankcharge_ratio;

        $knet_link_income = $account_group->knet_link_ratio;
        $knet_link_bank_commission = $account_group->knet_link_bankcharge_ratio;

        $credit_link_income = $account_group->credit_link_ratio;
        $credit_link_bank_commission = $account_group->credit_link_bankcharge_ratio;

        $insurance_account = $account_group->insurance_account;

        $voucher_details_data = [];

        $doctor = Doctor::loggedClinic()->findOrFail($this->doctor_id);
        $current_invoice = Invoice::loggedClinic()->findOrFail($invoice_id);
        $invoice_no = $current_invoice->invoice_no;
        $clinic_name = $current_invoice->clinic->name;

        if ($this->is_insurance) {

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $doctor->account->id,
                'narration' => 'Total Amount of Inv-' . $invoice_no . ' for ' . $clinic_name,
                'debit' => 0,
                'credit' => $this->total_amount,
            ];

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $insurance_account,  // Insurance Account
                'narration' => 'Insurance of Inv-' . $invoice_no . ' for ' . $clinic_name,
                'debit' => $this->total_amount,
                'credit' => 0,
            ];

        } else {

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $doctor->account->id,
                'narration' => 'Total Amount of Inv-' . $invoice_no . ' for ' . $clinic_name,
                'debit' => 0,
                'credit' => $this->total_amount,
            ];

            if ($this->cash > 0) {
                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $cash_receptionist_account,  // Cash Receptionist Account
                    'narration' => 'Total Cash of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => $this->cash,
                    'credit' => 0,
                ];
            }

            if ($this->knet > 0) {
                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_account,  // Bank Account
                    'narration' => 'K-Net of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => $this->knet - round($this->knet * $knet_bank_commission,3),
                    'credit' => 0,
                ];

                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_charge_account,  // Bank Charge Account
                    'narration' => 'K-Net Bank Charges of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => round($this->knet * $knet_bank_commission,3),
                    'credit' => 0,
                ];
            }

            if ($this->visa > 0) {
                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_account,  // Bank Account
                    'narration' => 'VISA of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => $this->visa - round($this->visa * $visa_bank_commission,3),
                    'credit' => 0,
                ];

                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_charge_account,  // Bank Charge Account
                    'narration' => 'VISA Bank Charges of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => round($this->visa * $visa_bank_commission,3),
                    'credit' => 0,
                ];
            }

            if ($this->master > 0) {
                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_account,  // Bank Account
                    'narration' => 'MASTERCARD of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => $this->master -round($this->master * $master_bank_commission,3),
                    'credit' => 0,
                ];

                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_charge_account,  // Bank Charge Account
                    'narration' => 'MASTERCARD Bank Charges of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => round($this->master * $master_bank_commission,3),
                    'credit' => 0,
                ];
            }

            if ($this->knet_link > 0) {
                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_account,  // Bank Account
                    'narration' => 'K-Net Link of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => $this->knet_link - 0.250 ,
                    'credit' => 0,
                ];

                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_charge_account,  // Bank Charge Account
                    'narration' => 'K-Net Link Bank Charges of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => 0.250,
                    'credit' => 0,
                ];
            }

            if ($this->credit_link > 0) {
                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_account,  // Bank Account
                    'narration' => 'Credit Card Link of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => $this->credit_link - 0.250 - round($this->credit_link * 2 / 100 , 3 ),
                    'credit' => 0,
                ];

                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $bank_charge_account,  // Bank Charge Account
                    'narration' => 'Credit Card Link Bank Charges of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => 0.250 + round($this->credit_link * 2 / 100 , 3 ),
                    'credit' => 0,
                ];
            }

            if ($this->balance > 0) {
                $voucher_details_data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $patients_receivable_account,
                    'narration' => 'Balance of Inv-' . $invoice_no . ' for ' . $clinic_name,
                    'debit' => $this->balance,
                    'credit' => 0,
                ];
            }

        }


        return $voucher_details_data;

    }

}
