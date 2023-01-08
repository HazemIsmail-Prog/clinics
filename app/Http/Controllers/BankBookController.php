<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class BankBookController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_Accounts'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::loggedAccountGroup()->doesnthave('childAccounts')->whereIsBank(1)->orderBy('name')->get();
        return view('pages.bank_book.index', compact('accounts'));
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
            ->whereIn('id', $request->accounts)
            ->with(['voucher_details' => function ($q) use ($request) {
                $q->with('voucher');
                $q->whereHas('voucher', function ($q2) use ($request) {
                    $q2->whereIn('voucher_type', ['bp', 'br']);
                    $q2->whereBetween('voucher_date', [$request->start, $request->end]);
                });
                //this with count for sorting while using whereHas
                $q->withCount(['voucher as voucher_date' => function ($q) use ($request) {
                    $q->select('voucher_date');
                }]);
                $q->orderBy('voucher_date');
            }])
            ->get();

        return view('pages.bank_book.show', compact('accounts'));
    }

}
