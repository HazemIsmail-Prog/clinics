<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        
        abort_if(Gate::denies('Invoices_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoices = Invoice::
        loggedClinic()
            ->with('user', 'patient', 'nurse')
            ->when($request->patient_file_no, function ($q1) use ($request) {
                return $q1->where('patient_id', Patient::loggedClinic()->whereFileNo($request->patient_file_no)->first()->id );
            })
            ->when($request->invoice_no, function ($q2) use ($request) {
                return $q2->where('invoice_no', $request->invoice_no);
            })
            ->when($request->created_at, function ($q3) use ($request) {
                return $q3->whereDate('created_at', $request->created_at);
            })
            ->when($request->nurse_id, function ($q4) use ($request) {
                return $q4->where('nurse_id', $request->nurse_id);
            })
            ->when($request->user_id, function ($q5) use ($request) {
                return $q5->where('user_id', $request->user_id);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        $nurses = Nurse::loggedClinic()
            ->whereHas('invoices')
            ->with(['department' => function ($q) {
                return $q->where('clinic_id', auth()->user()->clinic_id);
            }])->get();

        $users = User::loggedClinic()->whereHas('invoices')->get();
        return view('pages.invoices.index', compact('invoices', 'nurses', 'users'));

    }

    public function create($id)
    {
        abort_if(Gate::denies('Invoices_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patient = Patient::loggedClinic()->findOrFail($id);
        return view('pages.invoices.create', compact('patient'));
    }

    public function edit($id)
    {
        abort_if(Gate::denies('Invoices_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice = Invoice::loggedClinic()->with(['invoice_details'=>function($q){
            $q->with('treatment');
        }])->findOrFail($id);

        abort_if($invoice->created_at->year <= auth()->user()->clinic->account_group->last_closed_year,Response::HTTP_FORBIDDEN, 'Closed Year');

        return view('pages.invoices.edit', compact('invoice'));
    }

    public function show($id)
    {
        abort_if(Gate::denies('Invoices_Read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice = Invoice::loggedClinic()->with('invoice_details', 'doctor', 'nurse', 'patient', 'user')->findOrFail($id);
        return view('pages.invoices.show', compact('invoice'));
    }
}
