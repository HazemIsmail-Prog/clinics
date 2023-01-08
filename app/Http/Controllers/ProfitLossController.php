<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class ProfitLossController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_Accounts'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.profit_loss.index');
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('Reports_Accounts'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = \Validator::make(request()->all(),
            [
                'start' => 'required',
                'end' => 'required',
            ]);
        $validator->validate();

        $income_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->income_parent_account;
        $expenses_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->expenses_parent_account;

        $income_groups = Account::
        loggedAccountGroup()
            ->where('root_account', $income_parent_account_id)
            ->whereHas('childAccounts', function ($q1) use ($request) {
                $q1->doesnthave('childAccounts')->whereHas('voucher_details');
            })
            ->with(['childAccounts' => function ($q4) use ($request) {
                $q4->whereHas('voucher_details');
                $q4->withCount(['voucher_details as total' => function ($q7) use ($request) {
                    $q7->select(DB::raw('SUM(credit) - SUM(debit)'));
                    $q7->whereHas('voucher', function ($q8) use ($request) {
                        $q8->whereBetween('voucher_date', [$request->start , $request->end]);
                    });
                }]);
            }])->get();

        $expenses_groups = Account::
        loggedAccountGroup()
            ->where('root_account', $expenses_parent_account_id)
            ->whereHas('childAccounts', function ($q1) use ($request) {
                $q1->doesnthave('childAccounts')->whereHas('voucher_details');
            })
            ->with(['childAccounts' => function ($q4) use ($request) {
                $q4->whereHas('voucher_details');
                $q4->withCount(['voucher_details as total' => function ($q7) use ($request) {
                    $q7->select(DB::raw('SUM(debit) - SUM(credit)'));
                    $q7->whereHas('voucher', function ($q8) use ($request) {
                        $q8->whereBetween('voucher_date', [$request->start , $request->end]);
                    });
                }]);
            }])->get();

        return view('pages.profit_loss.show', compact('income_groups','expenses_groups'));
    }
}
