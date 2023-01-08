<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class BalanceSheetController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_Accounts'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.balance_sheet.index');
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

        $assets_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->assets_parent_account;
        $liabilities_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->liabilities_parent_account;
        $equity_parent_account_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->equity_parent_account;
        $income_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->income_parent_account;
        $expenses_parent_account_id = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id)->expenses_parent_account;


        $income_total = Account::
        loggedAccountGroup()
            ->where('root_account', $income_parent_account_id)
            ->doesnthave('childAccounts')
            ->withCount(['voucher_details as total' => function ($q7) use ($request) {
                $q7->select(DB::raw('SUM(credit) - SUM(debit)'));
                $q7->whereHas('voucher', function ($q8) use ($request) {
                    $q8->where('voucher_date', '<=', $request->end);
                });
            }])->get();


        $expenses_total = Account::
        loggedAccountGroup()
            ->where('root_account', $expenses_parent_account_id)
            ->doesnthave('childAccounts')
            ->withCount(['voucher_details as total' => function ($q7) use ($request) {
                $q7->select(DB::raw('SUM(credit) - SUM(debit)'));
                $q7->whereHas('voucher', function ($q8) use ($request) {
                    $q8->where('voucher_date', '<=', $request->end);
                });
            }])->get();

         $profit = $income_total->sum('total') + $expenses_total->sum('total');



        $assets_groups = Account::
        loggedAccountGroup()
            ->whereIn('root_account', [$assets_parent_account_id])
            ->whereHas('childAccounts', function ($q1) use ($request) {
                $q1->doesnthave('childAccounts');
                $q1->whereHas('voucher_details', function ($q) use ($request) {
                    $q->whereHas('voucher', function ($q8) use ($request) {
                        $q8->where('voucher_date', '<=', $request->end);
                    });
                });
            })
            ->with(['childAccounts' => function ($q4) use ($request) {
                $q4->whereHas('voucher_details');
                $q4->withCount(['voucher_details as total' => function ($q7) use ($request) {
                    $q7->select(DB::raw('SUM(debit) - SUM(credit)'));
                    $q7->whereHas('voucher', function ($q8) use ($request) {
                        $q8->where('voucher_date', '<=', $request->end);
                    });
                }]);
            }])->get();

        $liabilities_groups = Account::
        loggedAccountGroup()
            ->whereIn('root_account', [$liabilities_parent_account_id])
            ->whereHas('childAccounts', function ($q1) use ($request) {
                $q1->doesnthave('childAccounts');
                $q1->whereHas('voucher_details', function ($q) use ($request) {
                    $q->whereHas('voucher', function ($q8) use ($request) {
                        $q8->where('voucher_date', '<=', $request->end);
                    });
                });
            })
            ->with(['childAccounts' => function ($q4) use ($request) {
                $q4->whereHas('voucher_details');
                $q4->withCount(['voucher_details as total' => function ($q7) use ($request) {
                    $q7->select(DB::raw('SUM(credit) - SUM(debit)'));
                    $q7->whereHas('voucher', function ($q8) use ($request) {
                        $q8->where('voucher_date', '<=', $request->end);
                    });
                }]);
            }])->get();

        $equity_group = Account::
        loggedAccountGroup()
            ->whereIn('root_account', [$equity_parent_account_account_id])
            ->whereHas('childAccounts', function ($q1) use ($request) {
                $q1->doesnthave('childAccounts');
                $q1->whereHas('voucher_details', function ($q) use ($request) {
                    $q->whereHas('voucher', function ($q8) use ($request) {
                        $q8->where('voucher_date', '<=', $request->end);
                    });
                });
            })
            ->with(['childAccounts' => function ($q4) use ($request) {
                $q4->whereHas('voucher_details');
                $q4->withCount(['voucher_details as total' => function ($q7) use ($request) {
                    $q7->select(DB::raw('SUM(credit) - SUM(debit)'));
                    $q7->whereHas('voucher', function ($q8) use ($request) {
                        $q8->where('voucher_date', '<=', $request->end);
                    });
                }]);
            }])->get();

        return view('pages.balance_sheet.show', compact('assets_groups', 'liabilities_groups', 'equity_group','profit'));
    }
}
