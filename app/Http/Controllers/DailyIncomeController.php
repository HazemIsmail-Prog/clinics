<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class DailyIncomeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_DailyIncome'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.daily_income.index');
    }

    public function print(Request $request)
    {
        abort_if(Gate::denies('Reports_DailyIncome'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate($request, [
            'date' => 'required'
        ]);

        $date = $request->date;

        $invoices = Invoice::
        loggedClinic()
            ->whereDate('created_at', $request->date)
            ->with(['patient', 'nurse', 'doctor' => function ($q) {
                $q->with('department');
            }])
            ->orderBy('id')
            ->get();

        $doctors = Doctor::
        loggedClinic()
            ->with(['department','invoices'=>function($q) use ($request) {
                $q->whereDate('created_at', $request->date);
            }])
            ->withCount(['invoices' => function ($q) use ($request) {
                $q->whereDate('created_at', $request->date);
            }])
            ->whereHas('invoices', function ($q2) use ($request) {
                $q2->whereDate('created_at', $request->date);
            })
            ->withCount(['invoices as paid_amount' => function ($q) use ($request) {
                $q->select(DB::raw('SUM(cash) + SUM(knet) + SUM(visa) + SUM(master) + SUM(knet_link) + SUM(credit_link)'));
                $q->whereDate('created_at', $request->date);
            }])
            ->withCount(['invoices as new_patients' => function ($q) use ($request) {
                $q->select(DB::raw('COUNT(patient_id)'));
                $q->join('patients', 'patients.id', '=', 'invoices.patient_id');
                $q->whereDate('patients.created_at', $request->date);
                $q->whereDate('invoices.created_at', $request->date);

            }])
            ->get();

        return view('pages.daily_income.print', compact('invoices', 'date', 'doctors'));
    }
}
