<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Voucher;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class YearCloseController extends Controller
{
    public function index()
    {
        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        $year = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->last_closed_year + 1;

        abort_if($year == now()->year,Response::HTTP_FORBIDDEN, 'Previous Years Already been Closed');

        return view('pages.year_close.index', compact('year'));
    }

    public function store()
    {
        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        $year = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->last_closed_year + 1;

        $last_closed_year = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->last_closed_year;

        abort_if($year <= $last_closed_year,Response::HTTP_FORBIDDEN, 'Year Already Closed');

        $income_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->income_parent_account;
        $expenses_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->expenses_parent_account;
        $accumulated_loss_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->accumulated_loss_account;

        $income_accounts = Account::
        loggedAccountGroup()
            ->whereRootAccount($income_parent_account_id)
            ->whereHas('voucher_details', function ($q) use ($year) {
                $q->whereHas('voucher', function ($q2) use ($year) {
                    $q2->whereYear('voucher_date', $year);
                });
            })
            ->withCount(['voucher_details as debit_credit' => function ($q1) use ($year) {
                $q1->whereHas('voucher', function ($q2) use ($year) {
                    $q2->whereYear('voucher_date', $year);
                });
                $q1->select(DB::raw('SUM(debit) - SUM(credit)'));
            }])
            ->get();

        $expenses_accounts = Account::
        loggedAccountGroup()
            ->whereRootAccount($expenses_parent_account_id)
            ->whereHas('voucher_details', function ($q) use ($year) {
                $q->whereHas('voucher', function ($q2) use ($year) {
                    $q2->whereYear('voucher_date', $year);
                });
            })
            ->withCount(['voucher_details as debit_credit' => function ($q1) use ($year) {
                $q1->whereHas('voucher', function ($q2) use ($year) {
                    $q2->whereYear('voucher_date', $year);
                });
                $q1->select(DB::raw('SUM(debit) - SUM(credit)'));
            }])
            ->get();

        $data = [];

        foreach ($income_accounts as $account) {
            if ($account->debit_credit != 0) {
                $data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $account->id,
                    'narration' => 'Year ' . $year . ' Closing Voucher',
                    'debit' => $account->debit_credit < 0 ? abs($account->debit_credit) : 0,
                    'credit' => $account->debit_credit > 0 ? abs($account->debit_credit) : 0,
                    ];
            }
        }

        foreach ($expenses_accounts as $account) {
            if ($account->debit_credit != 0) {
                $data [] = [
                    'account_group_id' => auth()->user()->clinic->account_group_id,
                    'account_id' => $account->id,
                    'narration' => 'Year ' . $year . ' Closing Voucher',
                    'debit' => $account->debit_credit < 0 ? abs($account->debit_credit) : 0,
                    'credit' => $account->debit_credit > 0 ? abs($account->debit_credit) : 0,
                ];
            }
        }

        $data [] = [
            'account_group_id' => auth()->user()->clinic->account_group_id,
            'account_id' => $accumulated_loss_account_id,
            'narration' => 'Year ' . $year . ' Closing Voucher',
            'debit' => abs($income_accounts->sum('debit_credit')) - abs($expenses_accounts->sum('debit_credit')) < 0 ? abs($income_accounts->sum('debit_credit')) - abs($expenses_accounts->sum('debit_credit') ) : 0,
            'credit' => abs($income_accounts->sum('debit_credit')) - abs($expenses_accounts->sum('debit_credit')) > 0 ? abs($income_accounts->sum('debit_credit')) - abs($expenses_accounts->sum('debit_credit') ) : 0,
        ];

        $voucher_data = [
            'account_group_id' => auth()->user()->clinic->account_group_id,
            'voucher_no' => Voucher::loggedAccountGroup()->where('voucher_type', 'jv')->max('voucher_no') + 1,
            'voucher_type' => 'jv',
            'voucher_date' => $year.'-12-31',
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];




        DB::beginTransaction();
        try {
            $voucher = Voucher::create($voucher_data);
            $voucher->voucher_details()->createMany($data);
            $account_group = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id);
            $account_group->update(['last_closed_year' => $year]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->withInput();
        }


        session()->flash('success', 'Voucher Added Successfully');
        return redirect()->route('jvs.index');

    }
}
