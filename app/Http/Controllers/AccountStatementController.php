<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class AccountStatementController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_Accounts'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::loggedAccountGroup()->doesnthave('childAccounts')->orderBy('name')->get();
        return view('pages.account_statement.index',compact('accounts'));
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('Reports_Accounts'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = \Validator::make(request()->all(),
            [
                'start' => 'required',
                'end' => 'required',
                'accounts' => 'required',
                ]);
        $validator->validate();

        $accounts = Account::
        loggedAccountGroup()
            ->whereIn('id',$request->accounts)
            ->withCount(['voucher_details as opening_debit'=>function($q3) use ($request){
                $q3->select(DB::raw('SUM(debit)'))
                    ->whereHas('voucher', function ($q4) use ($request){
                        $q4->where('voucher_date','<',$request->start);
                });
            }])
            ->withCount(['voucher_details as opening_credit'=>function($q3) use ($request){
                $q3->select(DB::raw('SUM(credit)'))
                    ->whereHas('voucher', function ($q4) use ($request){
                        $q4->where('voucher_date','<',$request->start);
                    });
            }])
            ->with(['voucher_details'=>function ($q) use ($request){
                $q->with('voucher')
                    ->whereHas('voucher', function ($q2) use ($request){
                    $q2->whereBetween('voucher_date',[$request->start,$request->end]);
                });
            }])
            ->get();

        return view('pages.account_statement.show',compact('accounts'));
    }
}
