<?php

namespace App\Http\Livewire;

use App\Models\AccountGroup;
use App\Models\Balance;
use App\Models\Invoice;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceInvoice extends Component
{

    public $ref_invoice;
    public $ref_invoice_no;
    public $invoice_to_edit;

    public $action;
    public $doctor_id;
    public $nurse_id;
    public $patient_id;
    public $total_amount;
    public $total_paid;
    public $cash;
    public $knet;
    public $visa;
    public $master;
    public $knet_link;
    public $credit_link;
    public $balance;
    public $difference;
    public $notes;

    protected function rules()
    {
        return [
            'difference' => 'required|numeric|min:0',
            'cash' => 'nullable | numeric | min:0',
            'knet' => 'nullable | numeric | min:0',
            'visa' => 'nullable | numeric | min:0',
            'master' => 'nullable | numeric | min:0',
            'knet_link' => 'nullable | numeric | min:0',
            'credit_link' => 'nullable | numeric | min:0',
            'balance' => 'numeric | min:0',
            'total_paid' => 'numeric|required|not_in:0'
        ];
    }

    protected function messages()
    {
        return [
            'difference.min' => 'Cannot pay more than amount',
            'total_paid.not_in' => 'No Payments'
        ];

    }

    public function render()
    {
        return view('livewire.balance-invoice');
    }

    public function mount()
    {
        switch ($this->action) {
            case 'create' :
                $this->total_amount = $this->ref_invoice->balance;
                $this->doctor_id = $this->ref_invoice->doctor_id;
                $this->patient_id = $this->ref_invoice->patient->id;
                $this->nurse_id = $this->ref_invoice->nurse_id;
                $this->difference = 0;
                break;

            case 'edit' :
                $this->ref_invoice_no = $this->ref_invoice->invoice_no;
                $this->patient_id = $this->invoice_to_edit->patient_id;
                $this->total_amount = $this->ref_invoice->balance;
                $this->cash = $this->invoice_to_edit->cash > 0 ? $this->invoice_to_edit->cash : '';
                $this->knet = $this->invoice_to_edit->knet > 0 ? $this->invoice_to_edit->knet : '';
                $this->visa = $this->invoice_to_edit->visa > 0 ? $this->invoice_to_edit->visa : '';
                $this->master = $this->invoice_to_edit->master > 0 ? $this->invoice_to_edit->master : '';
                $this->knet_link = $this->invoice_to_edit->knet_link > 0 ? $this->invoice_to_edit->knet_link : '';
                $this->credit_link = $this->invoice_to_edit->credit_link > 0 ? $this->invoice_to_edit->credit_link : '';
                $this->get_total_and_balance();
                break;
        }

    }

    public function updated($key)
    {
        switch ($key){
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

    public function balance_confirmation()
    {
        $this->get_total_and_balance();
        $this->validate();
        if ($this->balance > 0){
            $this->dispatchBrowserEvent('balance');
        }else{
            switch ($this->action)
            {
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
        $this->validate();

        $new_invoice_data = [
            'invoice_no' => Invoice::loggedClinic()->max('invoice_no') + 1,
            'clinic_id' => auth()->user()->clinic_id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'nurse_id' => $this->nurse_id,
            'user_id' => auth()->user()->id,
            'ref' => $this->ref_invoice->id,
            'total' => $this->total_amount,
            'cash' => $this->cash == "" ? 0 : $this->cash,
            'knet' => $this->knet == "" ? 0 : $this->knet,
            'visa' => $this->visa == "" ? 0 : $this->visa,
            'master' => $this->master == "" ? 0 : $this->master,
            'knet_link' => $this->knet_link == "" ? 0 : $this->knet_link,
            'credit_link' => $this->credit_link == "" ? 0 : $this->credit_link,
            'balance' => $this->balance,
            'type' => 'Balance Invoice',
            'notes' => $this->notes,
        ];


        DB::beginTransaction();

        try {

            $new_invoice = Invoice::create($new_invoice_data);
            Balance::loggedClinic()->where('invoice_id', $this->ref_invoice->id)->delete();
            if ($this->balance > 0) {
                $this->create_balance_record($new_invoice->id);
            }
            $this->save_voucher($new_invoice);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

        return redirect()->route('invoices.show', $new_invoice->id);

    }

    public function update_invoice()
    {

        $this->get_total_and_balance();
        $this->validate();
        $invoice_data = [
            'cash' => $this->cash == "" ? 0 : $this->cash,
            'knet' => $this->knet == "" ? 0 : $this->knet,
            'visa' => $this->visa == "" ? 0 : $this->visa,
            'master' => $this->master == "" ? 0 : $this->master,
            'knet_link' => $this->knet_link == "" ? 0 : $this->knet_link,
            'credit_link' => $this->credit_link == "" ? 0 : $this->credit_link,
            'balance' => $this->balance,
            'notes' => $this->notes,
        ];


        DB::beginTransaction();

        try {

            Balance::loggedClinic()->whereInvoiceId($this->invoice_to_edit->id)->delete();
            $voucher = Voucher::loggedAccountGroup()->where('voucher_no', $this->invoice_to_edit->id)->where('voucher_type', 'inv')->first();
            if ($voucher) {
                $voucher->voucher_details()->delete();
            }


            $this->invoice_to_edit->update($invoice_data);

            if ($this->balance > 0) {
                $this->create_balance_record($this->invoice_to_edit->id);
            }

            $this->save_voucher($this->invoice_to_edit);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }


        return redirect()->route('invoices.show', $this->invoice_to_edit);

    }

    public function create_balance_record($invoice_id)
    {
        $balance_data = [
            'patient_id' => $this->patient_id,
            'invoice_id' => $invoice_id,
            'amount' => $this->balance,
            'clinic_id' => auth()->user()->clinic->id,
        ];
        Balance::create($balance_data);
    }

    public function save_voucher($invoice)
    {

        switch ($this->action) {
            case 'create' :
                $voucher_data = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'voucher_no' => $invoice->id,
                    'voucher_type' => 'inv',
                    'voucher_date' => today(),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ];
                $voucher = Voucher::create($voucher_data);
                $voucher->voucher_details()->createMany($this->get_voucher_details($invoice->invoice_no, $this->ref_invoice->invoice_no));
                break;

            case 'edit' :
                $voucher_data = [
                    'updated_by' => auth()->id(),
                ];

                $voucher = Voucher::loggedAccountGroup()->where('voucher_no', $invoice->id)->where('voucher_type', 'inv')->first();
                $voucher->update($voucher_data);
                $voucher->voucher_details()->createMany($this->get_voucher_details($invoice->invoice_no, $this->ref_invoice_no));
                break;
        }

    }

    public function get_voucher_details($current_invoice_no, $ref_invoice_no)
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


        $voucher_details_data = [];

        $voucher_details_data [] = [
            'account_group_id' => auth()->user()->clinic->account_group_id,
            'account_id' => $patients_receivable_account,
            'narration' => 'Paid Balance Amount of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
            'debit' => 0,
            'credit' => $this->total_paid,
        ];

        if ($this->cash > 0) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $cash_receptionist_account,  // Cash Receptionist Account
                'narration' => 'Total Cash of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => $this->cash,
                'credit' => 0,
            ];
        }

        if ($this->knet > 0) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_account,  // Bank Account
                'narration' => 'K-Net of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => $this->knet - round($this->knet * $knet_bank_commission,3),
                'credit' => 0,
            ];

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_charge_account,  // Bank Charge Account
                'narration' => 'K-Net Bank Charges of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => round($this->knet * $knet_bank_commission,3),
                'credit' => 0,
            ];
        }

        if ($this->visa > 0) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_account,  // Bank Account
                'narration' => 'VISA of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => $this->visa - round($this->visa * $visa_bank_commission,3),
                'credit' => 0,
            ];

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_charge_account,  // Bank Charge Account
                'narration' => 'VISA Bank Charges of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => round($this->visa * $visa_bank_commission,3),
                'credit' => 0,
            ];
        }

        if ($this->master > 0) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_account,  // Bank Account
                'narration' => 'MASTERCARD of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => $this->master -round($this->master * $master_bank_commission,3),
                'credit' => 0,
            ];

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_charge_account,  // Bank Charge Account
                'narration' => 'MASTERCARD Bank Charges of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => round($this->master * $master_bank_commission,3),
                'credit' => 0,
            ];
        }

        if ($this->knet_link > 0) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_account,  // Bank Account
                'narration' => 'K-Net Link of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => $this->knet_link - 0.250 ,
                'credit' => 0,
            ];

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_charge_account,  // Bank Charge Account
                'narration' => 'K-Net Bank Charges of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => 0.250,
                'credit' => 0,
            ];
        }

        if ($this->credit_link > 0) {
            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_account,  // Bank Account
                'narration' => 'Credit Card Link of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => $this->credit_link - 0.250 - round($this->credit_link * 2 / 100 ,3),
                'credit' => 0,
            ];

            $voucher_details_data [] = [
                'account_group_id' => auth()->user()->clinic->account_group_id,
                'account_id' => $bank_charge_account,  // Bank Charge Account
                'narration' => 'Credit Card Bank Charges of Inv-' . $current_invoice_no . ' for ' . auth()->user()->clinic->name . ' (Ref: ' . $ref_invoice_no . ")",
                'debit' => 0.250 + round($this->credit_link * 2 / 100 ,3),
                'credit' => 0,
            ];
        }

        

        return $voucher_details_data;

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
        $this->difference = 0;

        $this->total_paid =
            ($this->cash !== "" ? $this->cash : 0) +
            ($this->knet !== "" ? $this->knet : 0) +
            ($this->visa !== "" ? $this->visa : 0) +
            ($this->master !== "" ? $this->master : 0)+
            ($this->knet_link !== "" ? $this->knet_link : 0)+
            ($this->credit_link !== "" ? $this->credit_link : 0);
        $this->balance = $this->total_amount - $this->total_paid;
        $this->difference = $this->total_amount - $this->total_paid;
        $this->validate();
    }


}
