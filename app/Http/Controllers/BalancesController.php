<?php

namespace App\Http\Controllers;



use App\Models\Balance;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;


class BalancesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('Balances_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $balances = Balance::loggedClinic()->with('patient')
                ->when($request->patient_id,function ($q) use ($request){
                    return $q->where('patient_id',$request->patient_id);
                })
                ->orderBy('id','desc')->paginate(10);
            return view('pages.balances.index',compact('balances'));
    }

    public function create($id)
    {
        abort_if(Gate::denies('Balances_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ref_invoice = Invoice::loggedClinic()->findOrFail($id);

        //check if selected balance still has record in database
        //because of pay balance then click pay again before page refresh
        $balance = Balance::
        whereInvoiceId($id)
            ->where('clinic_id',auth()->user()->clinic_id)
            ->first();

        abort_if($ref_invoice == null,404);
        abort_if($balance == null,404);

        return view('pages.balances.create',compact('ref_invoice'));

    }

    public function edit($id)
    {
        abort_if(Gate::denies('Balances_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice = Invoice::loggedClinic()->findOrFail($id);
        $ref_invoice = Invoice::loggedClinic()->findOrFail($invoice->ref);;
        return view('pages.balances.edit',compact('invoice','ref_invoice'));
    }
}
