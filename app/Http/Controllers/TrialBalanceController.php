<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class TrialBalanceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_Accounts'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.trial_balance.index');
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


        $groups = Account::
        loggedAccountGroup()
            ->whereHas('childAccounts',function($q1) use ($request){
                $q1->doesnthave('childAccounts')->whereHas('voucher_details');
            })
            ->with(['childAccounts'=>function ($q4) use ($request){
                $q4->whereHas('voucher_details')
                    ->withCount(['voucher_details as opening_debit'=>function($q7) use ($request){
                        $q7->select(DB::raw('SUM(debit)'))
                            ->whereHas('voucher', function ($q8) use ($request){
                                $q8->where('voucher_date','<',$request->start);
                            });
                    }])
                    ->withCount(['voucher_details as opening_credit'=>function($q9) use ($request){
                        $q9->select(DB::raw('SUM(credit)'))
                            ->whereHas('voucher', function ($q10) use ($request){
                                $q10->where('voucher_date','<',$request->start);
                            });
                    }])
                    ->withCount(['voucher_details as transaction_debit'=>function($q11) use ($request){
                        $q11->select(DB::raw('SUM(debit)'))
                            ->whereHas('voucher', function ($q12) use ($request){
                                $q12->whereBetween('voucher_date',[$request->start,$request->end]);
                            });
                    }])
                    ->withCount(['voucher_details as transaction_credit'=>function($q13) use ($request){
                        $q13->select(DB::raw('SUM(credit)'))
                            ->whereHas('voucher', function ($q14) use ($request){
                                $q14->whereBetween('voucher_date',[$request->start,$request->end]);
                            });
                    }]);
            }])->get();

        return view('pages.trial_balance.show', compact('groups'));
    }
}
