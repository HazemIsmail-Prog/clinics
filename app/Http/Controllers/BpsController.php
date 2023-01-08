<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Doctor;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class BpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('BankPayments_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $vouchers = Voucher::
        loggedAccountGroup()
            ->with(['voucher_details'=>function($q){
                $q->with('account');
            }])
            ->with('creator')
            ->where('voucher_type','bp')
            ->when($request->voucher_no, function ($q) use ($request) {
                return $q->where('voucher_no', $request->voucher_no);
            })
            ->orderBy('id','desc')
            ->paginate(10);
        return view('pages.bps.index',compact('vouchers'));
    }

    public function show($id)
    {
        abort_if(Gate::denies('BankPayments_Read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $voucher = Voucher::
        loggedAccountGroup()
            ->with(['voucher_details'=>function ($q){
                $q->with('account');
            }])
            ->where('voucher_type','bp')
            ->findOrFail($id);
        return view('pages.bps.show', compact('voucher'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('BankPayments_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::loggedAccountGroup()->doesnthave('childAccounts')->orderBy('name')->get();
        $doctors = Doctor::loggedClinic()->get();
        return view('pages.bps.create',compact('accounts','doctors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('BankPayments_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accounts = Account::loggedAccountGroup()->doesnthave('childAccounts')->orderBy('name')->get();
        $doctors = Doctor::loggedClinic()->get();
        $current_voucher = Voucher::loggedAccountGroup()->whereVoucherType('bp')->findOrFail($id);
        abort_if(date('Y', strtotime($current_voucher->voucher_date)) <= auth()->user()->clinic->account_group->last_closed_year,Response::HTTP_FORBIDDEN, 'Closed Year');

        return view('pages.bps.edit',compact('accounts','doctors','current_voucher'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('BankPayments_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }
}
